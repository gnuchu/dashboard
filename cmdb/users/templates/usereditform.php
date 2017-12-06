<script type='text/javascript'>
  $(function(){
    $('#usersubmitbutton').on('click', function(event){

      event.preventDefault();

      var user_userid = $('#user_userid').val();
      var user_userlogin = $('#user_userlogin').val();
      var user_userfirstname = $('#user_userfirstname').val();
      var user_usersurname = $('#user_usersurname').val();
      var user_useremail = $('#user_useremail').val();
      var user_admin = $('#user_admin').val();
      var user_disabled = $('#user_disabled').val();
    
      var data = new Object;

      data['user_userid'] = user_userid;
      data['user_userlogin'] = user_userlogin;
      data['user_userfirstname'] = user_userfirstname;
      data['user_usersurname'] = user_usersurname;
      data['user_useremail'] = user_useremail;
      data['user_admin'] = user_admin;
      data['user_disabled'] = user_disabled;

      $.ajax({
        url: '/cmdb/users/useredit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/users';
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

<form class="form-horizontal" id='userform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Server Values</legend>

    <input type="hidden" name="user_userid" id='user_userid' value="%s">
    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="user_userlogin">Login</label>
      <div class="col-md-4">
        <input id="user_userlogin" name="user_userlogin" type="text" placeholder="Login" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="user_userfirstname">Firstname</label>
      <div class="col-md-4">
        <input id="user_userfirstname" name="user_userfirstname" type="text" placeholder="Firstname" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="user_usersurname">Surname</label>
      <div class="col-md-4">
        <input id="user_usersurname" name="user_usersurname" type="text" placeholder="Surname" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="user_useremail">Email Address</label>
      <div class="col-md-4">
        <input id="user_useremail" name="user_useremail" type="text" placeholder`="Email Address" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="user_admin">Admin</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="user_disabled">Disabled</label>
      <div class="col-md-4">
        %s
      </div>
    </div>


    <div class="form-group">
      <label class="col-md-4 control-label" for="button1id">Save Changes</label>
      <div class="col-md-8">
        <button id="usersubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/users';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>