<?php 
  
  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once '../../externals/database.php';
  require_once '../../externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if(!authenticatedAdmin()) {
    header('Location: /index.php');
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
    
    if($action == 'delete') {
      $config_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);

      if(deleteConfigurationItem($conn, $config_id)) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        echo json_encode($response_array);
      }
      else {
        header('Content-type: application/json');
        $response_array['status'] = 'error';
        echo json_encode($response_array);
      }
    }
    elseif ($action == 'new') {
      $config_name = filter_var($_POST['config_name'], FILTER_SANITIZE_STRING);
      $config_server = filter_var($_POST['config_server'], FILTER_SANITIZE_STRING);
      $config_value = filter_var($_POST['config_value'], FILTER_SANITIZE_STRING);

      if(addConfigurationItem($conn, $config_server, $config_name, $config_value)) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        echo json_encode($response_array);
      }
      else {
        header('Content-type: application/json');
        $response_array['status'] = 'error';
        echo json_encode($response_array);
      }
    }
    else {
      die('Unkown action');
    }
  }
  else {
    $parameterviewtableheader = file_get_contents('../../cmdb/globalconfiguration/templates/parameterviewtableheader.php');
    $parameterviewtablefooter = file_get_contents('../../cmdb/globalconfiguration/templates/parameterviewtablefooter.php');
    $parameterviewrowtemplate = file_get_contents('../../cmdb/globalconfiguration/templates/parameterviewrowtemplate.php');

    $parameters = getGlobalConfiguration($conn);

    $parameterviewtable = $parameterviewtableheader;

    foreach($parameters as &$parameter) {
      $server = $parameter['server'];
      $name = $parameter['name'];
      $value = $parameter['value'];
      $value = truncateString($value); //Trim down long strings for prettier display.
      $editlink = '/cmdb/globalconfiguration/parameteredit.php?id=' . (string)$parameter['id'];
      $deletelink = (string)$parameter['id'];

      $row = sprintf($parameterviewrowtemplate, $server, $name, $value, $editlink, $deletelink);
      $parameterviewtable .= $row;
    }
    $parameterviewtable .= $parameterviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $parameterviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>