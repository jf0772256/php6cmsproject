
<form class='navbar-form navbar-right' action="" method='POST'>
  <div class="form-group">
    <div class='alert alert-success'>
      Logged in as <?php echo $_SESSION['fullname']; ?>! <?php echo $_SESSION['lastTimeLoggedIn'];?>
      <input type='submit' name='logoffuser' value='Log Off' class='btn btn-primary' />
    </div>
  </div>
</form>
