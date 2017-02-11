<form class="navbar-form navbar-right" action="" method="post">
<?php if (isset($_POST["logIn"])){
  if (!empty($login_out_message)){
    echo "<p class='alert alert-danger'> $login_out_message </p>";
    $_POST = array();
  }elseif (empty($login_out_message)){
    set_Login();
    //tests
  }
}else{

}
?>
<div class="form-group">
  <label for="">Username:</label>
  <input type="text" name="Username" value="" class="form-control">
</div>
<div class="form-group">
  <label for="Password">Password:</label>
  <input type="password" name="Password" value="" class="form-control">
</div>
<button type="submit" name="logIn" class="btn btn-primary">Login</button>
<button type="submit" name="Register" class="btn btn-primary">Register</button>
</form>
