<?php

  function makeBuildDetailsTable($conn, $svnrevisionnumber) {
    
    $buildDetails = getBuildDetails($conn, $svnrevisionnumber);

    if(count($buildDetails) < 1) {
      return file_get_contents('templates/build_not_found.php');
    }

    $buildDeplymentDetails = getBuildDeploymentDetails($conn, $svnrevisionnumber);

    $artifactoryBaseUrl = getGlobalConfigurationValue($conn, 'ARTIFACTORY_BASE_URL');

    $html = "<h3>Build History for SVNRevision <strong>{$svnrevisionnumber}</strong></h3>";

    $buildDetailsTable = file_get_contents('templates/build_table_header.php');
    $buildDetailsTableRowTemplate = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';

    foreach($buildDetails as &$buildDetail) {
      $build_id = $buildDetail['id'];
      $appname = $buildDetail['appname'];
      $jenkinsbuildurl = urlify2($buildDetail['jenkinsbuildurl']);
      $createdate = $buildDetail['createdate'];
      $svnpath = $buildDetail['svnpath'];
      $trunk = $buildDetail['trunk'];

      if($trunk == 1) {
        $branch = 'trunk';
      }
      else {
        $regex = '#Hastings\/branches\/(.*?)\/#';
        preg_match($regex, $svnpath, $matches);
        $branch = $matches[1];
      }

      if($buildDetail['storedinartifactory'] == 1) {
        $artifactoryurl = $artifactoryBaseUrl . '/' . $buildDetail['artifactoryurl'];
        $artifactoryurl = urlify2($artifactoryurl);
      }
      else {
        $artifactoryurl = 'N/A';
      }

      $buildDetailsTable .= sprintf($buildDetailsTableRowTemplate, $build_id, $appname, $branch, $jenkinsbuildurl, $artifactoryurl, $createdate->format('M d Y h:iA'));
    }

    $buildDetailsTable .= file_get_contents('templates/generic_table_footer.php');
    $html .= $buildDetailsTable;

    $html .= '<br/>';
    $html .= "<h3>Deployment History for SVNRevision <strong>{$svnrevisionnumber}</strong></h3>";

    $deploymentDetailsTable = file_get_contents('templates/deployment_table_header.php');
    $deploymentDetailsRowTemplate = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';

    foreach($buildDeplymentDetails as &$deploymentDetail) {
      $environmentname = $deploymentDetail['environmentname'];
      $build_id = $deploymentDetail['build_id'];
      $deploymentuser = getDeploymentUser($deploymentDetail['deploymentuser']);
      $deploymenturl = urlify2($deploymentDetail['deploymenturl']);
      $deploymentdate = $deploymentDetail['deploymentdate'];

      $deploymentDetailsTable .= sprintf($deploymentDetailsRowTemplate, $environmentname, $build_id, $deploymentuser, $deploymenturl, $deploymentdate);

    }
    
    $deploymentDetailsTable .= file_get_contents('templates/generic_table_footer.php');
    $html .= $deploymentDetailsTable;

    return $html;

  }

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
    $svnrevisionnumber = filter_var($_POST['svnrevisionnumber'], FILTER_SANITIZE_STRING);
    $html = makeBuildDetailsTable($conn, $svnrevisionnumber);

    header('Content-type: application/json');
    $response_array['status'] = 'success';
    $response_array['html'] = $html;

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }
  else {
    $page = file_get_contents('templates/header.php');
    $page .= file_get_contents('templates/navbar.php');
    $page .= file_get_contents("templates/build_search_form.php");

    $page .= "<hr/></div></body></html>";
    $page = eval("?>$page");
    echo $page;
  }

?>