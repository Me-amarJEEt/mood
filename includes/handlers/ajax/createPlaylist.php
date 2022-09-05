<?php 
include("../../config.php");

if(isset($_POST['name']) && isset($_POST['username'])) {
    
    $name = $_POST['name'];
    $username = $_POST['username'];
    $date = date("Y-m-d");

    $query = mysqli_query($con, "INSERT INTO playlist (name, owner, dateCreated) VALUES('$name', '$username', '$date')");

}
else {
    echo "Invali Playlist Name!";
}
?>