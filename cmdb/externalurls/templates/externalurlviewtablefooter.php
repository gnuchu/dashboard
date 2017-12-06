  </tbody>
</table>

<div id="newexternalurlmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New External URL</h4>
      </div>
    
      <div class="modal-body">
        <form class="form-horizontal" id='environmentform'>
          <fieldset>
            
            <!-- 1 -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="externalurl_environment_name">Environment</label>
              <div class="col-md-6">
                <?php echo genericCreateHTMLSelect($conn, 'environments', 0, 'externalurl_environment_name', 'name', 'name', true, false); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="externalurl_app_name">App Name</label>
              <div class="col-md-6">
                <input id="externalurl_app_name" name="externalurl_app_name" type="text" placeholder="App Name" class="form-control input-md">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="externalurl_url">URL</label>
              <div class="col-md-6">
                <input id="externalurl_url" name="externalurl_url" type="text" placeholder="URL" class="form-control input-md">
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='externalurlsubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="confirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <input id='externalurl_id' name='externalurl_id' type='hidden' value='-1'>

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