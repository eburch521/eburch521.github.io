<?php
//functionality to edit item in inventory
include("header.php");
if (!isset($_GET['ItemID'])) {
    echo "<p>No item specified.</p>";
    include("footer.php");
    exit;
}
$itemId = (int)$_GET['ItemID'];
include("connDB.php");
$conn = connDB();
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT ItemName, ItemDescription, ItemPrice, ItemCategory, Quantity, ImageId FROM Merch WHERE ItemId = ?");
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<p>Item not found.</p>";
    $stmt->close();
    $conn->close();
    include("footer.php");
    exit;
}
$item = $result->fetch_assoc();
$stmt->close();

// Display image if it exists
$imageId = $item['ImageId'];
if ($imageId !== null) {
    $imageStmt = $conn->prepare("SELECT ImageFolderPath, ImageFileName FROM Images WHERE ImageId = ?");
    $imageStmt->bind_param("i", $imageId);
    $imageStmt->execute();
    $imageResult = $imageStmt->get_result();
    
    if ($imageRow = $imageResult->fetch_assoc()) {
        echo "<p>Current Image: <img src='./" . htmlspecialchars($imageRow['ImageFolderPath'] . $imageRow['ImageFileName']) . "' alt='Image for Item ID " . htmlspecialchars($itemId) . "' style='width: 100px; height: 100px;'></p>";
    }
    $imageStmt->close();
}
$conn->close();
?>
    <h1>Edit Item</h1>
    <form class="mb-3" action="updateItem.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ItemId" value="<?php echo htmlspecialchars($itemId); ?>">
        <input type="hidden" name="ImageId" value="<?php echo htmlspecialchars($item['ImageId'] ?? ''); ?>">
        <div class="mb-3">
            <label for="ItemName" class="form-label">Name</label>
            <input type="text" class="form-control" id="ItemName" name="ItemName" value="<?php echo htmlspecialchars($item['ItemName'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="ItemDescription" class="form-label">Description</label>
            <textarea class="form-control" id="ItemDescription" name="ItemDescription" rows="3" required><?php echo htmlspecialchars($item['ItemDescription'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="ItemPrice" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="ItemPrice" name="ItemPrice" value="<?php echo htmlspecialchars($item['ItemPrice'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="Quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="Quantity" name="Quantity" value="<?php echo htmlspecialchars($item['Quantity'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="ItemCategory" class="form-label">Category</label>
            <select class="form-select" id="ItemCategory" name="ItemCategory" required>
                <?php
                $categories = ['Albums', 'Poster', 'Accessories', 'Clothing'];
                foreach ($categories as $category) {
                    $selected = ($item['ItemCategory'] === $category) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($category) . "' " . $selected . ">" . htmlspecialchars($category) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fileToUpload" class="form-label">Image</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>

        <button type="submit" class="btn btn-primary mb-3">Update Item</button>
        <button action="inventory.php" class="btn btn-primary mb-3">Back to Inventory</button>
    </form>

    <?php include("footer.php"); ?>