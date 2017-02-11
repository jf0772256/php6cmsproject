  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3>User Options</h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" method="post">
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="view-posts" value="View Posts" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="change-email" value="Change Email" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="view-unread-posts" value="Unread Posts" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="create-new-post" value="New Post" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="ret2dash" value="Dashboard" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="readMessages" value="Read Messages" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="writeMessages" value="Write Messages" class="btn btn-primary btn-block">
              </div>
            </div>
          </div>
        </form>
      </div>
      <?php
        //get any other menus based on role
        $userRole = get_roles($_SESSION["personID"]);
        if ($userRole == "Super Administrator"){
          include("partial/admin/_sa_options.php");
        }elseif($userRole == "Administrator"){
          include("partial/admin/_admin_options.php");
        }elseif($userRole == "Moderator"){
          include("partial/admin/_mod_options.php");
        }else{
          //nothing additional displays here
        }
      ?>
    </div>
  </div>
