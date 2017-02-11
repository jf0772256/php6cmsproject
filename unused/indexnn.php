<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container">
      <?php
        //require_once('databse.php');
      ?>

      <p>This is a page built to test the ability to create and access database tables and their data</p> <br>

      

      <table>
        <tr>
          <th>UserID</th>
          <th>UserName</th>
          <th>Password</th>
          <th>Email</th>
          <th>Name</th>
          <th>Date Joined</th>
          <th>Last Visited</th>
          <th>Reputation Level</th>
          <th>Helpful Votes</th>
          <th>Negitive Votes</th>
          <th># of Posts</th>
        </tr>
        <tbody>
        <?php
        while($statement->fetch()){ ?>
          <tr>
            <td><?php echo $userID ?>|</td>
            <td><?php echo $Username ?>|</td>
            <td><?php echo $Password ?>|</td>
            <td><?php echo $emailAddress ?>|</td>
            <td><?php echo $Fname . ' ' . $Lname ?>|</td>
            <td><?php echo $Joined_Date ?>|</td>
            <td><?php echo $Last_Visited ?>|</td>
            <td><?php echo $RepLevel ?>|</td>
            <td><?php echo $HelpVotes ?>|</td>
            <td><?php echo $NegVotes ?>|</td>
            <td><?php echo $Posts ?>|</td>
          </tr>
          <?php ;} ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
