<?php include("header.php"); ?>
<!-- add and remove images from the images table -->
    <h1>Image Test</h1>
    <p>This page is for testing image display for merch items. Check the albums, accessories, and clothing pages to see if images are displayed correctly.</p>
     <?php
        include("connDB.php");
        $conn = connDB();
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $sql = "SELECT * FROM Images;";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $imageId = $row['ImageId'];
            $itemId = $row['ItemId'];
            $imageFolderPath = $row['ImageFolderPath'];
            $imageFileName = $row['ImageFileName'];
            echo "<p>Image ID: " . $imageId . "</p>";
            echo "<p>Item ID: " . $itemId . "</p>";
            echo "<p>Image Folder Path: " . $imageFolderPath . "</p>";
            echo "<p>Image File Name: " . $imageFileName . "</p>";
            echo "<img src='" . $imageFolderPath . "/" . $imageFileName . "' alt='Image for Item ID " . $itemId . "' width='100'>";
        }
        mysqli_close($conn);
     ?> 
    
<?php include("footer.php"); ?>