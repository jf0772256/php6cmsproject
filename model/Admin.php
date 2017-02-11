<?php
  //Admin Model and controller...
  function add_adminUser($adminUserId, $isAdmin, $isMod, $isFormerStaff, $isUser, $isBanned){
    //this serves to create an entry in the ADMIN table that the $adminUserId is an admin, and or mod.
    //isAdmin and isMOD require a integer to serve as bit, 1 == true, 0 == false
    //first user is always default 1,1.
    global $db;
    //we will select the user to ensure that the user exists.
    //then we will preform the add.
    $select = "SELECT * FROM users WHERE userID = ?;";
    $stmnt = $db -> prepare($select);
    @ $stmnt -> bind_param('i',$adminUserId);
    if (!$stmnt -> execute()){
      $message = "There was an error in the database.";
    }
    $results = $stmnt -> get_result();
    $data = $results -> fetch_assoc();

    if (sizeof($data)>0){
      //clear $data and $results.
      $data = array();
      $results = "";
      //add admin...
      $insert = "INSERT INTO roles
      (
        userID , isAdmin , isMod , isFormerStaff , isUser , isBanned
      )
      VALUES
      (
        ? , ? , ? , ? , ? , ?
      );";

      $stmnt2 = $db -> prepare($insert);
      $stmnt2 -> bind_param("iiiiii", $adminUserId,$isAdmin,$isMod,$isFormerStaff,$isUser,$isBanned);
      $results = $stmnt2 -> execute();
      if (!$results){
        $error_message ="Admin was not created successfully.";
      }

    }else{
      $message = "The user doesnt exist! please select a user that is currently a user in the Users table and try again.";
    }
  }//end of add admin function
  //new function just for the first user creation//
  function add_first_user(){
    //data is retrieved from the POST array, It is passed to here from the index/register page.
    //check if we have a full array => expecting 9 entities, at least 2 will be "" strings.
    //this is due to the hidden field and the cancel button. so we will verify if the register button is been clicked.
    //this will be done in the index/register page...

    //setting vars
    global $db;

    //validating passed data to ensure that all fields are entered and all match the needed requirements
    if (strlen($_POST["Username"]) > 4){
      $uName = $_POST["Username"];
    }else{
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    if (strlen($_POST["Password"]) > 5){
      $pw1 = $_POST["Password"]; //unsalted yet
    }else{
      $pw1 = "b";
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    if(strlen($_POST["Passwordconf"])>5){
      $pw2 = $_POST["Passwordconf"]; //pw verification
    }else{
      $pw2 ="a";
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    if(strlen($_POST["emailAddress"])>8){
      $email = $_POST["emailAddress"];
    }else{
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    if(strlen($_POST["Fname"])>0){
      $firstName = $_POST["Fname"]; //users first name
    }else{
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    if(strlen($_POST["Lname"])>0){
      $lastName = $_POST["Lname"]; //obviously the last name
    }else{
      $message = "A required field is missing data needed or is too short to continue, please try again.";
    }
    $encoded = ""; //empty string here - dont need to validate it.
    //The following is just a test... I want to see if i can catch this error and spit it back out...
    if ($pw1 === $pw2){
      $salt = bin2hex(random_bytes(256));
      @ $encoded = crypt($_POST["Password"],$salt);
    }else{
      //var_dump($message);
      //this should catch any messages and force an exit code with errors...
      if (empty($message)){
        $message = "The two password fields must be the same. Please start over.";
      }
      //var_dump($message);
      include("../../partial/user/_register.php");
      exit(); //jic something goes wrong we do not want to have the script resume.
    }
    //final catch for the $message string being full... meaning that there was an error and to halt code... even if passwords match.
    if (!empty($message)){
      include("../../partial/user/_register.php");
      exit(); //jic something goes wrong we do not want to have the script resume.
    }

    @ $query = "INSERT INTO users
      (
        Username , Password , pwsecret , emailAddress , Fname , Lname , Joined_Date
      )
      VALUES
      (
        ? , ?, ? , ? , ? , ?, CURRENT_TIMESTAMP
      )";

      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("ssssss",$uName,$encoded,$salt,$email,$firstName,$lastName);
      $result = $stmnt -> execute();
    if (!$result){
      $error_message = "There was a problem when attempting to add you as a user, please try again and if the issue persists please contact the webadmin.";
      //clear vars for reuse::
      $_POST = array(); //should stop data from persisting.
      $uName = "";
      $pw1 = "";
      $pw2 = "";
      $encoded = "";
      $email = "";
      $firstName = "";
      $lastName = "";
    }else{
      //clear vars for reuse::
      $_POST = array(); //should stop data from persisting.
      $uName = "";
      $pw1 = "";
      $pw2 = "";
      $encoded = "";
      $email = "";
      $firstName = "";
      $lastName = "";
    }
  } //ends add first user function
  function get_roles($userID){
    //returns a result of what roles are present on the user.
    global $db;
    $role_Data = array();
    $select ="SELECT * FROM roles WHERE userID = ?;";
    $stmnt = $db -> prepare($select);
    @ $stmnt -> bind_param("i",$userID);
    if (!$stmnt -> execute()){
      echo "there was an error";
      exit();
    }
    $result = $stmnt -> get_result();
    while ($data = $result->fetch_assoc()){
      $role_Data[] = $data;
    }
    error_reporting(0);
    //now we need to pull data from role_data and assign it to variables...
    $role_admin = $role_Data[0]["isAdmin"]; //should be 1 or 0
    $role_mod = $role_Data[0]["isMod"]; //should be 1 or 0
    $role_FS = $role_Data[0]["isFormerStaff"]; //should be 1 or 0
    $role_User = $role_Data[0]["isUser"]; //should be 1 or 0
    $role_Banned = $role_Data[0]["isBanned"]; //should be 1 or 0
    error_reporting(E_ALL);
    //now to work through the process of issueing a role value for each option...
    if ($role_admin == 1 && $role_mod == 1 && !$role_Banned){
      return "Super Administrator";
    }elseif ($role_admin == 1 && $role_mod == 0 && !$role_Banned){
      return "Administrator";
    }elseif ($role_admin == 0 && $role_mod == 1 && !$role_Banned){
      return "Moderator";
    }elseif ($role_admin === 0 && $role_mod === 0 && $role_FS === 1 && !$role_Banned){
      return "Retired Staff";
    }elseif ($role_admin === 0 && $role_mod === 0 && $role_FS === 0 && !$role_Banned){
      return "User";
    }else{
      return "There was an error!";
    }
  }
  //gets all users in roles
  function get_all_users($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db,$dashboard_message_users;
    $user_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isUser = 1";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $user_Data[] = $data;
    }

    if (sizeof($user_Data) === 0){
      $dashboard_message_users = "<p>There are no users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($user_Data); $i++) {
        $key = $user_Data[$i]["userID"];
        $value = $user_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select>";
    }

  }
  function get_all_banned($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db,$dashboard_message_users;
    $banneduser_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isBanned = 1";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $banneduser_Data[] = $data;
    }

    if (sizeof($banneduser_Data) === 0){
      $dashboard_message_users = "<p class='alert alert-success'>There are no banned users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($banneduser_Data); $i++) {
        $key = $banneduser_Data[$i]["userID"];
        $value = $banneduser_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select>";
      echo "<hr />";
      echo "<input type='submit' name='delbUser' value='Delete Selected' class='btn btn-danger'>";
      echo "<input type='submit' name='remBan' value='Remove Ban' class='btn btn-primary'>";
    }
  }
  function get_all_deleted($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db,$dashboard_message_users;
    $banneduser_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isUser = 0";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $banneduser_Data[] = $data;
    }

    if (sizeof($banneduser_Data) === 0){
      $dashboard_message_users = "<p class='alert alert-success'>There are no deleted users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($banneduser_Data); $i++) {
        $key = $banneduser_Data[$i]["userID"];
        $value = $banneduser_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select>";
      echo "<hr />";
      echo "<input type='submit' name='undelUser' value='Un-Delete Selected' class='btn btn-danger'>";
    }
  }
  function get_all_admin($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db;
    $adminuser_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isAdmin = 1";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $adminuser_Data[] = $data;
    }

    if (sizeof($adminuser_Data) === 0){
      echo "<p>There are no users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($adminuser_Data); $i++) {
        $key = $adminuser_Data[$i]["userID"];
        $value = $adminuser_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select><hr />";
      echo "<input type='submit' name='remAdm' value='Remove Admin' class='btn btn-danger'>";
    }

  }
  function get_all_mods($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db;
    $moduser_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isMOD = 1";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $moduser_Data[] = $data;
    }

    if (sizeof($moduser_Data) === 0){
      echo "<p>There are no users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($moduser_Data); $i++) {
        $key = $moduser_Data[$i]["userID"];
        $value = $moduser_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select><hr />";
      echo "<input type='submit' name='remMod' value='Remove Mod' class='btn btn-danger'>";
    }

  }
  function get_all_formerstaff($selectorName = ''){
    //expects to be fun from with in a form!
    //returns and echos a selection combobox to the form,
    //accepts one parameter, name for select...is Optional
    global $db;
    $fsuser_Data=array();
    $query ="SELECT users.userID, Username FROM users JOIN roles ON users.userID = roles.userID WHERE roles.isFormerStaff = 1";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $results = $stmnt -> get_result();
    while ($data = $results->fetch_assoc()){
      $fsuser_Data[] = $data;
    }

    if (sizeof($fsuser_Data) === 0){
      echo "<p>There are no users</p>";
    }else{
      echo '<select name="'.$selectorName.'" class="form-control">';
      for ($i=0; $i < sizeof($fsuser_Data); $i++) {
        $key = $fsuser_Data[$i]["userID"];
        $value = $fsuser_Data[$i]["Username"];
        echo "<option value='$key'>$value</option>";
      }
      echo "</select>";
    }
  }
  //gets only user that is attempting to log in isBanned status!
  function get_isbanned_status($user){
    //
    global $db;
    $query = "SELECT isBanned FROM roles WHERE roles.userID = ?";
    $stmnt = $db -> prepare($query);
    $stmnt -> bind_param("i",$user);
    $stmnt -> execute();
    $result = $stmnt -> get_result();
    $data = $result -> fetch_assoc();
    if ($data["isBanned"] == 1){
      return true;
    }else{
      return false;
    }
  }
  function get_isUser_status($user){
    global $db;
    $query = "SELECT isUser FROM roles WHERE roles.userID = ?";
    $stmnt = $db -> prepare($query);
    $stmnt -> bind_param("i",$user);
    $stmnt -> execute();
    $result = $stmnt -> get_result();
    $data = $result -> fetch_assoc();
    if ($data["isUser"] == 1){
      return true;
    }else{
      return false;
    }
  }
  //sets or unsets user roles !!USE WITH CAUTION!!
  //toggles ban/unban
  function set_user_banned($user){
    global $db,$dashboard_message_users;
    //now we need to create the query that will communicate with the roles table
    //and will flip the 0 to a 1...
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not Ban User with id of 1!</p>";
    //  include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isBanned = 1 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been banned.</p>";
      //include("partial/admin/_viewuser_banned.php");
    }
  }
  function unset_user_banned($user){
    global $db, $dashboard_message_users;
    $query = "UPDATE roles SET isBanned = 0 WHERE userID = ?";
    $stmnt = $db -> prepare($query);
    $stmnt -> bind_param("i",$user);
    $stmnt -> execute();
    $dashboard_message_users = "<p class='alert alert-success'>User has been un-banned.</p>";
    //include("partial/admin/_viewuser_banned.php");
  }
  //toggles "delete"/"undelete"
  function set_user_deleted($user){
    global $db,$dashboard_message_users;
    //now we need to create the query that will communicate with the roles table
    //and will flip the 0 to a 1...
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not Delete User with id of 1!</p>";
      include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isUser = 0 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been 'deleted'.</p>";
      //include("partial/admin/_viewuser_banned.php");
    }
  }
  function unset_user_deleted($user){
    global $db, $dashboard_message_users;
    $query = "UPDATE roles SET isUser = 1 WHERE userID = ?";
    $stmnt = $db -> prepare($query);
    $stmnt -> bind_param("i",$user);
    $stmnt -> execute();
    $dashboard_message_users = "<p class='alert alert-success'>User has been brought back to life.</p>";
  }
  //toggles mod/user
  function set_user_mod($user){
    global $db,$dashboard_message_users;
    //now we need to create the query that will communicate with the roles table
    //and will flip the 0 to a 1...
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not do this action to the User with id of 1!</p>";
      include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isMod = 1 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been added as a Moderator.</p>";
      //include("partial/admin/_viewuser_banned.php");
    }
  }
  function unset_user_mod($user){
    global $db, $dashboard_message_users;
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not do this action to the User with id of 1!</p>";
      include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isMod = 0 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been removed from being a moderator.</p>";
    }//add to former staff role...

  }
  //toggles admin/user
  function set_user_admin($user){
    global $db,$dashboard_message_users;
    //now we need to create the query that will communicate with the roles table
    //and will flip the 0 to a 1...
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not do this action to the User with id of 1!</p>";
      include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isadmin = 1 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been added as an Administrator.</p>";
      //include("partial/admin/_viewuser_banned.php");
    }
  }
  function unset_user_admin($user){
    global $db,$dashboard_message_users;
    //now we need to create the query that will communicate with the roles table
    //and will flip the 0 to a 1...
    if ($user == 1){
      //prevents banning user 1;
      $dashboard_message_users = "<p class='alert alert-danger'>You may not do this action to the User with id of 1!</p>";
      include("partial/admin/_viewuser_banned.php");
    }else{
      $query = "UPDATE roles SET isAdmin = 0 WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i",$user);
      $stmnt -> execute();
      $dashboard_message_users = "<p class='alert alert-success'>User has been removed as an Administrator.</p>";
      //include("partial/admin/_viewuser_banned.php");
    }
  }
  //toggles SA/user //This is where you will need to be careful...
  //we will instatute some safeguards but seriously...
  function set_user_super($user){}
  function unset_user_super($user){}
  //return count of VALUES
  function getcountsroles($searchParams, $searchCrit){
    /// does query, gets results, counts the number and returns that true or false
    ///this willbe used to show the dashboard user control more efficently.
    // $searchParams == column
    // $searchCrit == int 1 or 0
    //should fetch back the count back from the query
    global $db, $dashboard_message_users;
    $count_Data=array();
    $query = "SELECT COUNT(userID) AS countVal FROM roles WHERE $searchParams = ?";
    $stmnt = $db -> prepare($query);
    if(!$stmnt){
     echo var_dump($stmnt);
    }
    $stmnt -> bind_param("i", $searchCrit);

    if(!$stmnt -> execute()){
      $dashboard_message_users = "<p class='alert alert-danger'>The db query faulted.</p>";
    }
    $result = $stmnt -> get_result();
    while ($data = $result->fetch_assoc()){
      $count_Data[] = $data;
    }

    $value = (int)($count_Data[0]['countVal']);
    if ($value > 0){
      return true;
    }else{
      return false;
    }
  }
?>
