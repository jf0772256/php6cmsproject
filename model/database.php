<?php
//Database Model//
//connection string variables
$host = "localhost";
$userName = "jessefender";
$password = "test1234";
$database = "jessefender";
//The actual connection data.
@ $db = new mysqli($host,$userName,$password,$database);
//get error if any//
$error_message = $db -> connect_error;
//if message exists then display it//
if ($error_message != null) {
  include("error/conn_error.php");
  exit();
}

//function to display other errors//
function disp_DB_Errors ($error_message){
  global $app_path;
  include("error/db_error.php");
  exit();
}

//just for fun::Creates tables here rather than making a seperate "install" script (which may happen if the project goes big.)
//include_once("database/tables.php");
?>
