<?php
  include_once("../model/database.php");
  include_once("../model/Users.php");
  include_once("../model/Admin.php");
  include_once("../model/MailMessages.php");
  include_once("../model/Posts.php");

  //temp include the tables.php from the installer to test some functions...
  //include_once("../administration/database/tables.php");

  //this is a debug page.... no other use
  // session_start();
  // //var dump
  // var_dump($_SESSION);
  // //include("../partial/_loggedout.php");
  // //include("../partial/_loggedin.php");
  // include("../partial/user/_userdashboard.php");
  //
  // //user_showDashboard();
 ?>

 <?php include("../partial/_head.php"); ?>

     

<?php include("../partial/_pageend.php"); ?>
