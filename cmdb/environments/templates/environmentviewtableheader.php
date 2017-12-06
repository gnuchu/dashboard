<script>
  $(function(){
    $('#environmenttable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 5}
      ],
      "destroy": true
    });

    $('#newenvironmentlink').click(function(){
      $('#newenvironmentmodal').modal('show');
    });

    //Create new environment
    $('#environmentsubmitbutton').on('click', function(event){
      event.preventDefault();

      var name = $('#environment_name').val();
      var description = $('#environment_description').val();
      var owner = $('#environment_owner').val();
      var releasebranch = $('#environment_releasebranch').val();
      var environmenttype = $('#environment_environmenttype').val();
      var retired = $('#environment_retired').val() == 'Yes' ? 1 : 0;
      var isproduction = $('#environment_isproduction').val() == 'Yes' ? 1 : 0;
      var canbebackedupfrom = $('#environment_canbebackedupfrom').val() == 'Yes' ? 1 : 0;
      var canberestoredto = $('#environment_canberestoredto').val() == 'Yes' ? 1 : 0;
      var extractparametersandproperties = $('#environment_extractparametersandproperties') == 'Yes' ? 1 : 0;

      var data = new Object;

      data['name'] = name;
      data['description'] = description;
      data['owner'] = owner;
      data['releasebranch'] = releasebranch;
      data['environmenttype'] = environmenttype;
      data['retired'] = retired;
      data['isproduction'] = isproduction;
      data['canbebackedupfrom'] = canbebackedupfrom;
      data['canberestoredto'] = canberestoredto;
      data['extractparametersandproperties'] = extractparametersandproperties;
      data['action'] = 'new';

      $.ajax({
        url: '/cmdb/environments/index.php',
        method: 'POST',
        data: data,
        success: function(){
          $('#messages').html('Successfully added new environment.');
          window.location.href = '/cmdb/environments';
        },
        error: function(){
          $('#messages').html('Error adding environment.');
        }
      });

      $('#newenvironmentmodal').modal('hide');
    });

  });
</script>

<h2>Environments</h2>
<br/>

<a id='newenvironmentlink' href='#newenvironmentmodal' class='btn btn-primary'>New environment</a>

<hr/>

<table id='environmenttable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Name</th>
      <th>Is Production?</th>
      <th>Description</th>
      <th>Retired</th>
      <th>Environment Type</th>
      <th>Release Branch</th>
      <th>Owner</th>
      <th>Backupable?</th>
      <th>Restorable?</th>
      <th>Extract Parameters?</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>