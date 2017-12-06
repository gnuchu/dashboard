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

    $environmenttypeeditform = file_get_contents('../../cmdb/environmenttypes/templates/environmenttypeeditform.php');
    $sql = 'select * from environmenttypes where id = ?';
    $params = array($id);

    $environmenttyperow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $environmenttyperow['id'];
    $description = $environmenttyperow['description'];
    $rank = $environmenttyperow['rank'];
    
    $form = sprintf(  $environmenttypeeditform, 
                      $id,
                      $description,
                      $rank);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = (int)filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $rank = (int)filter_var($_POST['rank'], FILTER_SANITIZE_STRING);

    $sql ='';
    $sql .= ' update environmenttypes set  ';
    $sql .= '   description = ?, ';
    $sql .= '   rank = ? ';
    $sql .= ' where id = ? ';

    $params = array($description, $rank, $id);
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