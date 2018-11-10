<?php

include 'config.php';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
        echo "<div>";
    while($row = $result->fetch_assoc()) {
        
        echo '<div style="clear: both">';

        echo $row["artist"].' - '.$row["title"];

        // echo $row["artist"].' - '.$row["title"];
        echo " .. | .. ";
        if ($row["spotify_track_id"]) {
            echo '<a href="spotify:track:'.$row["spotify_track_id"].'">Add to spotify</a>';
        }


        echo ' <span style="float: right"></span>';
        echo "<hr>";
        echo "</div>";
        
    }
}

$conn->close();
