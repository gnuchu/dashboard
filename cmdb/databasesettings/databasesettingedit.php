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

    $databasesettingeditform = file_get_contents('../../cmdb/databasesettings/templates/databasesettingeditform.php');
    $sql = 'select * from databasesettings where id = ?';
    $params = array($id);

    $databasesettingrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $databasesettingrow['id'];
    $databaseserver = $databasesettingrow['databaseserver'];
    $databaseport = $databasesettingrow['databaseport'];
    $databasename = $databasesettingrow['databasename'];
    
    $credential_id = $databasesettingrow['credential_id'];
    $readonlycredential_id = $databasesettingrow['readonlycredential_id'];

    $credential_username = genericCreateHTMLSelect($conn, 'credentials', $credential_id, 'databasesetting_credential_username', 'username', 'username', false, true);
    if((int)$readonlycredential_id > 0) {
      $readonlycredential_username = genericCreateHTMLSelect($conn, 'credentials', $readonlycredential_id, 'databasesetting_readonlycredential_username', 'username', 'username', false, true);
    } 
    else {
      $readonlycredential_username = genericCreateHTMLSelect($conn, 'credentials', 0, 'databasesetting_readonlycredential_username', 'username', 'username', true, true);
    }
    
    $form = sprintf(  $databasesettingeditform, 
                      $id,
                      $databaseserver,
                      $databaseport,
                      $databasename,
                      $credential_username,
                      $readonlycredential_username );

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = (int)filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $databaseserver = filter_var($_POST['databaseserver'], FILTER_SANITIZE_STRING);
    $databaseport = (int)filter_var($_POST['databaseport'], FILTER_SANITIZE_STRING);
    $databasename = filter_var($_POST['databasename'], FILTER_SANITIZE_STRING);
    $credential_id = (int)filter_var($_POST['credential_id'], FILTER_SANITIZE_STRING);
    $readonlycredential_id = (int)filter_var($_POST['readonlycredential_id'], FILTER_SANITIZE_STRING);

    $credential_id = $credential_id == 0 ? NULL : $credential_id;
    $readonlycredential_id = $readonlycredential_id == 0 ? NULL : $readonlycredential_id;

    $sql ='';
    $sql .= ' update databasesettings set  ';
    $sql .= '   databaseserver = ?, ';
    $sql .= '   databaseport = ?, ';
    $sql .= '   databasename = ?, ';
    $sql .= '   credential_id = ?, ';
    $sql .= '   readonlycredential_id = ? ';
    $sql .= ' where id = ? ';

    $params = array($databaseserver, $databaseport, $databasename, $credential_id, $readonlycredential_id, $id);
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