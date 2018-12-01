<!DOCTYPE html>
<html lang="en">
  <head><title>Logout</title></head>
  <body>
    <?php
      session_start();
      session_destroy();
      header("Location: login.html");
      exit;
    ?>
  </body>
</html>
