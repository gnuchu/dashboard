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

    $environmenteditform = file_get_contents('../../cmdb/environments/templates/environmenteditform.php');

    $sql = 'select * from environments where id = ?';
    $params = array($id);

    $environmentrow = genericSQLRowGetParams($conn, $sql, $params);

    $id = $environmentrow['id'];
    $name = $environmentrow['name'];
    $description = $environmentrow['description'];
    $releasebranch = $environmentrow['releasebranch'];
    $owner = $environmentrow['owner'];
    
    $retired = $environmentrow['retired'] == 1 ? 'Yes' : 'No';
    $isproduction = $environmentrow['isproduction'] == 1 ? 'Yes' : 'No';
    $canbebackedupfrom = $environmentrow['canbebackedupfrom'] == 1 ? 'Yes' : 'No';
    $canberestoredto = $environmentrow['canberestoredto'] == 1 ? 'Yes' : 'No';
    $extractparametersandproperties = $environmentrow['extractparametersandproperties'] == 1 ? 'Yes' : 'No';

    $retired = createYesNoSelect($retired, 'environment_retired');
    $isproduction = createYesNoSelect($isproduction, 'environment_isproduction');
    $canbebackedupfrom = createYesNoSelect($canbebackedupfrom, 'environment_canbebackedupfrom');
    $canberestoredto = createYesNoSelect($canberestoredto, 'environment_canberestoredto');
    $extractparametersandproperties = createYesNoSelect($extractparametersandproperties, 'environment_extractparametersandproperties');

    $environmenttype_id = $environmentrow['environmenttype_id'];
    $environmenttype_description = genericCreateHTMLSelect($conn, 'environmenttypes', $environmenttype_id, 'environment_environmenttype', 'description', 'description', false, false);

    $form = sprintf($environmenteditform,
                    $id,
                    $name,
                    $description,
                    $releasebranch,
                    $owner,
                    $environmenttype_description,
                    $retired,
                    $isproduction,
                    $canbebackedupfrom,
                    $canberestoredto,
                    $extractparametersandproperties);

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
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $releasebranch = filter_var($_POST['releasebranch'], FILTER_SANITIZE_STRING);
    $owner = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);
    $environmenttype = (int)filter_var($_POST['environmenttype'], FILTER_SANITIZE_STRING);
    $retired = filter_var($_POST['retired'], FILTER_SANITIZE_STRING);
    $isproduction = filter_var($_POST['isproduction'], FILTER_SANITIZE_STRING);
    $canbebackedupfrom = filter_var($_POST['canbebackedupfrom'], FILTER_SANITIZE_STRING);
    $canberestoredto = filter_var($_POST['canberestoredto'], FILTER_SANITIZE_STRING);
    $extractparametersandproperties = filter_var($_POST['extractparametersandproperties'], FILTER_SANITIZE_STRING);

    $params = array($name, $description, $releasebranch, $owner, $environmenttype, $retired, $isproduction, $canbebackedupfrom, $canberestoredto, $extractparametersandproperties, $id);

    $sql = 'update environments set name = ?, description = ?, releasebranch = ?, owner = ?, environmenttype_id = ?, retired = ?, ';
    $sql .= 'isproduction = ?, canbebackedupfrom = ?, canberestoredto = ?, extractparametersandproperties = ?, updated_at = sysdatetime() ';
    $sql .= 'where id = ?';

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