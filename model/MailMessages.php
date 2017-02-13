<?php
//php model of a pm messenging service to be reitten later.
//setting up the model for messenging service. this will be in CRUD.

function check_new_messages($user){
  //requires user id to get from server the PM assigned to a specific user.
  global $db, $dashboard_message;
  $count_Data=array();
  $query = "SELECT COUNT(MessageRecipent) AS NewMessages FROM mailmessages WHERE MessageRecipent = ? AND MessageReadFlag = 0";
  $stmnt = $db -> prepare($query);
  if(!$stmnt){
   echo var_dump($stmnt);
  }
  $stmnt -> bind_param("i", $user);

  if(!$stmnt -> execute()){
    //$dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
    echo var_dump($stmnt->error);
  }
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $count_Data[] = $data;
  }

  $value = (int)($count_Data[0]['NewMessages']);
  // echo var_dump($value);
  $test = (int)("4");
  return $test;
}
function get_emailList($user){
  global $db;
  //gets a list of PMs from server and returns them based on recip userID
}
function new_messagepost(){
  global $db;
  //sends PM to server to be retrieved, this is for new mailposts and replies.
  $messageSubject = $_POST["messageSubject"];
  $messageSender = $_POST["messageSender"];
  $messageRecipent = $_POST["mailListContct"];
  $messageBody = $_POST["messageBody"];

  $query = "INSERT INTO mailmessages
  (
    MessageSender , MessageRecipent , MessageSubject , MessageBody , MessageTimeSent
  )
  VALUES
  (
    ? , ? , ? , ? , CURRENT_TIMESTAMP
  )";

  $stmnt = $db -> prepare($query);
  echo var_dump($stmnt->error);
  $stmnt -> bind_param("iiss", $messageSender, $messageRecipent, $messageSubject, $messageBody);
  if (!$stmnt->execute()){
    echo var_dump($stmnt->error);
  }
  
}
?>
