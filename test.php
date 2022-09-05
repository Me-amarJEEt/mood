<?php
include("includes/config.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");

// session_destroy(); :LOGOUT

if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
    echo "<script> userLoggedIn = '$userLoggedIn' ; </script>";
} else {
    header("Location: register.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
    <title>Mood</title>
</head>

<body>
    <!--Main Container------------------->
    <div id="mainContainer">
        <!--Top Container------------------->
        <div id="topContainer">
            <div id="navBarContainer">
                    <nav class="navBar">
                        <span onclick="openPage('index.php')" class="logo">
                            <i class="bi bi-boombox-fill"></i> MOOD
                        </span>

                        <div class="group">
                            <div class="navItem">
                            <span onclick='openPage("search.php")' class="navItemLink"> 
                                    search
                                    <img src="assets/images/icons/searchr.png" class="icon" alt="search">
                            </span>
                            </div>
                        </div>

                        <div class="group">
                            <div class="navItem">
                            <span onclick="openPage('yourPlaylist.php')" class="navItemLink">Your Playlist</span>
                            </div>
                        </div>

                    </nav>
            </div><!--navBar Left-->
            
            <div id="mainViewContainer"><!--MainView Container------------------->
                <div id="mainContent">


                    <?php 
                        include("includes/includedFile.php") ;
                    ?>

                    <h1 class="pageHeadingBig">Song Album</h1>

                        <div class="gridViewContainer">
                            <?php 
                                $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY rand() LIMIT 10");
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


                        
                </div>
            </div>
        </div><!--Top Container END here-->
    </div><!--Main Container END here-->
</body>
</html>
