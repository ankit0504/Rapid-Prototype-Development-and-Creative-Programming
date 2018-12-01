<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Add New User</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:thin,light,regular,bold,black&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel = "stylesheet" href = "appearance.css">
  </head>
  <body>
    <?php
      $new_user = $_GET['new_user'];

      if(is_dir("/srv/uploads/".$new_user)) {
        echo "Username taken. Please choose a new one.";
      } else if( !preg_match('/^[\w_\.\-]+$/', $new_user) ){
        echo "Invalid username.";
      } else {
        mkdir("/srv/uploads/".$new_user, 0777);
        file_put_contents ("/srv/users.txt", $new_user."\n", FILE_APPEND);
        echo "You have successfully registered.";
      }
    ?>
    <a href="./login.html">Go back to the login page</a>
  </body>
</html>
