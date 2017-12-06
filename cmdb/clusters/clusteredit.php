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

    $clustereditform = file_get_contents('../../cmdb/clusters/templates/clustereditform.php');
    $sql = 'select * from clusters where id = ?';
    $params = array($id);

    $clusterrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $clusterrow['id'];
    $name = $clusterrow['name'];
    $url = $clusterrow['url'];
    $environment_id = $clusterrow['environment_id'];
    $noclusterurl = createYesNoSelect($clusterrow['noclusterurl'] == 1 ? 'Yes' : 'No', 'cluster_noclusterurl');
    
    $environment = genericCreateHTMLSelect($conn, 'environments', $environment_id, 'cluster_environment_name', 'name', 'name', false, false);

    
    $form = sprintf(  $clustereditform, 
                      $id,
                      $name,
                      $url,
                      $environment,
                      $noclusterurl);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $id = (int)filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $url = filter_var($_POST['url'], FILTER_SANITIZE_STRING);
    $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
    $noclusterurl = (int)filter_var($_POST['noclusterurl'], FILTER_SANITIZE_STRING);

    $sql =' update clusters set name = ?, url = ?, environment_id = ?, noclusterurl = ?, updated_at = sysdatetime() where id = ?';

    $params = array($name, $url, $environment_id, $noclusterurl, $id);
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