  </tbody>
</table>

<div id="newenvironmentmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Environment</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id='environmentform'>
          <fieldset>
            <!-- Form Name -->

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_name">Environment</label>
              <div class="col-md-4">
                <input id="environment_name" name="environment_name" type="text" placeholder="Name" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_description">Description</label>
              <div class="col-md-4">
                <input id="environment_description" name="environment_description" type="text" placeholder="Description" class="form-control input-md">
              </div>
            </div>
  
            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_owner">Owner</label>
              <div class="col-md-4">
                <input id="environment_owner" name="environment_owner" type="text" placeholder="Owner" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_releasebranch">Release Branch</label>
              <div class="col-md-4">
                <input id="environment_releasebranch" name="environment_releasebranch" type="text" placeholder="Release Branch" class="form-control input-md">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_environmenttype">Environment Type</label>
              <div class="col-md-4">
                <?php echo genericCreateHTMLSelect($conn, 'environmenttypes', 0, 'environment_environmenttype', 'description', 'description', true, false); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_retired">Retired</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'environment_retired'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_isproduction">Production</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'environment_isproduction'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_canbebackedupfrom">Backupable</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'environment_canbebackedupfrom'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_canberestoredto">Restorable</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'environment_canberestoredto'); ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="environment_extractparametersandproperties">Extract Parameters?</label>
              <div class="col-md-4">
                <?php echo createYesNoSelect('No', 'environment_extractparametersandproperties'); ?>
              </div>
            </div>

          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='environmentsubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>