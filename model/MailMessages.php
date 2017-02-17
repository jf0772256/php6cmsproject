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
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
    //echo var_dump($stmnt->error);
  }
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $count_Data[] = $data;
  }

  $test1 = (int)($count_Data[0]['NewMessages']);
  return $test1;
}

function get_emailList($user){
  global $db, $dashboard_message;
  //gets a list of PMs from server and returns them based on recip userID
  $query = "SELECT messageid, messageSubject, Username FROM mailmessages JOIN users ON mailmessages.MessageSender = users.userID WHERE mailmessages.messageRecipent = ?";
  $stmnt = $db -> prepare($query);
  $stmnt->bind_param("i",$user);
  if (!$stmnt->execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $message_Data[] = $data;
  }
  return $message_Data;
}

function new_messagepost(){
  global $db, $dashboard_message;
  //sends PM to server to be retrieved, this is for new mailposts and replies.
  $messageSubject = $_POST["messageSubject"];
  $messageSender = $_SESSION['personID'];
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
  $stmnt -> bind_param("iiss", $messageSender, $messageRecipent, $messageSubject, $messageBody);
  if (!$stmnt->execute()){
    echo var_dump($stmnt->error);
  }else{
    $dashboard_message = "<p class='alert alert-success'>Message sent!</p>";
  }
}

function get_email_from_list($mID){
  global $db;
  //this will receive a message ID, from the button in mail list, and then will pull down the message from
  // the servere and store it in the session array being built for it. This will then display in the read message window.
  //creating message array, which will hold all the messages with in it.
  $_SESSION["currentmessage"] = array();
  // next we would need to build the query that would gather all the data about the message and store it in teh Session var.
}
?>
