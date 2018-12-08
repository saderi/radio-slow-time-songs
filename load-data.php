<?php

include 'config.php';
include 'classes/DB.php';

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

$song_list = '';
foreach ($lastPlay as $key => $value) { 

    if ($value['trackId']) {
        $linkButton = '<a class="success button expanded" 
                          href="spotify:track:'.$value['trackId'].'">Add to Spotify</a>';
    } else {
        $linkButton = '<a class="alert button expanded" target="_blank" 
        href="https://www.google.com.tr/search?q='.$value['song_title'].' '.$value['artist'].'">Search on Google</a>';
    }
    
    $song_list .= '
    <div class="grid-x grid-padding-x align-middle">
        <div class="cell medium-10 small-9">
            '.$value['song_title'].'
            <br />
            <small>
                 <strong>'.$value['artist'].'</strong>
            </small>
        </div>
        <div class="cell medium-2 small-3">'.$linkButton.'</div>
        <div class="cell small-12 text-center subheader">--------------------------</div>
    </div>';

}

echo $song_list;