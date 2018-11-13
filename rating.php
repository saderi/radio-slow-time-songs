<?php

include 'config.php';
include 'DB.php';

if ($_POST) {
    $song_id = $_POST["item_id"];
    $query = 'UPDATE play_list SET rating = rating + 1 WHERE id = '.$song_id;
    $DB->get_results($query);
    header("Location: https://saderi.com/tws/"); /* Redirect browser */
    exit();
}
