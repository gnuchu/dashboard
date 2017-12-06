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
      $externalurl_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      $params = array($externalurl_id);
      $sql = 'delete from externalurls where id = ?';

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
      $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
      $app_name = filter_var($_POST['app_name'], FILTER_SANITIZE_STRING);
      $url = filter_var($_POST['url'], FILTER_SANITIZE_STRING);
      
      $params = array($environment_id, $app_name, $url);
      
      $sql = ' insert into externalurls (environment_id, app_name, url, created_at, updated_at) ';
      $sql .= ' values (?, ?, ?, sysdatetime(), sysdatetime())';

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
    $externalurlviewtableheader = file_get_contents('../../cmdb/externalurls/templates/externalurlviewtableheader.php');
    $externalurlviewtablefooter = file_get_contents('../../cmdb/externalurls/templates/externalurlviewtablefooter.php');
    $externalurlviewrowtemplate = file_get_contents('../../cmdb/externalurls/templates/externalurlviewrowtemplate.php');

    $externalurls = genericSQLRowsGetNoParams($conn, 'select * from externalurls order by id');

    $externalurlviewtable = $externalurlviewtableheader;

    foreach($externalurls as &$externalurl) {
      $id = $externalurl['id'];
      $environment_id = $externalurl['environment_id'];
      $app_name = $externalurl['app_name'];
      $url = $externalurl['url'];

      $sql = 'select name from environments where id = ? ';
      $params = array($environment_id);
      $column_name = 'name';
      $environment_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $editlink = '/cmdb/externalurls/externalurledit.php?id=' . (string)$id;
      $deletelink = (string)$id;

      $row = sprintf($externalurlviewrowtemplate, $id, $environment_name, $app_name, $url, $editlink, $deletelink);
      $externalurlviewtable .= $row;
    }
    
    $externalurlviewtable .= $externalurlviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $externalurlviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>