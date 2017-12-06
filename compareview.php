<?php

  function doEnvironmentCompare($conn, $env1, $env2, $diffs) {
    
    $alldiffs = false;
    if($diffs==='true') {
      $alldiffs = true;
    }
    
    $html = '<h2>Comparing ' . $env1 . ' with ' . $env2 . '</h2>';
    $comparisontableheader = file_get_contents('templates/comparisontableheader.php');
    $comparisontablefooter = file_get_contents('templates/comparisontablefooter.php');
    $comparisontablerowtemplate = '<tr><td>%s</td><td>%s</td><td class="%s">%s</td><td class="%s">%s</td></tr>';
    $sectiondivider = '<tr class="sectiondivider"><td colspan="4">%s</td></tr>';

    $comparisontable = sprintf($comparisontableheader, $env1, $env2);
    if($alldiffs) {
      $comparisontable .= '<p>Showing all differences and similarities.</p>';
    }
    else {
      $comparisontable .= '<p>Showing only differences.</p>';
    }

    $revisions = getEnvironmentRevisions($conn, $env1, $env2);
    $flat = array();

    foreach($revisions as &$revision) {
      foreach(array_keys($revision) as &$key) {
        $flat[$key][$revision['environmentname']] = $revision[$key];
      }
    }

    $first = 1;

    $differenceCount = 0;

    foreach ($flat as $k => $v) {
      //Don't compare on  all keys from view
      $key = $k;
      $value_env_1 = $flat[$k][$env1];
      $value_env_2 = $flat[$k][$env2];

      $td_class = 'same-as-each-other';

      if($k == 'environmentname' || $k == 'rn' || $k == 'retired') {
        continue;
      }
      //Don't compare where one the other is blank
      if($value_env_1 == '' || $value_env_2 =='') {
        continue;
      }

      if($value_env_1 != $value_env_2) {
        $td_class = 'different-from-each-other';
        $differenceCount++;
      }
      
      $display_key = '';
      $category = '';

      if ($key == 'releasebranch') {
        $display_key = 'Code Branch Deployed';
        $category = '';
        $comparisontable .= sprintf($sectiondivider, 'Code Branch');
        
        if(($td_class == 'same-as-each-other' && $alldiffs) || $td_class == 'different-from-each-other') {
          $row = sprintf($comparisontablerowtemplate, $display_key, $category, $td_class, $value_env_1, $td_class, $value_env_2);
        }
      }
      else {
        $split_result = preg_split("/_/", $key);
        $display_key = $split_result[0];
        $category = $split_result[1];
        
        if($first) {
          $first = 0;
          $comparisontable .= sprintf($sectiondivider, 'App Revision Numbers');
        }
        if(($td_class == 'same-as-each-other' && $alldiffs) || $td_class == 'different-from-each-other') {
          $row = sprintf($comparisontablerowtemplate, appLongName($display_key), $category, $td_class, $value_env_1, $td_class, $value_env_2);
        }
      }
      if(isset($row) && $row!=''){
        $comparisontable .= $row;
      }
    }

    //Add Earni/Polaris compare.
    $polearnsettings = getPolarisAndEarnixSettings($conn, $env1, $env2);
    //Flatten the array.
    $flat = array();
    $environmentname = '';
    foreach($polearnsettings as &$row) {
      foreach(array_keys($row) as &$key) {
        if($key == 'environmentname') {
          $environmentname = $row[$key];
          continue;
        }
        $flat[$key][$environmentname] = $row[$key];
      }
    }

    //Polaris Section
    $comparisontable .= sprintf($sectiondivider, 'Polaris');
    $polaris = array('CommercialVan', 'Motorcycle', 'Home', 'PrivateCar');
    $earnix = array('Submission', 'PolicyChange', 'Renewal', 'TemporaryDriver', 'TemporaryVehicle');
    $ratebooks = array('ActiveRatebooks', 'InactiveRatebooks');

    foreach($flat as $k => $v) {
      if(!in_array($k, $polaris, true)) {
        continue;
      }
      
      $heading = 'Polaris';
      $category = $k;
      $value_env_1 = $v[$env1];
      $value_env_2 = $v[$env2];
      $td_class = 'same-as-each-other';
      if($value_env_1 != $value_env_2) {
        $td_class = 'different-from-each-other';
        $differenceCount++;
      }
      if(($td_class == 'same-as-each-other' && $alldiffs) || $td_class == 'different-from-each-other') {
        $row = sprintf($comparisontablerowtemplate, $heading, $category, $td_class, $value_env_1, $td_class, $value_env_2);
        $comparisontable .= $row;
      }
    }

    //Earnix Section
    $comparisontable .= sprintf($sectiondivider, 'Earnix');
    foreach($flat as $k => $v) {
      if(!in_array($k, $earnix, true)) {
        continue;
      }
      
      $heading = 'Earnix';
      $category = $k;
      $value_env_1 = $v[$env1];
      $value_env_2 = $v[$env2];
      $td_class = 'same-as-each-other';
      if($value_env_1 != $value_env_2) {
        $td_class = 'different-from-each-other';
        $differenceCount++;
      }

      if(($td_class == 'same-as-each-other' && $alldiffs) || $td_class == 'different-from-each-other') {
        $row = sprintf($comparisontablerowtemplate, $heading, $category, $td_class, $value_env_1, $td_class, $value_env_2);
        $comparisontable .= $row;
      }
    }

    //Ratebooks Section
    $comparisontable .= sprintf($sectiondivider, 'Ratebooks');
    foreach($flat as $k => $v) {
      if(!in_array($k, $ratebooks, true)) {
        continue;
      }
      
      if($k == 'InactiveRatebooks') {
        $heading = 'Inactive Ratebooks';
      }
      else {
        $heading = 'Active Ratebooks';
      }

      $category = '';
      $value_env_1 = $v[$env1];
      $value_env_2 = $v[$env2];
      $td_class = 'same-as-each-other';
      if($value_env_1 != $value_env_2) {
        $td_class = 'different-from-each-other';
        $differenceCount++;
      }
      if(($td_class == 'same-as-each-other' && $alldiffs) || $td_class == 'different-from-each-other') {
        $row = sprintf($comparisontablerowtemplate, $heading, $category, $td_class, $value_env_1, $td_class, $value_env_2);
        $comparisontable .= $row;
      }
    }

    $comparisontable .= $comparisontablefooter;
    $comparisontable .= file_get_contents('templates/compare_key_table.html');
    $nodifferencestemplate = '<h3>No differences between %s and %s found.</h3>';

    if($differenceCount==0 && !$alldiffs) {
      $comparisontable = sprintf($nodifferencestemplate, $env1, $env2);
    }
    $html .= $comparisontable;

    return $html;
  }

  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $environment1 = filter_var($_POST['environment1'], FILTER_SANITIZE_STRING);
    $environment2 = filter_var($_POST['environment2'], FILTER_SANITIZE_STRING);
    $alldiffs = filter_var($_POST['alldiffs'], FILTER_SANITIZE_STRING);
    $html = doEnvironmentCompare($conn, $environment1, $environment2, $alldiffs);

    header('Content-type: application/json');
    $response_array['status'] = 'success';
    $response_array['html'] = $html;

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }
  else {
    $page = file_get_contents('templates/header.php');
    $page .= file_get_contents('templates/navbar.php');
    $page .= file_get_contents("templates/compare_search_form.php");

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>