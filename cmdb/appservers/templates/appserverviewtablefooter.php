  </tbody>
</table>

<div id="newappservermodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Appserver</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id='appserverform'>
          <fieldset>
            <!-- Form Name -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_name">Name</label>
              <div class="col-md-4">
                <input id="appserver_name" name="appserver_name" type="text" placeholder="Appserver name" class="form-control input-md">
              </div>
            </div>
            <!-- 3 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_appservertype">Type</label>
              <div class="col-md-4">
                <input id="appserver_appservertype" name="appserver_appservertype" type="text" placeholder="Type" class="form-control input-md">
              </div>
            </div>
            <!-- 4 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_credential_id">Credential</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'credentials', 0, 'appserver_credential_id', 'username', 'username', false, false); ?>
              </div>
            </div>
            <!-- 5 -->
            <div class='form-group'>
              <label class="col-md-4 control-label" for='appserver_server_id'>Server</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'servers', 0, 'appserver_server_id', 'name', 'name', false, false); ?>
              </div>
            </div>
            <!-- 6 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_servicename">Service</label>
              <div class="col-md-4">
                <input id="appserver_servicename" name="appserver_servicename" type="text" placeholder="Service Name" class="form-control input-md">
              </div>
            </div>
            <!-- 7 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_port">Port</label>
              <div class="col-md-4">
                <input id="appserver_port" name="appserver_port" type="number" placeholder="Port" class="form-control input-md">
              </div>
            </div>
            <!-- 8 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_appport">App Port</label>
              <div class="col-md-4">
                <input id="appserver_appport" name="appserver_appport" type="number" placeholder="App Port" class="form-control input-md">
              </div>
            </div>
            <!-- 9 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_nodename">Node Name</label>
              <div class="col-md-4">
                <input id="appserver_nodename" name="appserver_nodename" type="text" placeholder="Node Name" class="form-control input-md">
              </div>
            </div>
            <!-- 10 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="appserver_profileroot">Profile Root</label>
              <div class="col-md-4">
                <input id="appserver_profileroot" name="appserver_profileroot" type="text" placeholder="Profile Root" class="form-control input-md">
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='appserversubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>