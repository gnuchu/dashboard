<?php

  ini_set( 'session.cookie_httponly', 1 );
  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  $header = file_get_contents('templates/header.php');
  $navbar = file_get_contents('templates/navbar.php');
  $footer = "<hr/></div></body></html>";

  $location = "http://bx-cinappd02.network.uk.ad:9001/";
  $frame = "<iframe width='100%' height='1000px' frameborder=0 scrolling='auto' src='$location'></iframe>";
  $html = $header . $navbar . $frame . $footer;

  $html = eval("?>$html");
  echo $html;

?>