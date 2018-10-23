<?php

include 'config.php';

if ($_POST) {

	$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

		$song_id = $_POST["item_id"];

	    $sql = 'UPDATE play_list SET rating = rating + 1 WHERE id = '.$song_id;
	    $conn->query($sql);

	    header("Location: https://saderi.com/tws/"); /* Redirect browser */
		exit();

	$conn->close();

}
