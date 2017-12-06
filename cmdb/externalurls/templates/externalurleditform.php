<script type='text/javascript'>
  $(function(){
    $('#externalurlsubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#externalurl_id').val();
      var environment_id = $('#externalurl_environment_name').val();
      var url = $('#externalurl_url').val();
      var app_name = $('#externalurl_app_name').val();

      var data = new Object;

      data['id'] = id;
      data['environment_id'] = environment_id;
      data['url'] = url;
      data['app_name'] = app_name;

      $.ajax({
        url: '/cmdb/externalurls/externalurledit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/externalurls';
        },
        error: function() {
          $('#messages').html('Error updating externalurl.');
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

<form class="form-horizontal" id='externalurlform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Credential Values</legend>

    <input type="hidden" name="externalurl_id" id='externalurl_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="externalurl_environment_name">Environment</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="externalurl_app_name">App Name</label>
      <div class="col-md-4">
        <input id="externalurl_app_name" name="externalurl_app_name" type="text" placeholder="App Name" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="externalurl_url">URL</label>
      <div class="col-md-4">
        <input id="externalurl_url" name="externalurl_url" type="text" placeholder="URL" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="externalurlsubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="externalurlsubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/externalurls';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>