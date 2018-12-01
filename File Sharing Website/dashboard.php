<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:thin,light,regular,bold,black&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel = "stylesheet" href = "appearance.css">

</head>
  <body>
    <h1 class = "dash">MY DASHBOARD</h1>
    <br>
    <hr>
    <div>
    </div>
    <h2> MY FILES </h2>
    <?php
        session_start();
        $username = $_SESSION['user'];
        if ($username == null) {
            header("Location: login.html");
        }
        $dir = sprintf("/srv/uploads/%s/", $username);
        $files = scandir($dir);

        for ($i = 2; $i < count($files); $i++) {
            print_r (htmlentities($files[$i]));
            echo nl2br("\n");
        }

    ?>
    <br>
    <h2> MANAGE FILES </h2>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
            <input type="submit" value="Upload File" />
    </form>
    <form name = "viewfile" action = "view.php" method = "GET">
        <label for = "viewfile">Type in document name from list to view:</label>
        <input type = "text" name = "viewfile" id = "viewfile"/>
        <input type = "submit" value = "View"/>
    </form>
    <form name = "deletefile" action = "delete.php" method = "GET">
        <label for = "deletefile">Type in document name from list to delete:</label>
        <input type = "text" name = "deletefile" id = "deletefile"/>
        <input type = "submit" value = "Delete"/>
    </form>
    <br>
    <h2> SHARE FILES </h2>
    <form name="sharefile" action="share.php" method="GET">
        <label for="other_user">User you want to share file with:</label>
        <input type="text" name="other_user" id="other_user" />
        <label for="file_to_share">File name you want to share:</label>
        <input type="text" name="file_to_share" id="file_to_share" />
        <input type="submit" value="Submit" />
    </form>
    <br>
    
    <h2>LOGOUT</h2>
    <form action="logout.php">
        <input type="submit" value="Logout" />
    </form>
  </body>
</html>

