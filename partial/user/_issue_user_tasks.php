<?php
// allows user to view issue statuses that they have started. also will eventully receive and send messages about the issue... Not the warns though... those would go to the messages.
  $user_issueArray = array();
?>
<div class="col-md-8"> <!-- satrt main section -->
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h1>Issue Tracking User Interface</h1>
    </div>
    <div class="panel-body">
      <form method="post">
        <label>Create new support issue:</label>
        <div class="form-group">
          <label for="systemIssue">Report An Issue With:</label>
          <select name="systemIssue" class="form-control">
            <option value="0">a Page</option>
            <option value="1">a Post</option>
            <option value="2">a content Story</option>
            <option value="3">a User</option>
            <option value="4">Requesting Access</option>
            <option value="5">Requesting Removal of Your Account</option>
          </select>
        </div>
        <div class="form-group">
          <label for="issueTitle">Issue Title (30char Max):</label>
          <input type="text" name="issueTitle" value="" maxlength="30" class="form-control">
        </div>
        <div class="form-group">
          <label for="issueDescription">Describe Your Issue(500Char Max):</label>
          <textarea name="issueDescription" rows="4" cols="50" class="form-control" maxlength="500"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="newIssue" value="Submit New Issue" class="btn btn-primary btn-block">
        </div>
      </form>
      <hr />
      <div class="row">
        <div class="col-md-6">
          <form method="post">
            <label>Review pending actions:</label>
            <div class="">

            </div>
            <div class="form-group">
              <input type="submit" name="dummysubmit" value="Submit Action" class="btn btn-primary btn-block">
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <form method="post">
            <label>something else here:</label>
            <div class="">

            </div>
            <div class="form-group">
              <input type="submit" name="dummysubmit" value="Submit Action" class="btn btn-primary btn-block">
            </div>
          </form>
        </div>
      </div>
      <hr />

      <form method="post">
        <label>Respond to support message:</label>
        <div class="form-group">
          <label for="">Select the open issue that you wish to respond:</label>
          <!-- placeholder code -->
          <select class="form-control" name="">
            <option value=""></option>
          </select>
        </div>
        <div class="form-group">
          <label for="replyMessage">Reply to the selected issue ticket below.</label>
          <textarea name="replyMessage" rows="4" cols="50" maxlength="5000" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="replyIssueMessage" value="Send Response" class="btn btn-primary btn-block">
        </div>
      </form>
      <hr />
    </div>
  </div>
</div> <!-- end main section -->
