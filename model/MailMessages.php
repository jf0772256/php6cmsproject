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
  }
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $count_Data[] = $data;
  }

  $value = (int)($count_Data[0]['NewMessages']);
  return $value;
}
?>
