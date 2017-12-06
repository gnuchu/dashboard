<script type='text/javascript'>
  $(function(){
    $( '#configform' ).submit(function(event){
      event.preventDefault();
      var server = $('#config_server').val();
      var name = $('#config_name').val();
      var value = $('#config_value').val();
      var id = $('#config_id').val();

      var data = new Object;
      data['server'] = server;
      data['name'] = name;
      data['value'] = value;
      data['id'] = id;
      
      $.ajax({
        url: '/cmdb/globalconfiguration/parameteredit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/globalconfiguration/index.php';
        },
        error: function() {
          $('#messages').html('Error updating parameter.');
        }
      })
    });
  });
</script>

<div class='row'>
  <div class='col-lg-4 col-lg-offset-4'>
    <div id='messages'></div>
  </div>
</div>

<form class="form-horizontal" id='configform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Configuration Value</legend>

    <input type="hidden" name="config_id" id='config_id' value="%s">
    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="config_server">Server</label>
      <div class="col-md-4">
      <input id="config_server" name="config_server" type="text" placeholder="Server name or Global" class="form-control input-md" value='%s'>
      </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="config_name">Name</label>
      <div class="col-md-4">
      <input id="config_name" name="config_name" type="text" placeholder="placeholder" class="form-control input-md" value='%s'>
      </div>
    </div>

    <!-- Textarea -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="config_value">Value</label>
      <div class="col-md-4">                     
        <textarea class="form-control" id="config_value" name="config_value">%s</textarea>
      </div>
    </div>

    <!-- Button (Double) -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="button1id">Save Changes</label>
      <div class="col-md-8">
        <button id="submit" name="submit" class="btn btn-primary">Submit</button>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='/cmdb/globalconfiguration/index.php';">Cancel</button>
      </div>
    </div>

  </fieldset>
</form>

<hr/>
</div>
</body>
</html>