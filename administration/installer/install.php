<?php
// installs sites web database
// uses hard coded params atm, but eventually not so.

//requires::
include_once("../../model/database.php");
include_once("../../model/Admin.php");
include_once("../database/tables.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Installation</title>
    <!-- includes -->
    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="container-fluid">
        <div class="col-3"></div>
        <div class="col-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3>Installation::</h3>
            </div>
            <div class="panel-body">
              <!-- this is where the magic happens -->
              <?php
                if (!empty($error_message) || !empty($message)){
                  // display error if any
                  echo "<div class='alert alert-danger'><p>There was an error: $error_message . $message</p></div>"; //will display errors if any.
                }
                //now if the tables have been generated
                if (sizeof($_POST) == 0){
                  echo "<form method='post'><input type='submit' name='cleanTables' value='Clean DB' class='btn btn-primary btn-block'></form>";
                }elseif (isset($_POST["cleanTables"])){
                  //runs only id db is dirty...
                  $cleaned = drop_dirty_tables();
                  if ($cleaned == "cleaned"){
                    echo "<button type='button' class='btn btn-success btn-block'>DB exists and is clean</button>";
                    echo "<form method='post'><input type='submit' name='createTables' value='Create Tables' class='btn btn-primary btn-block'></form>";
                  }else{
                    echo "<button type='button' class='btn btn-success btn-block'>DB exists and is clean</button>";
                    echo "<form method='post'><input type='submit' name='createTables' value='Create Tables' class='btn btn-primary btn-block'></form>";
                  }
                }elseif (isset($_POST["createTables"])){
                  $success = create_tables();
                  if ($success){
                    //clear POST Array
                    $_POST = array();
                    //display form2 asking if a user wants to create a user straight off (required)
                    echo "<button type='button' class='btn btn-success btn-block'>DB exists and is clean</button>";
                    echo "<button type='button' class='btn btn-success btn-block'>Create Tables</button>";
                    echo "<form method='post'><input type='submit' name='showcreateUser' value='Create First User' class='btn btn-primary btn-block' /></form>";
                  }else{
                    //show create tables form again
                    $_POST = array();
                    echo "<form method='post'><input type='submit' name='createTables' value='Create Tables' class='btn btn-primary btn-block'></form>";
                  }
                }elseif (isset($_POST["showcreateUser"])){
                  //clear post and show the create user form...
                  $_POST = array();
                  echo "<button type='button' class='btn btn-success btn-block'>DB exists and is clean</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Create Tables</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Create First User</button>";
                  echo "<div class='col-md-2'></div>";
                  echo "<div class='col-md-8'><div class='panel panel-primary'><div class='panel-heading'>";
                  echo "<h4>User Information:</h4></div><div class='panel-body'><form method='post' class='form-horizontal'>";
                  echo "<div class='form-group'><label for='Username' class='control-label'>Desired Username: </label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='text' name='Username' value='' class='form-control' />";
                  echo "</div><div class='form-group'><label for='Password' class='control-label'>Enter Your Password:</label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='password' name='Password' value='' class='form-control'>";
                  echo "</div><div class='form-group'><label for='Passwordconf' class='control-label'>Re-enter Your Password:</label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='password' name='Passwordconf' value='' class='form-control'>";
                  echo "</div><div class='form-group'><label for='Fname' class='control-label'>Enter your First Name:</label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='text' name='Fname' class='form-control' value=''>";
                  echo "</div><div class='form-group'><label for='Lname' class='control-label'>Enter your Last Name:</label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='text' name='Lname' class='form-control' value=''>";
                  echo "</div><div class='form-group'><label for='emailAddress' class='control-label'>Please enter your Email Address:</label>";
                  echo "<span style='color:red;'>&nbsp;&nbsp;* Required</span><input type='email' name='emailAddress' class='form-control' value=''>";
                  echo "</div><div class='form-group'><input type='submit' name='RegMe' value='Register User' class='btn btn-primary btn-block' />";
                  echo "</div></form></div></div></div>";
                  echo "<div class='col-md-2'></div>";
                }elseif (isset($_POST["RegMe"])){
                  add_first_user();
                  add_adminUser(1,1,1,0,1,0); //first user is always full admin.
                  $_POST = array();
                  echo "<button type='button' class='btn btn-success btn-block'>DB exists and is clean</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Create Tables</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Create First User</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Register First User</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Create First Admin</button>";
                  echo "<button type='button' class='btn btn-success btn-block'>Install Completed!</button>";
                  echo "<a href='http://localhost/phpprojects/php6/' class='btn btn-primary btn-block' role='button'>Go to site!</a>";
                }else{
                  echo "<form method='post'><input type='submit' name='createTables' value='Create Tables' class='btn btn-primary btn-block'></form>";
                }
              ?>
            </div>
          </div>
        </div>
        <div class="col-3"></div>
      </div>
    </div>
  </body>
</html>
