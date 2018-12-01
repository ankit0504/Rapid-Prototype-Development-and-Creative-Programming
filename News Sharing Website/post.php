<?php
require 'database.php';
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    $username = $_SESSION['user'];
    $link = $_POST['link'];
    $text = $_POST['title'];
    $body = $_POST['body'];
    $stmt = $mysqli->prepare("INSERT into posts (username, link, text, body) values (?, ?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    if (strlen ($link) > 256 || strlen ($text) > 256) {
        printf("Text and/or link is too many characters long.");
        exit;
    }
    $stmt->bind_param('ssss', $username, $link, $text, $body);
    $stmt->execute();
    $stmt->close();
    header('Location: dashboard.php');
?>
