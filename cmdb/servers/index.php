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
      // $server_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      // $params = array($server_id);
      // $sql = 'delete from servers where id = ?';

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
      $environment_id = filter_var($_POST['environment_name'], FILTER_SANITIZE_STRING);
      $domain = filter_var($_POST['domain'], FILTER_SANITIZE_STRING);
      $datapipe = filter_var($_POST['datapipe'], FILTER_SANITIZE_STRING);
      $credential_id = filter_var($_POST['credential_name'], FILTER_SANITIZE_STRING);

      $sql = 'insert into servers (name, environment_id, domain, datapipe, credential_id, created_at, updated_at)';
      $sql .= 'values (?, ?, ?, ?, ?, sysdatetime(), sysdatetime())';
      $params = array($name, $environment_id, $domain, $datapipe, $credential_id);

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
    $serverviewtableheader = file_get_contents('../../cmdb/servers/templates/serverviewtableheader.php');
    $serverviewtablefooter = file_get_contents('../../cmdb/servers/templates/serverviewtablefooter.php');
    $serverviewrowtemplate = file_get_contents('../../cmdb/servers/templates/serverviewrowtemplate.php');

    $servers = genericSQLRowsGetNoParams($conn, 'select * from servers order by id');

    $serverviewtable = $serverviewtableheader;

    foreach($servers as &$server) {
      $id = $server['id'];
      $name = $server['name'];
      $environment_id = $server['environment_id'];
      $domain = $server['domain'];
      $datapipe = (int)$server['datapipe'] == 1 ? 'Yes' : 'No';
      $credential_id = $server['credential_id'];

      $sql = 'select name from environments where id = ?';
      $params = array($environment_id);
      $column_name = 'name';
      $environment_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $sql = 'select username from credentials where id = ?';
      $params = array($credential_id);
      $column_name = 'username';
      $credential_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $editlink = '/cmdb/servers/serveredit.php?id=' . (string)$server['id'];

      $row = sprintf($serverviewrowtemplate, $id, $name, $environment_name, $domain, $datapipe, $credential_name, $editlink);
      $serverviewtable .= $row;
    }
    $serverviewtable .= $serverviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $serverviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>