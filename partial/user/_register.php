<!-- the registration form here -->
<form class="form-horizontal" method="post">
  <!-- space for an error message to show. -->
    <?php
      // should display an error message if one exists...
      if(!empty($_POST)){
        if (!empty($message)){
          echo "<p class='alert alert-danger'>ERROR:: $message</p>";
          $_POST = array();
        }elseif (!empty($error_message)) {
          echo "<p class='alert alert-danger'>ERROR:: $error_message</p>";
          $_POST = array();
        }
      }else{
        $_POST = array();
        if (!empty($message)){
          echo "<p class='alert alert-danger'>ERROR:: $message</p>";
          $_POST = array();
        }elseif (!empty($error_message)) {
          echo "<p class='alert alert-danger'>ERROR:: $error_message</p>";
          $_POST = array();
        }
      }
    ?>
  <!-- ok we need the primary data from the new user, name first and last, username, password, and email address -->
    <input type="hidden" name="captcha" value="">
  <div class="form-group">
    <label for="Username" class="control-label">Desired Username: </label><span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="text" name="Username" value="" class="form-control">
  </div>
  <div class="form-group">
    <label for="Password" class="control-label">Enter Your Password:</label> <span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="password" name="Password" value="" class="form-control">
  </div>
  <div class="form-group">
    <label for="Passwordconf" class="control-label">Re-enter Your Password:</label> <span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="password" name="Passwordconf" value="" class="form-control">
  </div>
  <div class="form-group">
    <label for="Fname" class="control-label">Enter your First Name:</label> <span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="text" name="Fname" class="form-control" value="">
  </div>
  <div class="form-group">
    <label for="Lname" class="control-label">Enter your Last Name:</label> <span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="text" name="Lname" class="form-control" value="">
  </div>
  <div class="form-group">
    <label for="emailAddress" class="control-label">Please enter your Email Address:</label> <span style="color:red;">&nbsp;&nbsp;* Required</span>
    <input type="email" name="emailAddress" class="form-control" value="">
  </div>
  <div class="form-group">
    <input type="submit" name="RegMe" value="Register" class="btn btn-primary">
    <input type="submit" name="cancel" value="Cancel" class="btn btn-danger">
    <br />
    <hr />
  </div>
</form>
