<script type='text/javascript'>
  $(function(){
    $('#spinner').hide();

    $( '#searchform' ).submit(function(event){
      event.preventDefault();
      var environment1 = $('select#environment-select-1').val();
      var environment2 = $('select#environment-select-2').val();

      if(!environment1 || !environment2) {
        alert('Please supply values for both environments to compare');
        return;
      }

      if(environment1 === environment2) {
        alert('Please supply different values of environments to compare.')
        return;
      }

      var data = new Object;
      data['environment1'] = environment1;
      data['environment2'] = environment2;

      $('#spinner').show();
      $('#compareresults').html('');

      $.ajax({
        url: '/props/compare.php',
        method: 'POST',
        data: data,
        success: function(response) {
          $('#spinner').hide();
          $('#compareresults').html(response.html);
        },
        error: function(response) {
        }
      })
    });

    $('#clearButton').on('click', function(event){
      event.preventDefault();
      $('#searchform')[0].reset();
      $('#compareresults').html('');
    });

  });
</script>

<div class='container-fluid'>
  <form id='searchform' class='form-inline'>
    <div class='form-group'>

      <label for="environment-select-1">Environment 1</label>
      <select name="environment-select-1" class="form-control" id="environment-select-1">
        <?php
          $sql = 'select id, name from environments where id in (select distinct environment_id from scriptparameters) order by name';
          $environments = genericSQLRowsGetNoParams($conn, $sql);

          foreach($environments as &$environment) {
            echo "<option value='" . $environment['id'] . "'>" . $environment['name'] . '</option>';
          }
        ?>
      </select>
      <label for="environment-select-2">Environment 2</label>
      <select name="environment-select-2" class="form-control" id="environment-select-2">
        <?php
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
    <div id='compareresults'></div>
  </div>
</div>
