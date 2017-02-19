
<?php
  // requires user model.
  //require("model/Users.php")
  //checks if user is logged in, if true then the user dashboard will be loaded.
  //if not then a login required page will be loaded prohibiting use untill logged in.
  error_reporting(0);
    session_start();
  error_reporting(E_ALL);

  if (!isset($_SESSION) || sizeof($_SESSION) == 0) {
    echo "<p>ERR:: You must be logged in to this site to view this page</p>";
    exit();
  }
  $pageTitle = "User Dashboard";
  //include("partial/_head.php")
?>

<div class="container">
  <div class="container-fluid">
    <!-- building the ui for the dashboard -->
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4 offset-4">
        <h2 style="text-align:center;"><?php echo get_roles($_SESSION["personID"]); ?> Dashboard</h2>
      </div>
    </div>
    <div class="row">
      <?php

        include("partial/user/_options.php");

        if (sizeof($_POST)==0){
          include("partial/user/_dashboard.php"); //start at the dashboard
        }

        //check if there are any vars in the $_POST
        if (isset($_POST["change-email"])){
          // opens the module to change and update user email address
          $_POST = array();
          include("partial/user/_changeEmail.php");
        }elseif (isset($_POST["create-new-post"])) {
          // opens the new post module to create a new post.
          $_POST = array();
          include("partial/user/_create_post.php");
        }elseif (isset($_POST["view-posts"])) {
          // opens the views section of the page to see the posts.
          $_POST = array();
          include("partial/user/_view_posts.php");
        }elseif (isset($_POST["ret2dash"])) {
          //displays dashboard
          $_POST = array();
          include("partial/user/_dashboard.php");
        }elseif(isset($_POST["submitEmailChng"])){
          //updates emailAddress and returns you back to the user dashboard.
          user_updateEmail($_POST["changeEmail"]);
          include("partial/user/_dashboard.php");
        }elseif (isset($_POST["bannedUserList"])){
          //displays a page with users and banned users
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["changeuserEmail"])){
          //displays a page with users and banned users
          include("partial/admin/_adm_chnguseremail.php");

        }elseif (isset($_POST["banUser"])){
          //function call and then reload::
          set_user_banned($_POST["UsersList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["delUser"])){
          //function call and then reload::
          set_user_deleted($_POST["UsersList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["delbUser"])){
          //function call and then reload::
          set_user_deleted($_POST["BannedList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["remBan"])){
          //function to remove ban from user
          unset_user_banned($_POST["BannedList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["makeMod"])){
          //function to make user a moderator
          set_user_mod($_POST["UsersList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["makeAdm"])){
          //function to make user a Admin
          set_user_admin($_POST["UsersList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["remMod"])){
          //function to remove user a moderator
          unset_user_mod($_POST["ModsList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["remAdm"])){
          //function to remove user a Admin
          unset_user_admin($_POST["AdminsList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["undelUser"])){
          //function to remove ban from user
          unset_user_deleted($_POST["DeletedList"]);
          include_once("partial/admin/_viewuser_banned.php");

        }elseif (isset($_POST["submituserEmailChng"])){
          //function to change selected users email
          user_updateEmail($_POST["admchangeuserEmail"],$_POST["UsersEmailList"]);
          include("partial/user/_dashboard.php");

        }elseif (isset($_POST["submitNewPost"])){
          //function to create a new post
          set_new_post();
          include("partial/user/_dashboard.php");

        }elseif (isset($_POST["readMessages"])){
          //function to read messages
          //ill be a function... but want to show the actions;
          include("partial/user/_message_list.php");
          include("partial/user/_read_message.php");

        }elseif (isset($_POST["messageRdme"])){
          //function to read messages posted from other users...
          $messagengerID = $_POST["msgID"];
          //togglereadflag($messagengerID);
          get_email_from_list($messagengerID);
          //ill be a function... but want to show the actions;
          include("partial/user/_message_list.php");
          include("partial/user/_read_message.php");

        }
        elseif (isset($_POST["replymsg"])){
          include("partial/user/_new_message.php");
          $_POST=array();

        }elseif (isset($_POST["toggleread"])){
          $messagengerID = $_POST["msgid"];
          togglereadflag($messagengerID);
          get_email_from_list($messagengerID);
          $_POST=array();
          //ill be a function... but want to show the actions;
          include("partial/user/_message_list.php");
          include("partial/user/_read_message.php");

        }elseif (isset($_POST["togglespam"])){
          $messagengerID = $_POST["msgid"];
          togglespamflag($messagengerID);
          $_POST=array();
          //ill be a function... but want to show the actions;
          include("partial/user/_message_list.php");
          include("partial/user/_read_message.php");

        }elseif (isset($_POST["sendReplyMessage"])){
          //function to reply to a message
          new_messagepost();
          include("partial/user/_dashboard.php");

        }elseif (isset($_POST["writeMessages"])){
          //function to create a new message
          //there willl more code for this feature later
          unset($_SESSION["currentmessage"]);
          include("partial/user/_new_message.php");

        }elseif (isset($_POST["sendMessage"])){
          //function to create a new message
          new_messagepost();
          include("partial/user/_dashboard.php");

        }elseif (isset($_POST['rvwDelNPurge'])) {
          //adding action to the button
          include("partial/admin/_mailpurgeandudelete.php");

        }elseif (isset($_POST["toggledel"])) {
          //toggle delete status
          toggledelete($_SESSION['currentmessage']['messagenumber']);
          $_POST=array();
          //ill be a function... but want to show the actions;
          include("partial/user/_message_list.php");
          include("partial/user/_read_message.php");

        }elseif (isset($_POST['spampurge'])) {
          //permanatly deletes messages from database.
          purgeSpam();
          include("partial/admin/_mailpurgeandudelete.php");

        }elseif (isset($_POST["deletepurge"])) {
          //permanatly deletes messages from database.
          purgeDeleted();
          include("partial/admin/_mailpurgeandudelete.php");

        }elseif (isset($_POST["unDeletemessage"])) {
          //the undelete option!
          $Mnum = $_POST["DelMsgList"];
          Undelete_Message($Mnum);
          include("partial/admin/_mailpurgeandudelete.php");
        }elseif (sizeof($_POST)>0){
          //catch all for the unused modules.
          $_POST = array();
          include("partial/user/_featNotAvail.php");
        }else{$_POST=array();}
      ?>
    </div>
  </div>
</div>

<?php
//  include("partial/_pageend.php");
?>
