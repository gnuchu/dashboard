<script type='text/javascript'>
  $(function(){
    $('#serversubmitbutton').on('click', function(event){

      event.preventDefault();
      var id = $('#server_id').val();
      var name = $('#server_name').val();
      var environment_name = $('#server_environment_name').val();
      var domain = $('#server_domain_select').val();
      var datapipe = $('#server_datapipe').is(":checked") ? 1 : 0;
      var credential_name = $('#server_credential_username').val();

      var data = new Object;

      data['id'] = id;
      data['name'] = name;
      data['environment_name'] = environment_name;
      data['domain'] = domain;
      data['datapipe'] = datapipe;
      data['credential_name'] = credential_name;

      $.ajax({
        url: '/cmdb/servers/serveredit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/servers';
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

<form class="form-horizontal" id='serverform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Server Values</legend>

    <input type="hidden" name="server_id" id='server_id' value="%s">
    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="server_name">Server</label>
      <div class="col-md-4">
        <input id="server_name" name="server_name" type="text" placeholder="Server name" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="server_environment_name">Environment Name</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class='form-group'>
      <label class="col-md-4 control-label" for='server_domain_select'>Domain</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="server_datapipe">Datapipe</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="server_credential_username">Credential Name</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="button1id">Save Changes</label>
      <div class="col-md-8">
        <button id="serversubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/servers';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>