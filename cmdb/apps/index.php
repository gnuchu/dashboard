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
      // $app_id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
      // $params = array($app_id);
      // $sql = 'delete from apps where id = ?';

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
      $appserver_id = (int)filter_var($_POST['appserver_id'], FILTER_SANITIZE_STRING);
      $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
      $sso = (int)filter_var($_POST['sso'], FILTER_SANITIZE_STRING);
      $batch = (int)filter_var($_POST['batch'], FILTER_SANITIZE_STRING);
      $cluster_id = (int)filter_var($_POST['cluster_id'], FILTER_SANITIZE_STRING);
      $environment_id = (int)filter_var($_POST['environment_id'], FILTER_SANITIZE_STRING);
      $databasesetting_id = (int)filter_var($_POST['databasesetting_id'], FILTER_SANITIZE_STRING);
      $build_id = (int)filter_var($_POST['build_id'], FILTER_SANITIZE_STRING);
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

      $build_id = $build_id == 0 ? NULL : $build_id;

      $params = array($name,
                      $appserver_id,
                      $category,
                      $sso,
                      $batch,
                      $environment_id,
                      $cluster_id,
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
                      $integrationpropertiespath,
                      $integrationpropertiestype,
                      $hidefromdashboard,
                      $islnloglevels,
                      $rootdir);

      $sql = '';
      $sql .= " insert into apps ( name,";
      $sql .= "     appserver_id, ";
      $sql .= "     category, ";
      $sql .= "     sso, ";
      $sql .= "     batch, ";
      $sql .= "     environment_id, ";
      $sql .= "     cluster_id, ";
      $sql .= "     databasesetting_id, ";
      $sql .= "     build_id, ";
      $sql .= "     switchedoff, ";
      $sql .= "     edgedebuglevel, ";
      $sql .= "     edgeconfiglocation, ";
      $sql .= "     edgeloglocation, ";
      $sql .= "     bridgeconfiglocation, ";
      $sql .= "     iissitename, ";
      $sql .= "     iissiteport, ";
      $sql .= "     contextroot, ";
      $sql .= "     integrationpropertiespath, ";
      $sql .= "     integrationpropertiestype, ";
      $sql .= "     hidefromdashboard, ";
      $sql .= "     islnloglevels, ";
      $sql .= "     rootdir, ";
      $sql .= "     created_at, ";
      $sql .= "     updated_at, ";
      $sql .= "     pinglastchecked) ";
      $sql .= " values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, sysdatetime(), sysdatetime(), sysdatetime()) ";

      $result = genericSQLUpdateDelete($conn, $sql, $params);

      if($result == true) {
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
    $appviewtableheader = file_get_contents('../../cmdb/apps/templates/appviewtableheader.php');
    $appviewtablefooter = file_get_contents('../../cmdb/apps/templates/appviewtablefooter.php');
    $appviewrowtemplate = file_get_contents('../../cmdb/apps/templates/appviewrowtemplate.php');

    $apps = genericSQLRowsGetNoParams($conn, 'select * from apps order by id');

    $appviewtable = $appviewtableheader;

    foreach($apps as &$app) {
      $id = $app['id'];
      $appserver_id = $app['appserver_id'];
      $name = $app['name'];
      $category = $app['category'];
      $databasesetting_id = $app['databasesetting_id'];
      $sso = $app['sso'] == 1 ? 'Yes' : 'No';
      $batch = $app['batch'] == 1 ? 'Yes' : 'No';
      $environment_id = $app['environment_id'];
      $build_id = $app['build_id'];
      $switchedoff = $app['switchedoff'] == 1 ? 'Yes' : 'No';;
      $contextroot = $app['contextroot'];
      $cluster_id = $app['cluster_id'];

      $sql = 'select name from environments where id = ?';
      $params = array($environment_id);
      $column_name = 'name';
      $environment_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $sql = 'select name from clusters where id = ?';
      $params = array($cluster_id);
      $column_name = 'name';
      $cluster_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $sql = 'select name from appservers where id = ?';
      $params = array($appserver_id);
      $column_name = 'name';
      $appserver_name = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $sql = "select databaseserver + ',' + databaseport as databaseinfo from databasesettings where id = ?";
      $params = array($databasesetting_id);
      $column_name = 'databaseinfo';
      $database_info = genericSQLReturnValue($conn, $sql, $params, $column_name);

      $editlink = '/cmdb/apps/appedit.php?id=' . (string)$app['id'];

      $row = sprintf($appviewrowtemplate, $id, $name, $appserver_name, $category, $sso, $batch, $database_info, $environment_name, $build_id, $switchedoff, $contextroot, $cluster_name, $editlink);
      $appviewtable .= $row;
    }
    $appviewtable .= $appviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $appviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>