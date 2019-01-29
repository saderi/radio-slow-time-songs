<?php

    include 'config.php';
    require 'classes/DB.php';

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
    function getStringBetween($string, $start, $end){
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
    function getUrlContents($url){
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

    /**
    * inser new song to DB.
    *
    * @param string $play_date The play date of song.
    * @param string $song_title The song title.
    * @param string $artist_name The song artist.
    * @param string $spotify_track_id Song soptify track id.
    *
    */
    function insetNewSong($DB, $play_date, $song_title, $artist_name, $spotify_track_id)
    {

        $query = '
            INSERT INTO play_list (play_date, title, artist, spotify_track_id) 
            VALUES (
                "'.$play_date.'",
                "'.$song_title.'",
                "'.$artist_name.'",
                "'.$spotify_track_id.'"
            )';
        $result = $DB->get_results($query);
    }

    /**
    * return spotify track ID.
    *
    * @param string $song_title The song title.
    * @param string $artist_name The song artist.
    *
    * @return string spotify track ID.
    */
    function getSpotifyTrackId($song_title, $artist_name)
    {
        include 'classes/spotify.php';
        $spotify = new Spotify(
            SPOTIFY_CLIENT_ID,
            SPOTIFY_CLIENT_SECRET
        );
        $accessToken = $spotify->getAccessToken();

        if ($accessToken)
            return $spotify->getSpotifyURL($accessToken, $song_title, $artist_name);

        return false;
    }

    $nowPlaying = getUrlContents($slowTimeLink);
    $nowPlaying = getStringBetween($nowPlaying, 'Song Title', 'Current Song');
    $nowPlaying = substr($nowPlaying, 8, -7);
    $play_date = date("Y-m-d H:i:s");
    $title_artist = explode(" - ",$nowPlaying);
    $song_title = $title_artist[1];
    $artist_name = $title_artist[0];

    $query = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 1";
    $result = $DB->get_row($query);
    $old = $result[2].' - '.$result[1];

    if (strcmp($old,$nowPlaying)) {
        
        $query = "SELECT spotify_track_id 
                  FROM `play_list` 
                  WHERE title='{$song_title}' 
                  AND artist='{$artist_name}'
                  ORDER BY `play_list`.`id` DESC";
        $result = $DB->get_row($query);

        if ($result[0]) {
            $spotify_track_id = $result[0];
        } else {
            $spotify_track_id = getSpotifyTrackId($song_title, $artist_name);
        }

        insetNewSong($DB, $play_date, $song_title, $artist_name, $spotify_track_id);

    }

