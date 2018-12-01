<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete File</title>
</head>
<body>
    <?php
        session_start();
        $file_to_delete = $_GET['deletefile'];
        $user = $_SESSION['user'];
        $path_to_file = sprintf("/srv/uploads/%s/%s", $user, $file_to_delete);
        chmod($path_to_file, 0777);
        if (file_exists($path_to_file)) {
            unlink($path_to_file);
            header("Location: dashboard.php");
        } else {
            echo htmlentities("Error deleting $file_to_delete.\n");
        }
    ?>
    <br>
    <a href="./dashboard.php">Go back to your dashboard.</a>
</body>
</html>
