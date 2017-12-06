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

    $servereditform = file_get_contents('../../cmdb/servers/templates/servereditform.php');

    $sql = 'select * from servers where id = ?';
    $params = array($id);

    $serverrow = genericSQLRowGetParams($conn, $sql, $params);

    $server_id = $serverrow['id'];
    $server_name = $serverrow['name'];
    $server_environment_id = $serverrow['environment_id'];
    $server_environment = genericCreateHTMLSelect($conn, 'environments', $server_environment_id, 'server_environment_name', 'name', 'name', false, false);
    $server_credential_id = $serverrow['credential_id'];
    $server_credential = genericCreateHTMLSelect($conn, 'credentials', $server_credential_id, 'server_credential_username', 'username', 'username', false, false);
    $server_domain = createDomainSelect($conn, $serverrow['domain']);
    $server_datapipe = createCheckbox($serverrow['datapipe'], 'server_datapipe');

    $form = sprintf($servereditform, $server_id, $server_name, $server_environment, $server_domain, $server_datapipe, $server_credential);

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
    $environment_id = filter_var($_POST['environment_name'], FILTER_SANITIZE_STRING); //this is OK - honest.
    $domain = filter_var($_POST['domain'], FILTER_SANITIZE_STRING);
    $datapipe = filter_var($_POST['datapipe'], FILTER_SANITIZE_STRING);
    $credential_id = filter_var($_POST['credential_name'], FILTER_SANITIZE_STRING); //this is OK - double honest.

    $params = array($name, $environment_id, $domain, $datapipe, $credential_id, $id);

    $sql = 'update servers set name = ?, environment_id = ?, domain = ?, datapipe = ?, credential_id = ?, updated_at = sysdatetime() where id = ?';
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