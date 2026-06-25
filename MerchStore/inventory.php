<?php
// backend interface for adding items to cart, displaying cart contents, and removing items from cart
//and managing available inventory
include("header.php");
?>
    <h1>Inventory Management</h1>
    <p>As an admin, you can manage the inventory of items available in the store. You can add new items, update existing items, and remove items from the inventory.</p>
    
    <!-- display all available inventory items in a table with options to edit or delete each item -->
    <div class="container-fluid">
    <?php
        include("connDB.php");
        $conn = connDB();
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $sql = "SELECT ItemId, ItemName, ItemDescription, ItemPrice, ItemCategory, Quantity, ImageId FROM Merch;";
        $result = mysqli_query($conn, $sql);
       if (!$result) {
            echo "<div class='alert alert-danger'>Query failed: " . htmlspecialchars($conn->error) . "</div>";
        } elseif ($result->num_rows === 0) {
            echo "<p>No inventory items found.</p>";
        } else {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Item ID</th><th>Name</th><th>Description</th><th>Price</th><th>Category</th><th>Quantity</th><th>Image URL</th><th>Actions</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                $itemId = htmlspecialchars($row["ItemId"] ?? '');
                $imageId = $row["ImageId"] ?? '';
                $imageUrl = '';
                if ($imageId) {
                    $imageResult = mysqli_query($conn, "SELECT ImageFolderPath, ImageFileName FROM Images WHERE ImageId = $imageId");
                    if ($imageResult && $imageRow = $imageResult->fetch_assoc()) {
                        $imageUrl = htmlspecialchars($imageRow["ImageFolderPath"] ?? '') . htmlspecialchars($imageRow["ImageFileName"] ?? '');
                    }
                }
                echo "<tr>";
                echo "<td>" . htmlspecialchars($itemId) . "</td>";
                echo "<td>" . htmlspecialchars($row["ItemName"] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row["ItemDescription"] ?? '') . "</td>";
                echo "<td>$" . htmlspecialchars($row["ItemPrice"] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row["ItemCategory"] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row["Quantity"] ?? '') . "</td>";
                echo "<td>" . $imageUrl . "</td>";
                echo "<td><a href='editItem.php?ItemID=" . $itemId . "' class='btn btn-sm btn-outline-primary'>Edit</a> <a href='deleteItem.php?ItemID=" . $itemId . "' class='btn btn-sm btn-outline-danger'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }

        mysqli_close($conn); ?>   
        <p><a href="addItem.php" class="btn btn-primary">Add New Item</a></p>
        </div>      
   <?php include("footer.php"); ?>