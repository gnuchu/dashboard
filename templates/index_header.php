<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
  <link rel='stylesheet' href='assets/css/custom.css'>
  <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <script src='assets/js/jQuery.min.js'></script>
  <script src='assets/js/bootstrap.min.js'></script>
  <script src='assets/js/loader.js'></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
      <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
      <![endif]--> 
      <title>RM Dashboard</title>
    </head>
    <body>
      <script>
        $(function(){
          var interval = 10000;
          var refresh = function() {
            $.ajax({
              url: "/index.php?refresh=1",
              cache: false,
              success: function(html) {
                $('#main-container').html(html);
                reset_collapses();
                setTimeout(function() {
                  refresh();
                }, interval);
              }
            });
          };
          refresh();
        });

      $(function () {
        reset_collapses();
      });
      
      function reset_collapses(){
        var c = document.cookie;
        $('.collapse').each(function () {
          if (this.id) {
            var pos = c.indexOf(this.id + "_collapse_in=");
            if (pos > -1) {
              c.substr(pos).split('=')[1].indexOf('false') ? $(this).addClass('in') : $(this).removeClass('in');
            }
          }
        }).on('shown.bs.collapse hidden.bs.collapse', function () {
          if (this.id) {
            document.cookie = this.id + "_collapse_in=" + $(this).hasClass('in');
          }
        });
      }

      </script>
    <div class='container-fluid' id="main-container">
