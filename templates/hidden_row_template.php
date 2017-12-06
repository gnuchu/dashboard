<tr>
  <td colspan='15' class='hidden-row'>
    <div class="collapse" id='info_%d'>
      <div class='col-xs-12'>
        <h5>%s</h5>
        <table class="table table-condensed table-hover table-striped table-sm small table-bordered">
          <thead>
            <tr>
              <th>Status</th>
              <th>Server</th>
              <th>Category</th>
              <th>Application</th>
              <?php 
                if(authenticatedAdmin()) {
                  echo "<th>RM Link</th>";
                }
              ?>
            </tr>
          </thead>
          <tbody>
            %s
          </tbody>
        </table>
      </div>
      POLARIS_TABLE
    </div>
  </td>
</tr>