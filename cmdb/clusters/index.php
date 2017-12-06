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
      // $cluster_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      // $params = array($cluster_id);
      // $sql = 'delete from clusters where id = ?';

      // if(genericSQLUpdateDelete($conn, $sql, $params)) {
      //   header('Content-type: clusterlication/json');
      //   $response_array['status'] = 'success';
      //   echo json_encode($response_array);
      // }
      // else {
      //   header('Content-type: clusterlication/json');
      //   $response_array['status'] = 'error';
      //   echo json_encode($response_array);
      // }
    }
    else {
      //New
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $url = filter_var($_POST['url'], FILTER_SANITIZE_STRING);
      $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
      $noclusterurl = (int)filter_var($_POST['noclusterurl'], FILTER_SANITIZE_STRING);

      $params = array($name, $url, $environment_id, $noclusterurl);
      $sql = 'insert into clusters (name, url, environment_id, noclusterurl, created_at, updated_at) values (?,?,?,?, sysdatetime(), sysdatetime())';

      $result = genericSQLUpdateDelete($conn, $sql, $params);

      if($result == true) {
        header('Content-type: clusterlication/json');
        $response_array['status'] = 'success';
        echo json_encode($response_array);
      }
      else {
        header('Content-type: clusterlication/json');
        $response_array['status'] = 'error';
        echo json_encode($response_array);
      }

    }
  }
  else {
    $clusterviewtableheader = file_get_contents('../../cmdb/clusters/templates/clusterviewtableheader.php');
    $clusterviewtablefooter = file_get_contents('../../cmdb/clusters/templates/clusterviewtablefooter.php');
    $clusterviewrowtemplate = file_get_contents('../../cmdb/clusters/templates/clusterviewrowtemplate.php');

    $clusters = genericSQLRowsGetNoParams($conn, 'select * from clusters order by id');

    $clusterviewtable = $clusterviewtableheader;

    foreach($clusters as &$cluster) {
      $id = $cluster['id'];
      $name = $cluster['name'];
      $url = truncateString($cluster['url']);
      $environment_id = $cluster['environment_id'];
      $noclusterurl = $cluster['noclusterurl'] == 1 ? 'Yes' : 'No';

      $sql = 'select name from environments where id = ?';
      $params = array($environment_id);
      $column_name = 'name';
      $environment_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $editlink = '/cmdb/clusters/clusteredit.php?id=' . (string)$cluster['id'];

      $row = sprintf($clusterviewrowtemplate, $id, $name, $url, $environment_name, $noclusterurl, $editlink);
      $clusterviewtable .= $row;
    }
    $clusterviewtable .= $clusterviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $clusterviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>