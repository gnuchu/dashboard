<?php

  define('STR_MAX_LENGTH', 50);

  function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
  }

  function authenticatedAdmin() {
    if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true && $_SESSION['Admin'] == 1) {
      return true;
    }
    else {
      return false;
    }
  }

  function authenticated() {
    if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
      return true;
    }
    else {
      return false;
    }
  }

  function passwordify($str) {
    $chars = str_split($str);
    $returnStr = '';
    foreach($chars as &$char) {
      $returnStr .= '*';
    }
    return $returnStr;
  }

  function printTraceDebug($file, $line) {
    $debug = $file . ' - ' . $line;
    file_put_contents('debug.log', $debug);
  }

  function truncateString($str) {

    if(strlen($str) < STR_MAX_LENGTH) {
      return $str;
    }
    else {
      $r = substr($str, 0, STR_MAX_LENGTH);
      $r .= '...';
      return $r;
    }
  }

  function authenticateUser($conn, $u, $p) {
    //Stub
    if(empty($u) || empty($p)) {
      return NULL;
    }

    $userrow = getUserDetails($conn, $u);
    $salted_password = $p . $userrow['Salt'];
    $hashed_password = hash("sha256", $salted_password, false);

    if($hashed_password === $userrow['UserPassword'] && $userrow['disabled']===0) {
      return $userrow;
    } else {
      return false;
    }
  }

  function createUser($conn, $username, $password, $email, $firstname, $surname) {
    if(empty($username) || empty($password) || empty($email) || empty($firstname) || empty($surname)) {
      return false;
    }

    $salt = uniqid(mt_rand(), true);
    $salted_password = $password . $salt;
    $hashed_password = hash("sha256", $salted_password, false);
    $success = createUserDatabase($conn, $username, $hashed_password, $email, $firstname, $surname, $salt, 0);
    if($success) {
      return true;
    }
    else {
      return false;
    }
  }

  function buildTableElement($conn, $env, $app, $category, $revisionNumber, $prodVersion, $extraClass) {
    $td_class = "unset";
    $tooltip = "";

    $appStatus = getAppStatus($conn, $env, $app, $category);

    if($appStatus['switchedoff'] === 1 && $appStatus['switchedoff'] === $appStatus['installed']) {
      $td_class = "app-switched-off";
      $revisionNumber = '';
      $tooltip = "App switched off in this environment";
    }
    else {
      if( $appStatus['installed'] === 0 || $revisionNumber === 0 ) {
        $td_class = "app-not-deployed";
        $revisionNumber = '';
        $tooltip = "App not deployed in this environment";
      }
      else {
        if((is_null($revisionNumber) || $revisionNumber === 'Not available' )) {
          $td_class = "no-version-information-available";
          $revisionNumber = '';
          $tooltip = "Revision information not available";
        }
        elseif (intval($revisionNumber) == intval($prodVersion)) {
          $td_class = "equals-production";
          $tooltip = "Same as production";
        }
        elseif (intval($revisionNumber) > intval($prodVersion)) {
          $td_class = "greater-than-production";
          $tooltip = "Newer version than Production";
        }
        elseif (intval($revisionNumber) < intval($prodVersion) && intval($revisionNumber) !== 0) {
          $td_class = "less-than-production";
          $tooltip = "Older version than Production";
        }
      }
    }
    
    $td = <<<EOT
<td class="$td_class $extraClass text-center" title="$tooltip">$revisionNumber</td>
EOT;

    return $td;
  }

  function buildHiddenRow($conn, $template, $row_number, $environment, $description) {
    $database_rows = getServersAndStatus($conn, $environment);
    
    if(authenticatedAdmin()) {
      $row_template = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
    }
    else {
      $row_template = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
    }
    $img_tag_template = "<img src='assets/img/%s.png' alt='Site Status' title='%s' height='15' width='15'>";

    $img_tag = "";
    $rows = "";
    $polaris_table = "";

    foreach($database_rows as &$db_row) {
      $status_reason = $db_row['statusreason'];
      if( $db_row['ispingable'] === 1) {
        if($db_row['statusreason'] === 'draining') {
          $img_tag = sprintf($img_tag_template, "purple", $status_reason);
        }
        else {
          $img_tag = sprintf($img_tag_template, "blue", $status_reason);
        }
      }
      elseif ($db_row['ispingable'] === 0 && strpos($db_row['statusreason'], 'NODAEMONS')) {
        $img_tag = sprintf($img_tag_template, "yellow", $status_reason);
      }
      else {
        $img_tag = sprintf($img_tag_template, "red", $status_reason);
      }

      $sname = ($db_row['batch']==1) ? strongify($db_row['servername']) : $db_row['servername'];
      $cat = ($db_row['batch']==1) ? strongify($db_row['category']) : $db_row['category'];
      $aname = ($db_row['batch']==1) ? strongify($db_row['appname']) : $db_row['appname'];
      $site_url = urlify($db_row['siteurl']);
      if(authenticatedAdmin()) {
        $row = sprintf($row_template, $img_tag, $sname, $cat, $aname, $site_url);
      }
      else {
        $row = sprintf($row_template, $img_tag, $sname, $cat, $aname);
      }

      $rows .= $row;
    }

    $hidden_row = sprintf($template, $row_number, $description, $rows);
    $hidden_row = str_replace("POLARIS_TABLE", $polaris_table, $hidden_row);
    return $hidden_row;
  }

  function strongify($text) {
    return "<strong>" . $text . "</strong>";
  }
  
  function urlify($url) {
    return "<a target='_blank' href='" . $url . "'><i class='fa fa-external-link'></i></a>";
  }

  function urlify2($url) {
    return "<a target='_blank' href='" . $url . "'>$url</a>";
  }

  function urlify3($url, $text) {
    return "(<a href='" . $url . "'>" . $text . "</a>)";
  }

  function dateSubtract($date1) {
    if(!isset($date1)) {
      return 0;
    }
    $now = new DateTime('now');
    $seconds = abs($now->getTimestamp()-$date1->getTimestamp());
    if($seconds > 120) {
      $seconds = $seconds - 3600; //Time zone hack.
    }

    return $seconds;
  }
  
  function buildPolarisTableElement($envValue, $prodValue, $extraClass) {
    $element="";
    if (intval($envValue) === intval($prodValue) ) {
      $element = "<td class='text-center equals-production $extraClass' title='As per Production'>" . $envValue . '</td>';
    }
    elseif (intval($envValue) > intval($prodValue) ) {
      $element = "<td class='text-center greater-than-production $extraClass' title='Ahead of Production'>" . $envValue . '</td>';
    }
    elseif (intval($envValue) < intval($prodValue) && intval($envValue) != 0) {
      $element = "<td class='text-center less-than-production $extraClass' title='Behind Production'>" . $envValue . '</td>';
    }
    else {
      $element = "<td class='text-center no-version-information-available $extraClass' title='Not currently available'>" . $envValue . '</td>';
    }
    return $element;
  }

  function ago($s) {
    $slug = $s < 2 ? "$s second ago" : "$s seconds ago";
    return $slug;
  }

  function getDeploymentUser($u) {
    switch($u) {
      case 'bridges4':
        $display_deploymentuser = 'Steve Bridges';
        break;
      case 'ipriyam':
        $display_deploymentuser = 'Priyamole I';
        break;
      case 'jsvishnu':
        $display_deploymentuser = 'Vishnu JS';
        break;
      case 'hills':
        $display_deploymentuser = 'Simon Hills';
        break;
      case 'ahmeds2':
        $display_deploymentuser = 'Shazad Ahmed';
        break;
      case 'witherj':
        $display_deploymentuser = 'James Withers';
        break;
      case 'griffij4':
        $display_deploymentuser = 'John Griffiths';
        break;
      case('chandrj'):
        $display_deploymentuser = 'Jayaraman Chandrasekaran';
        break;
      case('IPriyam'):
        $display_deploymentuser = 'Priya Mahalingam';
        break;
      case('jeffrim'):
        $display_deploymentuser = 'Mark Jeffries';
        break;
      case('koppars'):
        $display_deploymentuser = 'Swamy Kopparthi';
        break;
      case('luszynp'):
        $display_deploymentuser = 'Pawel Luszynski';
        break;
      case('padwict'):
        $display_deploymentuser = 'Tim Padwick';
        break;
      case('thomasd2'):
        $display_deploymentuser = 'Daryl Thomas';
        break;
      case('allenr2'):
        $display_deploymentuser = 'Raymond Allen';
        break;
      case('glennt'):
        $display_deploymentuser = 'Toby Glenn';
        break;
      case('steuart'):
        $display_deploymentuser = 'Trevor Steuart';
        break;
      case('adamss'):
        $display_deploymentuser = 'Stephen Adams';
        break;
      case('scalesg'):
        $display_deploymentuser = 'Gary Scales';
        break;
      case('ellings'):
        $display_deploymentuser = 'Stephen Ellingham';
        break;
      case('ramasap'):
        $display_deploymentuser = 'Prathiba Ramasamy';
        break;
      case('payned2'):
        $display_deploymentuser = 'David Payne';
        break;
      default:
        $display_deploymentuser = $u;
        break;
    }

    return $display_deploymentuser;
  }

  function appLongName($app) {
    $returnVal = '';
    $app = strtolower($app);

    switch($app) {
      case('ec'):
        $returnVal = 'Edge Connect';
        break;
      case('pc'):
        $returnVal = 'Policy Center';
        break;
      case('bc'):
        $returnVal = 'Billing Center';
        break;
      case('cc'):
        $returnVal = 'Claim Center';
        break;
      case('ab'):
        $returnVal = 'Contact Manager';
        break;
      case('bridge'):
        $returnVal = 'Agile Bridge';
        break;
      case('isl'):
        $returnVal = 'Integration Services Layer';
        break;
      case('pss'):
        $returnVal = 'Portal Security Service';
        break;

      default:
        $returnVal = $app;
        break;
    }
    return $returnVal;
  }

  function createDomainSelect($conn, $selected) {
    $options = array('test.hastings.local', 'network.uk.ad');
    $select_html = "<select class='form-control' id='server_domain_select'>";

    foreach($options as &$option) {
      if($option == $selected) {
        $select_html .= "<option selected>" . $option . "</option>";
      }
      else {
        $select_html .= "<option>" . $option . "</option>";
      }
    }

    $select_html .= "</select>";
    return $select_html;
  }

  function createCheckbox($selected, $html_id) {
    $html = '';
    if((int)$selected == 0) {
      $html .= '<input id="' . $html_id . '" type="checkbox">';
    }
    else {
      $html .= '<input id="' . $html_id . '" type="checkbox" checked>';
    }
    return $html;
  }
  
  function createYesNoSelect($selected, $html_id) {
    if(!isset($selected)) {
      $selected = 'No';
    }
    $html = "<select class='form-control' id='" . $html_id ."'>";
    $options = array('Yes', 'No');
    foreach($options as &$option) {
      if($option == $selected) {
        $html .= "<option selected>" . $option . "</option>";
      }
      else {
        $html .= "<option>" . $option . "</option>";
      }
    }
    $html .= '</select>';
    return $html;
  }

  function genericCreateHTMLSelect($conn, $table, $selected_id, $html_id, $order_column, $select_column, $header_row, $blank_row) {
    $header = "<option disabled selected style='display:none;'> -- select an option -- </option>";
    $blank = "<option value='0'>&nbsp;</option>";
    $sql = 'select * from ' . $table;
    
    if(isset($order_column)) {
      $sql .= ' order by ' . $order_column;
    }

    $options = genericSQLRowsGetNoParams($conn, $sql);
    $html = "<select class='form-control' id='" . $html_id ."'>";
    
    if($header_row) {
      $html .= $header;
    }
    
    if($blank_row) {
      $html .= $blank;
    }

    $first = 1;

    foreach($options as &$option) {
      if($option['id'] == $selected_id) {
        $html .= "<option value='" . $option['id'] . "' selected>" . $option[$select_column] . " (" . $option['id'] . ")</option>";
      }
      elseif ((!isset($selected_id) || $selected_id=='' || $selected_id == null) && $first == 1) {
        $first = 0;
        $html .= "<option value='' selected></option>";
      }
      else {
        $html .= "<option value='" . $option['id'] . "'>" . $option[$select_column] . " (" . $option['id'] . ")</option>";
      }
    }
    $html .= '</select>';
    return $html;
  }

?>
