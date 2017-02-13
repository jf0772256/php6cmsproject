<?php
/// posts model
///to be CRUD :)
function set_new_post(){
  global $db;
  //setting the creation of a new post.
  $pTitle = filter_input(INPUT_POST,'postTitle',FILTER_DEFAULT);
  $pSlug = filter_input(INPUT_POST,'postSlug',FILTER_DEFAULT);
  $pBody = filter_input(INPUT_POST,'postBody',FILTER_DEFAULT);
  $PosterID = $_SESSION["personID"];

  echo var_dump($_SESSION); //should print out the whole session array

  $query = "INSERT INTO posts (userID , postTitle , postSlug , postBody) VALUES (? , ? , ? , ? )";
  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param("isss",$PosterID,$pTitle,$pSlug,$pBody);
  $result = $stmnt -> execute();
  if(!$result){
    echo "<p class='alert alert-danger'>The Post didnt get created successfully.</p>";
    var_dump($stmnt->error);
  }else{
    echo "<p class='alert alert-success'>The Post was successfully created.</p>";
    set_new_post($_SESSION["personID"]);
  }
}

function get_post_count($user){
  global $db;
  $query = "SELECT Posts FROM Users WHERE userID = ?"; //query string
  $stmnt = $db -> prepare($query);
  $stmnt -> bind_param("i",$user);
  if (!$stmnt->execute()){
    echo var_dump($stmnt->error);
  }else{
    $result = $stmnt -> get_result();
    $data = $result -> fetch_assoc();
    return $data["Posts"];
  }
}

function set_post_count($user){
  global $db;
  $pcount = get_post_count($user);
  $query = "UPDATE Posts SET Posts = $pcount WHERE userID = ?";
  $stmnt = $db -> prepare($query);
  if(!$stmnt -> execute()){
    echo var_dump($stmnt->error);
  }else{
    $_SESSION["PostCount"] = $pcount;
  }
}

function get_all_posts(){
  //get all values
  global $db;
  $all_post_Data=array();
  $query = "SELECT * FROM posts";
  $stmnt = $db -> prepare($query);
  $stmnt -> execute();
  $result = $stmnt -> get_result();
  while ($data = $result->fetch_assoc()){
    $all_post_Data[] = $data;
  }

  //now to iterate through the array and display on the posts page.
  //for ($i=0; $i < sizeof($all_post_Data); $i++) {
    //first iteration
    foreach ($all_post_Data as $key => $posts) {
      $Title = $all_post_Data[$key]["postTitle"];
      $PostBody = $all_post_Data[$key]["postBody"];
      $Writer = "";
      echo '<div>';
      echo '<section>';
      echo '<article>';
      echo "<h3>$Title</h3>";
      echo "<h4></h4>";
      echo "<p>$PostBody</p>";
      echo '<form method="post">';
      echo "<input type='submit' name='dummybtn0' class='btn btn-primary' value='Read More'>";
      echo '</form>';
      echo '</article>';
      echo '</section>';
      echo '</div>';
    }
  //}
}
?>
