
<?php include("header.php");

//update inventory item details in the Merch table;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemId = (int)$_POST['ItemId'];
    $itemName = $_POST['ItemName'] ?? '';
    $itemDescription = $_POST['ItemDescription'] ?? '';
    $itemPrice = (float)($_POST['ItemPrice'] ?? 0);
    $itemCategory = $_POST['ItemCategory'] ?? '';
    $quantity = (int)($_POST['Quantity'] ?? 0);
    $imageId = (int)($_POST['ImageId'] ?? 0);
    
    include("connDB.php");
    $conn = connDB();
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    // echo "<p>" . $itemId . "</p>";

    // If no ImageId provided, get it from the database
    if ($imageId == 0) {
        $getImageStmt = $conn->prepare("SELECT ImageId FROM Merch WHERE ItemId=?");
        $getImageStmt->bind_param("i", $itemId);
        $getImageStmt->execute();
        $getImageResult = $getImageStmt->get_result();
        $getImageRow = $getImageResult->fetch_assoc();
        $imageId = (int)$getImageRow['ImageId'];
        
        echo "<p>" . $imageId . "</p>";
        $getImageStmt->close();
    }
    
   // Handle file upload - optional
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/MerchStore/Images/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($_FILES['fileToUpload']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, and GIF are allowed.</div>";
            $conn->close();
            include("footer.php");
            exit;
        }
        
        // Rename file to avoid conflicts
        $newFileName = uniqid() . '.' . $fileType;
        $uploadPath = $uploadDir . $newFileName;
        
         if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadPath)) {
            $imageFolderPath = "Images/";  

         // update image record into database
            $updateImageStmt = $conn->prepare("UPDATE Images SET ImageFolderPath=?,ImageFileName=? WHERE ImageId=?");
            $updateImageStmt->bind_param("ssi", $imageFolderPath, $newFileName, $imageId);
            
          if (!$updateImageStmt->execute()) {
                echo "<div class='alert alert-danger'>Error saving image to database: " . htmlspecialchars($updateImageStmt->error) . "</div>";
            }
            $updateImageStmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error uploading file.</div>";
            $conn->close();
            include("footer.php");
            exit;
        }
    }
    
    // Update the Merch item
    $stmt = $conn->prepare("UPDATE Merch SET ItemName=?, ItemDescription=?, ItemPrice=?, ItemCategory=?, ImageId=?, Quantity=? WHERE ItemId=?");
    $stmt->bind_param("ssdsiii", $itemName, $itemDescription, $itemPrice, $itemCategory, $imageId, $quantity, $itemId);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating item: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert alert-warning'>Invalid request method.</div>";
}
// redirect back to inventory page after updating item
echo "<p class='mt-3'><a href='inventory.php' class='btn btn-primary'>Back to Inventory</a></p>";

include("footer.php"); ?>