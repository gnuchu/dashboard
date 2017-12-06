<script type='text/javascript'>
  $(function(){
    $('#databasesettingsubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#databasesetting_id').val();
      var databaseserver = $('#databasesetting_databaseserver').val();
      var databaseport = $('#databasesetting_databaseport').val();
      var databasename = $('#databasesetting_databasename').val();
      var credential_id = $('#databasesetting_credential_username').val();
      var readonlycredential_id = $('#databasesetting_readonlycredential_username').val();

      var data = new Object;

      data['id'] = id;
      data['databaseserver'] = databaseserver;
      data['databaseport'] = databaseport;
      data['databasename'] = databasename;
      data['credential_id'] = credential_id;
      data['readonlycredential_id'] = readonlycredential_id;

      $.ajax({
        url: '/cmdb/databasesettings/databasesettingedit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/databasesettings';
        },
        error: function() {
          $('#messages').html('Error updating databasesetting.');
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

<form class="form-horizontal" id='databasesettingform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Credential Values</legend>

    <input type="hidden" name="databasesetting_id" id='databasesetting_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesetting_databaseserver">Server</label>
      <div class="col-md-4">
        <input id="databasesetting_databaseserver" name="databasesetting_databaseserver" type="text" placeholder="Database Server" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesetting_databaseport">Port</label>
      <div class="col-md-4">
        <input id="databasesetting_databaseport" name="databasesetting_databaseport" type="number" placeholder="Database Port" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesetting_databasename">Name</label>
      <div class="col-md-4">
        <input id="databasesetting_databasename" name="databasesetting_databasename" type="text" placeholder="Database Name" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesetting_credential_username">Credential</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesetting_readonlycredential_username">Readonly Credential</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="databasesettingsubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="databasesettingsubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/databasesettings';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>