<?php include("includes/includedFile.php");
      

if (isset($_GET['id'])) {
    $playlistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());
?>

<div class="entityInfo">

    <div class="leftSection">
        <div class='playlistImage'>
                <i class='bi bi-music-note-list'> </i>
                </div>
        </div>

    <div class="rightSection">
        <h2> <?php echo $playlist->getName(); ?> </h2>
        <p>By <?php echo $playlist->getOwner(); ?> </p>
        <p> <?php echo $playlist->getNumberOfSong(); ?> songs </p>
        <button class="button playlistButton" onclick="deletePlaylist('<?php echo $playlistId; ?>')" >Delete Playlist</button>
    </div>
</div>


<div class="traclistContainer">
    <ul class="trackList">

        <?php
            $songIdArray = $playlist->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId) {
                
                $playlistSong = new Song($con, $songId);
                $playlistArtist = $playlistSong->getArtist();

                echo "<li class='trackListRow'> 
                    <div class='trackCount'>
                         <i class='bi bi-play-fill' onclick='setTrack(\"" .$playlistSong->getId() ."\", tempPlaylist, true )'></i>
                         <span class='trackNumber'>$i</span>
                    </div>

                    <div class='trackInfo'>
                        <span class='trackName'>" .$playlistSong->getTitle().  "</span>
                        <span class='trackArtistName'>" .$playlistArtist->getName().  "</span>
                    </div>

                    <div class='tracOption'>
                        <i class='bi bi-three-dots'></i>  
                    </div>

                    <div class='trackDuration'>
                        <span class='duration'>" .$playlistSong->getDuration(). "</span>
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
