<?php

    include 'config.php';
    include 'DB.php';

    $query = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 10";
    $result = $DB->get_results($query);

    if ($result->num_rows > 0) {
        $lastPlay = array();
        while($row = $result->fetch_assoc()) {
            $lastPlay[$row['id']]['song_title'] = $row["title"];
            $lastPlay[$row['id']]['artist'] = $row["artist"];
            $lastPlay[$row['id']]['trackId'] = ($row["spotify_track_id"])? $row["spotify_track_id"] : '';
        }
    }

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
        <div class="grid-container">
            <p></p>            
            <div class="callout">
                <h1>10 last songs are played at <strong> Radio Slow Time </strong></h1>
                <p>When I listen to <strong>Radio Slow Time</strong>, sometimes I want to add a specific song to my Spotify playlist. So I made this</p>
                <a href="http://slowtime.com.tr/" class="font-bold">Radio Slow Time</a>
            </div>
            <?php 
                foreach ($lastPlay as $key => $value) { 

                    $linkButton = ($value['trackId'])? '<a class="success button expanded" href="spotify:track:'.$value['trackId'].'">Add to Spotify</a>' : '<a class="alert button expanded" href="#">Search on Google</a>';

                    ?>
                    
                    <div class="grid-x grid-padding-x align-middle">
                        <div class="cell medium-10 small-9">
                            <?php echo $value['song_title']; ?>
                            <br />
                            <small>
                                 <strong><?php echo $value['artist']; ?></strong>
                            </small>
                            
                                
                            </div>
                        <div class="cell medium-2 small-3"><?php  echo $linkButton; ?></div>
                        <div class="cell small-12 text-center subheader">--------------------------</div>
                    </div>
            <?php } ?>

        </div>
        <footer class="grid-container text-center">
            <p> 
                -------
                <a href="https://github.com/saderi/radio-slow-time-songs">Fork this on GitHub</a>
                -------
            </p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.0/js/foundation.min.js"></script>
    </body>
</html>