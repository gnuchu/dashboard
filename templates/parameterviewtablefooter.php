  </tbody>
</table>

<div id="confirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <input id='config_id' name='config_id' type='hidden' value='-1'>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirm</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this parameter?</p>
      </div>
      <div class="modal-footer">
        <button id='deletebutton' type="button" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="newparametermodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Global Parameter</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id='configform'>

          <fieldset>
            <div class="form-group">
              <label class="col-md-2 control-label" for="config_server">Server</label>
              <div class="col-md-8">
              <input id="config_server" name="config_server" type="text" placeholder="Server Name or 'Global'" class="form-control input-md">
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-2 control-label" for="config_name">Name</label>
              <div class="col-md-8">
              <input id="config_name" name="config_name" type="text" placeholder="Parameter Name" class="form-control input-md">
              </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
              <label class="col-md-2 control-label" for="config_value">Value</label>
              <div class="col-md-8">
                <textarea class="form-control" id="config_value" name="config_value" placeholder="Parameter Value"></textarea>
              </div>
            </div>
          </fieldset>

        </form>
      </div>
      <div class="modal-footer">
        <button id='submitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>