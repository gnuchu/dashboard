  </tbody>
</table>

<div id="newclustermodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Cluster</h4>
      </div>
    
      <div class="modal-body">
        <form class="form-horizontal" id='environmentform'>
          <fieldset>

            <div class="form-group">
              <label class="col-md-4 control-label" for="cluster_name">Cluster Name</label>
              <div class="col-md-4">
                <input id="cluster_name" name="cluster_name" type="text" placeholder="Cluster Name" class="form-control input-md">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="cluster_url">Cluster URL</label>
              <div class="col-md-4">
                <input id="cluster_url" name="cluster_url" type="text" placeholder="Cluster URL" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="cluster_environment_name">Environment</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'environments', 0, 'cluster_environment_name', 'name', 'name', true, false); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="cluster_noclusterurl">No Cluster URL</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'cluster_noclusterurl'); ?>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='clustersubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>