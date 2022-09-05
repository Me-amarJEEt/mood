<?php 

include("../../config.php");

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {

    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    $orderIdQuery = mysqli_query($con, "SELECT MAX(playlistOrder) + 1 as playlistOrder FROM playlistSongs WHERE playlistId = '$playlistId' ");
    $row = mysqli_fetch_array($orderIdQuery);
    $order = $row['playlistOrder'];


    // if($order = "NULL") {
    //     $order = rand();
    //     $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES ('$songId', '$playlistId', '$order') ");
    // }
    // else {
    //     $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES ('$songId', '$playlistId', '$order') ");
    // }


    $order = rand();

   $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES ('$songId', '$playlistId', '$order') ");
    

}
else {
     echo "Error in aadToPlaylist data not pass onto table";
}
?>