<?php

  function doCompare($conn, $env1, $env2) {
    $counter = 0;
    $differences = 0;
    $scriptparameterscomparetableheader = file_get_contents('templates/scriptparameterscomparetableheader.php');
    $scriptparameterscomparetablefooter = file_get_contents('templates/scriptparameterscomparetablefooter.php');
    $scriptparametersrowtemplate = "<tr><td>%s</td><td>%s</td><td class='%s' title='%s'>%s</td></tr>";

    $sql = 'select name from environments where id = ?';
    $params = array($env1);
    $env_name_1 = genericSQLReturnValue($conn, $sql, $params, 'name');
    $params = array($env2);
    $env_name_2 = genericSQLReturnValue($conn, $sql, $params, 'name');

    $html = sprintf($scriptparameterscomparetableheader, $env_name_1, $env_name_2);

    $sql = 'select a.name as appname, sp.name, sp.value
from scriptparameters as sp
join apps as a
on a.id = sp.app_id
where sp.environment_id = ?';
    
    $params = array($env1);
    $first_env_params = genericSQLRowsGetParams($conn, $sql, $params);

    $oldapp = '';
    $section_header_template = '<tr class="sectionheader2"><td colspan="3">%s</td></tr>';

    foreach($first_env_params as &$param) {
      $counter += 1;

      $sql = "select a.name as appname, sp.name, sp.value
from scriptparameters as sp
join apps as a
on a.id = sp.app_id
where sp.environment_id = ?
and sp.name = ?
and a.name = ?";
      $params = array($env2, $param['name'], $param['appname']);
      $second_env_param = genericSQLRowGetParams($conn, $sql, $params);

      if($oldapp !== $param['appname']) {
        $html .= sprintf($section_header_template, "<strong>" . strtoupper(appLongName($param['appname'])) . "</strong>");
        $oldapp = $param['appname'];
      }

      if($param['value'] === $second_env_param['value']) {
        continue;
      }
      else {
        $differences += 1;
        if($second_env_param['value'] === Null) {
          $second_class = 'value-missing-from-target';
          $title = sprintf('Parameter present in %s but missing from %s', $env_name_1, $env_name_2);
        }
        else {
          $second_class = 'value-different-from-target';
          $title = sprintf('Parameter different in %s from %s', $env_name_2, $env_name_1);
        }

        $html .= sprintf($scriptparametersrowtemplate, $param['name'], $param['value'], $second_class, $title, $second_env_param['value']);
      }
    }

    $html .= sprintf($scriptparameterscomparetablefooter, (string)$counter, (string)$differences);
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

  require_once '../externals/database.php';
  require_once '../externals/functions.php';

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    $environment1 = filter_var($_POST['environment1'], FILTER_SANITIZE_STRING);
    $environment2 = filter_var($_POST['environment2'], FILTER_SANITIZE_STRING);
    $html = doCompare($conn, $environment1, $environment2);

    header('Content-type: application/json');
    $response_array['status'] = 'success';
    $response_array['html'] = $html;

    usleep(250000);

    echo json_encode($response_array);
    exit;
  }
  else {
    $page = file_get_contents('../templates/header.php');
    $page .= file_get_contents('../templates/navbar.php');
    $page .= file_get_contents("templates/compare_form.php");

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>