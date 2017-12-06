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
    //No new or delete functionality supported.
  }
  else {
    $userviewtableheader = file_get_contents('../../cmdb/users/templates/userviewtableheader.php');
    $userviewtablefooter = file_get_contents('../../cmdb/users/templates/userviewtablefooter.php');
    $userviewrowtemplate = file_get_contents('../../cmdb/users/templates/userviewrowtemplate.php');

    $users = genericSQLRowsGetNoParams($conn, 'select * from users order by userid');

    $userviewtable = $userviewtableheader;

    foreach($users as &$user) {

      $userid = $user['UserId'];
      $userlogin = $user['UserLogin'];
      $userfirstname = $user['UserFirstname'];
      $usersurname = $user['UserSurname'];
      $useremail = $user['UserEmail'];
      $admin = $user['Admin'] === 1 ? 'Yes' : 'No';
      $disabled = $user['disabled'] === 1 ? 'Yes' : 'No';
      
      $editlink = '/cmdb/users/useredit.php?id=' . (string)$userid;

      $row = sprintf($userviewrowtemplate, $userid, $userlogin, $userfirstname, $usersurname, $useremail, $admin, $disabled, $editlink);
      $userviewtable .= $row;
    }
    $userviewtable .= $userviewtablefooter;

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $userviewtable;
    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>