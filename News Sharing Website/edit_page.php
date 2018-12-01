<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Delete a post or comment</title>
        <link rel = "stylesheet" href = "style.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:thin,light,regular,bold,black" rel="stylesheet">
    </head>
    <body>
        <form name="input" action="edit.php" method="POST">
                <label>Would you like to edit a post or comment:</label>
                <input type="radio" name="type" value="post" id="post" /> <label for="post">Post</label> &nbsp;
                <input type="radio" name="type" value="comment" id="comment" /> <label for="comment">Comment</label> &nbsp;
                <label for="id">ID</label>
                <input type="number" name="id" id="id" />
                <label for="new_text">NEW TEXT</label>
                <input type="text" name="new_text" id="new_text" />
                <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
                <input type="submit" value="Submit" />
        </form>
    </body>
</html>
