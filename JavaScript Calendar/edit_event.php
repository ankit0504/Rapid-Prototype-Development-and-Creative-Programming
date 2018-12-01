<?php

session_start();

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$event_id = $json_obj['event_id'];
$new_name = $json_obj['new_name'];
$new_year = $json_obj['new_year'];
$new_month = $json_obj['new_month'];
$new_day = $json_obj['new_day'];
$new_hour = $json_obj['new_hour'];
$new_minute = $json_obj['new_minute'];
$new_color = $json_obj['new_color'];
$new_priority = $json_obj['new_priority'];
$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'], $token)) {
	die("Request forgery detected");
}

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

require 'database.php';

$stmt = $mysqli->prepare("UPDATE events SET title = ?, year = ?, month = ?, day = ?, hour = ?, minute = ?, color = ?, is_priority = ? WHERE event_id = ?");

if(!$stmt) {
    echo json_encode(array(
		"success" => false
	));
	exit;
}

$stmt->bind_param('siiiiissi', $new_name, $new_year, $new_month, $new_day, $new_hour, $new_minute, $new_color, $new_priority, $event_id);
$stmt->execute();
$stmt->close();

echo json_encode(array(
    "success" => true
));
exit;

?>
