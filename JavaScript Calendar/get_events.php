<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
ini_set("session.cookie_httponly", 1);
session_start();

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$month = $json_obj['month'];
$year = $json_obj['year'];
$username = $_SESSION['username'];

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

require 'database.php';

$stmt = $mysqli->prepare("SELECT event_id, title, day, hour, minute, color, is_priority FROM events WHERE month = ? and year = ? and username = ?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$ids = array();
$days = array();
$names = array();
$hours = array();
$minutes = array();
$colors = array();
$priorities = array();

$stmt->bind_param('iis', $month, $year, $username);
$stmt->execute();
$stmt->bind_result($id, $name, $day, $hour, $minute, $color, $is_priority);

while($stmt->fetch()) {
    $ids[] = htmlentities($id); //these htmlentities are prevention for xss attacks
    $days[] = htmlentities($day);
    $names[] = htmlentities($name);
    $hours[] = htmlentities($hour);
    $minutes[] = htmlentities($minute);
    $colors[] = htmlentities($color);
    $priorities[] = htmlentities($is_priority);
}

$stmt->close();

if (count($minutes) < 1){
	echo json_encode(array(
		"success" => false
	));
	exit;
}

else {
    
	echo json_encode(array(
		"success" => true,
        "names" => $names,
        "days" => $days,
        "hours" => $hours,
        "minutes" => $minutes,
        "ids" => $ids,
        "colors" => $colors,
        "priorities" => $priorities
	));
	exit;
}

?>
