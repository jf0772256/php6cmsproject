<?php
//creation page for creating a message.

?>
<div class="col-md-8">
  <!-- leaves six - eight cols to work with when comming to reading pvt messages -->
  <!-- below here will be the message list panel -->
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3>Mailbox</h3>
    </div>
    <div class="panel-body">
      <!-- code to display form below for the usermessage below -->
      <!-- for now just a dummy text -->
      <form method="post">
        <div class="form-group">
          <label for="mailListContct" class="control-label">Select a recipent: </label>
          <?php get_all_users("mailListContct"); ?>
        </div>
        <div class="form-group">
          <label for="messageSubject">Subject: </label>
          <input type="text" name="messageSubject" value="" class="form-control">
        </div>
        <div class="form-group">
          <label for="messageBody">The body of the message: </label>
          <textarea name="messageBody" rows="7" cols="80" maxlength="1000"></textarea>
        </div>
        <div id="hiddenfields">
          <input type="hidden" name="messageSender" value="<?php $_SESSION['personID'] ?>">
          <input type="hidden" name="messageReadFlag" value="0"> <!-- sets default flag. -->
          <input type="hidden" name="messageSpamFlag" value="0"> <!-- sets default flag. -->
        </div>
        <div class="form-group">
          <input type="submit" name="sendMessage" value="Send" class="btn btn-success btn-block">
        </div>
      </form>
    </div>
  </div>
</div>
