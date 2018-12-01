<?php
    session_start();
    $token = $_SESSION['token'];
    echo json_encode(array(
		"token" => $token
	));
	exit;
?>