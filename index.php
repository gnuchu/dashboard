<?php
  ini_set( 'session.cookie_httponly', 1 );
  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  $refresh = 0;
  if(isset($_GET['refresh'])) {
    $refresh = 1;
  }

  $page = "";
  if($refresh===0) {
    $page .= file_get_contents('templates/index_header.php');
  }

  $page .= file_get_contents('templates/navbar.php');
  if(authenticatedAdmin()) {
    $lastUpdate = getLastPingUpdate($conn);
    $lu = '<small>' . $lastUpdate . '</small>';
    $page .= $lu;
  }

  $page .= file_get_contents('templates/tstart.php');

  define('img_tag_template', "<img src='%s' alt='Site Status' title='%s' height='20' width='20'>");
  $prod_versions = getEnvironmentVersions($conn, 'PRODUCTION');

  $rn = 0;
  $hidden_row_template = file_get_contents("templates/hidden_row_template.php");
  $section_header_template = '<tr class="sectionheader"><td colspan="15">%s</td></tr>';

  $all_statuses = getAllEnvStatus($conn);
  $old_type = 0;

  foreach( $all_statuses as &$row ) {
    $rn += 1;
    if($row['typerank'] !== $old_type) {
      $old_type = $row['typerank'];
      $page .= sprintf($section_header_template, strongify($row['typedesc']));
    }

    $img_tag = "";
    $env = $row['environmentname'];
    $status = $row['status'];
    $lastChecked = $row['lastchecked'];
    $desc = $row['environmentdescription'];

    $phrase = ago(dateSubtract($lastChecked));
    switch($status) {
      case "up":
        $img_tag = sprintf(img_tag_template, "assets/img/blue.png", $phrase);
        break;
      case "unstable":
        $img_tag = sprintf(img_tag_template, "assets/img/yellow.png", $phrase);
        break;
      case "down":
        $img_tag = sprintf(img_tag_template, "assets/img/red.png", $phrase);
        break;
    }

    $env_versions = getEnvironmentVersions($conn, $env);
    $html_row = "<tr>";
    $html_row .= "<td data-toggle='collapse' data-target='#info_$rn'>" . '<i id="#toggle_$rn" class="fa fa-plus-square-o" aria-hidden="true"></i>' . '</td>';
    $html_row .= "<td align='center'>" . $img_tag . "</td>";
    
    if( $env === 'PRODUCTION' || $env === 'PREPROD') {
      if(authenticated()) {
        $html_row .= "<td align='center' title='$desc'><a href='detailsview.php?env=$env'>" . $env . "</a></td>";
      }
      else {
        $html_row .= "<td align='center' title='$desc'>" . $env . "</td>";
      }
    }
    else {
      $html_row .= "<td align='center' title='$desc'><a href='detailsview.php?env=$env'>" . $env . "</a></td>";
    }
    
    $html_row .= sprintf("<td align='center'><small>%s</small></td>", $env_versions['releasebranch']);

    $html_row .= buildTableElement($conn, $env, 'bc', 'QH', $env_versions['bc_QH'], $prod_versions['bc_QH'], 'left-border');
    $html_row .= buildTableElement($conn, $env, 'pc', 'QH', $env_versions['pc_QH'], $prod_versions['pc_QH'], '');
    $html_row .= buildTableElement($conn, $env, 'bc', 'SOR', $env_versions['bc_SOR'], $prod_versions['bc_SOR'], 'left-border');
    $html_row .= buildTableElement($conn, $env, 'cc', 'SOR', $env_versions['cc_SOR'], $prod_versions['cc_SOR'], '');
    $html_row .= buildTableElement($conn, $env, 'ab', 'SOR', $env_versions['ab_SOR'], $prod_versions['ab_SOR'], '');
    $html_row .= buildTableElement($conn, $env, 'pc', 'SOR', $env_versions['pc_SOR'], $prod_versions['pc_SOR'], '');
    $html_row .= buildTableElement($conn, $env, 'ec', 'Digital', $env_versions['ec_Digital'], $prod_versions['ec_Digital'], 'left-border');
    $html_row .= buildTableElement($conn, $env, 'bridge', 'Digital', $env_versions['bridge_Digital'], $prod_versions['bridge_Digital'], '');
    $html_row .= buildTableElement($conn, $env, 'isl', 'ISL',     $env_versions['isl_ISL'] ?? 0, $prod_versions['isl_ISL'] ?? 0, 'left-border');
    $html_row .= buildTableElement($conn, $env, 'pss', 'PSS',     $env_versions['pss_PSS'] ?? 0, $prod_versions['pss_PSS'] ?? 0, '');
    $html_row .= buildTableElement($conn, $env, 'polaris', 'polaris', $env_versions['polaris_polaris'] ?? 0, $prod_versions['polaris_polaris'] ?? 0, '');

    $html_row .= '</tr>';
    $html_row .= buildHiddenRow($conn, $hidden_row_template, $rn, $env, $desc);

    $page .= $html_row . "\n";
  }
  $page .= file_get_contents('templates/tend.php');
  
  $key_template = file_get_contents('templates/html_row_template.html');
  $status_key = file_get_contents('templates/status_key_table.html');
  $versions_key = file_get_contents('templates/app_versions_key_table.html');
  $page .= sprintf($key_template, $status_key . $versions_key);

  $page .= '<hr/></div></body></html>';
  $page = eval("?>$page");
  echo $page;
?>