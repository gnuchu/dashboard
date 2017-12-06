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

    $appservereditform = file_get_contents('../../cmdb/appservers/templates/appservereditform.php');

    $sql = 'select * from appservers where id = ?';
    $params = array($id);

    $appserverrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $appserverrow['id'];
    $name = $appserverrow['name'];
    $appservertype = $appserverrow['appservertype'];
    $credential_id = $appserverrow['credential_id'];
    $server_id = $appserverrow['server_id'];
    $servicename = $appserverrow['servicename'];
    $port = $appserverrow['port'];
    $appport = $appserverrow['appport'];
    $nodename = $appserverrow['nodename'];
    $profileroot = $appserverrow['profileroot'];

    $server = genericCreateHTMLSelect($conn, 'servers', $server_id, 'appserver_server_id', 'name', 'name', false, false);
    $credential = genericCreateHTMLSelect($conn, 'credentials', $credential_id, 'appserver_credential_id', 'username', 'username', false, false);

    $form = sprintf($appservereditform, $id, $name, $appservertype, $credential, $server, $servicename, $port, $appport, $nodename, $profileroot);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
    $credential_id = filter_var($_POST['credential_id'], FILTER_SANITIZE_STRING);
    $server_id = filter_var($_POST['server_id'], FILTER_SANITIZE_STRING);
    $servicename = filter_var($_POST['servicename'], FILTER_SANITIZE_STRING);
    $port = (int)filter_var($_POST['port'], FILTER_SANITIZE_STRING);
    $appport = (int)filter_var($_POST['appport'], FILTER_SANITIZE_STRING);
    $nodename = filter_var($_POST['nodename'], FILTER_SANITIZE_STRING);
    $profileroot = filter_var($_POST['profileroot'], FILTER_SANITIZE_STRING);

    $params = array($name, $type, $credential_id, $server_id, $servicename, $port, $appport, $nodename, $profileroot, $id);

    $sql = 'update appservers set name = ?, appservertype = ?, credential_id = ?, server_id = ?, servicename = ?, port = ?, appport = ?, ';
    $sql .= ' nodename = ?, profileroot = ? where id = ?';
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