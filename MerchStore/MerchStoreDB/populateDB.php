<?php
// Populate the Merch table with sample data
include("connDB.php");
$conn = connDB();
$populateQuery = "INSERT INTO Merch (ItemName, ItemDescription, ItemPrice, ItemCategory, ImageId, Quantity)
VALUES ('T-Shirt', 'A comfortable cotton t-shirt with our logo.', 19.99, 'Clothing', 1, 10),
       ('Hoodie', 'A cozy hoodie perfect for cooler weather.', 39.99, 'Clothing', 2, 5),
       ('Mug', 'A ceramic mug with our logo, great for coffee or tea.', 14.99, 'Accessories', 3, 15),
       ('Sticker Pack', 'A pack of 10 high-quality stickers featuring our designs.', 9.99, 'Accessories', 4, 20),
       ('Baseball Cap', 'A stylish cap with an adjustable strap and our logo.', 24.99, 'Clothing', 5, 8);";
if (mysqli_query($conn, $populateQuery)){
    echo "<p>Merch table populated with sample data</p>";   
} else {
    echo "<p>Error populating Merch table: " . mysqli_error($conn) . "</p>";
}

$populateUsersQuery = "INSERT INTO users (FirstName, LastName, Email, StreetAddress, City, State, ZipCode, Password, PaymentMethod, isAdmin)
VALUES ('John', 'Doe', 'john.doe@example.com', '123 Main St', 'Anytown', 'CA', '12345', 'password123', 'Credit Card', FALSE),
       ('Jane', 'Smith', 'jane.smith@example.com', '456 Oak Ave', 'Somewhere', 'NY', '67890', 'password456', 'PayPal', FALSE),
       ('Alice', 'Johnson', 'alice.johnson@example.com', '789 Pine Rd', 'Elsewhere', 'TX', '54321', 'password789', 'Credit Card', FALSE),
       ('Todd', 'Wait', 'todd.wait@example.com', '321 Elm St', 'Nowhere', 'FL', '09876', 'password000', 'Bank Transfer', TRUE); ";
if (mysqli_query($conn, $populateUsersQuery)){
    echo "<p>Users table populated with sample data</p>";
} else {
    echo "<p>Error populating Users table: " . mysqli_error($conn) . "</p>";
}
// add files from Images folder to Images table with item id as foreign key
$images = [
    ['ItemId' => 1, 'ImageFolderPath' => 'images/', 'ImageFileName' => 'tshirt.jpg'],
    ['ItemId' => 2, 'ImageFolderPath' => 'images/', 'ImageFileName' => 'hoodie.jpg'],
    ['ItemId' => 3, 'ImageFolderPath' => 'images/', 'ImageFileName' => 'mug.jpg'],
    ['ItemId' => 4, 'ImageFolderPath' => 'images/', 'ImageFileName' => 'stickers.jpg'],
    ['ItemId' => 5, 'ImageFolderPath' => 'images/', 'ImageFileName' => 'cap.jpg']
];
foreach ($images as $image) {
    $itemId = $image['ItemId'];
    $imageFolderPath = $image['ImageFolderPath'];
    $imageFileName = $image['ImageFileName'];
    // Read image file content and escape it for SQL insertion

    $insertImageStmt = mysqli_prepare($conn, "INSERT INTO Images (ItemId, ImageFolderPath, ImageFileName) VALUES (?, ?, ?)");

    if ($insertImageStmt) {
        mysqli_stmt_bind_param($insertImageStmt, 'iss', $itemId, $imageFolderPath, $imageFileName);
        if (!mysqli_stmt_execute($insertImageStmt)) {
            echo "<p>Error adding image for Item ID $itemId: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($insertImageStmt); 
    }
    // display images
    $imageResult = mysqli_query($conn, "SELECT ImageFolderPath, ImageFileName FROM Images WHERE ItemId = $itemId");
    if ($imageResult && $imageRow = mysqli_fetch_assoc($imageResult)) {
        echo "<p>Image for Item ID $itemId: <img src='../" . $imageRow['ImageFolderPath'] . $imageRow['ImageFileName'] . "' alt='Image for Item ID $itemId' style='width: 100px; height: 100px;'></p>";
    }
    
}
mysqli_close($conn);    
?>
