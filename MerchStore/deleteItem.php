<?php
    //delete inventory item from the Merch table
    include("header.php");

// add code to confirm deletion

    if (isset($_GET['ItemID'])) {
        $itemId = (int)$_GET['ItemID'];
        include("connDB.php");  
        $conn = connDB();
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
    // get ImageId    
    $sql = $conn->prepare("SELECT ImageId FROM Merch WHERE ItemId=?");
    $sql->bind_param("i", $itemId);
    $sql->execute();
    $result = $sql->get_result();
    if($imageRow = $result->fetch_assoc()){
        $imageId = $imageRow['ImageId'];
    }
    $sql->close();
    // get filepath
    $filePath = '';
    $sql = $conn->prepare("SELECT * FROM Images WHERE ImageId=?");
    $sql->bind_param("i", $imageId);
    $sql->execute();
    $result = $sql->get_result();
    while($imageRow = $result->fetch_assoc()){
        $filePath = $imageRow['ImageFolderPath'] . $imageRow['ImageFileName'];
    }
   
    if(unlink($filePath)){
        echo "<p>File deleted.</p>";
    } else {
        echo "<p>Error deleting file.</p>";
    } 


    // Remove child rows first
    $stmt = $conn->prepare("DELETE FROM Images WHERE ItemId = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute(); 
    $stmt->close();
    // Remove parent row
    $stmt = $conn->prepare("DELETE FROM Merch WHERE ItemId=?");
    $stmt->bind_param("i", $itemId);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting item: " . htmlspecialchars($stmt->error) . "</div>";
    }
        $stmt->close();
        $conn->close();
    } else {
    echo "<div class='alert alert-warning'>No item ID specified.</div>";
}
echo "<p><a href='inventory.php' class='btn btn-primary'>Back to Inventory</a></p>";
include("footer.php");
?>