<?php if(TEST_DATABASE) echo '<h1>Test Database</h1>'; ?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><i class="fa fa-tachometer fa-2x" aria-hidden="true"></i></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav" id="navigiation-bar-tabs">
        <?php
          if(session_status() ===PHP_SESSION_NONE) {
            session_start();
          }

          echo "<li><a href='/'>Status</a></li>";
          echo "<li><a href='/settings.php'>Config Settings</a></li>";
          echo "<li><a href='/svn.php'>SVN Log Reports</a></li>";
          echo "<li><a href='/buildview.php'>Build History</a></li>";
          echo "<li><a href='/compareview.php'>Environment Compare</a></li>";

          echo "<li class='dropdown'>";
          echo "  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Environment Params <span class='caret'></span></a>";
          echo "  <ul class='dropdown-menu'>";
          echo "    <li class='disabled'><a href='#'>Integration Properties</a></li>";
          echo "    <li><a href='/props'>View</a></li>";
          echo "    <li><a href='/props/compare.php'>Compare</a></li>";
          echo "    <li role='separator' class='divider'></li>";
          echo "    <li class='disabled'><a href='#'>Script Parameters</a></li>";
          echo "    <li><a href='/scriptparameters'>View</a></li>";
          echo "    <li><a href='/scriptparameters/compare.php'>Compare</a></li>";
          echo "  </ul>";
          echo "</li>";

          if(authenticatedAdmin()) {
            echo "<li class='dropdown'>";
            echo "  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Tools <span class='caret'></span></a>";
            echo "  <ul class='dropdown-menu'>";
            echo "    <li><a href='/drivereport.php'>Disk Space Reports</a></li>";
            echo "    <li><a href='/jobaudit.php'>Job Audits</a></li>";
            echo "    <li><a href='/websphere'>Websphere Configuration</a></li>";
            echo "  </ul>";
            echo "</li>";
          }

          if(authenticatedAdmin()) {
            echo "<li class='dropdown'>";
            echo "  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>CMDB <span class='caret'></span></a>";
            echo "  <ul class='dropdown-menu'>";
            echo "    <li><a href='/cmdb'>Home</a></li>";
            echo "    <li class='divider'></li>";
            echo "    <li><a href='/cmdb/environments'>Environments</a></li>";
            echo "    <li><a href='/cmdb/servers'>Servers</a></li>";
            echo "    <li><a href='/cmdb/appservers'>Appservers</a></li>";
            echo "    <li><a href='/cmdb/apps'>Apps</a></li>";
            echo "    <li><a href='/cmdb/clusters'>Clusters</a></li>";
            echo "    <li><a href='/cmdb/credentials'>Credentials</a></li>";
            echo "    <li><a href='/cmdb/databasesettings'>Database Settings</a></li>";
            echo "    <li><a href='/cmdb/environmenttypes'>Environment Types</a></li>";
            echo "    <li><a href='/cmdb/externalurls'>External URLs</a></li>";
            echo "    <li><a href='/cmdb/globalconfiguration'>Global Configuration</a></li>";
            echo "  </ul>";
            echo "</li>";
          }
        ?>
      </ul>
      <ul class="nav navbar-nav pull-right">
        <?php 
          if(session_status() ===PHP_SESSION_NONE) {
            session_start();
          }

          if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']===true) {
            $user = ucwords($_SESSION['UserFirstname']);
            echo "<li class='dropdown'>";
            echo "  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>$user <span class='caret'></span></a>";
            echo "  <ul class='dropdown-menu'>";
            echo "    <li><a href='/logout.php'>Log Out</a></li>";
            echo "  </ul>";
            echo "</li>";
          } else {
            echo '<li><a href="login.php">Log In</a></li>';
          }
        ?>
      </ul>
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>