<script type='text/javascript'>
  $(function(){
    $('#environmentsubmitbutton').on('click', function(event){

      event.preventDefault();

      var id = $('#environment_id').val();
      var name = $('#environment_name').val();
      var description = $('#environment_description').val();
      var releasebranch = $('#environment_releasebranch').val();
      var owner = $('#environment_owner').val();
      var environmenttype = $('#environment_environmenttype').val();
      var retired = $('#environment_retired').val() == 'Yes' ? 1 : 0;
      var isproduction = $('#environment_isproduction').val() == 'Yes' ? 1 : 0;
      var canbebackedupfrom = $('#environment_canbebackedupfrom').val() == 'Yes' ? 1 : 0;
      var canberestoredto = $('#environment_canberestoredto').val() == 'Yes' ? 1 : 0;
      var extractparametersandproperties = $('#environment_extractparametersandproperties').val() == 'Yes' ? 1 : 0;

      var data = new Object;

      data['id'] = id;
      data['name'] = name;
      data['description'] = description;
      data['releasebranch'] = releasebranch;
      data['owner'] = owner;
      data['environmenttype'] = environmenttype;
      data['retired'] = retired;
      data['isproduction'] = isproduction;
      data['canbebackedupfrom'] = canbebackedupfrom;
      data['canberestoredto'] = canberestoredto;
      data['extractparametersandproperties'] = extractparametersandproperties;

      $.ajax({
        url: '/cmdb/environments/environmentedit.php',
        method: 'POST',
        data: data,
        success: function(response) {
          document.location.href='/cmdb/environments';
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

<form class="form-horizontal" id='environmentform'>
  <fieldset>
    <!-- Form Name -->
    <legend>Edit environment Values</legend>

    <input type="hidden" name="environment_id" id='environment_id' value="%s">
    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_name">Name</label>
      <div class="col-md-4">
        <input id="environment_name" name="environment_name" type="text" placeholder="Name" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_description">Description</label>
      <div class="col-md-4">
        <input id="environment_description" name="environment_description" type="text" placeholder="Description" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_releasebranch">Release Branch</label>
      <div class="col-md-4">
        <input id="environment_releasebranch" name="environment_releasebranch" type="text" placeholder="Release Branch" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_owner">Owner</label>
      <div class="col-md-4">
        <input id="environment_owner" name="environment_owner" type="text" placeholder="Owner" class="form-control input-md" value='%s'>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_environmenttype">Environment Type</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_retired">Retired</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_isproduction">Is Production</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_canbebackedupfrom">Backupable</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_canberestoredto">Restorable</label>
      <div class="col-md-4">
        %s
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="environment_extractparametersandproperties">Extract Parameters and Properties?</label>
      <div class="col-md-4">
        %s
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="button1id">Save Changes</label>
      <div class="col-md-8">
        <button id="environmentsubmitbutton" name="submit" class="btn btn-primary">Submit</button>
        </form>
        
        <button id="cancel" name="cancel" class="btn btn-default" onClick="document.location.href='/cmdb/environments';">Cancel</button>
      </div>
    </div>

  </fieldset>


<hr/>
</div>
</body>
</html>