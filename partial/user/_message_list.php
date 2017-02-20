<?php
  // The list of available messages,
?>
<div class="col-md-2">
  <!-- leaves six - eight cols to work with when comming to reading pvt messages -->
  <!-- below here will be the message list panel -->
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3>Mailbox</h3>
    </div>
    <div class="panel-body">
      <!-- code to display list below -->
      <?php
      $messageArray = array();
      $messageArray = get_emailList($_SESSION['personID']);
      $maxMessage = 0;
      if (!empty($messageArray) && !isset($_SESSION['currentmessage'])) {
        foreach ($messageArray as $id => $val) {
          $maxMessage = max(array($maxMessage, $val['messageid']));
          // code for max array for associated array values found on Stack overflow.com // http://stackoverflow.com/questions/5093171/hightest-value-of-an-associative-array
        }
        get_email_from_list($maxMessage);
      }
      if(!empty($messageArray)){
        foreach ($messageArray as $key => $value) {
          $msgSub = $messageArray[$key]['messageSubject'];
          $msgSndr = $messageArray[$key]['Username'];
          $msgisread = $messageArray[$key]['MessageReadFlag'];
          $mid = $messageArray[$key]['messageid'];
          echo "<form method='post'>";
          echo "<input type='hidden' name='msgID' value='$mid' />";
          echo "<div class='list-group'>";
          if ($msgisread){
            echo "<button type='submit' name='messageRdme' class='list-group-item'>";
            echo "<h5 class='list-group-item-heading'>$msgSub</h5>";
          }else{
            echo "<button type='submit' name='messageRdme' class='list-group-item list-group-item-info'>";
            echo "<h5 class='list-group-item-heading' style='font-weight:bold;'>$msgSub</h5>";
          }
          echo "</button></div></form>";
        }
      }else{
        echo "You do not have any messages yet.";
      }
      ?>
    </div>
  </div>
</div>
