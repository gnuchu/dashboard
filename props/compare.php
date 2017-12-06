<?php

  function doCompare($conn, $env1, $env2) {
    $sql = "select count(*) as count from integrationproperties where environment_id = ?";
    $params = array($env1);
    $counter = (int)genericSQLReturnValue($conn, $sql, $params, 'count');

    $differences = 0;
    $propscomparetableheader = file_get_contents('templates/propscomparetableheader.php');
    $propscomparetablefooter = file_get_contents('templates/propscomparetablefooter.php');
    $propsrowtemplate = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td class='%s' title='%s'>%s</td></tr>";

    $sql = 'select name from environments where id = ?';
    $params = array($env1);
    $env_name_1 = genericSQLReturnValue($conn, $sql, $params, 'name');
    $params = array($env2);
    $env_name_2 = genericSQLReturnValue($conn, $sql, $params, 'name');

    $html = sprintf($propscomparetableheader, $env_name_1, $env_name_2);

    $sql = 'select a.name as appname, a.category as category, sp.name, sp.value
from integrationproperties as sp
join apps as a
on a.id = sp.app_id
where sp.environment_id = ?
except 
select a.name as appname, a.category as category, sp.name, sp.value
from integrationproperties as sp
join apps as a
on a.id = sp.app_id
where sp.environment_id = ?
order by appname, name';
    
    $params = array($env1, $env2);
    $first_env_props = genericSQLRowsGetParams($conn, $sql, $params);

    $oldapp = '';
    $section_header_template = '<tr class="sectionheader2"><td colspan="5">%s</td></tr>';

    foreach($first_env_props as &$prop) {
      $sql = "select a.name as appname, a.category, ip.name, ip.value
from integrationproperties as ip
join apps as a
on a.id = ip.app_id
where ip.environment_id = ?
and ip.name = ?
and a.name = ?";
      $params = array($env2, $prop['name'], $prop['appname']);
      $second_env_props = genericSQLRowGetParams($conn, $sql, $params);

      if($prop['value'] === $second_env_props['value']) {
        continue;
      }
      else {
        if($oldapp !== $prop['appname']) {
          $html .= sprintf($section_header_template, "<strong>" . strtoupper(appLongName($prop['appname'])) . "</strong>");
          $oldapp = $prop['appname'];
        }

        $differences += 1;
        if($second_env_props['value'] === Null) {
          $second_class = 'value-missing-from-target';
          $title = sprintf('Parameter present in %s but missing from %s', $env_name_1, $env_name_2);
        }
        else {
          $second_class = 'value-different-from-target';
          $title = sprintf('Parameter different in %s from %s', $env_name_2, $env_name_1);
        }
        $first_prop = $prop['value'];
        $second_prop = $second_env_props['value'];

        if(strpos($prop['name'], 'PASSWORD') !== False || strpos($prop['name'], 'password') !== False) {
          $first_prop = passwordify($first_prop);
          $second_prop = passwordify($first_prop);
        }
        
        $html .= sprintf($propsrowtemplate, $prop['appname'], $prop['category'], $prop['name'], $first_prop, $second_class, $title, $second_prop);
      }
    }

    $html .= sprintf($propscomparetablefooter, (string)$counter, (string)$differences);
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