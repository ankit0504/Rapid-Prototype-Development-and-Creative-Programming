<html lang="en">
<head>
    <link rel = "stylesheet" href = "style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:thin,light,regular,bold,black|Source+Sans+Pro" rel="stylesheet">
</head>
<body>
    <div class = "top-bar">
        <h1 class = "newsfeed"> My Posts </h1>
    </div>
    <div class = "user_posts">
    <?php
        session_start();
        require 'database.php';
        $username = $_SESSION['user'];
        $stmt = $mysqli->prepare("SELECT post_id, link, username, text from posts where username = ? order by post_id");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($post_id, $post_link, $post_username, $post_text);
        while($stmt->fetch()){
            echo '<br><div class="title">'.htmlentities($post_text).'</div>';
            echo '<div class="sub">'.htmlentities($post_id)." | ".htmlentities($post_link)." | ".htmlentities($post_username).'</div>';
        }
        $stmt->close();
    ?>
    <br>
    <a href = "dashboard.php" class = "return"> Return to homepage </a>
    </div>
</body>
</html>
