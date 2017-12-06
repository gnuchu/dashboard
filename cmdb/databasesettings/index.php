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
      $databasesetting_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      $params = array($databasesetting_id);
      $sql = 'delete from databasesettings where id = ?';

      $result = genericSQLUpdateDelete($conn, $sql, $params);

      if($result === true) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        echo json_encode($response_array);
      }
      else {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1)));
      }
    }
    else {
      //New
      $databaseserver = filter_var($_POST['databaseserver'], FILTER_SANITIZE_STRING);
      $databaseport = (int)filter_var($_POST['databaseport'], FILTER_SANITIZE_STRING);
      $databasename = filter_var($_POST['databasename'], FILTER_SANITIZE_STRING);
      $credential_id = (int)filter_var($_POST['credential_id'], FILTER_SANITIZE_STRING);
      $readonlycredential_id = (int)filter_var($_POST['readonlycredential_id'], FILTER_SANITIZE_STRING);
      
      $credential_id = $credential_id == 0 ? NULL : $credential_id;
      $readonlycredential_id = $readonlycredential_id == 0 ? NULL : $readonlycredential_id;

      $params = array($databaseserver, $databaseport, $databasename, $credential_id, $readonlycredential_id);
      
      $sql = ' insert into databasesettings (databaseserver, databaseport, databasename, credential_id, readonlycredential_id, created_at, updated_at) ';
      $sql .= ' values (?, ?, ?, ?, ?, sysdatetime(), sysdatetime())';

      $result = genericSQLUpdateDelete($conn, $sql, $params);

      if($result == true) {
        header('Content-type: application/json');
        $response_array['status'] = 'success';
        echo json_encode($response_array);
      }
      else {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 2)));
      }

    }
  }
  else {
    $databasesettingviewtableheader = file_get_contents('../../cmdb/databasesettings/templates/databasesettingviewtableheader.php');
    $databasesettingviewtablefooter = file_get_contents('../../cmdb/databasesettings/templates/databasesettingviewtablefooter.php');
    $databasesettingviewrowtemplate = file_get_contents('../../cmdb/databasesettings/templates/databasesettingviewrowtemplate.php');

    $databasesettings = genericSQLRowsGetNoParams($conn, 'select * from databasesettings order by id');

    $databasesettingviewtable = $databasesettingviewtableheader;

    foreach($databasesettings as &$databasesetting) {
      $id = $databasesetting['id'];
      $databaseserver = $databasesetting['databaseserver'];
      $databaseport = $databasesetting['databaseport'];
      $databasename = $databasesetting['databasename'];
      $credential_id = $databasesetting['credential_id'];
      $readonlycredential_id = $databasesetting['readonlycredential_id'];

      $sql = 'select username from credentials where id = ?';
      $params = array($credential_id);
      $column_name = 'username';
      $credential = genericSQLReturnValue($conn, $sql, $params, $column_name) . ' (' . (int)$credential_id . ')';

      $sql = 'select username from credentials where id = ?';
      $params = array($readonlycredential_id);
      $column_name = 'username';
      if((int)$readonlycredential_id > 0) {
        $readonlycredential = genericSQLReturnValue($conn, $sql, $params, $column_name) . ' (' . (int)$readonlycredential_id . ')';
      }
      else {
        $readonlycredential = "";
      }

      $editlink = '/cmdb/databasesettings/databasesettingedit.php?id=' . (string)$id;
      $deletelink = (string)$id;

      $row = sprintf($databasesettingviewrowtemplate, $id, $id, $databaseserver, $databaseport, $databasename, $credential, $readonlycredential, $editlink, $deletelink);
      $databasesettingviewtable .= $row;
    }
    
    $databasesettingviewtable .= $databasesettingviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $databasesettingviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>