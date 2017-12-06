<?php

  function buildPropsPageForEnv($conn, $env_id) {
    $sql = 'select  a.name as appname, a.category, ip.* from integrationproperties as ip join apps as a on ip.app_id = a.id where ip.environment_id = ? order by a.name, a.category, ip.name';
    $params = array($env_id);
    $props = genericSQLRowsGetParams($conn, $sql, $params);

    $propstableheader = file_get_contents('templates/propstabletableheader.php');
    $propstablefooter = file_get_contents('templates/propstabletablefooter.php');
    $propsrowtemplate = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

    $html = $propstableheader;

    foreach($props as &$prop) {
      $prop_value = $prop['value'];
      $prop_name = $prop['name'];

      if(strpos($prop_name, 'PASSWORD') !== False || strpos($prop_name, 'password') !== False) {
        $prop_value = passwordify($prop_value);
      }
      $row = sprintf($propsrowtemplate, $prop['appname'], $prop['category'], $prop_name, $prop_value);
      $html .= $row;
    }
    $html .= $propstablefooter;
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
    $html = buildPropsPageForEnv($conn, $environment_id);

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
    $page .= file_get_contents("templates/properties_select_form.php");

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>