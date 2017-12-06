<script type='text/javascript'>
  $(function(){
    $('#environmenttypesubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#environmenttype_id').val();
      var description = $('#environmenttype_description').val();
      var rank = $('#environmenttype_rank').val();

      var data = new Object;

      data['id'] = id;
      data['description'] = description;
      data['rank'] = rank;

      $.ajax({
        url: '/cmdb/environmenttypes/environmenttypeedit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/environmenttypes';
        },
        error: function() {
          $('#messages').html('Error updating environmenttype.');
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

<form class="form-horizontal" id='environmenttypeform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit Credential Values</legend>

    <input type="hidden" name="environmenttype_id" id='environmenttype_id' value="%s">
    <!-- 1 -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="environmenttype_description">Description</label>
      <div class="col-md-4">
        <input id="environmenttype_description" name="environmenttype_description" type="text" placeholder="Description" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environmenttype_rank">Rank</label>
      <div class="col-md-4">
        <input id="environmenttype_rank" name="environmenttype_rank" type="number" placeholder="Display Rank" class="form-control input-md" value='%s'>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="environmenttypesubmitbutton">Save Changes</label>
      <div class="col-md-8">
        <button id="environmenttypesubmitbutton" name="submit" class="btn btn-primary">Submit</button>
      </form>
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='cmdb/environmenttypes';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>