<?php
/// this is being done to check the code to add users to the database -> Users table.. to ensure ref integ
/// we will attempt to create a set of identical users passed to it by the form on the registration section.
/// This is a critical step to keep teh registartions clean.

function add_user(){
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
    include("partial/user/_register.php");
    exit(); //jic something goes wrong we do not want to have the script resume.
  }
  //final catch for the $message string being full... meaning that there was an error and to halt code... even if passwords match.
  if (!empty($message)){
    include("partial/user/_register.php");
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
    @ $stmnt -> bind_param("ssssss",$uName,$encoded,$salt,$email,$firstName,$lastName);
    $resultQ1 = $stmnt -> execute();

  if (!$resultQ1){
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
    include("partial/user/_register.php");
    exit(); //jic something goes wrong we do not want to have the script resume.
  }else{
    //the following should add a newly generated user to the roles table.
    $select="(SELECT userID FROM users WHERE Username = ?)"; //sub query to return a user ID
    $insert="INSERT INTO roles (userID , isAdmin , isMod , isFormerStaff , isUser , isBanned) VALUES (? , 0 , 0 , 0 , 1 , 0);"; //insert query to add user to roles table
    //I tested this code in mysql phpmyAdmin and it worked with out error...
    $stmnt = $db -> prepare($select);
    @ $stmnt -> bind_param("s",$uName);
    $resultQ2 = $stmnt -> execute();

    if (!$resultQ2){
      echo "<p class='alert alert-danger'>User role not created - please add user manually.</p>";
    }else{
      $resultQ2 = $stmnt -> get_result();
      $data = $resultQ2 -> fetch_assoc();
      $newUser = $data["userID"];
      $stmnt2 = $db -> prepare($insert);
      $stmnt2 -> bind_param("i",$newUser);
      $resultQ3 = $stmnt2 -> execute();

      if (!$resultQ3){
        echo "<p class='alert alert-danger'>There was an error while attempting to update user role table for userID: " . $newUser . " please verify that the data is correct, or contact your webadmin.</p>";
      }else {
        echo "<p class='alert alert-success'>User and user roles updated successfully!</p>";
      }
    }

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
}

/// this process will now be used to log in, The function should be fairly simular to the actual code above... but less code otherwise,
/// since we are going to the database, grabbing the user information and compairing it to the value of the $_POST array, and if true
/// then format the page a little and eventually allow access to create posts or comments (since this is a test page as of yet...)

function set_Login(){
  // we get the post call...
  // we go to the database and search for the username, and return with the values.
  // we then compair the password to the users password, if valid and matching then we set the $_SESSION vars
  // we send the request to the index.php to reload with some changes in the form, which will be the removal of the login form
  //// the adding of some general personalization, and a log out button.

  //Define a few variables::
  error_reporting(0);
  global $db;
  $login_out_message = "";
  $uName = ""; //from login form
  $pword = ""; //from login form
  $un_from_db = ""; // username from database
  $pw_from_db = ""; // password from database

  $uID = ""; //userId?

  $user_Data = array(); //to try to store results in here after the query has been run;

  // I know that I really didnt have to do all the veriable setting before, but I wanted to show process... please bear with me:
  //set the uName and pword  to their POST array options.

  $uName = $_POST["Username"]; //Username from POST array should now be set to $uName

  // Set up the query
  $query = "SELECT * FROM Users WHERE Username = ?;"; // this should find the user from the database and return with all columns

  //captured working variant of documented code from stackOverflow.com url: http://stackoverflow.com/questions/11048622/how-to-fetch-all-in-assoc-array-in-mysqli

  //Process the query in a prepaired statement to handle the database values.
  $qStatement = $db -> prepare($query);

  //$result = $db -> query($query);
    if (!$qStatement) {
      $login_out_message = "There was an runtime script error, please contact the webadmin for assistance.";
      include("index.php");
      exit();
    }

    @ $qStatement -> bind_param("s",$uName); // i understand the command, not sure why it only seems to work with the uID though...
      //handle any errors from running this code
      if (!$qStatement->execute()){
        $login_out_message = "There was a runtime script error, please contact the webadmin for assistance.";
      }

      $result = $qStatement -> get_result();
      while ($data = $result->fetch_assoc()){
        $user_Data[] = $data;
      }

    //end capture note
    //test to see if the whole line is being grabbed, and it is...
    //var_dump($user_Data);
    @ $pword = crypt($_POST["Password"],$user_Data[0]["pwsecret"]); // here we have the Password from the login being salted, then placed in $pword to be checked.
    //echo $pword . " " . @ $user_Data[0]["Password"];
    // the salts are different even though the user information is tha same
    //Issue fixed :)
    //Salt issue resolved by reading php docs and website :: https://crackstation.net/hashing-security.htm

    //now to check if the two passwords are the same and return a result.
    if (isset($user_Data)){
      if ($pword == $user_Data[0]["Password"]){
        //do stuff here, but for now... just a success message.
        if ($user_Data[0]["Last_Visited"] == NULL){
          $myLastVisit = "This is your first visit.";
        }else{
          $visitTime = $user_Data[0]['Last_Visited'];
          $myLastVisit = "You last logged on this site on: $visitTime.";
        }
        $personName = $user_Data[0]['Fname'] . " " . $user_Data[0]['Lname'];
        //echo "<p class='alert alert-success'>Logged in as $personName! $myLastVisit</p><form class='' action='' method='POST'><input type='submit' value='Log Off' class='btn btn-primary' /></form>";
        //now to update the last visited time stamp!
        $userIDNum = $user_Data[0]['userID'];
        $query = "UPDATE Users
            SET Last_Visited = CURRENT_TIMESTAMP
            WHERE userID = $userIDNum;"; // this query is set to only change the user who has just logged in, and only their last visited time.
        $result = $db -> query($query); // this should push the update query to the database
        //var_dump($result, $userIDNum, $query); //fixed issue with query
        //Now to set the session vars to persist the data.
        $lifetime = 60*60; //session will last 1 hour, this shoudl be sufficient
        session_set_cookie_params($lifetime, '/'); // setting params of the session cookie, all but the first two are default.
          session_start(); // starts the session allowing for the setting of the session variables...
        //start setting session vars with a logged in flag::
        $_SESSION["loginSuccess"] = true; // if true than logged in, if false logged out
        $_SESSION["personID"] = $userIDNum; //stores userID from database to be used through out.
        $_SESSION["fullname"] = $personName; // users full name formatted
        $_SESSION["lastTimeLoggedIn"] = $myLastVisit; // Last visited string formatted
        $_SESSION["uNameSTR"] = $user_Data[0]["Username"]; // users username
        $_SESSION["DateJoined"] = $user_Data[0]["Joined_Date"]; // date user joined site
        $_SESSION["Reputation"] = $user_Data[0]["RepLevel"]; // Reputation level on site
        $_SESSION["HelpfullVotes"] = $user_Data[0]["HelpVotes"]; // voted positivly on user posts counted as number
        $_SESSION["DownVoted"] = $user_Data[0]["NegVotes"];  // voted negagtivly on user posts counted as number
        $_SESSION["PostCount"] = $user_Data[0]["Posts"]; //post count as number
        $_SESSION["Email"] = $user_Data[0]["emailAddress"]; // user email address
        ///more likely to be added later but this should suffice for now.

        //reload the index.php page
        $page = $_SERVER['index.php'];
        echo '<meta http-equiv="Refresh" content="0;' . $page . '">';

      }else{
        echo "<p class='alert alert-danger'>The log in crudentials that you provided did not match any users on our site.</p>";
      }
    }else{
      echo "Critical Error:: The page has stopped.";
      exit();
    }
  error_reporting(E_ALL);
}

/// The following will be a simple log out fuunction that will reset the page back to original as needed...

function set_Logout(){
  // first we get the POST Call
  // then we clear the $_SESSION vars and cache
  // then re send the request to reload and reset the index page back to normal.

  // Unset $_SESSION
  $_SESSION = array(); //clears array... effectivly logging off
  //user has been logged off!
}

function user_showDashboard(){
  //declare global and local vars for use during the display.
  global $db;
  $userdboard_message = ""; //used to pass messages to view.
  $uID = ""; //uid will be assigned by a session var set when user is logged in.
  $user_Data = array(); //to try to store results in here after the query has been run;

  $uID = $_SESSION["personID"]; // assign id to variable
  $query = "SELECT * FROM Users WHERE userID = ?;"; //build the query to be run
  $qStatement = $db -> prepare($query); // prepare query to be run
  @ $qStatement -> bind_param("i",$uID); // associate the binders to hold data from the query
  $qStatement -> execute(); //run the prepared query.

  $result = $qStatement -> get_result(); // go back in and get the data from all the columns
  while ($data = $result->fetch_assoc()){ //while there is data to be had in result, fill an associative array, and place into array called $Data.
    $user_Data[] = $data; //put the data in to the user_data array to be used in the code :)
  } //end while
  //debug processor
  //  var_dump($user_Data);
  //
}

//allow user to update users email address::
function user_updateEmail($newEmail, $user=0){
  //this process will be used to update a users email address...
  global $db,$dashboard_message;

  //process request to update users email
  if ($user === 0){
    $user = $_SESSION["personID"];
  }

  $query = "UPDATE users
    SET emailAddress = ?
    WHERE userID = ?";

    $stmnt = $db -> prepare($query);
    $stmnt -> bind_param("si", $newEmail, $user);
    //execute and error messages/success messages
    if(!$stmnt -> execute()){
      $dashboard_message = "<p class='alert alert-danger'>Your email has not updated.</p>";
    }else{
      $dashboard_message =  "<p class ='alert alert-success'>Your email address has been updated.</p>";
    }
    //we dont want this being run when an admin changes emailAddress's
    if($user === $_SESSION["personID"]){
      //requery db and get the new emailAddress and put that into the email session global.
      $query = "SELECT emailAddress FROM users WHERE userID = ?";
      $stmnt = $db -> prepare($query);
      $stmnt -> bind_param("i", $_SESSION["personID"]);
      $stmnt -> execute();
      $result = $stmnt -> get_result();
      $data = $result -> fetch_assoc();
      $_SESSION["Email"] = $data["emailAddress"];
    }
}
?>
