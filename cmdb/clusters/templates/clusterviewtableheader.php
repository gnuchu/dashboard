<script>
  $(function(){
    $('#clustertable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 5}
      ],
      "destroy": true
    });

    $('#newclusterlink').click(function(){
      $('#newclustermodal').modal('show');
    });

    //Create new cluster
    $('#clustersubmitbutton').on('click', function(event){
      event.preventDefault();

      var name = $('#cluster_name').val();
      var url = $('#cluster_url').val();
      var environment_id = $('#cluster_environment_name').val();
      var noclusterurl = $('#cluster_noclusterurl').val() == 'Yes' ? 1 : 0;

      var data = new Object;

      data['name'] = name;
      data['url'] = url;
      data['environment_id'] = environment_id;
      data['noclusterurl'] = noclusterurl;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/clusters/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response){
          $('#messages').html('Successfully added new cluster.');
          window.location.href = '/cmdb/clusters';
        },
        error: function(response){
          $('#messages').html('Error adding cluster.');
        }
      });

      $('#newclustermodal').modal('hide');
    });

  });
</script>

<h2>Clusters</h2>
<br/>

<a id='newclusterlink' href='#newclustermodal' class='btn btn-primary' data-keyboard="true">New cluster</a>

<hr/>

<table id='clustertable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Name</th>
      <th>URL</th>
      <th>Environment</th>
      <th>No Cluster URL?</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>