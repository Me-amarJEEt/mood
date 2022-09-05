<?php 
 
 include("../../config.php");

 if (isset($_POST['playlistId'])) {

     $playlistId = $_POST['playlistId']; 

     $queryPlaylist = mysqli_query($con, "DELETE FROM playlist WHERE id = '$playlistId'");
     $querySongs = mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistId = '$playlistId'");

 }


?>