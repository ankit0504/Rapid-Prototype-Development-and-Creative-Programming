<html lang="en">
  <head>
    <title>Newsfeed</title>
    <link rel = "stylesheet" href = "style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:thin,light,regular,bold,black|Source+Sans+Pro" rel="stylesheet">
  </head>
  <body>
    <div class = "top-bar">
    <h1 class = "newsfeed"> Newsfeed </h1>
    <form name="new_post" action="post.php" method="POST" class = "new_post">
      <label for="link">LINK</label>
      <input type="text" name="link" id="link" />
      <label for="title">TITLE</label>
      <input type="text" name="title" id="title" />
      <label for="body">BODY</label>
      <input type="text" name="body" id="body" />
      <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
      <input type="submit" value="Post" />
    </form>
    <a href = "userprofile.php" class = "my_posts"> MY POSTS </a>
    <form name="search_profile" action="search_profile.php" method="GET" class = "search_profile">
      <label for="search_profile">SEARCH</label>
      <input type="text" name="search_profile" id="search_profile" />
      <input type="submit" value="Search" />
    </form>
    </div>
    <div class = 'stories'>
    <?php
    require 'database.php';
    $stmt = $mysqli->prepare("SELECT posts.post_id, comments.comment, posts.username, comments.username, posts.link, posts.text, comments.comment_id, posts.body from
    posts left join comments on (comments.post_id = posts.post_id) order by posts.post_id");
    if(!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($post_id, $comment, $post_username, $comment_username, $link, $text, $comment_id, $post_body);
    $temp = null;
    while($stmt->fetch()){
        if ($comment == null) {
            echo '<br><div class="title">'.htmlentities($text).'</div>';
            echo '<div class="sub">'.htmlentities($post_id)."|";
            echo "<a href='$link'>$link</a>";
            echo "|".htmlentities($post_username).'</div>';
            if ($post_body != null) {
            echo htmlentities($post_body).'<br>';
            }
        }
        else if ($post_id != $temp) {
            echo '<br><div class="title">'.htmlentities($text).'</div>';
            echo '<div class="sub">'.htmlentities($post_id)." | ";
            echo "<a href='$link'>$link</a>";
            echo " | ".htmlentities($post_username).'</div>';
            if ($post_body != null) {
              echo htmlentities($post_body).'<br>';
            }
            echo '<hr>';
            echo '<span class="comment_username">'.htmlentities($comment_id)." ".htmlentities($comment_username)." ".'</span>'.'<span class="comment">'.htmlentities($comment).'</span>';
            echo '<br>';
        }
        else {
            echo '<span class="comment_username">'.htmlentities($comment_id)." ".htmlentities($comment_username)." ".'</span>'.'<span class="comment">'.$comment.'</span>';
            echo '<br>';

        }
        $temp = $post_id;
    }
    $stmt->close();
    ?>
    <br> <br>
    <form name="new_comment" action="comment.php" method="POST" class = "new_comment">
      <label for="post_id">Which post (ID) would you like to comment on?</label>
      <input type="text" name="post_id" id="post_id" />
      <label for="comment">Comment</label>
      <input type="text" name="comment" id="comment" />
      <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
      <input type="submit" value="Post" />
    </form>
    <br>
    <hr>
    <a href = "edit_page.php" class = "edit"> Edit post or comment by ID | </a>
    <a href = "delete_page.php" class = "edit"> Delete post or comment by ID </a>
    <br>
    <form action="logout.php">
        <input type="submit" value="Logout" />
    </form>
    </div>
  </body>
</html>

