<!DOCTYPE <!DOCTYPE html>
<html lang="en">
<head>
    <title>Share Files</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:thin,light,regular,bold,black&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel = "stylesheet" href = "appearance.css">
</head>
<body class = "share">
    <?php
        session_start();
        $username = $_SESSION['user'];
        $other_user = $_GET['other_user'];
        $file_name = $_GET['file_to_share'];
        $file_source = sprintf("/srv/uploads/%s/%s", $username, $file_name);
        $file_destination = sprintf("/srv/uploads/%s/%s", $other_user, $file_name);
        if (file_exists($file_destination)){
            echo "A file with this name exists in their account already, cannot share it.";
        }
        else if (copy($file_source, $file_destination)){
            echo "File was shared successfully.";
        }
        else {
            echo "Error sharing file.";
        }
    ?>
    <a href="./dashboard.php">Go back to the dashboard.</a>
</body>
</html>
