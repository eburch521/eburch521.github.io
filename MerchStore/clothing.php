<?php
include("header.php");
// display clothing items
?>
<h1>Clothing</h1>
<p>Check out our exclusive collection of clothing! Browse through our selection and add your favorite clothing items to your cart.</p>
<?php
include("connDB.php");
$conn = connDB();
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$sql = "SELECT * FROM Merch WHERE ItemCategory='Clothing';";
$result = mysqli_query($conn, $sql);
// display each item in a container with name, description, price, and category, 
// and a link to add to cart page with item id as parameter
echo "<div class='merch-container'>";
while ($row = mysqli_fetch_assoc($result)) {
    $itemId = $row['ItemId'];
    $userId = $_SESSION['user_id'] ?? null;
    $itemName = $row['ItemName'];
    $description = $row['ItemDescription'];
    $price = $row['ItemPrice'];
    $itemCategory = $row['ItemCategory'];
    $imageId = $row['ImageId'];

    $imageFolderPath = '';
    $imageFileName = '';
    // get image path from Images table using image id as foreign key
    if ($imageId !== null) {
        $imagePathStmt = $conn->prepare("SELECT ImageFolderPath, ImageFileName FROM Images WHERE ImageId = ?");
        $imagePathStmt->bind_param("i", $imageId);
        $imagePathStmt->execute();
        $imagePathResult = $imagePathStmt->get_result();

        if ($imageRow = $imagePathResult->fetch_assoc()) {
            $imageFolderPath = $imageRow['ImageFolderPath'];
            $imageFileName = $imageRow['ImageFileName'];
        }
        $imagePathStmt->close();
    }
    echo "<br><br>";
    echo "<div class='merch-item'>";
    echo "<h3>$itemName</h3>";
    echo "<p>$description</p>";
    echo "<p>Price: \$" . number_format($price, 2) . "</p>";
    echo "<p>Category: " . $itemCategory . "</p>";
    if (!empty($imageFileName)) {
        echo "<img class='merch-image' src='./" . htmlspecialchars($imageFolderPath . $imageFileName) . "' alt='" . htmlspecialchars($itemName) . "' style='width: 200px; height: 200px;'><br><br>";
    } else {
        echo "<p>No image available</p><br><br>";
    }
    // add to cart link adds item with itemId to cart that corresponds to the user with userId
    // pass itemId and userId as parameters to cart.php
    echo "<button class='btn btn-primary'><a href='addToCart.php?item_id=$itemId&user_id=$userId' style='color: white; text-decoration: none;'>Add to Cart</a></button>";
    echo "</div>";
}
echo "</div>";
mysqli_close($conn);
?>
<?php include("footer.php"); ?>