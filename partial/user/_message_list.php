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
      <!-- for now just a dummy text -->
      <?php
      $messageArray = array();
      $messageArray = get_emailList($_SESSION['personID']);
      //echo var_dump($messageArray);
      foreach ($messageArray as $key => $value) {
        $msgSub = $messageArray[$key]['messageSubject'];
        $msgSndr = $messageArray[$key]['Username'];
        $mid = $messageArray[$key]['messageid'];
        echo "<form method='post'>";
        echo "<input type='hidden' name='msgID' value='$mid' />";
        echo "<div class='list-group'>";
        echo "<button type='submit' name='messageRdme' class='list-group-item'>";
        echo "<h4 class='list-group-item-heading'>Subject: $msgSub</h4>";
        echo "<h5 class='list-group-item-text'>From: $msgSndr</h5>";
        echo "</button></div></form>";
      }
      ?>
    </div>
  </div>
</div>
