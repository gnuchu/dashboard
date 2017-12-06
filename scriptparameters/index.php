<?php

  function buildscriptparamsPageForEnv($conn, $env_id) {
    $sql = 'select a.name as appname, ip.* from scriptparameters as ip join apps as a on ip.app_id = a.id where ip.environment_id = ? order by a.name, ip.name';
    $params = array($env_id);
    $scriptparams = genericSQLRowsGetParams($conn, $sql, $params);

    $scriptparamstableheader = file_get_contents('templates/scriptparamstabletableheader.php');
    $scriptparamstablefooter = file_get_contents('templates/scriptparamstabletablefooter.php');
    $scriptparamsrowtemplate = "<tr><td>%s</td><td>%s</td><td>%s</td></tr>";

    $html = $scriptparamstableheader;

    foreach($scriptparams as &$scriptparam) {
      $row = sprintf($scriptparamsrowtemplate, $scriptparam['appname'], $scriptparam['name'], $scriptparam['value']);
      $html .= $row;
    }
    $html .= $scriptparamstablefooter;
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

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $environment_id = filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
    $html = buildscriptparamsPageForEnv($conn, $environment_id);

    header('Content-type: application/json');
    $response_array['status'] = 'success';
    $response_array['html'] = $html;

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }
  else {
    $page = file_get_contents('../templates/header.php');
    $page .= file_get_contents('../templates/navbar.php');
    $page .= file_get_contents("templates/scriptparams_select_form.php");

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>