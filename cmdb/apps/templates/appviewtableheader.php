<script>
  $(function(){
    $('#apptable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 11}
      ],
      "destroy": true
    });

    $('#newapplink').click(function(){
      $('#newappmodal').modal('show');
    });

    //Create new app
    $('#appsubmitbutton').on('click', function(event){
      event.preventDefault();

      var name = $('#app_name').val();
      var appserver_id = $('#app_appserver_name').val();
      var databasesetting_id = $('#app_databasesetting_name').val();
      var environment_id = $('#app_environment_name').val();
      var cluster_id = $('#app_cluster_name').val();
      var sso = $('#app_sso').val();
      var batch = $('#app_batch').val();
      var category = $('#app_category').val();
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

      if( !Number(appserver_id) || 
          !Number(environment_id) ||
          !Number(cluster_id) ||
          !Number(databasesetting_id) ||
          !name) {
        
        alert('Name, Appserver, Environment, Cluster and Database Setting are all required.');
        return;
      }

      data['name'] = name;
      data['appserver_id'] = appserver_id;
      data['category'] = category;
      data['sso'] = sso == 'Yes' ? 1 : 0;
      data['batch'] = batch == 'Yes' ? 1 : 0;
      data['environment_id'] = environment_id;
      data['cluster_id'] = cluster_id;
      data['databasesetting_id'] = databasesetting_id;
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
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/apps/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').html('Successfully added new app.');
          window.location.href = '/cmdb/apps';
        },
        error: function(response){
          $('#messages').html('Error adding app.');
        }
      });

      $('#newappmodal').modal('hide');
    });

  });
</script>

<h2>Apps</h2>
<br/>

<a id='newapplink' href='#newappmodal' class='btn btn-primary' data-keyboard="true">New app</a>
<hr/>
<hr/>

<table id='apptable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Name</th>
      <th>Appserver</th>
      <th>Category</th>
      <th>SSO</th>
      <th>Batch</th>
      <th>Database</th>
      <th>Environment</th>
      <th>Build ID</th>
      <th>Switched Off</th>
      <th>Contextroot</th>
      <th>Cluster</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>