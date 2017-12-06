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
      // $environment_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      // $params = array($environment_id);
      // $sql = 'delete from environments where id = ?';

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
      $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
      $description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
      $owner = filter_var($_POST['owner'],FILTER_SANITIZE_STRING);
      $releasebranch = filter_var($_POST['releasebranch'],FILTER_SANITIZE_STRING);
      $environmenttype = filter_var($_POST['environmenttype'],FILTER_SANITIZE_STRING);
      
      $retired = (int)filter_var($_POST['retired'],FILTER_SANITIZE_STRING);
      $isproduction = (int)filter_var($_POST['isproduction'],FILTER_SANITIZE_STRING);
      $canbebackedupfrom = (int)filter_var($_POST['canbebackedupfrom'],FILTER_SANITIZE_STRING);
      $canberestoredto = (int)filter_var($_POST['canberestoredto'],FILTER_SANITIZE_STRING);
      $extractparametersandproperties = (int)filter_var($_POST['extractparametersandproperties'], FILTER_SANITIZE_STRING);

      $sql = 'insert into environments (name, description, owner, releasebranch, environmenttype_id, ';
      $sql .= ' retired, isproduction, canbebackedupfrom, canberestoredto, extractparametersandproperties, created_at, updated_at) ';
      $sql .= 'values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, sysdatetime(), sysdatetime())';
      $params = array($name, $description, $owner, $releasebranch, $environmenttype, $retired, $isproduction, $canbebackedupfrom, $canberestoredto, $extractparametersandproperties);

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
    $environmentviewtableheader = file_get_contents('../../cmdb/environments/templates/environmentviewtableheader.php');
    $environmentviewtablefooter = file_get_contents('../../cmdb/environments/templates/environmentviewtablefooter.php');
    $environmentviewrowtemplate = file_get_contents('../../cmdb/environments/templates/environmentviewrowtemplate.php');

    $environments = genericSQLRowsGetNoParams($conn, 'select * from environments order by name');

    $environmentviewtable = $environmentviewtableheader;

    foreach($environments as &$environment) {
      $id = $environment['id'];
      $name = $environment['name'];
      $isproduction = $environment['isproduction'] == 1 ? 'Yes' : 'No';
      $description = $environment['description'];
      $retired = $environment['retired'] == 1 ? 'Yes' : 'No';
      $environmenttype_id = $environment['environmenttype_id'];
      $releasebranch = $environment['releasebranch'];
      $owner = $environment['owner'];
      $canbebackedupfrom = $environment['canbebackedupfrom'] == 1 ? 'Yes' : 'No';
      $canberestoredto = $environment['canberestoredto'] == 1 ? 'Yes' : 'No';
      $extractparametersandproperties = $environment['extractparametersandproperties'] == 1 ? 'Yes' : 'No';

      $sql = 'select description from environmenttypes where id = ?';
      $params = array($environmenttype_id);
      $column_name = 'description';
      $environmenttype_description = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $editlink = '/cmdb/environments/environmentedit.php?id=' . (string)$environment['id'];

      $row = sprintf($environmentviewrowtemplate, $id, $name, $isproduction, $description, $retired, $environmenttype_description, $releasebranch, $owner, $canbebackedupfrom, $canberestoredto, $extractparametersandproperties, $editlink);
      $environmentviewtable .= $row;
    }
    $environmentviewtable .= $environmentviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $environmentviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>