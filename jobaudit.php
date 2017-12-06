<?php
  if(!($_SERVER['REQUEST_METHOD']=='GET' || $_SERVER['REQUEST_METHOD']=='POST')) {
    http_response_code(404);
    include('404.php');
    die();
  }

  require_once 'externals/database.php';
  require_once 'externals/functions.php';

  if(session_status() ===PHP_SESSION_NONE) {
    session_start();
  }
  
  if(!authenticatedAdmin()) {
    header('Location: /index.php');
  }

  if($conn===NULL || $conn===false) {
    echo "Connection error";
    die();
  }

  $page = file_get_contents('templates/header.php');
  $page .= file_get_contents('templates/navbar.php');
  $page .= file_get_contents('templates/job_audit_table_header.php');
  $job_audit_row_template = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a target='_blank' href='%s'>%s</a></td><td>%s</td></tr>";

  $limit = getGlobalConfigurationValue($conn, 'JOBAUDIT_HISTORY_VISABLE');

  $jobaudits = getJobAudits($conn, (string)$limit);
  foreach($jobaudits as &$jobaudit) {
    $username = getDeploymentUser($jobaudit['username']);
    $jobname = $jobaudit['jobname'];
    $jobaction = $jobaudit['jobaction'];
    $buildurl = $jobaudit['buildurl'];
    $runtime = $jobaudit['runtime'];
    $environment = $jobaudit['envname'];

    if($jobaction==='null') {
      $jobaction = '';
    }

    $page .= sprintf($job_audit_row_template, $username, $environment, $jobname, $jobaction, $buildurl, $buildurl, $runtime);
  }

  $page .= file_get_contents('templates/job_audit_table_footer.php');

  $page .= "</div><hr/></body></html>";
  $page = eval("?>$page");
  echo $page;
?>