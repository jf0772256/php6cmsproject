<?php
  // displays a list of messages, and allows you to read, them.
  error_reporting(0);
?>
<div class="col-md-7">
  <!-- leaves six - eight cols to work with when comming to reading pvt messages -->
  <!-- below here will be the message list panel -->
  <?php
    $userMessages = array();
    $userMessages = get_emailList($_SESSION['personID']);
    if (!empty($userMessages) || count($userMessages)>0): ?>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3>Mailbox: Read Messages</h3>
    </div>
    <div class="panel-body">
      <?php
      $sub = $_SESSION['currentmessage']['messagesubject'];
      $from = $_SESSION['currentmessage']['messagesenderUN'];
      $tme = $_SESSION['currentmessage']['messagetime'];
      echo "<h4>Subject: $sub</h4>";
      echo "<p>From: $from</p>";
      echo "<p>Time Received: $tme</p>";
      echo "<hr />";
      echo $_SESSION['currentmessage']['messagebody'];
      echo "<hr /><br />";
      ?>
      <form method="post">
        <!-- probably be more code in here later -->
        <?php
          $sID = $_SESSION['currentmessage']['messagesender'];
          $isread = $_SESSION['currentmessage']['messageisread'];
          $isspam = $_SESSION['currentmessage']['messageisspam'];
          $msgid = $_SESSION['currentmessage']['messagenumber'];

          echo "<input type='hidden' name='senderid' value='$sID' />";
          echo "<input type='hidden' name='messageread' value='$isread' />";
          echo "<input type='hidden' name='messagespam' value='$isspam' />";
          echo "<input type='hidden' name='msgid' value='$msgid' />";
        ?>
        <div class="form-group">
          <input type="submit" name="replymsg" value="Reply" class="btn btn-success">
          <input type="submit" name="toggleread" value="Mark Read/Unread" class="btn btn-success">
          <input type="submit" name="toggledel" value="Delete Message" class="btn btn-danger">
          <input type="submit" name="togglespam" value="Mark Spam" class="btn btn-danger">
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
