<script>
  $(function(){
    $('#parametertable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 3},
        {"orderable": false, "targets": 4}
      ]
    });

    $(document).on('click', '.deletelink',function(){
      $("#config_id").val($(this).data('id'));
      $('#confirmationModal').modal('show');
    });

    $('#newparameterlink').click(function(){
      $('#newparametermodal').modal('show');
    });

    $('#submitbutton').on('click', function(event){
      event.preventDefault();
      var config_name = $('#config_name').val();
      var config_server = $('#config_server').val();
      var config_value = $('#config_value').val();

      if(!config_name || !config_value || !config_server) {
        alert('Please supply values for all parameters. Blanks not allowed.');
        return;
      }

      var data = new Object;

      data['config_server'] = config_server;
      data['config_name'] = config_name;
      data['config_value'] = config_value;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/globalconfiguration/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully added new config item.');
          window.location.href = '/cmdb/globalconfiguration/index.php';
        },
        error: function(){
          alert('Failure');
          $('#messages').html('Error adding config item.');
        }
      });

      $('#newparametermodal').modal('hide');
    });

    $('#deletebutton').on('click',function(event){
      event.preventDefault();
      var config_id = $('#config_id').val();
      
      var data = new Object;
      data['id'] = config_id;
      data['action'] = 'delete';

      $.ajax({
        url: '/cmdb/globalconfiguration/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully delete config item.');
          window.location.href = '/cmdb/globalconfiguration/index.php';
        },
        error: function(){
          alert('Failure');
          $('#messages').html('Error deleting config item.');
        }
      });

      $('#confirmationModal').modal('hide');
    });
  });
</script>

<h2 id='urls'>Global Configuration</h2>
<br/>

<a id='newparameterlink' href='#newparametermodal' class='btn btn-primary' data-keyboard="true">New Parameter</a>
<hr/>

<table id='parametertable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>Server</th>
      <th>Name</th>
      <th>Value</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>