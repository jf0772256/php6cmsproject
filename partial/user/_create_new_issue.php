<?php
  //used for creating a new issue.
  //this will interface with the database and the tables being used to generate an issue code.
?>
<div class="col-md-8 offset-2">
  <div class="panel panel-default">
    <div class="panel-heading">
      <!-- Page not avail -->
      <h3>Create a new Issue.</h3>
    </div>
    <div class="panel-body">
      <!-- page is not avail -->
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <form class="form-horizontal" method="post">
            <div class="form-group">
              <label for="postTitle" class="control-label">Issue Title:</label>
              <input type="text" name="postTitle" value="" placeholder="30 Characters MAX" class="form-control">
            </div>
            <div class="form-group">
              <label for="postBody" class="control-label">Issue Description</label>
              <textarea name="postBody" rows="5" cols="65" maxlength="500" class="form-conrtol"></textarea>
              <p style="font-style:italic;"> 500 characters MAX!</p>
            </div>
            <div class="form-group">
              <input type="submit" name="submitNewIssue" value="Submit Issue" class="btn btn-primary">
            </div>
          </form>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </div>
</div>
