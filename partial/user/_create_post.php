<div class="col-md-8 offset-2">
  <div class="panel panel-default">
    <div class="panel-heading">
      <!-- Page not avail -->
      <h3>Create a new post.</h3>
    </div>
    <div class="panel-body">
      <!-- page is not avail -->
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <form class="form-horizontal" method="post">
            <div class="form-group">
              <label for="postTitle" class="control-label">Post Title:</label>
              <input type="text" name="postTitle" value="" class="form-control">
            </div>
            <div class="form-group">
              <label for="postSlug" class="control-label">Post Slug:</label>
              <input type="text" name="postSlug" value="" class="form-control">
            </div>
            <div class="form-group">
              <label for="postBody" class="control-label">Post Body</label>
              <textarea name="postBody" rows="10" cols="65" maxlength="5000" class="form-conrtol"></textarea>
              <p style="font-style:italic;"> 5,000 characters MAX!</p>
            </div>
            <div class="form-group">
              <input type="submit" name="submitNewPost" value="Create Post" class="btn btn-primary">
            </div>
          </form>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </div>
</div>
