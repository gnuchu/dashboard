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

    $usereditform = file_get_contents('../../cmdb/users/templates/usereditform.php');

    $sql = 'select * from users where userid = ?';
    $params = array($id);

    $userrow = genericSQLRowGetParams($conn, $sql, $params);

    $userid = $userrow['UserId'];
    $userlogin = $userrow['UserLogin'];
    $userfirstname = $userrow['UserFirstname'];
    $usersurname = $userrow['UserSurname'];
    $useremail = $userrow['UserEmail'];
    $admin = $userrow['Admin'] === 1 ? 'Yes' : 'No';
    $disabled = $userrow['disabled'] === 1 ? 'Yes' : 'No';

    $admin_select = createYesNoSelect($admin, 'user_admin');
    $disabled_select = createYesNoSelect($disabled, 'user_disabled');

    $form = sprintf($usereditform, $userid, $userlogin, $userfirstname, $usersurname, $useremail, $admin_select, $disabled_select);

    $page = file_get_contents('../../templates/header.php');
    $page .= file_get_contents('../../templates/navbar.php');
    $page .= $form;

    $page = eval("?>$page");
    echo $page;
  }
  else {
    //POST
    $user_userid = filter_var($_POST['user_userid'], FILTER_SANITIZE_STRING);
    $user_userlogin = filter_var($_POST['user_userlogin'], FILTER_SANITIZE_STRING);
    $user_userfirstname = filter_var($_POST['user_userfirstname'], FILTER_SANITIZE_STRING);
    $user_usersurname = filter_var($_POST['user_usersurname'], FILTER_SANITIZE_STRING);
    $user_useremail = filter_var($_POST['user_useremail'], FILTER_SANITIZE_STRING);
    $user_admin = filter_var($_POST['user_admin'], FILTER_SANITIZE_STRING) === 'Yes' ? 1 : 0;
    $user_disabled = filter_var($_POST['user_disabled'], FILTER_SANITIZE_STRING)  === 'Yes' ? 1 : 0;

    $params = array($user_userlogin, $user_userfirstname, $user_usersurname, $user_useremail, $user_admin, $user_disabled, $user_userid);
    $sql = 'update users set userlogin = ?, userfirstname = ?, usersurname = ?, useremail = ?, admin = ?, disabled = ? where userid = ?';
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