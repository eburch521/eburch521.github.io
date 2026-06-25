<h1>Database Contents</h1>
<p>This page displays all items in the database for testing purposes.</p>
<?php
include("connDB.php");
$conn = connDB();
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$sql = "SELECT * FROM Merch;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $itemId = $row['ItemId'];
    $itemName = $row['ItemName'];
    $description = $row['ItemDescription'];
    $price = $row['ItemPrice'];
    $itemCategory = $row['ItemCategory'];
    echo "<br><br>";
    echo "<hr>";

    echo "<div class='merch-item'>";
    echo "<h3>$itemName</h3>";
    echo "<p>$description</p>";
    echo "<p>Price: \$" . number_format($price, 2) . "</p>";
    echo "<p>Category: " . $itemCategory . "</p>";
    echo "</div>";
}
// display users and info
$sql = "SELECT * FROM Users;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $userId = $row['UserId'];
    $email = $row['Email'];
    $password = $row['Password'];
    echo "<br><br>";
    echo "<hr>";

    echo "<div class='user-info'>";
    echo "<h3>User ID: $userId</h3>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";
    echo "</div>";
}
// display cart items
$sql = "SELECT * FROM Cart;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $cartId = $row['CartId'];
    $userId = $row['UserId'];
    $itemId = $row['ItemId'];
    echo "<br><br>";
    echo "<hr>";

    echo "<div class='cart-item'>";
    echo "<h3>Cart ID: $cartId</h3>";
    echo "<p>User ID: $userId</p>";
    echo "<p>Item ID: $itemId</p>";
    echo "</div>";
}
//display images
$sql = "SELECT * FROM Images;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $imageFolderPath = $row['ImageFolderPath'];
    $imageFileName = $row['ImageFileName'];

    echo "<br><br>";
    echo "<hr>";
    echo "<div class='image-container'>";
    echo "<img class='merch-image' src='" . $row['ImageFolderPath'] . $row['ImageFileName'] . "' alt='" . $itemName . "' style='width: 200px; height: 200px;'><br><br>";
    echo "</div>";
}
mysqli_close($conn);
?>