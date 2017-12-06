<script>
  $(function(){
    $('#jobaudittable').DataTable({
      "columnDefs": [
       { type: 'date-uk', targets: 5 }
      ],
      "paging": true,
      "lengthMenu" : [25,50,100],
      "order": [[5, 'desc']]
    });
  });
</script>

<h2>Jenkins Job Audit</h2>
<br/>
<table id='jobaudittable' class="table table-bordered table-striped table-condensed">
  <thead>
    <tr>
      <th>User Name</th>
      <th>Environment</th>
      <th>Job Name</th>
      <th>Job Action</th>
      <th>Build URL</th>
      <th>Build Time</th>
    </tr>
  </thead>
  <tbody>