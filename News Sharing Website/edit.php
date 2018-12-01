<?php
    require 'database.php';
    session_start();
    $username = $_SESSION['user'];
    if ($username == null) {
        exit;
        header('Location: dashboard.php');
    }
    $type = $_POST['type'];
    $id = $_POST['id'];
    $new_text = $_POST['new_text'];
    if(!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }
    if ($id == ""  || $type == "") {
        printf("Please choose post/comment and input an id.");
    } 
    else {
        switch($type) {
            case "comment":
                $stmt = $mysqli->prepare("UPDATE comments SET comment=? WHERE username=? AND comment_id=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('ssi', $new_text, $username, $id);
                $stmt->execute();
                $stmt->close();
            case "post":
                $stmt1 = $mysqli->prepare("UPDATE posts SET body=? WHERE username=? AND post_id=?");
                if(!$stmt1){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt1->bind_param('ssi', $new_text, $username, $id);
                $stmt1->execute();
                $stmt1->close();
        }
    }
    header('Location: dashboard.php');
?>
