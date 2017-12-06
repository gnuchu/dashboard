<!DOCTYPE html>
<html lang='en'>
  <head>
    
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <?php 
      if (file_exists('assets/css/bootstrap.min.css')) {
        echo "<link rel='stylesheet' href='assets/css/bootstrap.min.css'>";
        echo "<link rel='stylesheet' href='assets/css/custom.css'>";
        echo "<link rel='stylesheet' href='assets/font-awesome-4.7.0/css/font-awesome.min.css'>";
        echo "<link rel='stylesheet' href='assets/datatables/datatables.min.css'>";

        echo "<script src='assets/js/jQuery.min.js'></script>";
        echo "<script src='assets/js/bootstrap.min.js'></script>";
        echo "<script src='assets/js/loader.js'></script>";
        echo "<script src='assets/datatables/datatables.min.js' type='text/javascript' charset='utf-8' async></script>";

        echo "<link rel='icon' type='image/x-icon' href='favicon.ico' />";
      }
      else {
        echo "<link rel='stylesheet' href='../../assets/css/bootstrap.min.css'>";
        echo "<link rel='stylesheet' href='../../assets/css/custom.css'>";
        echo "<link rel='stylesheet' href='../../assets/font-awesome-4.7.0/css/font-awesome.min.css'>";
        echo "<link rel='stylesheet' href='../../assets/datatables/datatables.min.css'>";

        echo "<script src='../../assets/js/jQuery.min.js'></script>";
        echo "<script src='../../assets/js/bootstrap.min.js'></script>";
        echo "<script src='../../assets/js/loader.js'></script>";
        echo "<script src='../../assets/datatables/datatables.min.js' type='text/javascript' charset='utf-8' async></script>";

        echo "<link rel='icon' type='image/x-icon' href='../../favicon.ico' />";
      }
    ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
      <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
    <![endif]--> 
    <title>RM Dashboard</title>
  </head>
  <body>
    <div class='container-fluid' id='main-container'>