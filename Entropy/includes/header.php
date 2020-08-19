<?php
include ("config.php");
include ("classes/User.php");
include ("classes/Artist.php");
include ("classes/Album.php");
include ("classes/Song.php");
include ("classes/Playlist.php");


if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();
    echo "<script> userLoggedIn ='$username';</script>";
}
else {
    header("Location: register.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entropy</title>
    <link rel="icon" type="image/png" href="assets/images/music.png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>

<body>



<div id="mainContainer">
    <div id="topContainer">
        <?php include("navBar.php") ?>

        <div id="mainViewContainer">
            <div id="mainContent">