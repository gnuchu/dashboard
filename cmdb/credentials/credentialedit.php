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

    $credentialeditform = file_get_contents('../../cmdb/credentials/templates/credentialeditform.php');
    $sql = 'select id, username from credentials where id = ?';
    $params = array($id);

    $credentialrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $credentialrow['id'];
    $username = $credentialrow['username'];
    
    $form = sprintf(  $credentialeditform, 
                      $id,
                      $username);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = (int)filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $sql =' update credentials set username = ?, password = ?, updated_at = sysdatetime() where id = ?';

    $params = array($username, $password, $id);
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