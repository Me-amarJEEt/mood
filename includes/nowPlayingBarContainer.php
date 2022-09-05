<?php  

    $songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY rand()");
    $resultArray = array();

    while ($row = mysqli_fetch_array($songQuery)) {
        array_push($resultArray, $row['id']);
    }

    $jsonArray = json_encode($resultArray);
?>

<script>

$(document).ready(function() {
    var newPlaylist = <?php echo $jsonArray ?>;
    audioElement = new Audio();
    setTrack(newPlaylist[0], newPlaylist, false);


    $("#nowPlayingBarContainer").on("mousemove mousedown touchstart touchmove", function(e){
        e.preventDefault();
    });

    $(".playbackBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".playbackBar .progressBar").mousemove(function(e) {
        if(mouseDown) {
            timeFromOffset(e, this)
        }
    });

    $(".playbackBar .progressBar").mouseup(function(e) {
        timeFromOffset(e, this)
    });
 
    //volume Bar

    $(".volumeBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".volumeBar .progressBar").mousemove(function(e) {
        if(mouseDown) {
            var percentage = e.offsetX / $(this).width();

            if(percentage >=0 && percentage <=1) {
                audioElement.audio.volume = percentage;
            }
        }
    });

    $(".volumeBar .progressBar").mouseup(function(e) {
        if(mouseDown) {
            var percentage = e.offsetX / $(this).width();
            if(percentage >=0 && percentage <=1) {
                audioElement.audio.volume = percentage;
            }
        }
    }); 



    $(document).mouseup(function() {
        mouseDown = false;
    });
});

function timeFromOffset(mouse, progressBar) {
    var percentage = mouse.offsetX / $(progressBar).width() * 100;
    var seconds = audioElement.audio.duration * (percentage / 100); 
    audioElement.setTime(seconds);
}

function prevSong() {
    if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
        audioElement.setTime(0);
    }
    else {
        currentIndex = currentIndex - 1;
        setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }
}

function nextSong() {

    if(repeat) {
        audioElement.setTime(0);
        playSong();
        return;
    }
    if(currentIndex == currentPlaylist.length - 1) {
        currentIndex = 0;
    }
    else {
        currentIndex++;
    }

    var trackToPlay =  shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true)
}

function setRepeat() {
    repeat =! repeat;

    var colorValue = repeat ? "rgb(51, 255, 95)" : "rgb(255, 255, 255)";
    $(".bi.bi-arrow-repeat").css("color", colorValue);
}

function setShuffle() {
    shuffle =! shuffle;

    var colorValue = shuffle ? "rgb(51, 255, 95)" : "rgb(255, 255, 255)";
    $(".bi.bi-shuffle").css("color", colorValue);

    if(shuffle) {
        shuffleArray(shufflePlaylist);
        currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
    else {
        currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
}

function shuffleArray(a) {
    var j, x, i;
    for (i = a.length ; i; i--) {
        j = Math.floor(Math.random() * i);
        x = a[i-1];
        a[i-1] = a[j];
        a[j] = x;
    }
}

function setMute() {
    audioElement.audio.muted =! audioElement.audio.muted;
    var iconName = audioElement.audio.muted ? "bi bi-volume-mute" : "bi bi-volume-down";
    $(".bi.bi-volume-down").attr("class", iconName);
}
 
function setTrack(trackId, newPlaylist, play) {

    if(newPlaylist != currentPlaylist) {
        currentPlaylist = newPlaylist;
        shufflePlaylist = currentPlaylist.slice();
        shuffleArray(shufflePlaylist); 
    }
    
    if(shuffle) {
        currentIndex = shufflePlaylist.indexOf(trackId);
    }
    else {
        currentIndex = currentPlaylist.indexOf(trackId);
    }

    pauseSong();

    $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

        var track = JSON.parse(data);
        $(".trackName span").text(track.title);

        $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
            var artist = JSON.parse(data);
            $(".artistName span").text(artist.name);
        });


        $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
            var album = JSON.parse(data);
            $(".albumLink img").attr("src", album.artworkPath);
        });

        audioElement.setTrack(track); 
        if(play == true) { 
            playSong();
        }
    });

}


function playSong() {

    if(audioElement.audio.currentTime == 0) {
        $.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id }) ;
    }

    $(".controlButtons.play").hide();
    $(".controlButtons.pause").show();
    audioElement.play();
}
function pauseSong() {
    $(".controlButtons.play").show();
    $(".controlButtons.pause").hide();
    audioElement.pause();
}

</script>



<div id="nowPlayingBarContainer">
            <div id="nowPlayingBar">
                <div id="nowPlayingLeft">
                    <div class="content">
                        <span class="albumLink">
                            <img src="" alt="Current album" class="albumArtWork">
                        </span>
                        <div class="trackInfo">
                            <span class="trackName">
                                <span></span>
                            </span>
                            <span class="artistName">
                                <span></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div id="nowPlayingCentre">
                    <div class="content playerControls">
                        <div class="buttons">
                            <button class="controlButtons suffle" title="Suffle button" onclick="setShuffle()">
                                <i class="bi bi-shuffle" alt="Suffle"></i>
                            </button>
                            <button class="controlButtons backward" title="Previous" onclick="prevSong()">
                                <i class="bi bi-skip-backward" alt="Previous"></i>
                            </button>
                            <button class="controlButtons play" title="Play" onclick="playSong()">
                                <i class="bi bi-play-circle" alt="Play"></i>
                            </button>
                            <button class="controlButtons pause" title="Pause" style="display:none;" onclick="pauseSong()">
                                <i class="bi bi-pause-circle" alt="Pause"></i>
                            </button>
                            <button class="controlButtons forward" title="Next" onclick="nextSong()">
                                <i class="bi bi-skip-forward" alt="Next"></i>
                            </button>
                            <button class="controlButtons repeat" title="Repeat" onclick="setRepeat()">
                                <i class="bi bi-arrow-repeat" alt="Repeat"></i>
                            </button>
                        </div>

                        <div class="playbackBar">
                            <span class="progressTime current">0.00</span>

                            <div class="progressBar">
                                <div class="progressBarBg">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <span class="progressTime remaining">0.00</span>
                        </div>
                    </div>
                </div>

                <div id="nowPlayingRight">
                    <div class="volumeBar">
                        <button class="controlButtons volume" title="Volume button" onclick="setMute()">
                            <i class="bi bi-volume-down" alt="Volume" alt="Volume"></i>
                        </button>
                        <button class="controlButtons volume" title="Volume button Muted" style="display:none;">
                            <i class="bi bi-volume-mute" alt="Volume muted"></i>
                        </button>
                        <div class="progressBar">
                            <div class="progressBarBg">
                                <div class="progress"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>