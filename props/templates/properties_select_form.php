<script type='text/javascript'>
  $(function(){
    $('#spinner').hide();

    $('#propsform').submit(function(event){
      event.preventDefault();
      var environment_id = $('#environment-select-1').val();
      var data = new Object;
      data['environment_id'] = environment_id;

      $('#propsresults').html('');
      $('#spinner').show();

      $.ajax({
        url: '/props/index.php',
        method: 'POST',
        data: data,
        success: function(response) {
          $('#spinner').hide();
          $('#propsresults').html(response.html);
        }
      })
    });

    $('#clearButton').on('click', function(event){
      event.preventDefault();
      $('#propsform')[0].reset();
      $('#propsresults').html('');
    });

  });
</script>

<div class='container-fluid'>
  <form id='propsform' class='form-inline'>
    <div class='form-group'>
      <label for="environment-select-1">Environment </label>
      <select name="environment-select-1" class="form-control" id="environment-select-1">
        <?php
          $sql = "select id, name from environments where id in (select distinct environment_id from integrationproperties) order by name";
          $environments = genericSQLRowsGetNoParams($conn, $sql);

          foreach($environments as &$environment) {
            echo "<option value='" . $environment['id'] . "'>" . $environment['name'] . '</option>';
          }
        ?>
      </select>

      <button class='btn btn-primary btn-sm'>Submit</button>
      <button id="clearButton" class='btn btn-default btn-sm'>Clear</button>
      <img id="spinner" src="/assets/img/ajax-loader.gif"></img>

    </div>
  </form>

  <div class="row">
    <div id='propsresults'></div>
  </div>
</div>
