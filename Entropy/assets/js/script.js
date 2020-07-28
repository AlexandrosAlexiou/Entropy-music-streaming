let currentPlaylist = [];
let shufflePlaylist = [];
let tempPlaylist = [];
let audioElement;
let mouseDown = false;
let currentIndex = 0;
let repeat = false;
let shuffle = false;
var timer = null;
var userLoggedIn;

$(window).scroll(function () {
 hideOptionsMenu();
});

$(document).click(function (e) {
    const target = $(e.target);
    if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();
    }
});

$(document).on("change", "select.playlist", function() {
    const select = $(this);
    const playlistId = select.val();
    const songId = select.prev(".songId").val();

    // console.log("playlistId: " + playlistId);
    // console.log("songId: " + songId);
    $.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId}).done(function (error) {
        if(error) {
            alert(error);
            return;
        }
        hideOptionsMenu();
        select.val("");
    })
});

function logout() {
    $.post("includes/handlers/ajax/logout.php", function () {
        location.reload();
    });
}

function updateEmail(emailClass) {
    const  emailValue = $("." + emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php",{email: emailValue, username: userLoggedIn })
        .done( function (response) {
            $("." + emailClass).nextAll(".message").text(response); //goes to siblings
        })
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    const  oldPassword = $("." + oldPasswordClass).val();
    const  newPassword1 = $("." + newPasswordClass1).val();
    const  newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php",
        {
            oldPassword: oldPassword,
            newPassword1: newPassword1,
            newPassword2: newPassword2,
            username: userLoggedIn
        })
        .done( function (response) {
            $("." + oldPasswordClass).nextAll(".message").text(response); //goes to siblings
        })
}

function openPage(url) {
    if(timer !== null) {
        clearTimeout(timer);
    }
    if(url.indexOf("?") === -1) {
        url = url + "?";
    }
    var encodeUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodeUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
    const songId = $(button).prevAll(".songId").val();
    $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId})
        .done(function (error) {

            if(error) {
                alert(error);
                return;
            }
            openPage("playlist.php?id=" + playlistId);
        });
}
function createPlaylist() {
    const input = prompt("Enter the name or the playlist.");

    if(input != null) {
        $.post("includes/handlers/ajax/createPlaylist.php", {name: input, username: userLoggedIn})
            .done(function (error) {

                if(error) {
                   alert(error);
                   return;
                }
                openPage("music.php");
        })
    }
}

function deletePlaylist(playlistId) {
    const input = confirm("Are you sure you want to delete this playlist?")

    if(input) {
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId})
            .done(function (error) {

                if(error) {
                    alert(error);
                    return;
                }
                openPage("music.php");
            })
    }
}

function showOptionsMenu(button) {
    const songId = $(button).prevAll(".songId").val();
    const menu = $(".optionsMenu");
    const menuWidth = menu.width();
    menu.find(".songId").val(songId);

    const scrollTop = $(window).scrollTop(); //Distance from top of window to top of document
    const elementOffset = $(button).offset().top; //Distance from top of document

    const top = elementOffset - scrollTop;
    const left = $(button).position().left;

    menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

function hideOptionsMenu() {

    const menu = $(".optionsMenu");
    if(menu.css("display") !== "none") {
        menu.css("display", "none");
    }
}

function formatTime(duration) {
    const time = Math.round(duration);
    const minutes = Math.floor(time/60);
    const seconds = time - minutes * 60;
    const extraZero = ( seconds < 10) ? "0" : "";
    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    const progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress+ "%");
}

function updateVolumeProgressBar(audio) {
    const volume = audio.volume * 100;
    //console.log(volume);
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio() {

    this.currentlyPlaying = null;

    this.audio = document.createElement('audio');

    this.audio.addEventListener("ended", function () {
        nextSong();
    });

    this.audio.addEventListener("canplay", function () {
        $(".progressTime.remaining").text(formatTime(this.duration));
    });

    this.audio.addEventListener("timeupdate", function () {
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    this.audio.addEventListener("volumechange", function () {
        updateVolumeProgressBar(this);
    });

    this.setTrack = function (track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.play = function () {
        this.audio.play();
    }

    this.pause = function () {
        this.audio.pause();
    }

    this.setProgress = function (seconds) {
        this.audio.currentTime = seconds;
    }
}