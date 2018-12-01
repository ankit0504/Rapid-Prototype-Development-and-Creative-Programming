<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

require 'database.php';

$stmt = $mysqli->prepare("SELECT COUNT(*), username, hashed_password FROM users WHERE username=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($cnt, $username, $pwd_hash);
$stmt->fetch();

if ($cnt == 1 && password_verify($password, $pwd_hash)){
	
	ini_set("session.cookie_httponly", 1);
	session_start();
	$_SESSION['username'] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];

	echo json_encode(array(
		"success" => true,
		"token" => $token
	));
	exit;
}
else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>
