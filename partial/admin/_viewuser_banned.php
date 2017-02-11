<?php global $dashboard_message, $dashboard_message_users, $dashboard_message_banned; ?>
<div class="col-md-8 offset-2">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2>Messages</h2>
    </div>
    <div class="panel panel-body">
      <!-- Messages -->
      <div class="">
        <?php
          $dashboard_message_users=$dashboard_message_users;
          if (!empty($dashboard_message_users . $dashboard_message_banned)){
            $dashboard_message = $dashboard_message_users . $dashboard_message_banned;
            echo $dashboard_message;
            $dashboard_message=""; $dashboard_message_users="";$dashboard_message_banned ="";
          }

        ?>
      </div>
    </div>
  </div>
  <!-- begin user information -->
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2>User Lists</h2>
    </div>
    <div class="panel panel-body">
      <form class="form-horizontal" method="post">
        <?php
          get_all_users("UsersList");
          $dashboard_message = $dashboard_message_users;
        ?>
        <div class="">
          <hr />
          <input type="submit" name="banUser" value="Ban User" class="btn btn-danger">
          <input type="submit" name="delUser" value="Delete User" class="btn btn-danger"> &nbsp; || &nbsp;
          <input type="submit" name="makeMod" value="Make Moderator" class="btn btn-primary">
          <input type="submit" name="makeAdm" value="Make Administrator" class="btn btn-primary"> &nbsp; || &nbsp;
          <input type="submit" name="warnUsr" value="Warn User" class="btn btn-warning">
        </div>
      </form>
    </div>
  </div>
  <!-- we will be doing some "fancy" php to make this section more managable -->
  <?php
    if (isset($_POST) || sizeof($_POST)>=0 || empty($_POST)) {
      $show_banned = getcountsroles("isBanned",1);
      $show_deleted = getcountsroles("isUser",0);
      $show_Mods = getcountsroles("isMod",1);
      $show_Admin = getcountsroles("isAdmin",1);
      $show_FS = getcountsroles("isFormerStaff",1);
    }
      //call a function that returns a count of items matching the querys
      if ($show_banned == true){
        //display banned users section
        echo '<div class="panel panel-danger">';
        echo '<div class="panel-heading">';
        echo '<h2>Banned Users</h2>';
        echo '</div>';
        echo '<div class="panel panel-body">';
        echo '<form class="form-horizontal" method="post">';
        get_all_banned("BannedList");
        echo '</form>';
        echo '</div>';
        echo '</div>';
      }
      if ($show_deleted == true){
        //display deleted users section
        echo '<div class="panel panel-danger">';
        echo '<div class="panel-heading">';
        echo '<h2>Deleted Users</h2>';
        echo '</div>';
        echo '<div class="panel panel-body">';
        echo '<form class="form-horizontal" method="post">';
        get_all_deleted("DeletedList");
        echo '</form>';
        echo '</div>';
        echo '</div>';
      }
      if ($show_FS == true){
        // there are former staff available
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h2>Formar-Staff Lists</h2>';
        echo '</div>';
        echo '<div class="panel panel-body">';
        echo '<form class="form-horizontal" method="post">';
        get_all_formerstaff("FSList");
        echo '</form>';
        echo '</div>';
        echo '</div>';
      }
      if ($show_Mods == true){
        //display Moderator users section
        echo '<div class="panel panel-warning">';
        echo '<div class="panel-heading">';
        echo '<h2>Moderator Lists</h2>';
        echo '</div>';
        echo '<div class="panel panel-body">';
        echo '<form class="form-horizontal" method="post">';
        get_all_mods("ModsList");
        echo '</form>';
        echo '</div>';
        echo '</div>';
      }
      if ($show_Admin == true){
        //display Administrator users section
        echo '<div class="panel panel-info">';
        echo '<div class="panel-heading">';
        echo '<h2>Administrator Lists</h2>';
        echo '</div>';
        echo '<div class="panel panel-body">';
        echo '<form class="form-horizontal" method="post">';
        get_all_admin("AdminsList");
        echo '</form>';
        echo '</div>';
        echo '</div>';
      }
  ?>
</div>
