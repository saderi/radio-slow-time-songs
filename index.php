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
        $lastPlay = array();
        while($row = $result->fetch_assoc()) {
            $lastPlay[$row['id']]['name'] = $row["artist"].' - '.$row["title"];
            $lastPlay[$row['id']]['trackId'] = ($row["spotify_track_id"])? $row["spotify_track_id"] : '';
        }
    }

    $conn->close();
?>

<!doctype html>
<html class="no-js">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Slow Time to Spotify</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.0/css/foundation.min.css">
    </head>
    <body>
        <div class="grid-x grid-padding-x">
        <?php 
            foreach ($lastPlay as $key => $value) { 

                $linkButton = ($value['trackId'])? '<a class="success button expanded" href="spotify:track:'.$value['trackId'].'">Add to Spotify</a>' : '<a class="alert button expanded" href="#">Search on Google</a>';

                ?>
                
                <div class="cell medium-10 small-9"><?php echo $value['name']; ?></div>
                <div class="cell medium-2 small-3"><?php  echo $linkButton; ?></div>
        <?php } ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.0/js/foundation.min.js"></script>
    </body>
</html>