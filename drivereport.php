<?php
  ini_set( 'session.cookie_httponly', 1 );
  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if(!authenticatedAdmin()) {
    header('Location: /index.php');
  }

  $header = file_get_contents('templates/header.php');
  $navbar = file_get_contents('templates/navbar.php');
  $footer = file_get_contents('templates/drivespace_footer.html');
  $table_start = file_get_contents('templates/drivespace_table_start.html');
  $table_end = "</tbody></table>";
  $function_template = file_get_contents('templates/function_template.html');

  $div_template = "<div id='piechart%d' style='width: 270px; height: 135px;'></div>";
  $js_inject = "<script>\n";
  $js_inject .= "google.charts.load('current', { 'packages': ['corechart'] });\n";

  $title = "<h2>Disk Space</h2><br/>";
  date_default_timezone_set('Europe/London');
  $heading = sprintf("<h5>Last generated at: %s</h5>", date("D M d, Y G:i"));
  $html = $header . $navbar . $title . $heading . $table_start;

  $tr_s = "<tr>\n";
  $tr_e = "</tr>\n";
  $td_s = "<td>\n";
  $td_e = "</td>\n";

  $i = 0;

  $servers = array('bx-cinappd01.network.uk.ad','bx-cinappd02.network.uk.ad','bx-cinappd03.network.uk.ad');
  
  $all_json = array();

  foreach($servers as &$server) {
    $ps_command = realpath('./scripts/ps_script.ps1');
    $ps_command = str_replace(' ', '` ', $ps_command);
    $command = "powershell -executionpolicy bypass -command $ps_command -server $server";
    $results = shell_exec($command);
    $json = json_decode($results, true);
    array_push($all_json, $json);
  }

  foreach($all_json as &$server) {
    foreach($server as &$disk) {
      $i += 1;
      $computer = $disk['PSComputerName'];
      $diskname = $disk['Name'];
      $disksize = round($disk['Size']/1024/1024/1024, 1);
      $diskfree = round($disk['FreeSpace']/1024/1024/1024, 1);
      $diskused = round($disksize - $diskfree, 1);

      $js_inject .= sprintf($function_template, $i, $diskused, $diskfree, $i, $i);

      $html .= $tr_s;
      $html .= $td_s . $computer . $td_e;
      $html .= $td_s . $diskname . $td_e;
      $html .= $td_s . $disksize . " Gb" . $td_e;
      $html .= $td_s . $diskfree . " Gb" . $td_e;
      $html .= $td_s . $diskused . " Gb" . $td_e;
      $html .= $td_s . (sprintf($div_template, $i)) . $td_e;
      $html .= $tr_e;
    }
  }
  $js_inject .= '</script>';
  
  $html .= $table_end . '</div>';
  $html .= $js_inject;
  $html .= $footer;

  $page = eval("?>$html");
  echo $page;
?>