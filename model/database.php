<?php

//just for fun::Creates tables here rather than making a seperate "install" script (which may happen if the project goes big.)
//include_once("database/tables.php");

class database
{
  private $host = "";
  private $userName = "";
  private $password = "";
  private $database = "";
  private $db = null;
  public function __construct(){
    //
  }
  protected function connect($host, $dbname, $user, $password){
    $this->host = $host;
    $this->database = $database;
    $this->userName = $user;
    $this->password = $password;
    $this->db = new mysqli($this->host,$this->userName,$this->password,$this->database);
    $error_message = $db->connect_error;
    //if message exists then display it//
    if ($error_message != null) {
      include("error/conn_error.php");
      exit();
    }
  }
  
  //function to display other errors//
  function disp_DB_Errors ($error_message){
    global $app_path;
    include("error/db_error.php");
    exit();
  }
}



?>
