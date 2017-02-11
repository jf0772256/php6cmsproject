<!-- Head snippet default -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title><?php echo $pageTitle; ?></title>
  </head>
    <body>
      <div class="container">
        <!-- uses bootstrap to prettyfy page -->
        <!-- This is where we will do 99% of the code... like creating a user registration and login models -->
        <!-- This will however focus mostly on the registration of users rather than authentication at this time since that is a heavier topic -->
        <?php
        include('partial/_nav.php');
        ?>
