<script type='text/javascript'>
  $(function(){
    $( '#searchform' ).submit(function(event){
      event.preventDefault();
      var svnrevisionnumber = $('input#svnrevisionnumber').val();
      var data = new Object;
      data['svnrevisionnumber'] = svnrevisionnumber;
      $('#searchform')[0].reset();

      $.ajax({
        url: '/buildview.php',
        method: 'POST',
        data: data,
        success: function(response) {
          console.log(response.html);
          $('#buildtable').html(response.html);
        }
      })
    });
  });
</script>

<div class='container-fluid'>
  <div class='row'>
    <form id='searchform'>
      <div class="form-group">
        <label for="svnrevisionnumber">SVN Revision</label>
        <input type="text" class="form-control" name='svnrevisionnumber' id="svnrevisionnumber" placeholder="SVN Revision Number">
      </div>
      
      <button class='btn btn-default'>Submit</button>
    </form>
  </div>

  <div class="row">
    <div id='buildtable'></div>
  </div>
</div>
