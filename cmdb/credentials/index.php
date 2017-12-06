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
      $credential_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      $params = array($credential_id);
      $sql = 'delete from credentials where id = ?';

      if(genericSQLUpdateDelete($conn, $sql, $params)) {
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
      //New
      $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

      $params = array($username, $password);
      $sql = 'insert into credentials (username, password, created_at, updated_at) values ( ?, ?, sysdatetime(), sysdatetime())';

      $result = genericSQLUpdateDelete($conn, $sql, $params);

      if($result == true) {
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
  }
  else {
    $credentialviewtableheader = file_get_contents('../../cmdb/credentials/templates/credentialviewtableheader.php');
    $credentialviewtablefooter = file_get_contents('../../cmdb/credentials/templates/credentialviewtablefooter.php');
    $credentialviewrowtemplate = file_get_contents('../../cmdb/credentials/templates/credentialviewrowtemplate.php');

    $credentials = genericSQLRowsGetNoParams($conn, 'select id, username from credentials order by id');

    $credentialviewtable = $credentialviewtableheader;

    foreach($credentials as &$credential) {
      $id = $credential['id'];
      $username = $credential['username'];
      $password = '**********';

      $editlink = '/cmdb/credentials/credentialedit.php?id=' . (string)$credential['id'];
      $deletelink = (string)$id;

      $row = sprintf($credentialviewrowtemplate, $id, $id, $username, $password, $editlink, $deletelink);
      $credentialviewtable .= $row;
    }
    
    $credentialviewtable .= $credentialviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $credentialviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>