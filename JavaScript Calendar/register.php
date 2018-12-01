<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$new_user = $json_obj['new_username'];
$pwd = $json_obj['new_password'];

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

require 'database.php';

$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
$stmt->bind_param('s', $new_user);
$stmt->execute();
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();

if(strlen($new_user) > 16 || strlen($new_user) < 3) {
  echo json_encode(array(
		"success" => false,
		"message" => "Username length is invalid. Please choose a username between 3 and 16 characters."
	));
	exit;
} 

else if($cnt > 0) {
  echo json_encode(array(
		"success" => false,
		"message" => "Username taken. Please choose a new one."
	));
	exit;
} 

else if( !preg_match('/^[\w_\.\-]+$/', $new_user) ){
  echo json_encode(array(
		"success" => false,
		"message" => "Invalid username."
	));
	exit; 
} 

else if (strlen($pwd) > 16 || strlen($pwd) < 3){
  echo json_encode(array(
		"success" => false,
		"message" => "Password length is invalid. Please choose a password between 3 and 16 characters."
	));
	exit; 
} 

else {
  $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
  $to_insert = $mysqli->prepare("INSERT into users (username, hashed_password) values (?, ?)");
  if(!$to_insert){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $to_insert->bind_param('ss', $new_user, $pwd_hash);
  $to_insert->execute();
  $to_insert->close();
	echo json_encode(array(
		"success" => true
	));
	exit;
} 
?>