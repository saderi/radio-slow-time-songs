<?php

include 'config.php';
include 'classes/DB.php';

    /**
    * function to strip out a string between two specified pieces of text.
    *
    * @param string $string The whole string.
    * @param string $start The start string.
    * @param string $end The end string.
    *
    * @return string.
    *
    */
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
    * function to strip out a string between two specified pieces of text.
    *
    * @param string $url The url want to save.
    *
    * @return string All content of the url.
    *
    */
    function get_url_contents($url){
        $crl = curl_init();
        $timeout = 5;
        curl_setopt ($crl, CURLOPT_URL,$url);
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


    $query = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 1";
    $result = $DB->get_results($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $old = $row["artist"].' - '.$row["title"];
        }
    }

    if (strcmp($old,$nowPlaying)) {

        include 'classes/spotify.php';

        $spotify = new Spotify(
            $clientID,
            $clientSecret
        );

        $accessToken = $spotify->getAccessToken();
        if ($accessToken) {
            $spotifyTrackId = $spotify->getSpotifyURL($accessToken, $title_artist[1], $title_artist[0]);
        }

        $query = 'INSERT INTO play_list (play_date, title, artist, spotify_track_id) 
                VALUES ("'.$play_date.'", "'.$title_artist[1].'", "'.$title_artist[0].'", "'.$spotifyTrackId.'")';
                $result = $DB->get_results($query);

    }

