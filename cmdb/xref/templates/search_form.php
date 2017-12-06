<script type='text/javascript'>
  $(function(){
    $( '#searchform' ).submit(function(event){

      event.preventDefault();
      
      debugger;

      var environment = $('#environment-select-1').val();

      var data = new Object;
      data['environment'] = environment;

      $.ajax({
        url: '/cmdb/index.php',
        method: 'POST',
        data: data,
        success: function(response) {
          console.log(response);
          $('#envxreftable').html(response.html);
        },
        failure: function(response) {
          console.log(response);
        }
      })
    });

    $('#clearButton').on('click', function(event){
      event.preventDefault();
      $('#searchform')[0].reset();
      $('#envxreftable').html('');
    });

  });
</script>

<div class='container-fluid'>
  <form id='searchform' class='form-inline'>
    <div class='form-group'>

      <label for="environment-select-1">Environment 1</label>
      <select name="environment-select-1" class="form-control" id="environment-select-1">
        <?php
          $environments = getEnvironments($conn);

          foreach($environments as &$environment) {
            echo "<option value='" . $environment['name'] . "'>" . $environment['name'] . '</option>';
          }
        ?>
      </select>

      <button class='btn btn-primary btn-sm'>Submit</button>
      <button id="clearButton" class='btn btn-default btn-sm'>Clear</button>

    </div>
  </form>

  <hr/>

  <div class="row">
    <div id='envxreftable'></div>
  </div>
</div>
