<?php 
include("includes/includedFile.php");

if(isset($_GET['term'])) {
    $term = urldecode($_GET['term']);

}
else {
    $term ="";
}
?>

<div class="searchContainer">
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Search songs, albums.." onfocus="this.value = this.value">
</div>

<script>
    $(".searchInput").focus();

$(function() { 

    $(".searchInput").keyup(function() {
        clearTimeout(timer);

        timer = setTimeout(function() {
             var value = $(".searchInput").val();
             openPage("search.php?term=" + value);
        }, 1000);
    })
})

$(document).ready(function(){
		$(".searchInput").focus();
		var search = $(".searchInput").val();
		$(".searchInput").val('');
		$(".searchInput").val(search);
	})
    
</script>




<div class="traclistContainer">
    <h2>Songs</h2>
    <ul class="trackList">

        <?php

            $songsQuery =  mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 4");

            if (mysqli_num_rows($songsQuery) == 0) {
                    echo "<span class='noResult'> No songs found matching  <strong>"  .$term.  " </strong> </span>"; 
            }

            $songIdArray = array();

            $i = 1;
            while($row = mysqli_fetch_array($songsQuery)) {
                
                if($i > 15) {
                    break;
                } 

                array_push($songIdArray, $row['id']);

                $albumSong = new Song($con, $row['id']);
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
                        <i class='bi bi-three-dots'></i>  
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

<hr>

<div class="gridViewContainer">
    <h2>Albums</h2>
        <?php 
            $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 4");

            if (mysqli_num_rows($albumQuery) == 0) {
                echo "<span class='noResult'> No album found matching  <strong>"    .$term.    " </strong> </span>"; 
            }

            while ($row = mysqli_fetch_array($albumQuery)) {
                
                echo "<div class='gridViewItem'>
                <span onclick='openPage(\"album.php?id=".$row['id']."\")'> 
                            <img src='" .$row['artworkPath']. "'>

                            <div class='gridViewInfo'>" .$row['title']. "</div>
                        </span>
                    </div>";

            }
        ?>
    </div>
