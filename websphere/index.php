<?php

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

  if(!authenticatedAdmin()) {
    header('Location: /index.php');
  }

  if($_SERVER['REQUEST_METHOD']=='POST') {
    //Deal with POST request?
    exit;
  }
  else {
    $page = file_get_contents('../templates/header.php');
    $page .= file_get_contents('../templates/navbar.php');

    $websphereconfigviewheader = file_get_contents("templates/websphereconfigviewheader.php");
    $websphereconfigviewfooter = file_get_contents("templates/websphereconfigviewfooter.php");
    $websphereconfigviewrowtemplate = file_get_contents("templates/websphereconfigviewrowtemplate.php");

    $linktemplate = '<a href="configview.php?appserver=%s">Configuration</a>';

    $sql = "select appservers.id as appserverid, appservers.name as appservername, servers.name as servername, environments.name as envname, 
            stuff((select ', ' + apps.name from apps where apps.appserver_id = appservers.id and apps.switchedoff = 0 for xml path('')), 1, 2, '') as apps,
            wasconf.updated_at as configupdatedat,
            case when wasconf.config_json is NULL then 0 else 1 end as hasconfig
            from appservers
            left join websphereconfiguration as wasconf on appservers.id = wasconf.appserver_id
            join servers on appservers.server_id = servers.id
            join environments on servers.environment_id = environments.id
            where appservers.appservertype = 'was'
            order by environments.environmenttype_id asc, envname asc";
    $websphereconfig = genericSQLRowsGetNoParams($conn, $sql);

    $environments = [];
    $appserversbyenv = [];
    foreach ($websphereconfig as $row) {
      $env = $row['envname'];
      array_push($environments, $env);

      if (!array_key_exists($env, $appserversbyenv)) {
        $appserversbyenv[$env] = [];
      }

      $appservers = $appserversbyenv[$env];
      $appservers[$row['appserverid']] = $row;
      $appserversbyenv[$env] = $appservers;
    }
    $environments = array_unique($environments);

    $page .= $websphereconfigviewheader;
    foreach ($environments as $env) {
      $appservers = $appserversbyenv[$env];

      foreach ($appservers as $appserverid => $row) {
        $datestr = $row['hasconfig'] ? $row['configupdatedat'] -> format('d-m-Y H:i:s') : "";
        $link = $row['hasconfig'] ? sprintf($linktemplate, $appserverid) : "";

        $page .= sprintf($websphereconfigviewrowtemplate, $env, $row['appservername'], $row['servername'], $row['apps'], $link, $datestr);
      }
    }
    $page .= $websphereconfigviewfooter;

    $page .= "<hr/></div></body></html>";

    $page = eval("?>$page");
    echo $page;
  }

?>