<?php
include("includes/includedFile.php");
?>


<div class="playlistContainer">
    <div class="gridViewContainer">

        <h2>Your Playlist</h2>
        <div class="buttonItems">
            <button class="button playlistButton" onclick="createPlaylist()">New Playlist</button>
        </div>


        <?php

        $username = $userLoggedIn->getUsername();
        $playlistQuery = mysqli_query($con, "SELECT * FROM playlist WHERE owner='$username' ");

        if (mysqli_num_rows($playlistQuery) == 0) {
            echo "<span class='noResult'> You don't have Playlist yet! </span>";
        }

        while ($row = mysqli_fetch_array($playlistQuery)) {

            $playlist = new Playlist($con, $row);

            echo "<div class='gridViewItem' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>

                        <div class='playlistImage'>
                        <i class='bi bi-music-note-list'></i>
                        </div>

                        <div class='gridViewInfo'>" 
                            . $playlist->getName() . 
                        "</div>

                  </div>";
        }
        ?>






    </div>
</div>