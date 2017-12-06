<script type='text/javascript'>
  $(function(){
    $( '#searchform' ).submit(function(event){
      event.preventDefault();
      var environment1 = $('select#environment-select-1').val();
      var environment2 = $('select#environment-select-2').val();
      var alldiffs = false;
      if($('#alldiffs').is(':checked')) {
        alldiffs = true;
      }

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
      data['alldiffs'] = alldiffs;

      $.ajax({
        url: '/compareview.php',
        method: 'POST',
        data: data,
        success: function(response) {
          $('#compareresults').html(response.html);
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
          $environments = getEnvironments($conn);

          foreach($environments as &$environment) {
            echo "<option value='" . $environment['name'] . "'>" . $environment['name'] . '</option>';
          }
        ?>
      </select>
      <label for="environment-select-2">Environment 2</label>
      <select name="environment-select-2" class="form-control" id="environment-select-2">
        <?php
          foreach($environments as &$environment) {
            echo "<option value='" . $environment['name'] . "'>" . $environment['name'] . '</option>';
          }
        ?>
      </select>

      <label for='alldiffs' class="checkbox-inline"><input title='Show differences and similarities?' id='alldiffs' type="checkbox" value="">Show All</label>

      <button class='btn btn-primary btn-sm'>Submit</button>
      <button id="clearButton" class='btn btn-default btn-sm'>Clear</button>

    </div>
  </form>

  <div class="row">
    <div id='compareresults'></div>
  </div>
</div>
