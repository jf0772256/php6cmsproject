
<?php
  // requires user model.
  //require("model/Users.php")
?>
<!-- User Dashboard -->
<?php
  //checks if user is logged in, if true then the user dashboard will be loaded.
  //if not then a login required page will be loaded prohibiting use untill logged in.
  if (!isset($_SESSION)) {
    include("error/loginreq.php");
    exit();
  }
  $pageTitle = "User Dashboard";
  include("partial/_head.php")
?>

<?php
  include("partial/_footercontent.php");
  include("partial/_pageend.php");
?>
