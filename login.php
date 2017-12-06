<?php 
  ini_set( 'session.cookie_httponly', 1 );
  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  if(isset($_SESSION['authenticated'])) {
    header("location: /index.php");
    die();
  }
  
  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if($_SERVER['REQUEST_METHOD'] == 'GET') {

    $header = file_get_contents('templates/header.php');
    $page = $header;

    $page .= "<div class='row'>\n";
    $page .= "  <form action='/login.php' method='post'>\n";
    $page .= "    <div class='col-md-offset-5 col-md-3'>\n";
    $page .= "      <div class='form-login'>\n";
    $page .= "        <h4>Welcome back.</h4>\n";
    $page .= "        <input type='text' name='username' id='username' class='form-control input-sm chat-input' placeholder='username' autofocus/>\n";
    $page .= "        <br>\n";
    $page .= "        <input type='password' name='password' id='password' class='form-control input-sm chat-input' placeholder='password' />\n";
    $page .= "        <br>\n";
    $page .= "        <div class='wrapper'>\n";
    $page .= "          <span class='group-btn'>\n";
    $page .= "            <button type='submit' class='btn btn-primary btn-md'>login <i class='fa fa-sign-in'></i></button>\n";
    $page .= "          </span>\n";
    $page .= "        </div>\n";
    $page .= "      </div>\n";
    $page .= "    </div>\n";
    $page .= "  </form>\n";
    $page .= "</div>\n";

    $footer = '<hr/></div></body></html>';
    $page .= $footer;
    $page = eval("?>$page");
    echo $page;
  }


  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
      $u = $_POST['username'];
      $p = $_POST['password'];

      $userdata = authenticateUser($conn, $u, $p);
      if(!empty($userdata)) {
        //Successfully logged in.
        if(session_status() ===PHP_SESSION_NONE) {
          session_start();
        }

        $_SESSION['authenticated'] = true;
        $_SESSION['UserLogin'] = $userdata['UserLogin'];
        $_SESSION['UserFirstname'] = $userdata['UserFirstname'];
        $_SESSION['UserEmail'] = $userdata['UserEmail'];
        $_SESSION['UserSurname'] = $userdata['UserSurname'];
        $_SESSION['Admin'] = $userdata['Admin'];
        header('Location: index.php');
        die();
      }
      else {
        header('Location: login.php');
        die();
      }
    }
    else {
      header('Location: login.php');
      die();
    }
  }

?>