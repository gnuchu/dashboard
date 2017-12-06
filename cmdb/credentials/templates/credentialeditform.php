<script type='text/javascript'>
  $(function(){
    $('#credentialsubmitbutton').on('click', function(event){

      event.preventDefault();

      var username = $('#credential_username').val();
      var password = $('#credential_password').val();
      var id = $('#credential_id').val();

      var data = new Object;

      data['username'] = username;
      data['password'] = password;
      data['id'] = id;

      $.ajax({
        url: '/cmdb/credentials/credentialedit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/credentials';
        },
        error: function() {
          $('#messages').html('Error updating credential.');
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

<form class="form-horizontal" id='credentialform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Credential Values</legend>

    <input type="hidden" name="credential_id" id='credential_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="credential_username">Username</label>
      <div class="col-md-4">
        <input id="credential_username" name="credential_username" type="text" placeholder="Username" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="credential_password">New Password</label>
      <div class="col-md-4">
        <input id="credential_password" name="credential_password" type="password" placeholder="Password" class="form-control input-md">
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="credentialsubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="credentialsubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/credentials';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>