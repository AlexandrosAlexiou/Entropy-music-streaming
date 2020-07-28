<?php
    include ("../../config.php");

    if(isset($_POST['playlistId']) && isset($_POST['songId'])) {

        $playlistId = $_POST['playlistId'];
        $songId = $_POST['songId'];

        $orderedIdQuery = mysqli_query($con, "SELECT MAX(playlist_order) AS playlistOrder FROM playlist_songs WHERE playlist_id='$playlistId'");
        $row = mysqli_fetch_array($orderedIdQuery);
        $order = $row['playlistOrder'];
        if($order == null) {
            $order = 1;
        }
        $query = mysqli_query($con, "INSERT INTO playlist_songs VALUES(NULL, '$songId', '$playlistId', '$order')");
    } else {
        echo "PlaylistId or songId was not passed into addToPlaylist.php";
    }