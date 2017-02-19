<?php global $dashboard_message, $dashboard_message_users, $dashboard_message_banned;
  $SpamCount = get_spam_messages_count();
  $DeletCount = get_deleted_messages_count();
?>
<div class="col-md-8 offset-2">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2>System Messages</h2>
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
      <h2>Purge and Undelete Messages</h2>
    </div>
    <div class="panel panel-body">
      <form class="form-horizontal" method="post">
        <?php
          //display selector for the undelete process.
          get_delete_flagged_messages("DelMsgList");
        ?>
        <div class="">
          <hr />
          <button type="submit" name="spampurge" class="btn btn-danger">Purge Spam &nbsp;&nbsp;<?php echo "<span class='badge'>$SpamCount</span>"; ?></button>
          <button type="submit" name="deletepurge" class="btn btn-danger">Purge Deleted &nbsp;&nbsp;<?php echo "<span class='badge'>$DeletCount</span>"; ?></button> &nbsp; || &nbsp;
          <button type="submit" name="unDeletemessage" class="btn btn-primary">Undelete Seletect Message</button>
        </div>
      </form>
    </div>
  </div>
</div>
