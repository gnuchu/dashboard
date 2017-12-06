<?php
  ini_set( 'session.cookie_httponly', 1 );
  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  $page = "";
  $page .= file_get_contents("templates/header.php");
  $page .= file_get_contents("templates/navbar.php");
  $page .= file_get_contents("templates/polaris_tstart.html");

  $productionPolarisEarnixSettings = getPolarisEarnixSettings($conn, "PRODUCTION");
  $settings = allPolarisAndEarnixSettings($conn);

  $section_header_template = '<tr class="sectionheader"><td colspan="14">%s</td></tr>';
  $old_type = "";

  foreach($settings as &$setting) {
    
    if($setting['typedesc'] !== $old_type) {
      $old_type = $setting['typedesc'];
      $page .= sprintf($section_header_template, strongify($setting['typedesc']));
    }

    $environmentname = $setting['environmentname'];
    $CommercialVan = $setting['CommercialVan'];
    $Motorcycle = $setting['Motorcycle'];
    $Home = $setting['Home'];

    $PrivateCar = $setting['PrivateCar'];
    $Submission = $setting['Submission'];
    $PolicyChange = $setting['PolicyChange'];
    $Renewal = $setting['Renewal'];
    $TemporaryDriver = $setting['TemporaryDriver'];
    $TemporaryVehicle = $setting['TemporaryVehicle'];

    $activeRatebooks = $setting['active'];
    $inactiveRatebooks = $setting['inactive'];

    $row = "<tr>";
    $row .= "<td>" . $environmentname . "</td>";
    //Polaris
    $row .= buildPolarisTableElement($CommercialVan, $productionPolarisEarnixSettings['CommercialVan'], '');
    $row .= buildPolarisTableElement($Motorcycle, $productionPolarisEarnixSettings['Motorcycle'], '');
    $row .= buildPolarisTableElement($PrivateCar, $productionPolarisEarnixSettings['PrivateCar'], '');
    $row .= buildPolarisTableElement($Home, $productionPolarisEarnixSettings['Home'], '');
    //Earnix
    $row .= buildPolarisTableElement($Submission, $productionPolarisEarnixSettings['Submission'], 'left-border');
    $row .= buildPolarisTableElement($PolicyChange, $productionPolarisEarnixSettings['PolicyChange'], '');
    $row .= buildPolarisTableElement($Renewal, $productionPolarisEarnixSettings['Renewal'], '');
    $row .= buildPolarisTableElement($TemporaryDriver, $productionPolarisEarnixSettings['TemporaryDriver'], '');
    $row .= buildPolarisTableElement($TemporaryVehicle, $productionPolarisEarnixSettings['TemporaryVehicle'], '');
    //Ratebooks
    $row .= buildPolarisTableElement($activeRatebooks,$productionPolarisEarnixSettings['active'], 'left-border');
    $row .= buildPolarisTableElement($inactiveRatebooks,$productionPolarisEarnixSettings['inactive'], '');

    $row .= "</tr>\n";
    $page .= $row;

  }

  $page .= '</tbody></table>';

  $key_template = file_get_contents("templates/html_row_template.html");
  $version_key = file_get_contents("templates/polearn_versions_key_table.html");
  $page .= sprintf($key_template, $version_key);

  $page .= "<hr/></div></body></html>";
  $page = eval("?>$page");
  echo $page;

?>