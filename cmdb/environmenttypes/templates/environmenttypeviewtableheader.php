<script>
  $(function(){
    $('#environmenttypetable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 3},
        {"orderable": false, "targets": 4},
      ],
      "destroy": true
    });

    $('#newenvironmenttypelink').click(function(){
      $('#newenvironmenttypemodal').modal('show');
    });

    $(document).on('click', '.deletelink',function(){
      $("#environmenttype_id").val($(this).data('id'));
      $('#confirmationModal').modal('show');
    });

    $("#deletebutton").on('click', function(event){
      event.preventDefault();
      var environmenttype_id = $('#environmenttype_id').val();
      
      var data = new Object;
      data['id'] = environmenttype_id;
      data['action'] = 'delete';

      $.ajax({
        url: '/cmdb/environmenttypes/index.php',
        method: 'POST',
        data: data,
        success: function(response){
          $('#messages').html('Successfully deleted.');
          $('#messages').addClass('alert alert-success');
          window.location.href = '/cmdb/environmenttypes';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Database setting cannot be deleted as it is in use.');
        }
      });

      $('#confirmationModal').modal('hide');
    });

    //Create new environmenttype
    $('#environmenttypesubmitbutton').on('click', function(event){
      event.preventDefault();

      var id = $('#environmenttype_id').val();
      var description = $('#environmenttype_description').val();
      var rank = $('#environmenttype_rank').val();

      var data = new Object;

      data['id'] = id;
      data['description'] = description;
      data['rank'] = rank;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/environmenttypes/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').addClass('alert alert-success');
          $('#messages').html('Successfully added new environmenttype.');
          window.location.href = '/cmdb/environmenttypes';
        },
        error: function(response){
          $('#messages').addClass('alert alert-danger');
          $('#messages').html('Error adding environmenttype.');
        }
      });

      $('#newenvironmenttypemodal').modal('hide');
    });

  });
</script>

<h2>Environment Types</h2>
<br/>

<div class='row'>
  <div class='col-lg-4 col-lg-offset-4'>
    <div id='messages'></div>
  </div>
</div>

<a id='newenvironmenttypelink' href='#newenvironmenttypemodal' class='btn btn-primary' data-keyboard="true">New Environment Type</a>

<hr/>

<table id='environmenttypetable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Description</th>
      <th>Rank</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>