<script>
  $(function(){
    $('#servertable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 5}
      ],
      "destroy": true
    });

    $('#newserverlink').click(function(){
      $('#newservermodal').modal('show');
    });

    //Create new server
    $('#serversubmitbutton').on('click', function(event){
      event.preventDefault();

      var name = $('#server_name').val();
      var environment_name = $('#server_environment_name').val();
      var domain = $('#server_domain_select').val();
      var datapipe = $('#server_datapipe').is(':checked') ? 1 : 0;
      var credential_name = $('#server_credential_username').val();

      if(!name || !environment_name || !domain ) {
        alert('Please supply values for all servers. Blanks not allowed.');
        return;
      }

      var data = new Object;

      data['name']= name;
      data['environment_name']= environment_name;
      data['domain']= domain;
      data['datapipe']= datapipe;
      data['credential_name']= credential_name;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/servers/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully added new server.');
          window.location.href = '/cmdb/servers';
        },
        error: function(){
          $('#messages').html('Error adding server.');
        }
      });

      $('#newservermodal').modal('hide');
    });

  });
</script>

<h2>Servers</h2>
<br/>

<a id='newserverlink' href='#newservermodal' class='btn btn-primary' data-keyboard="true">New server</a>

<hr/>

<table id='servertable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Name</th>
      <th>Environment</th>
      <th>Domain</th>
      <th>Datapipe</th>
      <th>Credential</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>