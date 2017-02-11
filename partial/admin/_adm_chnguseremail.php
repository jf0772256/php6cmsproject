<div class="col-md-8 offset-2">
  <div class="panel panel-default">
    <div class="panel-heading">
      <!-- Page not avail -->
      <h3>Change the users email on file</h3>
    </div>
    <div class="panel-body">
      <!-- page is not avail -->
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <form class="form-horizontal" method="post">
            <div class="form-group">
              <label for="UsersEmailList" class="control-label">Please select a user that you wish to change the email of.</label>
              <?php get_all_users("UsersEmailList"); ?>
            </div>
            <div class="form-group">
              <label for="changeuserEmail" class="control-label">Please eneter the new email address:</label>
              <input type="email" name="admchangeuserEmail" value="" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" name="submituserEmailChng" value="Change Email" class="btn btn-primary">
            </div>
          </form>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </div>
</div>
