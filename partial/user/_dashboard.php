<?php global $dashboard_message; ?>
<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2><?php echo $_SESSION["fullname"] ?></h2>
    </div>
    <div class="panel panel-body">
      <div class="row">
        <div class="col-md-12">
          <?php
          //if messages then display them... All messages here are positive...
          echo "<div>$dashboard_message</div>";
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h4>Username: </h4>
        </div>
        <div class="col-md-6 offset-2">
          <h4><?php echo $_SESSION["uNameSTR"]; ?></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h4>Date Joined: </h4>
        </div>
        <div class="col-md-4 offset-2">
          <h4><?php echo $_SESSION["DateJoined"]; ?></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h4>Your Last Login: </h4>
        </div>
        <div class="col-md-8">
          <h4><?php echo $_SESSION["lastTimeLoggedIn"]; ?></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h4>Account Email Address: </h4>
        </div>
        <div class="col-md-4 offset-2">
          <h4><?php echo $_SESSION["Email"]; ?></h4>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-4">
          <h4>Your Reputation Level is: </h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <h3><?php echo $_SESSION["Reputation"]; ?></h3>
        </div>
      </div>
      <h4>Your total post count is: <?php echo $_SESSION["PostCount"]; ?></h4>
      <h4><?php echo $_SESSION["HelpfullVotes"]; ?> number of people found your posts helpful.</h4>
      <h4><?php echo $_SESSION["DownVoted"]; ?> number of people found your posts unhelpful.</h4>
    </div>
  </div>
</div>
