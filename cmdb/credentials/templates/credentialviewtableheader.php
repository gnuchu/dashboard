<script>
  $(function(){
    $('#credentialtable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 3},
        {"orderable": false, "targets": 4}
      ],
      "destroy": true
    });

    $('#newcredentiallink').click(function(){
      $('#newcredentialmodal').modal('show');
    });

    $(document).on('click', '.deletecredlink',function(){
      $("#credential_id").val($(this).data('id'));
      $('#confirmationModal').modal('show');
    });

    $("#deletebutton").on('click', function(event){
      event.preventDefault();
      var credential_id = $('#credential_id').val();
      
      var data = new Object;
      data['id'] = credential_id;
      data['action'] = 'delete';

      $.ajax({
        url: '/cmdb/credentials/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully deleted config item.');
          window.location.href = '/cmdb/credentials';
        },
        error: function(){
          alert('Failure');
          $('#messages').html('Error deleting credential.');
        }
      });

      $('#confirmationModal').modal('hide');

    });

    //Create new credential
    $('#credentialsubmitbutton').on('click', function(event){
      event.preventDefault();

      var username = $('#credential_username').val();
      var password = $('#credential_password').val();

      var data = new Object;

      data['username'] = username;
      data['password'] = password;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/credentials/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').html('Successfully added new credential.');
          window.location.href = '/cmdb/credentials';
        },
        error: function(response){
          debugger;
          $('#messages').html('Error adding credential.');
        }
      });

      $('#newcredentialmodal').modal('hide');
    });

  });
</script>

<h2>Credentials</h2>
<br/>

<a id='newcredentiallink' href='#newcredentialmodal' class='btn btn-primary' data-keyboard="true">New credential</a>

<hr/>

<table id='credentialtable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Username</th>
      <th>Password</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>