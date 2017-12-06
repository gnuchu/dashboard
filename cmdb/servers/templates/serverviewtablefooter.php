  </tbody>
</table>

<div id="newservermodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Server</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id='serverform'>
          <fieldset>
            <!-- Form Name -->

            <div class="form-group">
              <label class="col-md-4 control-label" for="server_name">Server</label>
              <div class="col-md-4">
                <input id="server_name" name="server_name" type="text" placeholder="Server name" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="server_environment_name">Environment Name</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'environments', 0, 'server_environment_name', 'name', 'name', true, false); ?>
              </div>
            </div>

            <div class='form-group'>
              <label class="col-md-4 control-label" for='server_domain_select'>Domain</label>
              <div class="col-md-4">
                <?php echo createDomainSelect($conn, 'test.hastings.local'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="server_datapipe">Datapipe</label>
              <div class="col-md-4">
                <?php echo createCheckbox(0, 'server_datapipe'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="server_credential_username">Credential Name</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'credentials', 0, 'server_credential_username', 'username', 'username', true, false); ?>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='serversubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>