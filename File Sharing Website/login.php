<!DOCTYPE html>
<html lang="en">
  <head><title>Files</title></head>
  <body>
    <?php
      session_start();
      $_SESSION['user'] = $_GET['user'];
      $user_list = fopen ("/srv/users.txt", "r");
      while(!feof($user_list)){
        $trimmed = trim(fgets($user_list));
        if ($trimmed == $_SESSION['user']) {
          header("Location: dashboard.php");
          fclose($user_list);
          exit;
        }
      }
      fclose($user_list);
      session_destroy();
      header("Location: notfound.html");
      exit;
    ?>
  </body>
</html>
