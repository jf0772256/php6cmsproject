<?php
  // Since the guy in the book is NOT using PHP to create tables I am neededing to have this do so.
  // Requirements:: Must have a active connection to database server with a user with roles to create or drop tables.
  // This can be set in the model/database.php with your needed setup.
  function create_tables(){
    // This call will intregrate to the install, this will get the version numbers of the sql server, and deturmine what the server is running and then will
    //create the tabels for the correct server.
    global $db;
    $query = "SHOW VARIABLES LIKE '%version%'";
    $stmnt = $db -> prepare($query);
    $stmnt -> execute();
    $result = $stmnt -> get_result();
    while ($results = $result -> fetch_assoc()) {
        $version[] = $results;
    }

    //echo var_dump($version );
    foreach ($version as $versions) {
      if ($versions['Variable_name'] == "version_comment") {
        echo var_dump($versions['Value']);
        $ver_name = $versions['Value'];
      }
    }
    //$ver_name = $versions['Value'];
    echo var_dump(substr($ver_name,0,5));
    if (substr($ver_name,0,5)==="MySQL") {
      return create_tables_MySQL();
    }elseif (substr($ver_name,0,5)==="Maria") {
      return create_tables_MariaDB();
    }else{
      echo "<p class='alert alert-danger'>Invalid Database Connection. Please only use MySQL or MariaDB servers.</p>";
    }
  }

  function create_tables_MariaDB(){
    //create tables for server using Maria DB
    global $db;
    $query = ""; //will be used in each request
    $ifExistsQuery = "IF EXISTS "; //sql for if existing then...
    $ifNotExistsQuery = "IF NOT EXISTS "; // sql for checking is something is not existing... simular to the if exists statement but reversed....
    $message = ""; //I think I will try to get this to pass messages to users when something is completed... not sure though.
    //create user db table
    $query = "CREATE TABLE $ifNotExistsQuery Users
    (
      userID INT NOT NULL Auto_Increment,
      Username VARCHAR(12) NOT NULL,
      Password  TEXT NOT NULL,
      pwsecret  TEXT NOT NULL,
      emailAddress  VARCHAR(30) NOT NULL,
      Fname VARCHAR(25) NOT NULL,
      Lname VARCHAR(25) NOT NULL,
      Joined_Date TIMESTAMP DEFAULT '0000-00-00 00:00:00',
      Last_Visited  TIMESTAMP DEFAULT 0,
      RepLevel  VARCHAR(30) DEFAULT 'Newbie',
      HelpVotes INT DEFAULT 0,
      NegVotes INT DEFAULT 0,
      Posts INT DEFAULT 0,
      WarnCnt INT DEFAULT 0,

      PRIMARY KEY (userID),
      INDEX userID_Indx (userID),
      UNIQUE (Username),
      UNIQUE (emailAddress)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Users table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    // next we will need to create a Posts table.
    $query = "CREATE TABLE $ifNotExistsQuery Posts
    (
      postID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      postTitle VARCHAR(25) NOT NULL,
      postSlug VARCHAR(12) NOT NULL,
      postBody VARCHAR(5000) NOT NULL,
      postHelp INT DEFAULT 0,
      postNeg INT DEFAULT 0,
      postCommentCNT INT DEFAULT 0,
      postAuthor VARCHAR(35),
      postModApprvalReq TINYINT(1) DEFAULT 0,
      postIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (postID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX postID_Indx (postID),
      UNIQUE (postTitle),
      UNIQUE (postSlug)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Posts table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //Next is a Comments table.
    $query = "CREATE TABLE $ifNotExistsQuery PostComments
    (
      commentID INT NOT NULL Auto_Increment,
      postID INT NOT NULL,
      userID INT NOT NULL,
      commentTitle VARCHAR(25) NOT NULL,
      commentBody VARCHAR(500) NOT NULL,
      commentHelp INT DEFAULT 0,
      commentNeg  INT DEFAULT 0,
      commentModApprvalReq TINYINT(1) DEFAULT 0,
      commentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (commentID),
      FOREIGN KEY (postID) REFERENCES Posts(postID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX comment_Indx (commentID)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Comments table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //Now we are going to create the skeleton of the admin tables... This is a critical thing but Not wholy sure how to carry it out.
    //we need two tables, admin table, and moderation que table...

    $query = "CREATE TABLE $ifNotExistsQuery roles
    (
      adminID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      isAdmin TINYINT(1) DEFAULT 0,
      isMod TINYINT(1) DEFAULT 0,
      isFormerStaff TINYINT(1) DEFAULT 0,
      isUser TINYINT(1) DEFAULT 1,
      isBanned TINYINT(1) DEFAULT 0,
      PRIMARY KEY (adminID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX isAdmin_Indx (isAdmin),
      INDEX isMod_Indx (isMod),
      INDEX isFS_Indx (isFormerStaff),
      INDEX isUSR_Indx (isUser),
      INDEX isBND_Indx (isBanned)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Admin table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    // Im not wholly sure how I am going to do this... I was thinking like a ticket gen and have it add and clear from there...
    // que id, que ticket, and something to identify the moderation, then a way to either accept or remove item...
    // I may have to come back to this one...
    //$query ="";

    //Story content tables... should really only be one... but still useful
    $query = "CREATE TABLE $ifNotExistsQuery Content
    (
      contentID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      contentTitle VARCHAR(50) NOT NULL,
      contentSlug VARCHAR(25) NOT NULL,
      contentBody VARCHAR(5000) NOT NULL,
      contentHelp INT DEFAULT 0,
      contentNeg INT DEFAULT 0,
      contentModApprovalReq TINYINT(1) DEFAULT 0,
      contentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (contentID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX contentStory_Indx (contentID),
      UNIQUE (contentSlug),
      UNIQUE (contentTitle)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Content table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    $query = "CREATE TABLE $ifNotExistsQuery ContentComments
    (
      commentID INT NOT NULL Auto_Increment,
      contentID INT NOT NULL,
      userID INT NOT NULL,
      commentTitle VARCHAR(25) NOT NULL,
      commentBody VARCHAR(500) NOT NULL,
      commentHelp INT DEFAULT 0,
      commentNeg  INT DEFAULT 0,
      commentModApprvalReq TINYINT(1) DEFAULT 0,
      commentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (commentID),
      FOREIGN KEY (contentID) REFERENCES Content(contentID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX comment_Indx (commentID)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Content Comments table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //PM Table -- Yay!
    $query = "CREATE TABLE $ifNotExistsQuery MailMessages
    (
      MessageId INT UNSIGNED NOT NULL Auto_Increment,
      MessageSender INT NOT NULL,
      MessageRecipent INT NOT NULL,
      MessageTimeSent TIMESTAMP DEFAULT 0,
      MessageReadFlag TINYINT(1) DEFAULT 0,
      MessageSpamFlag TINYINT(1) DEFAULT 0,
      MessageDeleteFlag TINYINT(1) DEFAULT 0,
      MessageSubject VARCHAR(100) NOT NULL,
      MessageBody VARCHAR(1000) NOT NULL,
      PRIMARY KEY (MessageId),
      FOREIGN KEY (MessageSender) REFERENCES Users(userID),
      FOREIGN KEY (MessageRecipent) REFERENCES Users(userID),
      INDEX MsgRead_Indx (MessageReadFlag),
      INDEX msgSpam_Indx (MessageSpamFlag),
      INDEX MsgSubject_Indx (MessageSubject)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the PM table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }
    //Query to create a report-issue-table
    //used for creating site issues, as well as reporting users, and posts.
    $query ="CREATE TABLE $ifNotExistsQuery IssuesReports
    (
      IssueID INT UNSIGNED NOT NULL Auto_Increment,
      IssueUserID INT NOT NULL,
      IssueTimeStamp TIMESTAMP DEFAULT 0,
      IssueTitle VARCHAR(30) NOT NULL,
      IssueDescription VARCHAR(500) NOT NULL,
      IssueStatus TINYINT(1) DEFAULT 1,
      IssueAssignedID INT,
      SiteIssue TINYINT(1) DEFAULT 0,
      PostIssue TINYINT(1) DEFAULT 0,
      CommentIssue TINYINT(1) DEFAULT 0,
      UserIssue TINYINT(1) DEFAULT 0,
      IssuePostID INT DEFAULT 0,
      IssueWUserID INT DEFAULT 0,
      IssueWCommentID INT DEFAULT 0,
      PRIMARY KEY (IssueID),
      FOREIGN KEY (IssueUserID) REFERENCES Users(userID),
      FOREIGN KEY (IssueAssignedID) REFERENCES Users(userID),
      INDEX Assgignedto_Indx (IssueAssignedID),
      INDEX IssueStatusValue_Indx (IssueStatus),
      INDEX isSiteIssue_Indx (SiteIssue),
      INDEX isPostIssue_Indx (PostIssue),
      INDEX isCommentIssue_Indx (CommentIssue),
      INDEX isUserIssue_Indx (UserIssue)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Issue Reporting table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //now for the messaging for the issue... if needed, but the bable will be used to store messages off table re issues.
    $query ="CREATE TABLE $ifNotExistsQuery IssueMessages
    (
      IssueMessageID INT UNSIGNED NOT NULL Auto_Increment,
      IssueID INT UNSIGNED NOT NULL,
      ReporterID INT NOT NULL,
      AssigneeID INT NOT NULL,
      R_New TINYINT(1) DEFAULT 0,
      A_New TINYINT(1) DEFAULT 0,
      MessageBody VARCHAR(5000),
      PRIMARY KEY (IssueMessageID),
      FOREIGN KEY (IssueID) REFERENCES IssuesReports(IssueID),
      FOREIGN KEY (ReporterID) REFERENCES IssuesReports(IssueUserID),
      FOREIGN KEY (AssigneeID) REFERENCES IssuesReports(IssueAssignedID),
      INDEX ReporterNewMessage_Indx (R_New),
      INDEX AssigneeNewMessage_Indx (A_New)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_num = $db -> error;
      $error_message = "There was a problem with the Issue Messaging table creation and it has stopped. ErrorNumber: " . $error_num;
      include("error/db_error.php");
      exit();
    }

    //if completed then it will return true...
    return true;
  }

  function create_tables_MySQL(){
    //For mysql servers
    global $db;
    $query = ""; //will be used in each request
    $ifExistsQuery = "IF EXISTS "; //sql for if existing then...
    $ifNotExistsQuery = "IF NOT EXISTS "; // sql for checking is something is not existing... simular to the if exists statement but reversed....
    $message = ""; //I think I will try to get this to pass messages to users when something is completed... not sure though.
    //create user db table
    $query = "CREATE TABLE $ifNotExistsQuery Users
    (
      userID INT NOT NULL Auto_Increment,
      Username VARCHAR(12) NOT NULL,
      Password  TEXT NOT NULL,
      pwsecret  TEXT NOT NULL,
      emailAddress  VARCHAR(30) NOT NULL,
      Fname VARCHAR(25) NOT NULL,
      Lname VARCHAR(25) NOT NULL,
      Joined_Date TIMESTAMP,
      Last_Visited  TIMESTAMP,
      RepLevel  VARCHAR(30) DEFAULT 'Newbie',
      HelpVotes INT DEFAULT 0,
      NegVotes INT DEFAULT 0,
      Posts INT DEFAULT 0,
      WarnCnt INT DEFAULT 0,

      PRIMARY KEY (userID),
      INDEX userID_Indx (userID),
      UNIQUE (Username),
      UNIQUE (emailAddress)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Users table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    // next we will need to create a Posts table.
    $query = "CREATE TABLE $ifNotExistsQuery Posts
    (
      postID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      postTitle VARCHAR(25) NOT NULL,
      postSlug VARCHAR(12) NOT NULL,
      postBody VARCHAR(5000) NOT NULL,
      postHelp INT DEFAULT 0,
      postNeg INT DEFAULT 0,
      postCommentCNT INT DEFAULT 0,
      postAuthor VARCHAR(35),
      postModApprvalReq TINYINT(1) DEFAULT 0,
      postIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (postID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX postID_Indx (postID),
      UNIQUE (postTitle),
      UNIQUE (postSlug)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Posts table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //Next is a Comments table.
    $query = "CREATE TABLE $ifNotExistsQuery PostComments
    (
      commentID INT NOT NULL Auto_Increment,
      postID INT NOT NULL,
      userID INT NOT NULL,
      commentTitle VARCHAR(25) NOT NULL,
      commentBody VARCHAR(500) NOT NULL,
      commentHelp INT DEFAULT 0,
      commentNeg  INT DEFAULT 0,
      commentModApprvalReq TINYINT(1) DEFAULT 0,
      commentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (commentID),
      FOREIGN KEY (postID) REFERENCES Posts(postID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX comment_Indx (commentID)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Comments table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //Now we are going to create the skeleton of the admin tables... This is a critical thing but Not wholy sure how to carry it out.
    //we need two tables, admin table, and moderation que table...

    $query = "CREATE TABLE $ifNotExistsQuery roles
    (
      adminID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      isAdmin TINYINT(1) DEFAULT 0,
      isMod TINYINT(1) DEFAULT 0,
      isFormerStaff TINYINT(1) DEFAULT 0,
      isUser TINYINT(1) DEFAULT 1,
      isBanned TINYINT(1) DEFAULT 0,
      PRIMARY KEY (adminID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX isAdmin_Indx (isAdmin),
      INDEX isMod_Indx (isMod),
      INDEX isFS_Indx (isFormerStaff),
      INDEX isUSR_Indx (isUser),
      INDEX isBND_Indx (isBanned)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Admin table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    // Im not wholly sure how I am going to do this... I was thinking like a ticket gen and have it add and clear from there...
    // que id, que ticket, and something to identify the moderation, then a way to either accept or remove item...
    // I may have to come back to this one...
    //$query ="";

    //Story content tables... should really only be one... but still useful
    $query = "CREATE TABLE $ifNotExistsQuery Content
    (
      contentID INT NOT NULL Auto_Increment,
      userID INT NOT NULL,
      contentTitle VARCHAR(50) NOT NULL,
      contentSlug VARCHAR(25) NOT NULL,
      contentBody VARCHAR(5000) NOT NULL,
      contentHelp INT DEFAULT 0,
      contentNeg INT DEFAULT 0,
      contentModApprovalReq TINYINT(1) DEFAULT 0,
      contentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (contentID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX contentStory_Indx (contentID),
      UNIQUE (contentSlug),
      UNIQUE (contentTitle)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Content table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    $query = "CREATE TABLE $ifNotExistsQuery ContentComments
    (
      commentID INT NOT NULL Auto_Increment,
      contentID INT NOT NULL,
      userID INT NOT NULL,
      commentTitle VARCHAR(25) NOT NULL,
      commentBody VARCHAR(500) NOT NULL,
      commentHelp INT DEFAULT 0,
      commentNeg  INT DEFAULT 0,
      commentModApprvalReq TINYINT(1) DEFAULT 0,
      commentIsApproved TINYINT(1) DEFAULT 0,
      PRIMARY KEY (commentID),
      FOREIGN KEY (contentID) REFERENCES Content(contentID),
      FOREIGN KEY (userID) REFERENCES Users(userID),
      INDEX comment_Indx (commentID)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Content Comments table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //PM Table -- Yay!
    $query = "CREATE TABLE $ifNotExistsQuery MailMessages
    (
      MessageId INT UNSIGNED NOT NULL Auto_Increment,
      MessageSender INT NOT NULL,
      MessageRecipent INT NOT NULL,
      MessageTimeSent TIMESTAMP,
      MessageReadFlag TINYINT(1) DEFAULT 0,
      MessageSpamFlag TINYINT(1) DEFAULT 0,
      MessageDeleteFlag TINYINT(1) DEFAULT 0,
      MessageSubject VARCHAR(100) NOT NULL,
      MessageBody VARCHAR(1000) NOT NULL,
      PRIMARY KEY (MessageId),
      FOREIGN KEY (MessageSender) REFERENCES Users(userID),
      FOREIGN KEY (MessageRecipent) REFERENCES Users(userID),
      INDEX MsgRead_Indx (MessageReadFlag),
      INDEX msgSpam_Indx (MessageSpamFlag),
      INDEX MsgSubject_Indx (MessageSubject)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the PM table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }
    //Query to create a report-issue-table
    //used for creating site issues, as well as reporting users, and posts.
    $query ="CREATE TABLE $ifNotExistsQuery IssuesReports
    (
      IssueID INT UNSIGNED NOT NULL Auto_Increment,
      IssueUserID INT NOT NULL,
      IssueTimeStamp TIMESTAMP,
      IssueTitle VARCHAR(30) NOT NULL,
      IssueDescription VARCHAR(500) NOT NULL,
      IssueStatus TINYINT(1) DEFAULT 1,
      IssueAssignedID INT,
      SiteIssue TINYINT(1) DEFAULT 0,
      PostIssue TINYINT(1) DEFAULT 0,
      CommentIssue TINYINT(1) DEFAULT 0,
      UserIssue TINYINT(1) DEFAULT 0,
      IssuePostID INT DEFAULT 0,
      IssueWUserID INT DEFAULT 0,
      IssueWCommentID INT DEFAULT 0,
      PRIMARY KEY (IssueID),
      FOREIGN KEY (IssueUserID) REFERENCES Users(userID),
      FOREIGN KEY (IssueAssignedID) REFERENCES Users(userID),
      INDEX Assgignedto_Indx (IssueAssignedID),
      INDEX IssueStatusValue_Indx (IssueStatus),
      INDEX isSiteIssue_Indx (SiteIssue),
      INDEX isPostIssue_Indx (PostIssue),
      INDEX isCommentIssue_Indx (CommentIssue),
      INDEX isUserIssue_Indx (UserIssue)
    )";

    $result = $db -> query($query);
    if (!$result){
      $error_message = "There was a problem with the Issue Reporting table creation and it has stopped.";
      include("error/db_error.php");
      exit();
    }

    //now for the messaging for the issue... if needed, but the bable will be used to store messages off table re issues.
    $query ="CREATE TABLE $ifNotExistsQuery IssueMessages
    (
      IssueMessageID INT UNSIGNED NOT NULL Auto_Increment,
<<<<<<< HEAD
      IssueID INT NOT NULL,
=======
      IssueID INT UNSIGNED NOT NULL,
>>>>>>> refs/remotes/origin/master
      ReporterID INT NOT NULL,
      AssigneeID INT NOT NULL,
      R_New TINYINT(1) DEFAULT 0,
      A_New TINYINT(1) DEFAULT 0,
      MessageBody VARCHAR(5000),
      PRIMARY KEY (IssueMessageID),
<<<<<<< HEAD
      FOREIGN KEY (IssueID) REFERENCES IssuesReorts(IssueID),
      FOREIGN KEY (ReporterID) REFERENCES IssuesReorts(IssueUserID),
      FOREIGN KEY (AssigneeID) REFERENCES IssuesReorts(IssueAssignedID),
=======
      FOREIGN KEY (IssueID) REFERENCES IssuesReports(IssueID),
      FOREIGN KEY (ReporterID) REFERENCES IssuesReports(IssueUserID),
      FOREIGN KEY (AssigneeID) REFERENCES IssuesReports(IssueAssignedID),
>>>>>>> refs/remotes/origin/master
      INDEX ReporterNewMessage_Indx (R_New),
      INDEX AssigneeNewMessage_Indx (A_New)
    )";

    $result = $db -> query($query);
    if (!$result){
<<<<<<< HEAD
      $error_message = "There was a problem with the Issue Messaging table creation and it has stopped.";
=======
      $error_num = $db -> error;
      $error_message = "There was a problem with the Issue Messaging table creation and it has stopped. ErrorNumber: " . $error_num;
>>>>>>> refs/remotes/origin/master
      include("error/db_error.php");
      exit();
    }

    //if completed then it will return true...
    return true;
  }//end of create_tables

  //check if tables already exists
  function check_preexisting_tables(){
    global $db, $database;
    //query the database and if the table is there then retun true.
    //if the tables are not created then return false.
    $query = "SELECT COUNT(*) AS Count FROM information_schema.TABLES WHERE TABLE_Schema = '$database' AND TABLE_NAME=?";
    $stmnt = $db -> prepare($query);
    $tableNameVar = "users";
    $stmnt -> bind_param("s",$tableNameVar);
    if (!$stmnt -> execute()){
      $error_message = "Something went wrong and the process has stopped, contact your support contact for further assistance.";
      include("error/db_error.php");
      exit();
    }
    $tblresult = $stmnt -> get_result();
    $tblCount = $tblresult -> fetch_assoc(); //non associated array , easier to get to result for this purpose.
    //if statements to return either true or false...
    if ($tblCount["Count"] === 1){
      //table exists and the database is dirty...
      return true;
    }elseif ($tblCount["Count"] === 0){
      //table does not exist and the database if clean...
      return false;
    }else{
      //something has gone wrong... horribly wrong...
      $error_message = "An unknown error has occured and we have stopped the script from running further, contact us through your POC.";
      include("error/db_error.php");
      exit();
    }
  }

  //drop preexisting tables... You need clean db to install...
  function drop_dirty_tables(){
    // we are going to test this command to see if the process will work...
    global $db;
    $dbIsDirty = check_preexisting_tables();
    $query = "DROP TABLE ContentComments, Content, PostComments, Posts, roles, MailMessages, IssueMessages, IssuesReorts, Users";
    if ($dbIsDirty){
      $result = $db -> query($query); //no need to prep the query as no injection is expected in hardcoded values
      if (!$result){
        $error_message = "Failed to clean the database, please conduct cleansing manually and try the install again.";
        include("error/db_error.php");
        exit();
      }else{
        return "cleaned"; //drop tables concluded without error
      }
    }else{
      return "No Cleaning Required"; //database was clean and no need to drop tables
    }
  }
?>
