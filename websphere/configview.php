<?php
  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  require_once '../externals/database.php';
  require_once '../externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if(isset($_GET["appserver"])) {
    $appserver = $_GET["appserver"];
  }
  else
  {
    header('Location: index.php');
    die('No appserver passed.');
  }

  $appserver_details = getAppserverDetails($conn, $appserver);
  $appserver_name = $appserver_details['name'];

  $sql = 'select * from servers where id = ?';
  $params = array($appserver_details['server_id']);
  $server_details = genericSQLRowGetParams($conn, $sql, $params);

  $sql = 'select * from environments where id = ?';
  $params = array($server_details['environment_id']);
  $env_details = genericSQLRowGetParams($conn, $sql, $params);

  if(($env_details["name"] === 'PRODUCTION' || $env_details["name"] === 'PREPROD') && !authenticated()) {
    header('Location: index.php');
    die('No appserver passed.');
  }
  
  $sql = 'select * from websphereconfiguration where appserver_id = ?';
  $params = array($appserver_details['id']);
  $websphereconfig = genericSQLRowGetParams($conn, $sql, $params);
 
  $json_obj = json_decode($websphereconfig['config_json'], true);

  $page = file_get_contents('../templates/header.php');
  $page .= file_get_contents('../templates/navbar.php');
  $page .= file_get_contents('../templates/jstemplate.html');
  $html = '';
  $tab_counter = 0;

  $page .= "<h1>$appserver_name</h1>";
  //Links
  $page .= file_get_contents('templates/configviewlinkstable.php');

  $nothead2columntableheader = file_get_contents('templates/nothead2columntableheader.php');
  $nothead2columntablefooter = file_get_contents('templates/nothead2columntablefooter.php');
  $nothead2columnrowtemplate = '<tr><td>%s</td><td>%s</td></tr>';

  //JVM Details
  $jvm = $json_obj['jvm'];

  $jvmdetailstableheader = file_get_contents('templates/jvmdetailstableheader.php');
  $jvmdetailstablefooter = file_get_contents('templates/jvmdetailstablefooter.php');

  $jvmdetailstablerowtemplate = "<tr><td>%s</td><td>%s</td></tr>";

  $jvmdetailshtml = $jvmdetailstableheader;

  foreach ($jvm as $key => $val) {
    if (is_array($val)) {
      $jvmsubpropertieshtml = $nothead2columntableheader;
      foreach ($val as $key1 => $val1) {
        $jvmsubpropertieshtml .= sprintf($nothead2columnrowtemplate, $key1, $val1);
      }
      $jvmsubpropertieshtml .= $nothead2columntablefooter;
      $jvmdetailshtml .= sprintf($jvmdetailstablerowtemplate, $key, $jvmsubpropertieshtml);
    }
    else {
      $jvmdetailshtml .= sprintf($jvmdetailstablerowtemplate, $key, $val);
    }
  }

  $jvmdetailshtml .= $jvmdetailstablefooter;
  $jvmdetailshtml .= '<br/>';
  $page .= '<h2 id="jvm">JVM</h2>';
  $page .= $jvmdetailshtml;
  //JVM Details table End.

  $tab_bar_start = '<ul class="nav nav-tabs">';
  $tab_bar_end = '</ul>';

  $first_tab_template = '<li class="active"><a data-toggle="tab" href="#%s">%s</a></li>';
  $normal_tab_template = '<li><a data-toggle="tab" href="#%s">%s</a></li>';

  $tab_content_start = '<div class="well"><div class="tab-content">';
  $tab_content_end = '</div></div>';

  $tab_content_first_element = '<div id="%s" class="tab-pane fade in active">';
  $tab_content_normal_element = '<div id="%s" class="tab-pane fade">';
  $tab_content_element_end = '</div>';

  $datasourcedetailsheader = file_get_contents('templates/datasourcedetailstableheader.php');
  $datasourcedetailsfooter = file_get_contents('templates/datasourcedetailstablefooter.php');
  $datasourcedetailsrowtemplate = '<tr><td>%s</td><td>%s</td></tr>';

  $tab_bar = $tab_bar_start;
  $tab_content = $tab_content_start;

  //Datasource Details
  $datasources = $json_obj['dataSources'];

  $page .= '<h2 id="datasources">Database Details</h2>';

  $tab_counter = 0;
  foreach($datasources as $datasource) {
    $tab_id = $datasource['name'] . '_' . $tab_counter;
    $tab_counter++;
    if($tab_counter===1) {
      $tab = sprintf($first_tab_template, $tab_id, $datasource['name']);
      $content = sprintf($tab_content_first_element, $tab_id);
    }
    else {
      $tab = sprintf($normal_tab_template, $tab_id, $datasource['name']);
      $content = sprintf($tab_content_normal_element, $tab_id);
    }

    $datasourcedetailshtml = $datasourcedetailsheader;
    foreach($datasource as $key => $val) {
      $datasourcesubpropertieshtml = $nothead2columntableheader;
      if (is_array($val)) {
        foreach ($val as $key1 => $val1) {
          $datasourcesubpropertieshtml .= sprintf($nothead2columnrowtemplate, $key1, $val1);
        }
        $datasourcesubpropertieshtml .= $nothead2columntablefooter;
        $datasourcedetailshtml .= sprintf($datasourcedetailsrowtemplate, $key, $datasourcesubpropertieshtml);
      }
      else {
        $datasourcedetailshtml .= sprintf($datasourcedetailsrowtemplate, $key, $val);
      }
    }
    $datasourcedetailshtml .= $datasourcedetailsfooter;

    $content .= $datasourcedetailshtml;
    $content .= $tab_content_element_end;
    $tab_bar .= $tab;
    $tab_content .= $content;
  }

  //$datasourcedetailshtml = '<br/>';
  //$page .= $datasourcedetailshtml;
  //Datasource Details End.

  $tab_bar .= $tab_bar_end;
  $tab_content .= $tab_content_end;

  $page .= $tab_bar;
  $page .= $tab_content;

  $panelgrouptop = '<div class="panel-group">';
  $panelgroupbottom = '</div>';
  $paneltop = '<div class="panel panel-default">';
  $panelbottom = '</div>';
  $panelheader = '<div class="panel-heading">%s</div>';
  $panelbody = '<div class="panel-body">%s</div>';

  //Certificate Details
  $keystores = $json_obj['keystores'];

  $certificatedetailshtml = '<br/>';
  $certificatedetailshtml .= $panelgrouptop;
  $certificatedetailshtml .= '<h2 id="certificates">Certificate Details</h2>';

  foreach ($keystores as $keystore) {
    $ksname = $keystore['name'];
    $personalcerts = $keystore['personalCerts'];
    $signercerts = $keystore['signerCerts'];

    $certtabbarhtml = $tab_bar_start;
    $certtabcontenthtml = $tab_content_start;

    $tab_counter = 0;
    foreach ($personalcerts as $cert) {
      $tab_counter++;
      $tab_id = $cert['alias'] . '_' . $tab_counter;
      if($tab_counter===1) {
        $tab = sprintf($first_tab_template, $tab_id, $cert['alias']);
        $content = sprintf($tab_content_first_element, $tab_id);
      }
      else {
        $tab = sprintf($normal_tab_template, $tab_id, $cert['alias']);
        $content = sprintf($tab_content_normal_element, $tab_id);
      }

      $certdetailshtml = $nothead2columntableheader;
      foreach($cert as $key => $val) {
        $certdetailshtml .= sprintf($nothead2columnrowtemplate, $key, $val);
      }
      $certdetailshtml .= $nothead2columntablefooter;

      $content .= $certdetailshtml;
      $content .= $tab_content_element_end;

      $certtabbarhtml .= $tab;
      $certtabcontenthtml .= $content;
    }

    $certtabbarhtml .= $tab_bar_end;
    $certtabcontenthtml .= $tab_content_end;

    $certtabgrouphtml = $certtabbarhtml.$certtabcontenthtml;

    $personalcerthtml = $paneltop;
    $personalcerthtml .= sprintf($panelheader, "Personal Certificates");
    $personalcerthtml .= sprintf($panelbody, $certtabgrouphtml);
    $personalcerthtml .= $panelbottom;

    $signercerthtml = $paneltop;
    $signercerthtml .= sprintf($panelheader, "Signer Certificates");

    $certtabbarhtml = $tab_bar_start;
    $certtabcontenthtml = $tab_content_start;

    $tab_counter = 0;
    foreach ($signercerts as $cert) {
      $tab_counter++;
      $tab_id = $cert['alias'] . '_' . $tab_counter;
      if($tab_counter===1) {
        $tab = sprintf($first_tab_template, $tab_id, $cert['alias']);
        $content = sprintf($tab_content_first_element, $tab_id);
      }
      else {
        $tab = sprintf($normal_tab_template, $tab_id, $cert['alias']);
        $content = sprintf($tab_content_normal_element, $tab_id);
      }

      $certdetailshtml = $nothead2columntableheader;
      foreach($cert as $key => $val) {
        $certdetailshtml .= sprintf($nothead2columnrowtemplate, $key, $val);
      }
      $certdetailshtml .= $nothead2columntablefooter;

      $content .= $certdetailshtml;
      $content .= $tab_content_element_end;

      $certtabbarhtml .= $tab;
      $certtabcontenthtml .= $content;
    }

    $certtabbarhtml .= $tab_bar_end;
    $certtabcontenthtml .= $tab_content_end;

    $certtabgrouphtml = $certtabbarhtml.$certtabcontenthtml;

    $signercerthtml .= sprintf($panelbody, $certtabgrouphtml);
    $signercerthtml .= $panelbottom;

    $certgrouphtml = $personalcerthtml;
    $certgrouphtml .= $signercerthtml;

    $certgrouphtml .= $panelgroupbottom;

    $certificatedetailshtml .= $paneltop;
    $certificatedetailshtml .= sprintf($panelheader, $ksname);
    $certificatedetailshtml .= sprintf($panelbody, $certgrouphtml);
    $certificatedetailshtml .= $panelbottom;
  }

  $certificatedetailshtml .= $panelgroupbottom;

  $page .= $certificatedetailshtml;
  //Certificate Details End.

  $tab_bar .= $tab_bar_end;
  $tab_content .= $tab_content_end;

  $page .= file_get_contents('../templates/footer.php');
  $page = eval("?>$page");
  echo $page;

?>