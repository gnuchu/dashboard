<?php

  function buildReferenceTable($rows, $env) {

    $header = file_get_contents('xref/templates/crossreferencetableheader.php');
    $footer = file_get_contents('xref/templates/crossreferencetablefooter.php');
    $rowtemplate = file_get_contents('xref/templates/crossreferencetablerowtemplate.php');

    $html = sprintf($header, $env);

    foreach($rows as &$row) {
      $environment = $row['environment'];
      $environment_id = $row['environment_id'];
      $environment_link = urlify3('/cmdb/environments/environmentedit.php?id=' . (string)$environment_id, (string)$environment_id);

      $server = $row['server'];
      $server_id = $row['server_id'];
      $server_link = urlify3('/cmdb/servers/serveredit.php?id=' . (string)$server_id, (string)$server_id);

      $appserver = $row['appserver'];
      $appserver_id = $row['appserver_id'];
      $appserver_link = urlify3('/cmdb/appservers/appserveredit.php?id=' . (string)$appserver_id, (string)$appserver_id);

      $app = $row['app'];
      $app_id = $row['app_id'];
      $app_link = urlify3('/cmdb/apps/appedit.php?id=' . (string)$app_id, (string)$app_id);

      $databasesetting = $row['databasesetting'];
      $databasesetting_id = $row['databasesetting_id'];
      
      if($databasesetting !== '') {
        $databasesetting_link = urlify3('/cmdb/databasesettings/databasesettingedit.php?id=' . (string)$databasesetting_id, (string)$databasesetting_id);
      }
      else {
        $databasesetting_link = '';
      }

      $credential = $row['credential'];
      $credential_id = $row['credential_id'];

      if($credential !== '') {
        $credential_link = urlify3('/cmdb/credentials/credentialedit.php?id=' . (string)$credential_id, (string)$credential_id);
      }
      else {
        $credential_link = '';
      }
      
      $category = $row['category'];

      $td_row = sprintf($rowtemplate, $environment, $environment_link, $server, $server_link, $appserver, $appserver_link, $app, $app_link, $databasesetting, $databasesetting_link, $credential, $credential_link, $category);
      $html .= $td_row;
    }

    $html .= $footer;

    return $html;
  }

  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once '../externals/database.php';
  require_once '../externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if(!authenticatedAdmin()) {
    header('Location: /index.php');
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $environment = filter_var($_POST['environment'], FILTER_SANITIZE_STRING);
    $data = idReferenceTable($conn, $environment);
    $refTable = buildReferenceTable($data, $environment);

    header('Content-type: application/json');
    $response_array['status'] = 'success';
    $response_array['html'] = $refTable;

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }
  else {
    $page = file_get_contents('../templates/header.php');
    $page .= file_get_contents('../templates/navbar.php');
    $page .= file_get_contents("xref/templates/search_form.php");

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>