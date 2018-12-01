<?php
    require 'database.php';
    session_start();
    $username = $_SESSION['user'];
    $type = $_POST['type'];
    $id = $_POST['id'];
    echo "SESSION: ";
    echo $_SESSION['token'];
    echo "<br>";
    echo "POST: ";
    echo $_POST['token'];
    if(!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }
    if ($id == ""  || $type == "") {
        printf("Please choose post/comment and input an id.");
    } 
    else {
        switch($type) {
            case "comment":
                $stmt = $mysqli->prepare("DELETE from comments where comment_id=? and username=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('is', $id, $username);
                $stmt->execute();
                $stmt->close();
            case "post":
                $stmt2 = $mysqli->prepare("SELECT COUNT(*) from posts where post_id=? and username=?");
                if(!$stmt2){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt2->bind_param('is', $id, $username);
                $stmt2->execute();
                $stmt2->bind_result($count_);
                $stmt2->close();
                if ($count_ === 0){
                    header('Location: dashboard.php');
                    exit;
                }
                $stmt1 = $mysqli->prepare("DELETE from comments where post_id=?");
                if(!$stmt1){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt1->bind_param('i', $id);
                $stmt1->execute();
                $stmt1->close();

                $stmt2 = $mysqli->prepare("DELETE from posts where post_id=? and username=?");
                if(!$stmt2){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt2->bind_param('is', $id, $username);
                $stmt2->execute();
                $stmt2->close();
        }
    }
    header('Location: dashboard.php');
?>
