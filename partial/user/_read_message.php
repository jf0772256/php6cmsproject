<?php
  // displays a list of messages, and allows you to read, them.
?>
<div class="col-md-7">
  <!-- leaves six - eight cols to work with when comming to reading pvt messages -->
  <!-- below here will be the message list panel -->
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3>Mailbox: Read Messages</h3>
    </div>
    <div class="panel-body">
      <!-- code to display form below for the usermessage below -->
      <!-- for now just a dummy text -->
      <p>Dear Jane Doe,</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <p>Salute!</p>
      <p>John Smith</p>
      <form method="post">
        <!-- probably be more code in here later -->
        <div class="form-group">
          <input type="submit" name="demobtn5" value="Reply" class="btn btn-success">
          <input type="submit" name="demoBtn2" value="Mark Read/Unread" class="btn btn-success">
          <input type="submit" name="demobtn3" value="Delete Message" class="btn btn-danger">
          <input type="submit" name="demobtn4" value="Mark Spam" class="btn btn-danger">
        </div>
      </form>
    </div>
  </div>
</div>
