<script>
  $(function(){
    $('#wasconfigtable').DataTable({
      "paging" : true,
      "destroy": true
    });
  });
</script>

<table id="wasconfigtable" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>Environment</th>
      <th>Appserver</th>
      <th>Server</th>
      <th>Apps</th>
      <th>Config</th>
      <th>Last Updated</th>
    </tr>
  </thead>
  <tbody>