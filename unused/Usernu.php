<?php
  /// User controller to add and do things with users ///

  //set a variable for use to return messages to the user//
  $message = '';

  //Database Connection string.
  $userHost = 'localhost';
  $user = 'jessefender';
  $userPW = 'test1234';
  $dbUsed = 'jessefender';
  $dbU = new mysqli($userHost,$user,$userPW,$dbUsed);
  $c_Error = $dbU->connect_error;
  // if ($c_Error != null) {
  //   echo "<div class = 'alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>There was an error when attempting to connect to the databse:: $c_Error</p></div>";
  //   exit();
  // }else {
  //   echo "<div class = 'alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>The databse has been connected to successfully.</p></div>";
  // }

  //now that we have connected to the database, we will now need to create the queries,
  // for this we will use an array to store the queries. One for DML and one for Select and such.
  $aryQueryDML = array(); //db creator, alter, drop...
  $aryQuerySQL = array(); //select, update, insert, and delete

  //query 1 will be to build the user table. 2 will be to build the second table
  //query 1 will be to add user, 2 will be to update user, 3 will be to delete user.

  $aryQueryDML[0] = "CREATE TABLE Users (userID  INT Auto_Increment,Username VARCHAR(12) NOT NULL UNIQUE,Password  TEXT NOT NULL, emailAddress  VARCHAR(30) NOT NULL UNIQUE, Fname   VARCHAR(25) NOT NULL, Lname   VARCHAR(25) NOT NULL, Joined_Date TIMESTAMP, Last_Visited  TIMESTAMP, RepLevel  VARCHAR(30) DEFAULT 'Newbie', HelpVotes INT DEFAULT 0, NegVotes  INT DEFAULT 0, Posts     INT DEFAULT 0, PRIMARY KEY (userID), INDEX UserID_Index (userID));";
  $aryQueryDML[1] = ""; //I havent figured out what I want to do with this table quite yet.
  $aryQueryDML[2] = "DROP TABLE IF EXISTS Users;";

  //making php happy :) //
  $uName = "";
  $pw1 = "";
  $uEmail = "";
  $uFName = "";
  $uLName = "";
  $sqlParams = array();
  $sqlParams[0] = "userID";
  $whereClause = "";
  //now it should be happy//

  $aryQuerySQL[0] = "INSERT Users(Username,Password,emailAddress,Fname,Lname,Joined_Date,Last_Visited) VALUES('$uName','$pw1','$uEmail','$uFName','$uLName',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);"; //add User
  $aryQuerySQL[1] = "SELECT * FROM Users;"; // Select All
  $aryQuerySQL[3] = ""; // will be a structured update query request.
  $aryQuerySQL[4] = "SELECT $sqlParams[0] FROM Users WHERE $whereClause;"; //Uses array to hold the columns you want to select in ($sqlParams) and your WHERE clause in the ($whereClause) variable.
  //done for now...

  //$result = $dbU->query($aryQueryDML[2]);
  //  if ($result) {echo "<div class = 'alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>The table has been successfully dropped from the database.</p></div>";}else{$error_msg = $db -> error;echo "<div class = 'alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>There was an error when attempting to connect to the databse:: $error_msg</p></div>";}
  //$result = $dbU->query($aryQueryDML[0]);
  //  if ($result) {echo "<div class = 'alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>The database table has been created sucessfully</p></div>";}else{$error_msg = $db -> error;echo "<div class = 'alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>There was an error when attempting to create the table:: $error_msg</p></div>";}

  //due to some constraints on the the language we will have to define a function that will be called when action taken.
  // this function will be used to generate the user in the database

  function registerUser() {
    global $dbU;
    if (isset($_POST["RegMe"])){
      $uName = $_POST["Username"];
      @ $pw1 = crypt($_POST["Password"]);
      //password confirm
      @ $pwConf = crypt($_POST["Passwordconf"]); //this should be equal to $pw1 if no return an error when validating.
      //end password confirmation
      $uFName = $_POST["Fname"];
      $uLName = $_POST["Lname"];
      $uEmail = $_POST["emailAddress"];
      //Now we need to validate the code. This is important to help keep errors out of the database, esp password error
      $passValidates = validate_password($pw1,$pwConf);
      // other validation will be included

      //now that at least the passwords are validated - we need to move on or print an error message
      if ($passValidates){
        $result = $dbU->query("INSERT INTO Users(Username,Password,emailAddress,Fname,Lname,Joined_Date,Last_Visited) VALUES('$uName','$pw1','$uEmail','$uFName','$uLName',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);");
      }
      if (!$result){
        $error_msg = $dbU -> error;
        if (!empty($error_msg)){
          echo "<div class = 'alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>There was an error when attempting to register:: $error_msg</p></div>";
        }elseif (empty($error_msg) && !empty($message)){
          echo "<div class = 'alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-lable='Close'><span aria-hidden='true'>&times;</span></button><p>There was an error when attempting to register:: $message</p></div>";
        }
      }
    }
  }

  function validate_password($pass1, $pass2){
    if ($pass1 != $pass2){
      $message = $message + "Your passwords did not match, please check that you have them entered correctly before continuing.";
      return false;
    }else{
      return true;
    }
  }

?>
