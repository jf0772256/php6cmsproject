<?php //includes
  //requires database.php found in models
  include_once("model/database.php");
  include_once("model/Users.php");
  include_once("model/Admin.php");
  include_once("model/Posts.php");
  if (empty($pageTitle)) {$pageTitle = "Home!";}
  include("partial/_head.php");
  //session_start();
?>
    <!-- main content starts below here -->

      <div class="container">
        <div class="container-fluid">
          <!-- Log out commands -->
          <?php
            $banned_bool;
            if (isset($_POST["logoffuser"])){
              set_Logout();
              $_POST = array();
              //tests
              $page = $_SERVER['PHP_SELF'];
              echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
              //include_once("index.php");
            }elseif (isset($_SESSION) && sizeof($_SESSION) > 0) {
              $banned_bool = get_isbanned_status($_SESSION["personID"]);
              $delUser_bool = get_isUser_status($_SESSION["personID"]);
              if ($banned_bool == true){
                echo "<p class = 'alert alert-danger'>Your account has been banned, contact site admin for details.</p>";
                include("partial/_pageend.php");
                exit();
              }elseif ($delUser_bool == false){
                echo "<p class = 'alert alert-danger'>Your account has been deleted, contact site admin for details.</p>";
                include("partial/_pageend.php");
                exit();
              }else{
                include("partial/user/_useradmin.php");
                include("partial/_pageend.php");
                exit();
              }
            }
          ?>

          <!-- registration area here -->
          <article class="" id="registration">
            <?php
              if (isset($_POST["Register"])) {
                // clears POST array before displaying the registration form. making sure that the data is kept clean
                $_POST = array();
                include_once("partial/user/_register.php");
              }
              if (isset($_POST["RegMe"])){
                add_user();
                $_POST = array();
                //var_dump($_POST);
              }elseif (isset($_POST["cancel"])){
                // clears POST array before dropping the register user and just displaying the index page
                $_POST = array();
                include_once("index.php");
              }
            ?>
          </article>

          <!-- main content falls below this for postings -->
          <div class="row">
            <div class="col-md-4">
              <section>
                <article class="">
                  <h3>This is a main content story</h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </article>
              </section>
            </div>
            <div class="col-md-4">
              <section>
                <article class="">
                  <h3>This is a main content story</h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </article>
              </section>
            </div>
            <div class="col-md-4">
              <section>
                <article class="">
                  <h3>This is a main content story</h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </article>
              </section>
            </div>
          </div>
        </div>
      </div>

      <!-- main content ends here -->

      <?php include("partial/_pageend.php"); ?>
