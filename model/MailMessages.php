<?php
//php model of a pm messenging service to be reitten later.
//setting up the model for messenging service. this will be in CRUD.

function check_new_messages($user){
  //requires user id to get from server the PM assigned to a specific user.
  global $db, $dashboard_message;
  $count_Data=array();
  $query = "SELECT COUNT(MessageRecipent) AS NewMessages FROM mailmessages WHERE MessageRecipent = ? AND MessageReadFlag = 0 AND MessageSpamFlag = 0 AND MessageDeleteFlag = 0";
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
  $message_Data=array();
  $query = "SELECT messageid, messageSubject, Username FROM mailmessages JOIN users ON mailmessages.MessageSender = users.userID WHERE mailmessages.messageRecipent = ? AND MessageSpamFlag = 0 AND MessageDeleteFlag = 0 ORDER BY mailmessages.MessageTimeSent DESC";
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
  if (!isset($_SESSION["currentmessage"])){
    $messageRecipent = $_POST["mailListContct"];
  }else{
    $messageRecipent = $_SESSION["currentmessage"]["messagesender"];
    unset($_SESSION["currentmessage"]);
  }
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
  $query = "SELECT * FROM mailmessages WHERE messageid = ? AND MessageSpamFlag = 0 AND MessageDeleteFlag = 0";
  $query2 = "SELECT Username FROM users WHERE userID = ?";
  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param('i',$mID);
  if (!$stmnt ->execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
  $results = $stmnt -> get_result();
  $data = $results->fetch_assoc(); // there can only be one reply.
  //set to session array
  $_SESSION['currentmessage']["messagenumber"] = $data['MessageId'];
  $_SESSION['currentmessage']["messagesender"] = $data['MessageSender'];
  $_SESSION['currentmessage']["messagereciver"] = $data['MessageRecipent'];
  $_SESSION['currentmessage']["messagetime"] = $data['MessageTimeSent'];
  $_SESSION['currentmessage']["messageisread"] = $data['MessageReadFlag'];
  $_SESSION['currentmessage']["messageisspam"] = $data['MessageSpamFlag'];
  $_SESSION['currentmessage']["messagedeleted"] = $data['MessageDeleteFlag'];
  $_SESSION['currentmessage']["messagesubject"] = $data['MessageSubject'];
  $_SESSION['currentmessage']["messagebody"] = $data['MessageBody'];
  //get the user name of the sender.
  $senderID = $_SESSION['currentmessage']['messagesender'];
  $stmnt = $db -> prepare($query2);
  $stmnt -> bind_param('i',$senderID);
  if (!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
  $result = $stmnt ->get_result();
  $data = $result -> fetch_assoc();
  $_SESSION['currentmessage']['messagesenderUN'] = $data['Username'];
}

function togglereadflag($mID){
  global $db, $dashboard_message;
  //flip bit if is read is 0 make it 1, else make it 0
  if ($_SESSION['currentmessage']['messageisread'] === 0){
    $query = "UPDATE mailmessages SET MessageReadFlag = 1 WHERE MessageId = ?";
  }else{
    $query = "UPDATE mailmessages SET MessageReadFlag = 0 WHERE MessageId = ?";
  }

  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param("i",$mID);
  if (!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
  getReadStatus($mID);
}

function togglespamflag($mID){
  global $db, $dashboard_message;
  //flip bit if is read is 0 make it 1, else make it 0
  if ($_SESSION['currentmessage']['messageisspam'] === 0){
    $query = "UPDATE mailmessages SET MessageSpamFlag = 1, MessageReadFlag = 1 WHERE MessageId = ?";
  }else{
    $query = "UPDATE mailmessages SET MessageSpamFlag = 0, MessageReadFlag = 0 WHERE MessageId = ?";
  }

  $stmnt = $db -> prepare($query);
  if (!$stmnt) {
   echo var_dump($stmnt ->error);
  }
  $stmnt -> bind_param("i",$mID);
  //if (!$stmnt) {
  //  echo var_dump($stmnt ->error);
  //}
  if (!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
}

function toggledelete($mID){
  //here will be simular to the other flag flippers :)
  //delete is special as it requires Admin or staff privlages to undelete a message.

  global $db, $dashboard_message;
  //flip bit if is read is 0 make it 1, else make it 0
  if ($_SESSION['currentmessage']['messageisspam'] === 0){
    $query = "UPDATE mailmessages SET MessageDeleteFlag = 1, MessageReadFlag = 1 WHERE MessageId = ?";
  }else{
    $dashboard_message = "<p class='alert alert-danger'>The db query could not be created.</p>";
  }
  $stmnt = $db -> prepare($query);
  if (!$stmnt) {
   echo var_dump($stmnt ->error);
  }
  $stmnt -> bind_param("i",$mID);
  //if (!$stmnt) {
  //  echo var_dump($stmnt ->error);
  //}
  if (!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }
}

function getReadStatus($mID){
  global $db, $dashboard_message;
  $query = "SELECT MessageReadFlag FROM mailmessages WHERE MessageId = ?";
  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param("i",$mID);
  $stmnt -> execute();
  $result = $stmnt ->get_result();
  $data = $result -> fetch_assoc();
  $_SESSION["currentmessage"]["messageisread"] = $data["MessageReadFlag"];
  error_reporting(0);
  $page = $_SERVER['index.php'];
  echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
  error_reporting(E_ALL);
}

function purgeSpam(){
  global $db, $dashboard_message;
  //this function will purge all messages marked as spam from the database... and is not to be done lightly ...
  //auto runs, may use JS to verify the post request before this script is run.
  $query = "DELETE FROM mailmessages WHERE MessageSpamFlag = 1";
  $stmnt = $db ->prepare($query);
  if (!$stmnt -> execute()) {
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }else{
    $dashboard_message = "<p class='alert alert-success'>The spam flagged messages have been purged sucessfully.</p>";
  }
}

function purgeDeleted(){
  global $db, $dashboard_message;
  //this function will purge all messages marked for deletion from the database... and is not to be done lightly ...
  //auto runs, may use JS to verify the post request before this script is run.
  $query = "DELETE FROM mailmessages WHERE MessageDeleteFlag = 1";
  $stmnt = $db ->prepare($query);
  if (!$stmnt -> execute()) {
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }else{
    $dashboard_message = "<p class='alert alert-success'>The deleted messages have been purged sucessfully.</p>";
  }
}

function Undelete_Message($mid){
  global $db, $dashboard_message;
  //admin access to undelete message...
  $query = "UPDATE mailmessages SET MessageDeleteFlag = 0 WHERE MessageId = ?";
  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param("i",$mid);
  if (!$stmnt -> execute()) {
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
  }else{
    $dashboard_message = "<p class='alert alert-success'>The message has been undeleted.</p>";
  }
}

function get_deleted_messages_count(){
  //gets a count to return and to be used as a badge value
  global $db, $dashboard_message;
  $count_Data=array();
  $query = "SELECT COUNT(MessageRecipent) AS DeletedMessages FROM mailmessages WHERE MessageDeleteFlag = 1";
  $stmnt = $db -> prepare($query);

  if(!$stmnt){
   echo var_dump($stmnt);
  }

  if(!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
    //echo var_dump($stmnt->error);
  }
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $count_Data[] = $data;
  }

  $retVal = (int)($count_Data[0]['DeletedMessages']);
  return $retVal;
}

function get_spam_messages_count(){
  //gets a count to return and to be used as a badge value
  global $db, $dashboard_message;
  $count_Data=array();
  $query = "SELECT COUNT(MessageRecipent) AS SpamMessages FROM mailmessages WHERE MessageSpamFlag = 1";
  $stmnt = $db -> prepare($query);
  if(!$stmnt){
   echo var_dump($stmnt);
  }

  if(!$stmnt -> execute()){
    $dashboard_message = "<p class='alert alert-danger'>The db query faulted.</p>";
    //echo var_dump($stmnt->error);
  }

  $result = $stmnt -> get_result();

  while ($data = $result->fetch_assoc()){
    $count_Data[] = $data;
  }

  $retVal = (int)($count_Data[0]['SpamMessages']);
  return $retVal;
}

function get_delete_flagged_messages($selectorName = ''){
  //gets a list of all messages that are in the database. and echos them to a form select
  global $db,$dashboard_message_users;
  $user_Data=array();
  $query ="SELECT mailmessages.MessageId, mailmessages.messageSubject FROM mailmessages WHERE mailmessages.MessageDeleteFlag = 1";
  $stmnt = $db -> prepare($query);
  $stmnt -> execute();
  $results = $stmnt -> get_result();
  while ($data = $results->fetch_assoc()){
    $user_Data[] = $data;
  }

  if (sizeof($user_Data) === 0){
    $dashboard_message_users = "<p class='alert alert-success'>There are no deleted messages</p>";
  }else{
    echo '<select name="'.$selectorName.'" class="form-control">';
    foreach ($user_Data as $key => $value) {
        $mNum = $user_Data[$key]['MessageId'];
        $mSub = $user_Data[$key]['messageSubject'];
        echo "<option value='$mNum'>$mSub</option>";
    }
    echo "</select>";
  }
}
?>
