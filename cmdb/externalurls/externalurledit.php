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

  if($_SERVER['REQUEST_METHOD']=='GET') {
    if(isset($_GET["id"])) {
      $id = $_GET["id"];
    }
    else
    {
      header('Location: .');
      die('No Environment passed.');
    }

    $externalurleditform = file_get_contents('../../cmdb/externalurls/templates/externalurleditform.php');
    $sql = 'select * from externalurls where id = ?';
    $params = array($id);

    $externalurlrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $externalurlrow['id'];
    $environment_id = $externalurlrow['environment_id'];
    $app_name = $externalurlrow['app_name'];
    $url = $externalurlrow['url'];
    
    $environment = genericCreateHTMLSelect($conn, 'environments', $environment_id, 'externalurl_environment_name', 'name', 'name', false, false);
    
    $form = sprintf(  $externalurleditform, 
                      $id,
                      $environment,
                      $app_name,
                      $url);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = (int)filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
    $app_name = filter_var($_POST['app_name'], FILTER_SANITIZE_STRING);
    $url = filter_var($_POST['url'], FILTER_SANITIZE_STRING);

    $sql ='';
    $sql .= ' update externalurls set  ';
    $sql .= '   environment_id = ?, ';
    $sql .= '   app_name = ?, ';
    $sql .= '   url = ? ';
    $sql .= ' where id = ? ';

    $params = array($environment_id, $app_name, $url, $id);
    $result = genericSQLUpdateDelete($conn, $sql, $params);

    if($result) {
      header('Content-type: application/json');
      $response_array['status'] = 'success';
    }
    else {
      header('Content-type: application/json');
      $response_array['status'] = 'error';
    }

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }


?>