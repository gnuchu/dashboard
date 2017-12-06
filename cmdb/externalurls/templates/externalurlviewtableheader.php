<script>
  $(function(){
    $('#externalurltable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 4},
        {"orderable": false, "targets": 5},
      ],
      "destroy": true
    });

    $('#newexternalurllink').click(function(){
      $('#newexternalurlmodal').modal('show');
    });

    $(document).on('click', '.deletelink',function(){
      $("#externalurl_id").val($(this).data('id'));
      $('#confirmationModal').modal('show');
    });

    $("#deletebutton").on('click', function(event){
      event.preventDefault();
      var externalurl_id = $('#externalurl_id').val();
      
      var data = new Object;
      data['id'] = externalurl_id;
      data['action'] = 'delete';

      $.ajax({
        url: '/cmdb/externalurls/index.php',
        method: 'POST',
        data: data,
        success: function(response){
          $('#messages').html('Successfully deleted.');
          $('#messages').addClass('alert alert-success');
          window.location.href = '/cmdb/externalurls';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Database setting cannot be deleted as it is in use.');
        }
      });

      $('#confirmationModal').modal('hide');
    });

    //Create new externalurl
    $('#externalurlsubmitbutton').on('click', function(event){
      event.preventDefault();

      var id = $('#externalurl_id').val();
      var environment_id = $('#externalurl_environment_name').val();
      var app_name = $('#externalurl_app_name').val();
      var url = $('#externalurl_url').val();

      var data = new Object;

      data['id'] = id; 
      data['environment_id'] = environment_id; 
      data['app_name'] = app_name; 
      data['url'] = url; 
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/externalurls/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').addClass('alert alert-success');
          $('#messages').html('Successfully added new externalurl.');
          window.location.href = '/cmdb/externalurls';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Error adding externalurl.');
        }
      });

      $('#newexternalurlmodal').modal('hide');
    });

  });
</script>

<h2>External URLs</h2>
<br/>

<div class='row'>
  <div class='col-lg-4 col-lg-offset-4'>
    <div id='messages'></div>
  </div>
</div>

<a id='newexternalurllink' href='#newexternalurlmodal' class='btn btn-primary' data-keyboard="true">New External URL</a>

<hr/>

<table id='externalurltable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Environment</th>
      <th>App Name</th>
      <th>URL</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>