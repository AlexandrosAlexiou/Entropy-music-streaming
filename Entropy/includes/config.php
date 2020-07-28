<?php
    ob_start();
    session_start();

    $timezone = date_default_timezone_set("Europe/Athens");

    /* Connection variable */
    //$con = mysqli_connect("127.0.0.1", "root", "password", "EntropyDB");

    /* Connection variable running with Docker*/
    $con = mysqli_connect("db", "user", "test", "EntropyDB");

    if(mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno();
    }
