  </tbody>
</table>

<div id="newappmodal" class="modal fade" role="dialog">
  <div class="modal-admin">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New App</h4>
      </div>
    
      <div class="modal-body">
        <form class="form-horizontal" id='appform'>
          <fieldset>
            <div class='col-md-6'>
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_name">App Name</label>
                <div class="col-md-4">
                  <input id="app_name" name="app_name" type="text" placeholder="App Name" class="form-control input-md">
                </div>
              </div>
              <!-- 2 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_environment_name">Environment</label>
                <div class="col-md-4">
                  <?php echo genericCreateHTMLSelect($conn, 'environments', 0, 'app_environment_name', 'name', 'name', true, false); ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label" for="app_appserver_name">Appserver</label>
                <div class="col-md-4">
                  <?php echo genericCreateHTMLSelect($conn, 'appservers', 0, 'app_appserver_name', 'name, id', 'name', true, false); ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_cluster_name">Cluster</label>
                <div class="col-md-4">
                  <?php echo genericCreateHTMLSelect($conn, 'clusters', 0, 'app_cluster_name', 'name', 'name', true, false); ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_databasesetting_name">Database Setting</label>
                <div class="col-md-4">
                  <?php echo genericCreateHTMLSelect($conn, 'databasesettings', 0, 'app_databasesetting_name', 'databaseserver', 'databaseserver', true, false); ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_category">Category</label>
                <div class="col-md-4">
                  <input id="app_category" name="app_category" type="text" placeholder="Category" class="form-control input-md">
                </div>
              </div>
              <!-- 4 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_sso">SSO</label>
                <div class="col-md-4">
                  <?php echo createYesNoSelect('No', 'app_sso'); ?>
                </div>
              </div>
              <!-- 5 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_batch">Batch</label>
                <div class="col-md-4">
                  <?php echo createYesNoSelect('No', 'app_batch'); ?>
                </div>
              </div>
              <!-- 9 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_build_id">Build</label>
                <div class="col-md-4">
                  <input id="app_build_id" name="app_build_id" type="text" placeholder="Build ID" class="form-control input-md">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label" for="app_switchedoff">Switched Off</label>
                <div class="col-md-4">
                  <?php echo createYesNoSelect('No', 'app_switchedoff'); ?>
                </div>
              </div>
              <!-- 14 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_edgedebuglevel">Edge Debug Level</label>
                <div class="col-md-4">
                  <input id="app_edgedebuglevel" name="app_edgedebuglevel" type="text" placeholder="Edge Debug Level" class="form-control input-md">
                </div>
              </div>
            </div>
            <div class='col-md-6'>
              <!-- 15 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_edgeconfiglocation">Edge Config Location</label>
                <div class="col-md-4">
                  <input id="app_edgeconfiglocation" name="app_edgeconfiglocation" type="text" placeholder="Edge Config Location" class="form-control input-md">
                </div>
              </div>
              <!-- 16 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_edgeloglocation">Edge Log Location</label>
                <div class="col-md-4">
                  <input id="app_edgeloglocation" name="app_edgeloglocation" type="text" placeholder="Edge Log Location" class="form-control input-md">
                </div>
              </div>
              <!-- 17 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_bridgeloglocation">Bridge Config Location</label>
                <div class="col-md-4">
                  <input id="app_bridgeloglocation" name="app_bridgeloglocation" type="text" placeholder="Bridge Config Location" class="form-control input-md">
                </div>
              </div>
              <!-- 18 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_iissitename">IIS Site Name</label>
                <div class="col-md-4">
                  <input id="app_iissitename" name="app_iissitename" type="text" placeholder="IIS Site Name" class="form-control input-md">
                </div>
              </div>
              <!-- 19 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_iissiteport">IIS Site Port</label>
                <div class="col-md-4">
                  <input id="app_iissiteport" name="app_iissiteport" type="number" placeholder="IIS Site Port" class="form-control input-md">
                </div>
              </div>
              <!-- 20 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_contextroot">Context Root</label>
                <div class="col-md-4">
                  <input id="app_contextroot" name="app_contextroot" type="text" placeholder="Context Root" class="form-control input-md">
                </div>
              </div>
              <!-- 21 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_integrationpropertiespath">Integ Props Path</label>
                <div class="col-md-4">
                  <input id="app_integrationpropertiespath" name="app_integrationpropertiespath" type="text" placeholder="Integ Props Path" class="form-control input-md">
                </div>
              </div>
              <!-- 22 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_integrationpropertiestype">Integ Props Type</label>
                <div class="col-md-4">
                  <input id="app_integrationpropertiestype" name="app_integrationpropertiestype" type="text" placeholder="Integ Props Type" class="form-control input-md">
                </div>
              </div>
              <!-- 23 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_hidefromdashboard">Hidden from Dashboard</label>
                <div class="col-md-4">
                  <?php echo createYesNoSelect('No', 'app_hidefromdashboard'); ?>
                </div>
              </div>
              <!-- 24 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_islnloglevels">ISL NLog Level</label>
                <div class="col-md-4">
                  <input id="app_islnloglevels" name="app_islnloglevels" type="text" placeholder="ISL NLog Level" class="form-control input-md">
                </div>
              </div>
              <!-- 25 -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="app_rootdir">Root Dir</label>
                <div class="col-md-4">
                  <input id="app_rootdir" name="app_rootdir" type="text" placeholder="Root Dir" class="form-control input-md">
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button id='appsubmitbutton' type="button" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>