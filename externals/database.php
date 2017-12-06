<?php
  if(gethostname() !== 'BX-CINAPPD02') {
    define('TEST_DATABASE', true);
    $serverName = "localhost\SQLEXPRESS";
    $connectionInfo = array( "Database"=>"RMCMDB", "UID"=>"JenkinsUser", "PWD"=>"P@ssw0rd", "CharacterSet" =>"UTF-8");
    $conn = sqlsrv_connect( $serverName, $connectionInfo); 
  }
  else {
    define('TEST_DATABASE', false);
    $serverName = "BX1-PRD-SQL01.network.uk.ad,1433";
    $connectionInfo = array( "Database"=>"RMCMDB", "UID"=>"JenkinsUser", "PWD"=>"Ak5TLEIB5HSDSB2otvN0ONdejwLkXtup!");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
  }
  
  if($conn===false) {
    die( print_r( sqlsrv_errors(), true)); 
  }

  function genericSQLRowsGetNoParams($conn, $sql) {
    $stmt = sqlsrv_prepare($conn, $sql);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function genericSQLRowsGetParams($conn, $sql, &$params) {
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function genericSQLRowGetParams($conn, $sql, &$params) {
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }
  }

  function genericSQLReturnValue($conn, $sql, &$params, $column_name) {
    //Note params should be array before getting here.
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row[$column_name];
    }
  }

  function genericSQLUpdateDelete($conn, $sql, &$params) {
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if(sqlsrv_execute($stmt) === false) {
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
          file_put_contents('debug.log', "SQLSTATE: ".$error['SQLSTATE'], FILE_APPEND);
          file_put_contents('debug.log', "code: ".$error['code'], FILE_APPEND);
          file_put_contents('debug.log', "message: ".$error['message'], FILE_APPEND);
        }
      }
      return false;
    }
    else {
      return true;
    }
  }

  function getEnvironmentVersions($conn, $environment) {
    $sql = "select *
from v_deployedversions
where environmentname= ?";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$environment));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
  }

  function getConfigValue($conn, $id) {
    $sql = 'select * from globalconfiguration where id = ?';
    $stmt = sqlsrv_prepare($conn, $sql, array(&$id));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
  }

  function updateConfigurationValue($conn, $server, $name, $value, $id) {
    $sql = 'update globalconfiguration set server=?, value=?, name=? where id=?';
    $stmt = sqlsrv_prepare($conn, $sql, array(&$server, &$value, &$name, &$id));
    
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
      return false;
    }
    else {
      return true;
    }
  }

  function deleteConfigurationItem($conn, $id) {
    $sql = 'delete from globalconfiguration where id = ?';
    $stmt = sqlsrv_prepare($conn, $sql, array(&$id));
    if(sqlsrv_execute($stmt) === false) {
      return false;
    }
    else {
      return true;
    }
  }

  function addConfigurationItem($conn, $server, $name, $value) {
    $sql = 'insert into globalconfiguration (server, name, value, created_at, updated_at) values (?, ?, ?, sysdatetime(), sysdatetime())';
    $stmt = sqlsrv_prepare($conn, $sql, array(&$server, &$name, &$value));
    if(sqlsrv_execute($stmt) === false) {
      return false;
    }
    else {
      return true;
    }
  }

  function getGlobalConfiguration($conn) {
    $sql = 'select * from globalconfiguration order by server asc, name asc';
    $stmt = sqlsrv_prepare($conn, $sql);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getISLDetails($conn, $env) {
    $sql = "select distinct e.name, isl.islservice, isl.endpoint, isl.active from islsettings as isl
join apps as a
on a.id = isl.app_id
join environments as e
on e.id = a.environment_id
where e.name = ?";
    $params = array($env);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getEnvironments($conn) {
    $sql = "select name from environments where retired=0 order by name";
    $stmt = sqlsrv_prepare($conn, $sql);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getEnvironmentRevisions($conn, $env1, $env2) {
    $sql = "select * from v_deployedversions where environmentname in (?, ?)";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env1, &$env2));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getPolarisAndEarnixSettings($conn, $env1, $env2) {
    $sql = "select environmentname, CommercialVan, Motorcycle, Home, PrivateCar, Submission, PolicyChange, Renewal, 
TemporaryDriver, TemporaryVehicle, active as ActiveRatebooks, inactive as InactiveRatebooks from v_polarisandearnixsettings where environmentname in (?, ?)";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$env1, &$env2));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getAppsForEnvironment($conn, $environment) {
    $sql = "select *
from v_allappurls
where environmentname= ?
and hidefromdashboard=0
and switchedoff=0
order by appname";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$environment));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getDatabaseDetails($conn, $env) {
    $sql = "select distinct appname, category, databaseserver, databaseport, databasename
from v_appdatabasesettings
where environmentname = ?
and databasename != 'ISLTestDeploy'
order by category desc, appname";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }


  function getClustersForEnvironment($conn, $env) {
    $sql = "select  distinct c.name, 
c.url, 
a.category, 
a.name as appname,
c.noclusterurl,
e.name as environmentname
from clusters as c 
join environments as e 
on c.environment_id = e.id 
join apps as a
on a.cluster_id = c.id
where e.name = ?
and a.switchedoff = 0
order by a.category, a.name";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getUrlForEnvAndApp($conn, $envname, $appname, $category) {
    $sql = "select * from v_allappurls where environmentname = ? and appname = ? and category = ? and hidefromdashboard = 0 and switchedoff = 0";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$envname, &$appname, &$category));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }
  }

  function getJobAudits($conn, $limit) {
    $sql = "select top " . $limit . " 
ja.username, 
ja.jobname,
coalesce(ja.jobaction, '') as jobaction,
ja.buildurl,
coalesce(e.name,'') as envname,
convert(varchar(10), ja.created_at,103) + ' ' + convert(varchar(8), ja.created_at,108) as runtime
from jobaudits as ja
left join environments as e on ja.environment_id=e.id
order by ja.created_at desc";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getAllEnvStatus($conn) {
    $sql = "select * from v_environmentstatus where retired=0 order by typerank asc, environmentname asc";
    $stmt = sqlsrv_query( $conn, $sql );
  
    if( $stmt === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getExternalUrls($conn, $env) {
    $sql = "select ext.* from externalurls as ext join environments as e on ext.environment_id = e.id where e.name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getDeploymentHistory($conn, $appid) {
    $sql = "select cast(b.svnrevisionnumber as varchar) as revision,
cast(b.buildidentifier as varchar) as identifier,
b.svnpath as svnpath,
cast(adh.deploymentuser as varchar) as deploymentuser,
cast(adh.deploymentdate as varchar) as deploymentdate,
b.trunk
from appdeploymenthistories adh
join builds as b
on adh.build_id = b.id
where adh.app_id = ?
order by adh.deploymentdate desc";
    
    $stmt = sqlsrv_prepare($conn, $sql, array(&$appid));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function environmentHasAQuoteHub($conn, $env) {
    $sql = "select count(*) as count from environments as e join apps as a on e.id = a.environment_id where e.name = ? and a.category = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env, 'QH'));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      if($row['count'] == 0) {
        return false;
      }
      else {
        return true;
      }
    }
  }

  function getCluster($conn, $clustername) {
    $sql = "select * from clusters where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$clustername));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }
  }

  function getLastPingUpdate($conn) {
    $sql = "select value from globalconfiguration where name = 'PING_LAST_CHECKED'";
    $stmt = sqlsrv_prepare($conn, $sql);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row['value'];
    }

  }
  

  function getServersAndStatus($conn, $env) {
    $sql = "select 
ispingable,
statusreason,
servername,
appname,
appid,
category,
batch,
buildidentifier,
siteurl,
isproduction,
CONVERT(DATETIME2(0),pinglastchecked) as pingLastChecked
from v_allappurls
where environmentname = ?
and switchedoff = 0
order by category, appname, servername";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }
      return $rows;
    }
  }

  function getAppStatus($conn, $env, $app, $category) {
    $sql = "select coalesce(sum(cast(switchedoff as int)), 0) as switchedoff, count(appname) as installed from v_allappurls where environmentname = ? and appname = ? and category = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env, &$app, &$category));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }

  }

  function getGlobalConfigurationValue($conn, $val) {
    $sql = "select value from globalconfiguration where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$val));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $data['value'];
    }
  }

  function getPolarisEarnixSettings($conn, $env) {
    $sql = "select * from v_polarisandearnixsettings where environmentname='PRODUCTION'";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
  }

  function allPolarisAndEarnixSettings($conn) {
    $sql = "select * from v_polarisandearnixsettings order by typerank, environmentname";
    $stmt = sqlsrv_prepare($conn, $sql);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }

      return $rows;
    }
  }

  function getEnvironmentDetails($conn, $env) {
    $sql = "select * from environments where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$env));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }
  }

  function getAppserverDetails($conn, $appserver) {
    $sql = "select * from appservers where id = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$appserver));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      return $row;
    }
  }

  //Users
  function getUserDetails($conn, $user) {
    $sql = "select * from users where UserLogin = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$user));

    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
  }

  function createUserDatabase($conn, $username, $password, $email, $firstname, $surname, $salt) {
    $sql = "insert into Users (UserLogin, UserPassword, UserFirstname, UserSurname, UserEmail, Salt) values (?, ?, ?, ?, ?, ?)";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$username, &$password, &$firstname, &$surname, &$email, &$salt));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      return true;
    }
  }

  function updateReleaseBranch($conn, $env, $new_release_branch) {
    $sql = "update environments set releasebranch = ? where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$new_release_branch, &$env));
    if(sqlsrv_execute($stmt) === false) {
      return false;
    }
    else {
      return true;
    }
  }

  function updateOwner($conn, $env, $new_owner) {
    $sql = "update environments set owner = ? where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$new_owner, &$env));
    if(sqlsrv_execute($stmt) === false) {
      return false;
    }
    else {
      return true;
    }
  }

  function updateDescription($conn, $env, $new_description) {
    $sql = "update environments set description = ? where name = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$new_description, &$env));
    if(sqlsrv_execute($stmt) === false) {
      return false;
    }
    else {
      return true;
    }
  }

  function getBuildDetails($conn, $buildid) {
    $buildid = (int)$buildid;
    $sql = "select * from builds where svnrevisionnumber = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$buildid));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }

      return $rows;
    }
  }

  function getBuildDeploymentDetails($conn, $buildid) {
    $buildid = (int)$buildid;
    $sql = "select * from v_buildhistories where svnrevisionnumber = ? order by deploymentdate desc";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$buildid));
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }

      return $rows;
    }
    
  }

  function idReferenceTable($conn, $environmentname) {
    $sql = <<<EOT
select  e.name as environment,
e.id as environment_id,
s.name as server,
s.id as server_id,
asv.name as appserver,
asv.id as appserver_id,
a.name as app,
a.id as app_id,
coalesce(dbs.databaseserver, '') as databasesetting,
coalesce(dbs.id, '') as databasesetting_id,
coalesce(c.username, '') as credential,
coalesce(c.id, '') as credential_id,
a.category as category
from environments as e
join servers as s
on s.environment_id = e.id
join appservers as asv
on asv.server_id = s.id
join apps as a
on a.appserver_id = asv.id
left join databasesettings as dbs
on dbs.id = a.databasesetting_id
left join credentials as c
on c.id = dbs.credential_id
where e.name = ?
order by environment, category desc, server, appserver, app;
EOT;

    $params = array(&$environmentname);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if(sqlsrv_execute($stmt) === false) {
      die( print_r( sqlsrv_errors(), true));
    }
    else {
      $rows = [];
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
      }

      return $rows;
    }
  }

?>