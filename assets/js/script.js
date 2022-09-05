var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false; 
var userLoggedIn;
var timer;

$(document).click(function(click) {
	var target = $(click.target);

	if(!target.hasClass("item") && !target.hasClass("bi-three-dots")) {
		hideOptionMenu();
	}
});


$(window).scroll(function() {
	hideOptionMenu();
});


$(document).on("change", "select.playlist", function() {
	var selectt = $(this);
	var playlistId = selectt.val();
	var songId = selectt.prev(".songId").val();

	console.log(playlistId);
	console.log(songId);

	$.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
	.done(function() {

		

		hideOptionMenu();
		selectt.val("");
	});
}); 



//logout,updatepassword/email




function openPage(url) {  

    if(timer != null) {
       clearTimeout(timer);
    }

    if(url.indexOf("?") == -1) {
       url = url + "?";
    }

    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url)
}


//remove from playlist

function createPlaylist() {

	var popup = prompt("Please enter the name of your playlist");

	if(popup != null) {

		$.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("yourPlaylist.php");
		});

	}

}

function deletePlaylist(playlistId) {
	var prompt = confirm("Are you sure you want to delte this playlist?");

	if(prompt == true) {

		$.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("yourPlaylist.php");
		});


	}
}

function hideOptionMenu() {
	var menu = $(".optionMenu");
	if(menu.css("display") != "none") {
		menu.css("display", "none");
	}
}

function showOptionMenu(button) {
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);

	var scrollTop = $(window).scrollTop(); //Distance from top of window to top of document
	var elementOffset = $(button).offset().top; //Distance from top of document

	var top = elementOffset - scrollTop;
	var left = $(button).position().left;

	menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });

}



function formateTime(seconds) {
    var time = Math.round(seconds);
    var minute = Math.floor(time/60);
    var second = time - (minute*60);

    return minute + ":" + second;
}

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formateTime(audio.currentTime));
    $(".progressTime.remaining").text(formateTime(audio.duration - audio.currentTime));

    var progress = (audio.currentTime / audio.duration) * 100;
    $(".playbackBar  .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {

    var volume = audio.volume * 100;
    $(".volumeBar  .progress").css("width", volume + "%");
}

//playfirst song, 

function Audio() {

    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener("ended", function() {
        nextSong();
    });

    this.audio.addEventListener("canplay", function() {
        var duration = formateTime(this.duration);
        $(".progressTime.remaining ").text(duration);
    });

    this.audio.addEventListener("timeupdate", function() {
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });
    this.audio.addEventListener("volumechange", function() {
            updateVolumeProgressBar(this);
    });

    this.setTrack = function(track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    };

    this.play = function () {
        this.audio.play();
    };

    this.pause = function () {
        this.audio.pause();
    };

    this.setTime = function (seconds) {
        this.audio.currentTime = seconds; 
    };

}