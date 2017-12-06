<script type='text/javascript'>
  $(function(){
    $('#appserversubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#appserver_id').val();
      var name = $('#appserver_name').val();
      var type = $('#appserver_type').val();
      var credential_id = $('#appserver_credential_id').val();
      var server_id = $('#appserver_server_id').val();
      var servicename = $('#appserver_servicename').val();
      var port = $('#appserver_port').val();
      var appport = $('#appserver_appport').val();
      var nodename = $('#appserver_nodename').val();
      var profileroot = $('#appserver_profileroot').val();

      var data = new Object;

      data['id'] = id;
      data['name'] = name;
      data['type'] = type;
      data['credential_id'] = credential_id;
      data['server_id'] = server_id;
      data['servicename'] = servicename;
      data['port'] = port;
      data['appport'] = appport;
      data['nodename'] = nodename;
      data['profileroot'] = profileroot;
      data['action'] = 'edit';

      $.ajax({
        url: '/cmdb/appservers/appserveredit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/appservers';
        },
        error: function() {
          $('#messages').html('Error updating Appserver.');
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

<form class="form-horizontal" id='appserverform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Server Values</legend>

    <!-- 1 -->
    <input type="hidden" name="appserver_id" id='appserver_id' value="%s">
    <!-- 2 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_name">Name</label>
      <div class="col-md-4">
        <input id="appserver_name" name="appserver_name" type="text" placeholder="Appserver name" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 3 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_type">Type</label>
      <div class="col-md-4">
        <input id="appserver_type" name="appserver_type" type="text" placeholder="Type" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 4 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_credential_id">Credential</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 5 -->
    <div class='form-group'>
      <label class="col-md-4 control-label" for='appserver_server_id'>Server</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 6 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_servicename">Service</label>
      <div class="col-md-4">
        <input id="appserver_servicename" name="appserver_servicename" type="text" placeholder="Service Name" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 7 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_port">Port</label>
      <div class="col-md-4">
        <input id="appserver_port" name="appserver_port" type="number" placeholder="Port" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 8 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_appport">App Port</label>
      <div class="col-md-4">
        <input id="appserver_appport" name="appserver_appport" type="number" placeholder="App Port" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 9 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_nodename">Node Name</label>
      <div class="col-md-4">
        <input id="appserver_nodename" name="appserver_nodename" type="text" placeholder="Node Name" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 10 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="appserver_profileroot">Profile Root</label>
      <div class="col-md-4">
        <input id="appserver_profileroot" name="appserver_profileroot" type="text" placeholder="Profile Root" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="button1id">Save Changes</label>
      <div class="col-md-8">
        <button id="appserversubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/appservers';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>