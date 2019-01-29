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
        $lastPlay[$row['id']]['play_date'] = $row["play_date"];
        $lastPlay[$row['id']]['trackId'] = ($row["spotify_track_id"])? $row["spotify_track_id"] : '';
    }
}

$song_list = '';
foreach ($lastPlay as $key => $value) { 

    if ($value['trackId']) {

        $linkButton = '<a class="success button small expanded text-center" 
                          href="spotify:track:'.$value['trackId'].'">Go spotify</a>';
        $spotify_link = 'https://open.spotify.com/track/'.$value['trackId'];
        $share_link = '
        <a href="https://twitter.com/intent/tweet?url='.$spotify_link.'&text='.$value['song_title'].'%20by%20'.$value['artist'].'&via=radioslowtime&hashtags=Nowplaying" target="_blank">
            <i class="socicon-twitter"></i>
        </a>
        <a href="https://telegram.me/share/url?url='.$spotify_link.'" target="_blank">
            <i class="socicon-telegram"></i>
        </a>
        <a href="https://www.facebook.com/sharer.php?u='.$spotify_link.'" target="_blank">
            <i class="socicon-facebook"></i>
        </a>
        <a href="'.$spotify_link.'" target="_blank">
            <i class="socicon-spotify"></i>
        </a>';

    } else {
        $linkButton = '<a class="alert button expanded small text-center" target="_blank" 
        href="https://www.google.com.tr/search?q='.$value['song_title'].' '.$value['artist'].'">Google it!</a>';
        $share_link = '';
    }
    
    $song_list .= '
    <div class="grid-x grid-padding-x align-middle">
        <div class="cell medium-10 small-8">
            <div>
                <div>
                    <strong>'.$value['song_title'].'</strong>
                </div>
                <small>'.$value['artist'].'</small>
            </div>
            <div>
                <small>'.$share_link.'</small>
            </div>
        </div>
        <div class="cell medium-2 small-4">'.$linkButton.'</div>
        <div class="cell small-12 text-center subheader">--------------------------</div>
    </div>';

}

echo $song_list;