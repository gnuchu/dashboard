<script>
    $('#propstable').DataTable({
      "paging" : true,
      "destroy": true,
      "lengthMenu": [ 20, 50, 100 ]
    });
</script>
<h2>Script Parameters</h2>
<br/>
<hr/>
<table id='propstable' class='table table-bordered table-hover table-striped table-condensed'>
  <thead>
    <tr>
      <th>App</th>
      <th>Name</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
    