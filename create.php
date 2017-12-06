<?php
  ini_set( 'session.cookie_httponly', 1 );

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
    $page .= "  <form action='/create.php' method='post'>\n";
    $page .= "    <div class='col-md-offset-5 col-md-3'>\n";
    $page .= "      <div class='form-login'>\n";
    $page .= "        <h4>Sign Up.</h4>\n";
    $page .= "        <input type='text' name='username' id='username' class='form-control input-sm chat-input' placeholder='username' autofocus/>\n";
    $page .= "        <br>\n";
    $page .= "        <input type='password' name='password' id='password' class='form-control input-sm chat-input' placeholder='password' />\n";
    $page .= "        <br>\n";
    $page .= "        <input type='text' name='email' id='email' class='form-control input-sm chat-input' placeholder='email' />\n";
    $page .= "        <br>\n";
    $page .= "        <input type='text' name='firstname' id='firstname' class='form-control input-sm chat-input' placeholder='firstname' />\n";
    $page .= "        <br>\n";
    $page .= "        <input type='text' name='surname' id='surname' class='form-control input-sm chat-input' placeholder='surname' />\n";
    $page .= "        <br>\n";
    $page .= "        <div class='wrapper'>\n";
    $page .= "          <span class='group-btn'>\n";
    $page .= "            <button type='submit' class='btn btn-primary btn-md'>sign up <i class='fa fa-sign-in'></i></button>\n";
    $page .= "          </span>\n";
    $page .= "        </div>\n";
    $page .= "      </div>\n";
    $page .= "    </div>\n";
    $page .= "  </form>\n";
    $page .= "</div>\n";

    $footer = file_get_contents('templates/footer.php');
    $page .= $footer;
    $page = eval("?>$page");
    echo $page;
  }


  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['firstname']) && !empty($_POST['surname'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];
      $firstname = $_POST['firstname'];
      $surname = $_POST['surname'];

      $success = createUser($conn, $username, $password, $email, $firstname, $surname);
      if($success) {
        //Successfully creaed user.
        header('Location: index.php');
        die();
      }
      else {
        header('Location: create.php');
        die();
      }
    }
    else {
      header('Location: login.php');
      die();
    }
  }

?>