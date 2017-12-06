<script type='text/javascript'>
  $(function(){
    $('#clustersubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#cluster_id').val();
      var name = $('#cluster_name').val();
      var url = $('#cluster_url').val();
      var environment_id = $('#cluster_environment_name').val();
      var noclusterurl = $('#cluster_noclusterurl').val() == 'Yes' ? 1 : 0;

      var data = new Object;

      data['id'] = id;
      data['name'] = name;
      data['url'] = url;
      data['environment_id'] = environment_id;
      data['noclusterurl'] = noclusterurl;

      $.ajax({
        url: '/cmdb/clusters/clusteredit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/clusters';
        },
        error: function() {
          $('#messages').html('Error updating parameter.');
        }
      })
    });
  });
</script>

<div class='row'>
  <div class='col-lg-4 col-lg-offset-4'>
    <div id='messages'></div>
  </div>
</div>

<form class="form-horizontal" id='clusterform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Cluster Values</legend>

    <input type="hidden" name="cluster_id" id='cluster_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="cluster_name">Cluster Name</label>
      <div class="col-md-4">
        <input id="cluster_name" name="cluster_name" type="text" placeholder="Cluster Name" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="cluster_url">Cluster URL</label>
      <div class="col-md-4">
        <input id="cluster_url" name="cluster_url" type="text" placeholder="Cluster URL" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="cluster_environment_name">Environment</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="cluster_noclusterurl">No Cluster URL</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="clustersubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="clustersubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/clusters';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>