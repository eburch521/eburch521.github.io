<?php
//add new inventory item to the Merch table
include("header.php");
// establish database connection
include("connDB.php");
$conn = connDB();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<h1>Add New Item</h1>
<form action="insertItem.php" method="post" enctype="multipart/form-data">
    <div class="md-3">
        <label for="ItemName" class="form-label">Name</label>
        <input type="text" class="form-control" id="ItemName" name="ItemName" required>
    </div>
    <div class="md-3">
        <label for="ItemDescription" class="form-label">Description</label>
        <textarea class="form-control" id="ItemDescription" name="ItemDescription" rows="3" required></textarea>
    </div>
    <div class="md-3">
        <label for="ItemPrice" class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control" id="ItemPrice" name="ItemPrice" required>
    </div>
    <div class="md-3">
        <label for="Quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control" id="Quantity" name="Quantity" required>
    </div>
    <div class="md-3">
        <label for="ItemCategory" class="form-label">Category</label>
        <select class="form-select" id="ItemCategory" name="ItemCategory" required>
            <option value="">Select a category</option>
            <option value="Albums">Albums</option>
            <option value="Poster">Poster</option>
            <option value="Accessories">Accessories</option>
            <option value="Clothing">Clothing</option>
        </select>
    </div>
     <div class="md-3">
        <label for="fileToUpload" class="form-label">Image</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Item</button>
    <p><a href='inventory.php' class='btn btn-primary'>Back to Inventory</a></p>
</form>

<?php include("footer.php"); ?>