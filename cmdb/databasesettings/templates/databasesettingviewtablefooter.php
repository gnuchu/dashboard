  </tbody>
</table>

<div id="newdatabasesettingmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Credential</h4>
      </div>
    
      <div class="modal-body">
        <form class="form-horizontal" id='environmentform'>
          <fieldset>
            <div class="form-group">
              <label class="col-md-4 control-label" for="databasesetting_databaseserver">Server</label>
              <div class="col-md-4">
                <input id="databasesetting_databaseserver" name="databasesetting_databaseserver" type="text" placeholder="Database Server" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="databasesetting_databaseport">Port</label>
              <div class="col-md-4">
                <input id="databasesetting_databaseport" name="databasesetting_databaseport" type="number" placeholder="Database Port" class="form-control input-md">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="databasesetting_databasename">Name</label>
              <div class="col-md-4">
                <input id="databasesetting_databasename" name="databasesetting_databasename" type="text" placeholder="Database Name" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="databasesetting_credential_username">Credential</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'credentials', 0, 'databasesetting_credential_username', 'username', 'username', true, true); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="databasesetting_readonlycredential_username">Readonly Credential</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'credentials', 0, 'databasesetting_readonlycredential_username', 'username', 'username', true, true); ?>
              </div>
            </div>

          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='databasesettingsubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="confirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <input id='databasesetting_id' name='databasesetting_id' type='hidden' value='-1'>

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