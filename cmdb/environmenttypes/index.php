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
      $environmenttype_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      $params = array($environmenttype_id);
      $sql = 'delete from environmenttypes where id = ?';

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
      $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $rank = filter_var($_POST['rank'], FILTER_SANITIZE_STRING);
      
      $params = array($description, $rank);
      
      $sql = ' insert into environmenttypes (description, rank, created_at, updated_at) ';
      $sql .= ' values (?, ?, sysdatetime(), sysdatetime())';

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
    $environmenttypeviewtableheader = file_get_contents('../../cmdb/environmenttypes/templates/environmenttypeviewtableheader.php');
    $environmenttypeviewtablefooter = file_get_contents('../../cmdb/environmenttypes/templates/environmenttypeviewtablefooter.php');
    $environmenttypeviewrowtemplate = file_get_contents('../../cmdb/environmenttypes/templates/environmenttypeviewrowtemplate.php');

    $environmenttypes = genericSQLRowsGetNoParams($conn, 'select * from environmenttypes order by id');

    $environmenttypeviewtable = $environmenttypeviewtableheader;

    foreach($environmenttypes as &$environmenttype) {
      $id = $environmenttype['id'];
      $description = $environmenttype['description'];
      $rank = $environmenttype['rank'];

      $editlink = '/cmdb/environmenttypes/environmenttypeedit.php?id=' . (string)$id;
      $deletelink = (string)$id;

      $row = sprintf($environmenttypeviewrowtemplate, $id, $description, $rank, $editlink, $deletelink);
      $environmenttypeviewtable .= $row;
    }
    
    $environmenttypeviewtable .= $environmenttypeviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $environmenttypeviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>