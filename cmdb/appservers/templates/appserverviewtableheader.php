<script>
  $(function(){
    $('#appservertable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 10}
      ],
      "destroy": true
    });

    $('#newappserverlink').click(function(){
      $('#newappservermodal').modal('show');
    });

    //Create new appserver
    $('#appserversubmitbutton').on('click', function(event){
      event.preventDefault();

      var id = $('#appserver_id').val();
      var name = $('#appserver_name').val();
      var appservertype = $('#appserver_appservertype').val();
      var credential_id = $('#appserver_credential_id').val();
      var server_id = $('#appserver_server_id').val();
      var servicename = $('#appserver_servicename').val();
      var port = $('#appserver_port').val();
      var appport = $('#appserver_appport').val();
      var nodename = $('#appserver_nodename').val();
      var profileroot = $('#appserver_profileroot').val();

      var data = new Object;

      data['name'] = name;
      data['appservertype'] = appservertype;
      data['credential_id'] = credential_id;
      data['server_id'] = server_id;
      data['servicename'] = servicename;
      data['port'] = port;
      data['appport'] = appport;
      data['nodename'] = nodename;
      data['profileroot'] = profileroot;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/appservers/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully added new appserver.');
          window.location.href = '/cmdb/appservers';
        },
        error: function(){
          $('#messages').html('Error adding appserver.');
        }
      });

      $('#newappservermodal').modal('hide');
    });

  });
</script>

<h2>Appservers</h2>
<br/>

<a id='newappserverlink' href='#newappservermodal' class='btn btn-primary' data-keyboard="true">New Appserver</a>

<hr/>

<table id='appservertable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Name</th>
      <th>Type</th>
      <th>Credential Name</th>
      <th>Server</th>
      <th>Service</th>
      <th>Port</th>
      <th>Appport</th>
      <th>Node Name</th>
      <th>Profile Root</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>