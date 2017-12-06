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
      // $appserver_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      // $params = array($appserver_id);
      // $sql = 'delete from appservers where id = ?';

      // if(genericSQLUpdateDelete($conn, $sql, $params)) {
      //   header('Content-type: application/json');
      //   $response_array['status'] = 'success';
      //   echo json_encode($response_array);
      // }
      // else {
      //   header('Content-type: application/json');
      //   $response_array['status'] = 'error';
      //   echo json_encode($response_array);
      // }
    }
    else {
      //New

      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $appservertype = filter_var($_POST['appservertype'], FILTER_SANITIZE_STRING);
      $credential_id = (int)filter_var($_POST['credential_id'], FILTER_SANITIZE_STRING);
      $server_id = (int)filter_var($_POST['server_id'], FILTER_SANITIZE_STRING);
      $servicename = filter_var($_POST['servicename'], FILTER_SANITIZE_STRING);
      $port = (int)filter_var($_POST['port'], FILTER_SANITIZE_STRING);
      $appport = (int)filter_var($_POST['appport'], FILTER_SANITIZE_STRING);
      $nodename = filter_var($_POST['nodename'], FILTER_SANITIZE_STRING);
      $profileroot = filter_var($_POST['profileroot'], FILTER_SANITIZE_STRING);

      $sql = 'insert into appservers (name, appservertype, credential_id, server_id, servicename, port, appport, nodename, profileroot, created_at, updated_at) ';
      $sql .= 'values (?, ?, ?, ?, ?, ?, ?, ?, ?, sysdatetime(), sysdatetime())';
      $params = array($name, $appservertype, $credential_id, $server_id, $servicename, $port, $appport, $nodename, $profileroot);

      $debug = var_export($params, true);

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
  }
  else {
    $appserverviewtableheader = file_get_contents('../../cmdb/appservers/templates/appserverviewtableheader.php');
    $appserverviewtablefooter = file_get_contents('../../cmdb/appservers/templates/appserverviewtablefooter.php');
    $appserverviewrowtemplate = file_get_contents('../../cmdb/appservers/templates/appserverviewrowtemplate.php');

    $appservers = genericSQLRowsGetNoParams($conn, 'select * from appservers order by id');

    $appserverviewtable = $appserverviewtableheader;

    foreach($appservers as &$appserver) {
      $id = $appserver['id'];
      $name = $appserver['name'];
      $appservertype = $appserver['appservertype'];
      $credential_id = $appserver['credential_id'];
      $server_id = $appserver['server_id'];
      $servicename = $appserver['servicename'];
      $port = $appserver['port'];
      $appport = $appserver['appport'];
      $nodename = $appserver['nodename'];
      $profileroot = $appserver['profileroot'];
      
      $sql = 'select username from credentials where id = ?';
      $params = array($credential_id);
      $column_name = 'username';
      $credential_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $sql = 'select name from servers where id = ?';
      $params = array($server_id);
      $column_name = 'name';
      $server_name = genericSQLReturnValue($conn, $sql, $params, $column_name);
      $editlink = '/cmdb/appservers/appserveredit.php?id=' . (string)$appserver['id'];

      $row = sprintf( $appserverviewrowtemplate,
                      $id,
                      $name,
                      $appservertype,
                      $credential_name,
                      $server_name,
                      $servicename,
                      $port,
                      $appport,
                      $nodename,
                      $profileroot,
                      $editlink);

      $appserverviewtable .= $row;
    }
    $appserverviewtable .= $appserverviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $appserverviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>