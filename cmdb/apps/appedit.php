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

    $appeditform = file_get_contents('../../cmdb/apps/templates/appeditform.php');
    $sql = 'select * from apps where id = ?';
    $params = array($id);

    $approw = genericSQLRowGetParams($conn, $sql, $params);

    $id = $approw['id'];
    $appserver_id = $approw['appserver_id'];
    $name = $approw['name'];
    $category = $approw['category'];
    $sso = createYesNoSelect($approw['sso'] == 1 ? 'Yes' : 'No', 'app_sso');
    $batch = createYesNoSelect($approw['batch'] == 1 ? 'Yes' : 'No', 'app_batch');
    $environment_id = $approw['environment_id'];
    $databasesetting_id = $approw['databasesetting_id'];
    $build_id = $approw['build_id'];
    $switchedoff = createYesNoSelect($approw['switchedoff'] == 1 ? 'Yes' : 'No', 'app_switchedoff');
    $edgedebuglevel = $approw['edgedebuglevel'];
    $edgeconfiglocation = $approw['edgeconfiglocation'];
    $edgeloglocation = $approw['edgeloglocation'];
    $bridgeconfiglocation = $approw['bridgeconfiglocation'];
    $iissitename = $approw['iissitename'];
    $iissiteport = $approw['iissiteport'];
    $contextroot = $approw['contextroot'];
    $cluster_id = $approw['cluster_id'];
    $integrationpropertiespath = $approw['integrationpropertiespath'];
    $integrationpropertiestype = $approw['integrationpropertiestype'];
    $hidefromdashboard = createYesNoSelect($approw['hidefromdashboard'] == 1 ? 'Yes' : 'No', 'app_hidefromdashboard');
    $islnloglevels = $approw['islnloglevels'];
    $rootdir = $approw['rootdir'];

    $appserver = genericCreateHTMLSelect($conn, 'appservers', $appserver_id, 'app_appserver_name', 'name, id', 'name', false, false);
    $environment = genericCreateHTMLSelect($conn, 'environments', $environment_id, 'app_environment_name', 'name', 'name', false, false);
    $cluster = genericCreateHTMLSelect($conn, 'clusters', $cluster_id, 'app_cluster_name', 'name', 'name', false, false);
    $databasesetting = genericCreateHTMLSelect($conn, 'databasesettings', $databasesetting_id, 'app_databasesetting_name', 'databaseserver', 'databaseserver', false, false);

    $form = sprintf(  $appeditform, 
                      $id,
                      $name,
                      $appserver,
                      $category,
                      $sso,
                      $batch,
                      $environment,
                      $cluster,
                      $databasesetting,
                      $build_id,
                      $switchedoff,
                      $edgedebuglevel,
                      $edgeconfiglocation,
                      $edgeloglocation,
                      $bridgeconfiglocation,
                      $iissitename,
                      $iissiteport,
                      $contextroot,
                      $integrationpropertiespath,
                      $integrationpropertiestype,
                      $hidefromdashboard,
                      $islnloglevels,
                      $rootdir);

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
    $appserver_id = (int)filter_var($_POST['appserver_id'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $sso = (int)filter_var($_POST['sso'], FILTER_SANITIZE_STRING);
    $batch = (int)filter_var($_POST['batch'], FILTER_SANITIZE_STRING);
    $cluster_id = (int)filter_var($_POST['cluster_id'], FILTER_SANITIZE_STRING);
    $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
    $databasesetting_id = (int)filter_var($_POST['databasesetting_id'], FILTER_SANITIZE_STRING);
    
    $build_id = (int)filter_var($_POST['build_id'], FILTER_SANITIZE_STRING);
    $build_id = ($build_id == 0) ? NULL : $build_id;

    $switchedoff = (int)filter_var($_POST['switchedoff'], FILTER_SANITIZE_STRING);
    $edgedebuglevel = filter_var($_POST['edgedebuglevel'], FILTER_SANITIZE_STRING);
    $edgeconfiglocation = filter_var($_POST['edgeconfiglocation'], FILTER_SANITIZE_STRING);
    $edgeloglocation = filter_var($_POST['edgeloglocation'], FILTER_SANITIZE_STRING);
    $bridgeloglocation = filter_var($_POST['bridgeloglocation'], FILTER_SANITIZE_STRING);
    $iissitename = filter_var($_POST['iissitename'], FILTER_SANITIZE_STRING);
    $iissiteport = (int)filter_var($_POST['iissiteport'], FILTER_SANITIZE_STRING);
    $contextroot = filter_var($_POST['contextroot'], FILTER_SANITIZE_STRING);
    $integrationpropertiespath = filter_var($_POST['integrationpropertiespath'], FILTER_SANITIZE_STRING);
    $integrationpropertiestype = filter_var($_POST['integrationpropertiestype'], FILTER_SANITIZE_STRING);
    $hidefromdashboard = (int)filter_var($_POST['hidefromdashboard'], FILTER_SANITIZE_STRING);
    $islnloglevels = filter_var($_POST['islnloglevels'], FILTER_SANITIZE_STRING);
    $rootdir = filter_var($_POST['rootdir'], FILTER_SANITIZE_STRING);

    $sql = '';
    $sql .=' update apps set ';
    $sql .='  appserver_id = ?, ';
    $sql .='  name = ?, ';
    $sql .='  category = ?, ';
    $sql .='  sso = ?, ';
    $sql .='  batch = ?, ';
    $sql .='  environment_id = ?, ';
    $sql .='  databasesetting_id = ?, ';
    $sql .='  build_id = ?, ';
    $sql .='  switchedoff = ?, ';
    $sql .='  edgedebuglevel = ?, ';
    $sql .='  edgeconfiglocation = ?, ';
    $sql .='  edgeloglocation = ?, ';
    $sql .='  bridgeconfiglocation = ?, ';
    $sql .='  iissitename = ?, ';
    $sql .='  iissiteport = ?, ';
    $sql .='  contextroot = ?, ';
    $sql .='  updated_at = sysdatetime(), ';
    $sql .='  cluster_id = ?, ';
    $sql .='  integrationpropertiespath = ?, ';
    $sql .='  integrationpropertiestype = ?, ';
    $sql .='  hidefromdashboard = ?, ';
    $sql .='  islnloglevels = ?, ';
    $sql .='  rootdir = ? ';
    $sql .='where id = ? ';

    $params = array($appserver_id,
      $name, 
      $category, 
      $sso, 
      $batch, 
      $environment_id, 
      $databasesetting_id, 
      $build_id, 
      $switchedoff, 
      $edgedebuglevel, 
      $edgeconfiglocation, 
      $edgeloglocation, 
      $bridgeloglocation, 
      $iissitename, 
      $iissiteport, 
      $contextroot, 
      $cluster_id, 
      $integrationpropertiespath, 
      $integrationpropertiestype, 
      $hidefromdashboard, 
      $islnloglevels, 
      $rootdir, 
      $id);
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