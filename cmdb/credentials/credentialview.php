<?php 

  if(!($_SERVER['REQUEST_METHOD']=='GET')) {
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


  if(isset($_GET["id"])) {
    $id = $_GET["id"];
  }
  else
  {
    header('Location: .');
    die('No Environment passed.');
  }

  $credentialview = file_get_contents('../../cmdb/credentials/templates/credentialviewtemplate.php');
  $rowtemplate = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';

  $sql = 'select  e.name as environment, 
asv.name as appserver, 
s.name as server, 
a.name as app 
from appservers as asv 
join servers as s 
on s.id = asv.server_id 
join apps as a 
on a.appserver_id = asv.id 
join environments as e 
on e.id = s.environment_id 
where asv.credential_id = ? 
order by environment, app';
  $params = array($id);
  $credentials = genericSQLRowsGetParams($conn, $sql, $params);

  $html = sprintf($credentialview, $id);

  foreach($credentials as &$credential) {
    $environment = $credential['environment'];
    $appserver = $credential['appserver'];
    $app = $credential['app'];
    $server = $credential['server'];

    $html .= sprintf($rowtemplate, $environment, $appserver, $app, $server);
  }

  $html .= '</tbody></table>';
  
  $page = file_get_contents('../../templates/header.php');
  $page .= file_get_contents('../../templates/navbar.php');
  $page .= $html;

  $page = eval("?>$page");
  echo $page;

?>