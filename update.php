<?php

include 'config.php';

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function get_url_contents($slowTimeLink){
    $crl = curl_init();
    $timeout = 5;
    curl_setopt ($crl, CURLOPT_URL,$slowTimeLink);
    curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return strip_tags($ret);
}

$nowPlaying = get_url_contents($slowTimeLink);
$nowPlaying = get_string_between($nowPlaying, 'Song Title', 'Current Song');
$nowPlaying = substr($nowPlaying, 8, -7);
$play_date = date("Y-m-d H:i:s");
$title_artist = explode(" - ",$nowPlaying);

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        $old = $row["artist"].' - '.$row["title"];
        
    }
}

if (strcmp($old,$nowPlaying)) {

    include 'spotifyClass.php';

    $spotify = new Spotify(
        $clientID,
        $clientSecret
    );

    $accessToken = $spotify->getAccessToken();
    if ($accessToken) {
        $spotifyTrackId = $spotify->getSpotifyURL($accessToken, $title_artist[1], $title_artist[0]);
    }


    $sql = 'INSERT INTO play_list (play_date, title, artist, spotify_track_id) 
            VALUES ("'.$play_date.'", "'.$title_artist[1].'", "'.$title_artist[0].'", "'.$spotifyTrackId.'")';
    $conn->query($sql);

}

$conn->close();
