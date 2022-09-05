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
