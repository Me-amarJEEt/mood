<?php include("includes/includedFile.php");
      

if (isset($_GET['id'])) {
    $albumId = $_GET['id'];
} else {
    header("Location: index.php");
}

$album = new Album($con, $albumId);
$artist = $album->getArtist();

?>

<div class="entityInfo">

    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>" alt="Album Image">
    </div>

    <div class="rightSection">
        <h2> <?php echo $album->getTitle(); ?> </h2>
        <p>By <?php echo $artist->getName(); ?> </p>
        <p><?php echo $album->getNumberOfSong(); ?> songs </p>
    </div>
</div>


<div class="traclistContainer">
    <ul class="trackList">

        <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId) {
                
                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li class='trackListRow'> 
                    <div class='trackCount'>
                         <i class='bi bi-play-fill' onclick='setTrack(\"" .$albumSong->getId() ."\", tempPlaylist, true )'></i>
                         <span class='trackNumber'>$i</span>
                    </div>

                    <div class='trackInfo'>
                        <span class='trackName'>" .$albumSong->getTitle().  "</span>
                        <span class='trackArtistName'>" .$albumArtist->getName().  "</span>
                    </div>

                    <div class='tracOption'>
                    <input type='hidden' class='songId' value='"  .$albumSong->getId() .  "'>
                        <i class='bi bi-three-dots' onclick='showOptionMenu(this)'></i>  
                    </div>

                    <div class='trackDuration'>
                        <span class='duration'>" .$albumSong->getDuration(). "</span>
                    </div>

                </li>";
                $i++;
            }
         ?>

         <script>
             var tempsongId = '<?php echo json_encode($songIdArray); ?>';
             tempPlaylist = JSON.parse(tempsongId);
         </script>

    </ul>
</div>


<nav class="optionMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
    <div class="item">Share on whatsApp</div>
    <div class="item">Add to dowload</div>
</nav>