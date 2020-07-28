<?php
    $songQuery = mysqli_query($con , "SELECT * FROM songs ORDER BY RAND() LIMIT 10");
    $resultArray = array();
    while($row = mysqli_fetch_array($songQuery)) {
        array_push($resultArray, $row['id']);
    }
    $jsonArray = json_encode($resultArray);

?>

<script>

    $(document).ready(function () {
        const newPlaylist = <?php  echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        updateVolumeProgressBar(audioElement.audio);

        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function (e) {
           e.preventDefault();
        });

        // Progress bar control
        $(".playbackBar .progressBar").mousedown(function () {
            mouseDown = true;
        });

        $(".playbackBar .progressBar").mousemove(function (e) {
            if(mouseDown) {
                timeFromOffset(e, this);
            }
        });

        $(".playbackBar .progressBar").mouseup(function (e) {
            timeFromOffset(e, this);
        });

        // Volume bar control
        $(".volumeBar .progressBar").mousedown(function () {
            mouseDown = true;
        });

        $(".volumeBar .progressBar").mousemove(function (e) {
            if(mouseDown) {
                const volumePercentage = e.offsetX / $(this).width();

                if(volumePercentage >= 0 && volumePercentage <=1) {
                    audioElement.audio.volume = volumePercentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function (e) {
            const volumePercentage = e.offsetX / $(this).width();
            audioElement.audio.volume = volumePercentage;
        });

        $(document).mouseup(function () {
            mouseDown = false;
        })
    });

    function timeFromOffset(mouse, progressBar) {
        const percentage = mouse.offsetX / $(progressBar).width() * 100;
        const seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setProgress(seconds);
    }

    function previousSong() {
        if(audioElement.audio.currentTime >= 3 || currentIndex === 0) {
            audioElement.setProgress(0);
        } else {
            currentIndex--;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }
    }

    function nextSong() {
        if(repeat) {
            audioElement.setProgress(0);
            playSong();
            return;
        }
        if(currentIndex === currentPlaylist.length-1) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }

        const trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);
    }

    function toggleRepeat() {
        repeat = !repeat;
        const imageName = (repeat) ? "repeat-active.png" : "repeat.png"
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    function toggleMute() {
        audioElement.audio.muted = !audioElement.audio.muted
        const imageName = (audioElement.audio.muted) ? "volume-mute.png" : "volume.png"
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function toggleShuffle() {
        shuffle = !shuffle;
        const imageName = (shuffle) ? "shuffle-active.png" : "shuffle.png"
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

        if(shuffle) {
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            //shuffle turned off
            //go back to regular playlist
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
    }

    function shuffleArray(a) {
        let j, x ,i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i-1];
            a[i-1] = a[j];
            a[j] = x;
        }
    }

    function setTrack(trackId, newPlaylist, play) {
        if(newPlaylist !== currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist =  currentPlaylist.slice();
            shuffleArray(shufflePlaylist);
        }
        if(shuffle) {
            currentIndex = shufflePlaylist.indexOf(trackId);
        } else {
            currentIndex = currentPlaylist.indexOf(trackId);
        }

        $.post("includes/handlers/ajax/getSongJson.php",{ songId: trackId}, function (data) {
            const track = JSON.parse(data);
            //console.log(track);
            $(".trackName span").text(track.title);

            $.post("includes/handlers/ajax/getArtistJson.php",{ artistId: track.artist }, function (data) {
                const artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name).attr("onclick", "openPage('artist.php?id="+ artist.id +"')");
            });

            $.post("includes/handlers/ajax/getAlbumJson.php",{ albumId: track.album }, function (data) {
                const album = JSON.parse(data);
                $(".content .albumLink img").attr("src", album.artworkPath).attr("onclick", "openPage('album.php?id="+ album.id +"')");
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id="+ album.id +"')");
            });

            audioElement.setTrack(track);

            if(play) {
                playSong();
            }
        });

    }

    function playSong() {

        if(audioElement.audio.currentTime === 0) {
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }
        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play();
    }

    function pauseSong() {
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause();
    }

</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                    <span class="albumLink">
                        <img src="" alt="" class="albumArtwork">
                    </span>
                <div class="trackInfo">
                        <span class="trackName">
                            <span role="link" tabindex="0"></span>
                        </span>

                    <span class="artistName">
                            <span role="link" tabindex="0"></span>
                        </span>
                </div>
            </div>
        </div>

        <div id="nowPlayingCenter">

            <div class="content playerControls">

                <div class="buttons">

                    <button class="controlButton shuffle" title="Shuffle button" onclick="toggleShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>

                    <button class="controlButton previous" title="Previous button" onclick="previousSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>

                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick="toggleRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>

                </div>


                <div class="playbackBar">

                    <span class="progressTime current">0.00</span>

                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>

                    <span class="progressTime remaining"></span>
                </div>
            </div>
        </div>

        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume button" onclick="toggleMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume">
                </button>

                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php
