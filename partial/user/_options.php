<?php
  global $disp_msg_cnt, $err_msg;
  $disp_msg_cnt = check_new_messages($_SESSION["personID"]);
?>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4>User Options</h4>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" method="post">
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="view-posts" value="View Posts" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="change-email" value="Change Email" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="view-unread-posts" value="Unread Posts" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="create-new-post" value="New Post" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="ret2dash" value="Dashboard" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="issuetracker" value="Issue Tracking UI" class="btn btn-primary btn-xs btn-block">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <!-- <input type="submit" name="readMessages" value="Read Messages" class="btn btn-primary btn-block"> -->
                <button type="submit" name="readMessages" class="btn btn-primary btn-xs btn-block">Read Messages&nbsp;&nbsp;<?php echo "<span class='badge'>$disp_msg_cnt</span>"; ?></button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <input type="submit" name="writeMessages" value="Write Messages" class="btn btn-primary btn-xs btn-block">
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
