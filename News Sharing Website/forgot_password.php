<?php
    require 'database.php';

    $stmt = $mysqli->prepare("SELECT COUNT(*), username, password_recover FROM users WHERE username=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $user = $_POST['user'];
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->bind_result($cnt, $username, $hash);
    $stmt->fetch();
    $stmt->close();
    $updated_password = $_POST['password_new'];
    $new_password_hash = password_hash($updated_password, PASSWORD_DEFAULT);
    $answer = $_POST['answer'];
    if ($cnt == 1 && password_verify($answer, $hash)){
        $stmt1 = $mysqli->prepare("UPDATE users SET hashed_password=? WHERE username=?");
        if(!$stmt1){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt1->bind_param('ss', $new_password_hash, $username);
        $stmt1->execute();
        $stmt1->close();
        echo "Password update success";
        echo '<a href = "loginscreen.php" class = "my_posts"> Return to login screen. </a>';
    } else {
        echo "Password updated failed.";
        header('Location: password_reset_failure.html');
        exit;
    }
?>

