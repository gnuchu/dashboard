<?php
  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $env = filter_var($_POST['env'], FILTER_SANITIZE_STRING);
    $changetype = filter_var($_POST['changetype'], FILTER_SANITIZE_STRING);

    if(!isset($env) || !isset($changetype)) {
      die;
    }

    $response_array = [];

    if($changetype === 'releasebranch') {
      $new_release_branch = filter_var($_POST['new_release_branch'], FILTER_SANITIZE_STRING);
      if(updateReleaseBranch($conn, $env, $new_release_branch)) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        $response_array['env'] = $env;
        $response_array['new_release_branch'] = $new_release_branch;
      }
      else {
        header('Content-type: application/json');
        $response_array['status'] = 'failure';
      }
    }
    elseif ($changetype === 'owner') {
      $new_owner = filter_var($_POST['new_owner'], FILTER_SANITIZE_STRING);
      if(updateOwner($conn, $env, $new_owner)) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        $response_array['env'] = $env;
        $response_array['new_owner'] = $new_owner;
      }
      else {
        header('Content-type: application/json');
        $response_array['status'] = 'failure';
      }
    }
    elseif ($changetype === 'description') {
      $new_description = filter_var($_POST['new_description'], FILTER_SANITIZE_STRING);
      if(updateDescription($conn, $env, $new_description)) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        $response_array['env'] = $env;
        $response_array['new_description'] = $new_description;
      }
      else {
        header('Content-type: application/json');
        $response_array['status'] = 'failure';
      }
    }
    usleep(250000); //Have a little rest so that the UI doesn't get bombarded with the response.
    echo json_encode($response_array);
    exit;
  }
  else {
    if(isset($_GET["env"])) {
      $env = $_GET["env"];
      $sql = 'select id from environments where name = ?';
      $params = array($env);
      $env_id = genericSQLReturnValue($conn, $sql, $params, 'id');
      if(!(int)$env_id > 0) {
        header('Location: index.php');
        die('No Environment passed.');
      }
    }
    else
    {
      header('Location: index.php');
      die('No Environment passed.');
    }

    if(($env === 'PRODUCTION' || $env === 'PREPROD') && !authenticated()) {
      header('Location: index.php');
      die('No Environment passed.');
    }
    
    $page = file_get_contents('templates/header.php');
    $page .= file_get_contents('templates/navbar.php');
    $page .= file_get_contents('templates/jstemplate.html');
    $html = '';
    $tab_counter = 0;

    $page .= "<h1>$env</h1>";
    //Links
    $page .= file_get_contents('templates/detailsviewlinkstable.php');
    
    //Description
    $description_table_template = '';
    if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']===true && $_SESSION['Admin'] == 1) {
      $description_table_template = file_get_contents('templates/description_table.php');
    }
    else {
      $description_table_template = file_get_contents('templates/description_table_noedit.php');
    }

    $env_details = getEnvironmentDetails($conn, $env);
    $description = $env_details['description'];
    $description = IsNullOrEmptyString($description) ? 'TBC' : $description;
    $branch = $env_details['releasebranch'];
    $branch = IsNullOrEmptyString($branch) ? 'TBC' : $branch;
    $owner = $env_details['owner'];
    $owner = IsNullOrEmptyString($owner) ? 'TBC' : $owner;

    $description_table = sprintf($description_table_template, $env, $description, $branch, $owner);
    $page .= $description_table;
    //End
    
    $tab_bar_start = '<ul class="nav nav-tabs">';
    $tab_bar_end = '</ul>';

    $tab_content_start = '<div class="well"><div class="tab-content">';
    $tab_content_end = '</div></div>';

    $tab_bar = '<h2 id="serverhistory">Server History</h2>';
    $tab_bar .= $tab_bar_start;
    $tab_content = $tab_content_start;

    $first_tab_template = '<li class="active"><a data-toggle="tab" href="#%s">%s</a></li>';
    $normal_tab_template = '<li><a data-toggle="tab" href="#%s">%s</a></li>';

    $tab_content_first_element = '<div id="%s" class="tab-pane fade in active">';
    $tab_content_normal_element = '<div id="%s" class="tab-pane fade">';
    $tab_content_element_end = '</div>';

    $tab_content_table_template = file_get_contents('templates/detailsview.php');
    $deploymenthistoryheader = file_get_contents('templates/deploymenthistoryheader.php');
    $deploymenthistoryfooter = file_get_contents('templates/deploymenthistoryfooter.php');
    $deploymenthistoryrowtemplate = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';

    $clusters = getClustersForEnvironment($conn, $env);

    $clusters_table_template_start = file_get_contents('templates/clusters_table_start.php');
    $clusters_table_template_end   = file_get_contents('templates/clusters_table_end.php');
    $clusters_table_row_template   = "<tr><td>%s</td><td>%s</td><td>%s</td></tr>";

    $clusters_table = $clusters_table_template_start;
    foreach($clusters as &$cluster) {
      list($envname, $appname, $categoryname) = explode('-', $cluster['name']);

      if($cluster['url'] === 'TBC' || $cluster['noclusterurl']===1 || is_null($cluster['url'])) {
        $row = getUrlForEnvAndApp($conn, $envname, $appname, $categoryname);
        $url = $row['siteurl'];
      }
      else {
        $url = $cluster['url'];
      }

      $url = ($url === 'TBC') ? $url : urlify2($url);

      $clusters_table .= sprintf($clusters_table_row_template, $appname, $categoryname, $url);
    }

    $clusters_table .= $clusters_table_template_end;
    $page .= $clusters_table;

    //Get external urls
    $external_urls = getExternalUrls($conn, $env);
    if(sizeof($external_urls)!==0) {
      $external_urls_table_start = file_get_contents('templates/external_urls_table_start.php');
      $external_urls_table_end = file_get_contents('templates/external_urls_table_end.php');
      $external_urls_row_template = "<tr><td>%s</td><td>%s</td></tr>";

      $external_urls_table = $external_urls_table_start;

      foreach($external_urls as &$url) {
        $external_url_row = sprintf($external_urls_row_template, $url['app_name'], urlify2($url['url']));
        $external_urls_table .= $external_url_row;
      }
      $external_urls_table .= $external_urls_table_end;
      $page .= $external_urls_table;
    }

    //WSDL table.
    $wsdl_table_template = file_get_contents('templates/wsdl_table_template.php');
    $envcategory = "";
    $quoteHubAvailable = environmentHasAQuoteHub($conn, $env);

    $envcategory = $quoteHubAvailable ? 'QH' : 'SOR';

    $cluster_name = sprintf("%s-pc-%s", $env, $envcategory);
    $cluster = getCluster($conn, $cluster_name);

    $pc_base_url = "";
    
    if($cluster['noclusterurl']===1 || $cluster['url']==='TBC') {
      $row = getUrlForEnvAndApp($conn, $envname, 'pc', $envcategory);
      $pc_base_url = $row['siteurl'];
    }
    else {
      $pc_base_url = $cluster['url'];
    }

    $homequotewsdlurl = $pc_base_url . getGlobalConfigurationValue($conn, 'HMQuoteWSDLEndpoint');
    $carquotewsdlurl = $pc_base_url . getGlobalConfigurationValue($conn, 'PCQuoteWSDLEndpoint');
    $bikequotewsdlurl = $pc_base_url . getGlobalConfigurationValue($conn, 'MCQuoteWSDLEndpoint');
    $vanquotewsdlurl = $pc_base_url . getGlobalConfigurationValue($conn, 'CVQuoteWSDLEndpoint');

    $homequotewsdlurl = urlify2($homequotewsdlurl);
    $carquotewsdlurl = urlify2($carquotewsdlurl);
    $bikequotewsdlurl = urlify2($bikequotewsdlurl);
    $vanquotewsdlurl = urlify2($vanquotewsdlurl);

    $wsdl_table = sprintf($wsdl_table_template, $homequotewsdlurl, $carquotewsdlurl, $bikequotewsdlurl, $vanquotewsdlurl);
    
    if(!$pc_base_url == '') {
      $page .= $wsdl_table; //Only add the WSDLs if PC is installed.
    }
    
    //WSDL table end

    //Database Details table

    $databasedetailstablerowtemplate = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
    $databasedetailstableheader = file_get_contents('templates/databasedetailstableheader.php');
    $databasedetailstablefooter = file_get_contents('templates/databasedetailstablefooter.php');

    $databasedetails = getDatabaseDetails($conn, $env);
    $databasedetailshtml = $databasedetailstableheader;

    foreach($databasedetails as &$dbdetail) {
      $appname = $dbdetail['appname'];
      $category = $dbdetail['category'];
      $databaseserver = $dbdetail['databaseserver'];
      $databaseport = (string)$dbdetail['databaseport'];
      $databasename = $dbdetail['databasename'];

      $databasedetailshtml .= sprintf($databasedetailstablerowtemplate, $appname, $category, $databaseserver, $databaseport, $databasename);
    }

    //Look for AggHub details

    $sql = "select distinct name, value from integrationproperties where name in ('hastingDB_JDBC_URL','hastingDB_NAME') and environment_id = ?";
    $params = array($env_id);
    $aggHubDetails = genericSQLRowsGetParams($conn, $sql, $params);
    
    $aggHubDbName = '';
    $aggHubDbServer = '';

    foreach($aggHubDetails as &$aggHubDetail) {
      if($aggHubDetail['name'] == 'hastingDB_JDBC_URL') {
        $aggHubDbServer = $aggHubDetail['value'];
      }
      else {
        $aggHubDbName = $aggHubDetail['value'];
      }
    }

    if($aggHubDbName !== '' && $aggHubDbServer !== '') {
      $aggHubDbServer = preg_replace('/jdbc:sqlserver:\/\//', '', $aggHubDbServer);
      list($server, $name) = explode(';', $aggHubDbServer);
      list($server, $port) = explode(':', $server);
      $name = preg_replace('/DatabaseName=/', '', $name);
      $databasedetailshtml .= sprintf($databasedetailstablerowtemplate, 'AggHub', 'AggHub', $server, $port, $name);
    }
    //

    $databasedetailshtml .= $databasedetailstablefooter;
    $databasedetailshtml .= '<br/>';
    $page .= $databasedetailshtml;

    //Database Details table End.

    //ISL Table

    $isldetailstableheader = file_get_contents('templates/isldetailstableheader.php');
    $isldetailstablefooter = file_get_contents('templates/isldetailstablefooter.php');
    $isldetailsrowtemplate = file_get_contents('templates/isldetailsrowtemplate.php');

    $isl_table = '<h2 id="islsettings">ISL Settings</h2>';

    $isl_details = getISLDetails($conn, $env);

    if(count($isl_details) < 1) {
      $isl_table .= 'Not available.';
    }
    else {
      $isl_table .= $isldetailstableheader;
      
      foreach($isl_details as &$isl) {
        $active = $isl['active'] == 1 ? 'YES' : 'NO';
        $isl_row = sprintf($isldetailsrowtemplate, $isl['islservice'], $isl['endpoint'], $active);
        $isl_table .= $isl_row;
      }

      $isl_table .= $isldetailstablefooter;
    }

    $page .= $isl_table;

    //ISL Table End.

    $apps = getAppsForEnvironment($conn, $env);

    foreach($apps as &$app) {
      $tab_counter += 1;

      $appname = $app['appname'];
      $appid = $app['appid'];
      $isPingable = $app['ispingable'];
      $pingLastChecked = $app['pinglastchecked'];
      $servername = $app['servername'];
      $buildidentifier = $app['buildidentifier'];
      $deploymentdate = $app['deploymentdate'];
      $deploymentuser = $app['deploymentuser'];
      $category = $app['category'];
      $status_reason = $app['statusreason'];
      $switchedoff = $app['switchedoff'];

      switch($appname) {
        case 'pc':
          $display_appname = "<small>PC - $category<br/>$servername</small>";
          break;
        case 'bc':
          $display_appname = "<small>BC - $category<br/>$servername</small>";
          break;
        case 'cc':
          $display_appname = "<small>CC - <br/>$servername</small>";
          break;
        case 'ab':
          $display_appname = "<small>CM - <br/>$servername</small>";
          break;
        case 'ec':
          $display_appname = "<small>Edge - <br/>$servername</small>";
          break;
        case 'bridge':
          $display_appname = "<small>Bridge - <br/>$servername</small>";
          break;
        case 'isl':
          $display_appname = "<small>ISL - <br/>$servername</small>";
          break;
        case 'pss':
          $display_appname = "<small>PSS - <br/>$servername</small>";
          break;
        case 'polaris':
          $display_appname = "<small>Polaris - <br/>$servername</small>";
          break;
      }

      $tab_id = $env . '_' . $appname . '_' . $tab_counter;
      if($tab_counter===1) {
        $tab = sprintf($first_tab_template, $tab_id, $display_appname);
        $content_element = sprintf($tab_content_first_element, $tab_id);
      }
      else {
        $tab = sprintf($normal_tab_template, $tab_id, $display_appname);
        $content_element = sprintf($tab_content_normal_element, $tab_id);
      }

      $display_deploymentuser = getDeploymentUser($deploymentuser);

      $pingDate = $pingLastChecked == NULL ? NULL : $pingLastChecked->format('M d Y h:iA');
      $dd = "";
      if(isset($deploymentdate)) {
        $dd = $deploymentdate->format('M d Y h:iA');
      }

      $off = ($switchedoff == 1) ? 'Yes' : 'No';
      $content_element .= sprintf($tab_content_table_template, $servername, $status_reason, $pingDate, $display_deploymentuser, $dd, $off);

      $deploymenthistory = getDeploymentHistory($conn, $appid);
      $deploymenthistoryhtml = $deploymenthistoryheader;

      foreach($deploymenthistory as &$history) {
        $revision       = $history['revision'];
        $identifier     = $history['identifier'];
        $svnpath        = $history['svnpath'];
        $trunk          = $history['trunk'];

        if($trunk === 1) {
          $branch = 'trunk';
        }
        else {
          $regex = '#Hastings\/branches\/(.*?)\/#';
          preg_match($regex, $svnpath, $matches);
          if(count($matches) > 0) {
            $branch = $matches[1];
          }
        }

        $deploymentuser = getDeploymentUser($history['deploymentuser']);
        $deploymentdate = $history['deploymentdate'];

        $deploymenthistoryhtml .= sprintf($deploymenthistoryrowtemplate, $deploymentuser, $deploymentdate, $revision, $svnpath, $branch, $identifier);
      }

      $deploymenthistoryhtml .= $deploymenthistoryfooter;
      $content_element .= $deploymenthistoryhtml;
      $content_element .= $tab_content_element_end;
      
      $tab_bar .= $tab;
      $tab_content .= $content_element;
    }

    $tab_bar .= $tab_bar_end;
    $tab_content .= $tab_content_end;

    $page .= $tab_bar;
    $page .= $tab_content;

    $page .= file_get_contents('templates/footer.php');
    $page = eval("?>$page");
    echo $page;
  }

?>