  </tbody>
</table>

<div id="newcredentialmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Credential</h4>
      </div>
    
      <div class="modal-body">
        <form class="form-horizontal" id='environmentform'>
          <fieldset>

            <div class="form-group">
              <label class="col-md-4 control-label" for="credential_username">Username</label>
              <div class="col-md-4">
                <input id="credential_username" name="credential_username" type="text" placeholder="Username" class="form-control input-md">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="credential_password">Password</label>
              <div class="col-md-4">
                <input id="credential_password" name="credential_password" type="password" placeholder="Password" class="form-control input-md">
              </div>
            </div>

          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='credentialsubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="confirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <input id='credential_id' name='credential_id' type='hidden' value='-1'>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirm</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete?</p>
      </div>
      <div class="modal-footer">
        <button id='deletebutton' type="button" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>