<script>
  $(function(){
    $('#databasesettingtable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 6},
        {"orderable": false, "targets": 7},
      ],
      "destroy": true
    });

    $('#newdatabasesettinglink').click(function(){
      $('#newdatabasesettingmodal').modal('show');
    });

    $(document).on('click', '.deletelink',function(){
      $("#databasesetting_id").val($(this).data('id'));
      $('#confirmationModal').modal('show');
    });

    $("#deletebutton").on('click', function(event){
      event.preventDefault();
      var databasesetting_id = $('#databasesetting_id').val();
      
      var data = new Object;
      data['id'] = databasesetting_id;
      data['action'] = 'delete';

      $.ajax({
        url: '/cmdb/databasesettings/index.php',
        method: 'POST',
        data: data,
        success: function(response){
          $('#messages').html('Successfully deleted.');
          $('#messages').addClass('alert alert-success');
          window.location.href = '/cmdb/databasesettings';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Database setting cannot be deleted as it is in use.');
        }
      });

      $('#confirmationModal').modal('hide');
    });

    //Create new databasesetting
    $('#databasesettingsubmitbutton').on('click', function(event){
      event.preventDefault();

      var databaseserver = $('#databasesetting_databaseserver').val();
      var databaseport = $('#databasesetting_databaseport').val();
      var databasename = $('#databasesetting_databasename').val();
      var credential_id = $('#databasesetting_credential_username').val();
      var readonlycredential_id = $('#databasesetting_readonlycredential_username').val();

      var data = new Object;

      data['databaseserver'] = databaseserver;
      data['databaseport'] = databaseport;
      data['databasename'] = databasename;
      data['credential_id'] = credential_id;
      data['readonlycredential_id'] = readonlycredential_id;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/databasesettings/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').addClass('alert alert-success');
          $('#messages').html('Successfully added new databasesetting.');
          window.location.href = '/cmdb/databasesettings';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Error adding databasesetting.');
        }
      });

      $('#newdatabasesettingmodal').modal('hide');
    });

  });
</script>

<h2>Database Settings</h2>
<br/>

<div class='row'>
  <div class='col-lg-4 col-lg-offset-4'>
    <div id='messages'></div>
  </div>
</div>

<a id='newdatabasesettinglink' href='#newdatabasesettingmodal' class='btn btn-primary' data-keyboard="true">New Database Setting</a>

<hr/>

<table id='databasesettingtable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Server</th>
      <th>Port</th>
      <th>Name</th>
      <th>Credential</th>
      <th>Read Only Credential</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>