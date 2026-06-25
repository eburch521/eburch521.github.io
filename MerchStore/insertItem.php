<?php
// code to insert new inventory item into the Merch table
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST['ItemName'] ?? '';
    $itemDescription = $_POST['ItemDescription'] ?? '';
    $itemPrice = (float)($_POST['ItemPrice'] ?? 0);
    $itemCategory = $_POST['ItemCategory'] ?? '';
    $quantity = (int)($_POST['Quantity']);
    
    include("connDB.php");
    $conn = connDB();
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $imageId = null;
    $imageFolderPath = "Images/";
    
    // Handle file upload
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
            // Insert image record into database
            $insertImageStmt = $conn->prepare("INSERT INTO Images (ImageFolderPath, ImageFileName) VALUES (?, ?)");
            $insertImageStmt->bind_param("ss", $imageFolderPath, $newFileName);
            
            if ($insertImageStmt->execute()) {
                $imageId = $insertImageStmt->insert_id;
            } else {
                echo "<div class='alert alert-danger'>Error saving image to database: " . htmlspecialchars($insertImageStmt->error) . "</div>";
            }
            $insertImageStmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error uploading file.</div>";
            $conn->close();
            include("footer.php");
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>No file uploaded or file upload error.</div>";
        $conn->close();
        include("footer.php");
        exit;
    }
    
    // Insert item into Merch table
    $stmt = $conn->prepare("INSERT INTO Merch (ItemName, ItemDescription, ItemPrice, ItemCategory, Quantity, ImageId) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsii", $itemName, $itemDescription, $itemPrice, $itemCategory, $quantity, $imageId);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding item: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert alert-warning'>Invalid request method.</div>";
}
?>
<p><a href='inventory.php' class='btn btn-primary'>Back to Inventory</a></p>
<?php include("footer.php"); ?>