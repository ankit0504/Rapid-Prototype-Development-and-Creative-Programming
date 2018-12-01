<!DOCTYPE html>
<html lang="en">
  <head>
    <title>New User</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:thin,light,regular,bold,black&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel = "stylesheet" href = "appearance.css">
  </head>
  <body>
    <?php
      session_start();
      require 'database.php';
      $new_user = $_POST['new_user'];
      $pwd = $_POST['new_password'];
      $pwd_recovery = $_POST['password_recovery'];
      $amount = $_POST['amount'];
      $pwd_hash;
      $recovery_hash;
      $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
      $stmt->bind_param('s', $new_user);
      $stmt->execute();
      $stmt->bind_result($cnt);
      $stmt->fetch();
      $stmt->close();
      if(strlen($new_user) > 16 || strlen($new_user) < 3) {
        echo "Username length is invalid. Please choose a username between 3 and 16 characters.\n";
        echo "LENGTH: ".strlen($new_user)."\n";
        echo "USERNAME: ".$new_user;
      } else if($cnt > 0) {
        echo "Username taken. Please choose a new one.";
      } else if( !preg_match('/^[\w_\.\-]+$/', $new_user) ){
        echo "Invalid username.";
      } else if (strlen($pwd) > 16 || strlen($pwd) < 3){
        echo "Password length is invalid. Please choose a password between 3 and 16 characters.";
      } else {
        $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
        $recovery_hash = password_hash($pwd_recovery, PASSWORD_DEFAULT);
        $to_insert = $mysqli->prepare("INSERT into users (username, hashed_password, password_recover) values (?, ?, ?)");
        if(!$to_insert){
          printf("Query Prep Failed: %s\n", $mysqli->error);
          exit;
        }
        $to_insert->bind_param('sss', $new_user, $pwd_hash, $recovery_hash);
        $to_insert->execute();
        $to_insert->close();
        echo "You have successfully registered.";
      }
    ?>
    <a href="./loginscreen.php">Go back to the login page</a>
  </body>
</html>
