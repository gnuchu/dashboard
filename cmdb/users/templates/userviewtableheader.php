<script>
  $(function(){
    $('#servertable').DataTable({
      "paging" : true,
      "columnDefs": [
        {"orderable": false, "targets": 5}
      ],
      "destroy": true
    });
  });
</script>

<h2>Users</h2>
<br/>

<hr/>

<table id='servertable' class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>UserId</th>
      <th>UserLogin</th>
      <th>UserFirstname</th>
      <th>UserSurname</th>
      <th>UserEmail</th>
      <th>Admin</th>
      <th>Disabled</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>