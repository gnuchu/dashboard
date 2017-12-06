<script type='text/javascript'>
  $(function(){
    $('#appsubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#app_id').val();
      var name = $('#app_name').val();
      var appserver_name = $('#app_appserver_name').val();
      var category = $('#app_category').val();
      var sso = $('#app_sso').val();
      var batch = $('#app_batch').val();
      var environment_name = $('#app_environment_name').val();
      var cluster_name = $('#app_cluster_name').val();
      var databasesetting_name = $('#app_databasesetting_name').val();
      var build_id = $('#app_build_id').val();
      var switchedoff = $('#app_switchedoff').val();
      var edgedebuglevel = $('#app_edgedebuglevel').val();
      var edgeconfiglocation = $('#app_edgeconfiglocation').val();
      var edgeloglocation = $('#app_edgeloglocation').val();
      var bridgeloglocation = $('#app_bridgeloglocation').val();
      var iissitename = $('#app_iissitename').val();
      var iissiteport = $('#app_iissiteport').val();
      var contextroot = $('#app_contextroot').val();
      var integrationpropertiespath = $('#app_integrationpropertiespath').val();
      var integrationpropertiestype = $('#app_integrationpropertiestype').val();
      var hidefromdashboard = $('#app_hidefromdashboard').val();
      var islnloglevels = $('#app_islnloglevels').val();
      var rootdir = $('#app_rootdir').val();

      var data = new Object;

      data['id'] = id;
      data['name'] = name;
      data['appserver_id'] = appserver_name;
      data['category'] = category;
      data['sso'] = sso == 'Yes' ? 1 : 0;
      data['batch'] = batch == 'Yes' ? 1 : 0;
      data['environment_id'] = environment_name;
      data['cluster_id'] = cluster_name;
      data['databasesetting_id'] = databasesetting_name;
      data['build_id'] = build_id;
      data['switchedoff'] = switchedoff == 'Yes' ? 1 : 0;
      data['edgedebuglevel'] = edgedebuglevel;
      data['edgeconfiglocation'] = edgeconfiglocation;
      data['edgeloglocation'] = edgeloglocation;
      data['bridgeloglocation'] = bridgeloglocation;
      data['iissitename'] = iissitename;
      data['iissiteport'] = iissiteport;
      data['contextroot'] = contextroot;
      data['integrationpropertiespath'] = integrationpropertiespath;
      data['integrationpropertiestype'] = integrationpropertiestype;
      data['hidefromdashboard'] = hidefromdashboard == 'Yes' ? 1 : 0;
      data['islnloglevels'] = islnloglevels;
      data['rootdir'] = rootdir;

      $.ajax({
        url: '/cmdb/apps/appedit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/apps';
        },
        error: function() {
          $('#messages').html('Error updating parameter.');
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

<form class="form-horizontal" id='appform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Server Values</legend>

    <input type="hidden" name="app_id" id='app_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_name">App Name</label>
      <div class="col-md-4">
        <input id="app_name" name="app_name" type="text" placeholder="App Name" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 2 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_appserver_name">Appserver</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 3 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_category">Category</label>
      <div class="col-md-4">
        <input id="app_category" name="app_category" type="text" placeholder="Category" class="form-control input-md" value='%s'>
      </div>
    </div>
    <!-- 4 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_sso">SSO</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 5 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_batch">Batch</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 6 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_environment_name">Environment</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_cluster_name">Cluster</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    <!-- 7 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_databasesetting_name">Database Setting</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_build_id">Build</label>
      <div class="col-md-4">
        <input id="app_build_id" name="app_build_id" type="text" placeholder="Build ID" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_switchedoff">Switched Off</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_edgedebuglevel">Edge Debug Level</label>
      <div class="col-md-4">
        <input id="app_edgedebuglevel" name="app_edgedebuglevel" type="text" placeholder="Edge Debug Level" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_edgeconfiglocation">Edge Config Location</label>
      <div class="col-md-4">
        <input id="app_edgeconfiglocation" name="app_edgeconfiglocation" type="text" placeholder="Edge Config Location" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_edgeloglocation">Edge Log Location</label>
      <div class="col-md-4">
        <input id="app_edgeloglocation" name="app_edgeloglocation" type="text" placeholder="Edge Log Location" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="app_bridgeloglocation">Bridge Config Location</label>
      <div class="col-md-4">
        <input id="app_bridgeloglocation" name="app_bridgeloglocation" type="text" placeholder="Bridge Config Location" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_iissitename">IIS Site Name</label>
      <div class="col-md-4">
        <input id="app_iissitename" name="app_iissitename" type="text" placeholder="IIS Site Name" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_iissiteport">IIS Site Port</label>
      <div class="col-md-4">
        <input id="app_iissiteport" name="app_iissiteport" type="number" placeholder="IIS Site Port" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_contextroot">Context Root</label>
      <div class="col-md-4">
        <input id="app_contextroot" name="app_contextroot" type="text" placeholder="Context Root" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_integrationpropertiespath">Integ Props Path</label>
      <div class="col-md-4">
        <input id="app_integrationpropertiespath" name="app_integrationpropertiespath" type="text" placeholder="Integ Props Path" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_integrationpropertiestype">Integ Props Type</label>
      <div class="col-md-4">
        <input id="app_integrationpropertiestype" name="app_integrationpropertiestype" type="text" placeholder="Integ Props Type" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_hidefromdashboard">Hidden from Dashboard</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_islnloglevels">ISL NLog Level</label>
      <div class="col-md-4">
        <input id="app_islnloglevels" name="app_islnloglevels" type="text" placeholder="ISL NLog Level" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="app_rootdir">Root Dir</label>
      <div class="col-md-4">
        <input id="app_rootdir" name="app_rootdir" type="text" placeholder="Root Dir" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="appsubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="appsubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/apps';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>