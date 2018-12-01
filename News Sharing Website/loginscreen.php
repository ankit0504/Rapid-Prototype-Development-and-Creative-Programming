<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <link rel = "stylesheet" href = "style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:thin,light,regular,bold,black" rel="stylesheet">
  </head>
  <body>
    <div class = "top-bar">
      <h1 class = "login">LOGIN</h1>
    </div>
    <div class = "login">
    <br>
      <form name="input" action="login.php" method="POST">
        <label for="user">Username</label>
        <input type="text" name="user" id="user" />
        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
        <input type="submit" value="Login" />
      </form>
      <br> <br>
      <a href="./forgot_password.html" class = "guest_link">Forgot password</a>
    </div>
    <br> <br>
    <div class = "top-bar">
      <h1 class = "login">REGISTER</h1>
    </div>
    <br>
    <div class = "login">
      <form name="input" action="register.php" method="POST">
        <label for="new_user">Username</label>
        <input type="text" name="new_user" id="new_user" />
        <label for="new_password">Password</label>
        <input type="password" name="new_password" id="new_password" />
        <label for="password_recovery">Mother's Maiden Name</label>
        <input type="text" name="password_recovery" id="password_recovery" />
        <input type="submit" value="Register" />
      </form>
      <br> <br>
      <a href="./dashboard.php" class = "guest_link">View stories as guest</a>
    </div>

  </body>
</html>
