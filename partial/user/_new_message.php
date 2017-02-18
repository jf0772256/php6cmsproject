<?php
//creation page for creating a message.
  if (isset($_SESSION["currentmessage"])) {
    $replyMsgSubject = "RE: " . $_SESSION["currentmessage"]["messagesubject"];
    $replyRecip = $_SESSION["currentmessage"]["messagesender"];
    $replyrecUN = $_SESSION["currentmessage"]["messagesenderUN"];
  }
?>
<div class="col-md-9">
  <!-- leaves six - eight cols to work with when comming to reading pvt messages -->
  <!-- below here will be the message list panel -->
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3>Mailbox:Compose Message</h3>
    </div>
    <div class="panel-body">
      <!-- code to display form below for the usermessage below -->
      <!-- for now just a dummy text -->
      <form method="post">
        <div class="form-group">
          <?php
          if (isset($_SESSION["currentmessage"])) {
            echo "<label for='mailListContct' class='control-label'>Reply to: </label>";
            echo " $replyrecUN";
          }else{
            echo "<label for='mailListContct' class='control-label'>Select a recipent: </label>";
            get_all_users("mailListContct");
          }
          ?>
        </div>
        <div class="form-group">
          <?php
          if (isset($_SESSION["currentmessage"])) {
            echo "<label for='messageSubject'>Subject: </label>";
            echo "<input type='text' name='messageSubject' value='$replyMsgSubject' class='form-control'>";
          }else{
            echo "<label for='messageSubject'>Subject: </label>";
            echo "<input type='text' name='messageSubject' value='' class='form-control'>";
          }
          ?>
        </div>
        <div class="form-group">
          <label for="messageBody">The body of the message: </label><br />
          <textarea name="messageBody" rows="4" cols="40" maxlength="1000"></textarea>
        </div>
        <div id="hiddenfields">
          <input type="hidden" name="messageReadFlag" value="0"> <!-- sets default flag. -->
          <input type="hidden" name="messageSpamFlag" value="0"> <!-- sets default flag. -->
        </div>
        <div class="form-group">
          <?php
          if (isset($_SESSION["currentmessage"])) {
            echo "<input type='submit' name='sendReplyMessage' value='Send Reply' class='btn btn-success btn-block'>";
          }else{
            echo "<input type='submit' name='sendMessage' value='Send' class='btn btn-success btn-block'>";
          }
          ?>
        </div>
      </form>
    </div>
  </div>
</div>
