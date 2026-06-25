<?php
// add shopping cart functionality to the website
include("header.php");
?>
    <h1>Your Cart</h1>
    <p>Here you can view the items in your cart and proceed to checkout.</p>
    <?php
    // get user id from session variable
   if (isset($_SESSION['user_id'])) {       
    $userId = $_SESSION['user_id'];
    } else {
        echo "<p>Please <a href='signin.php' style='color: blue; text-decoration: none;'>sign in</a> or <a href='signup.php' style='color: blue; text-decoration: none;'>sign up</a> to view your cart.</p>";
        include("footer.php");
        exit;
    }
        // display all items in the cart with details and a link to checkout page
        include("connDB.php");
        $conn = connDB();

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }   
        
        $stmt = $conn->prepare("SELECT c.CartId, c.UserId, c.ItemId, m.ItemName, m.ItemDescription, m.ItemPrice, m.ItemCategory, m.ImageId
                FROM Cart c 
                JOIN Merch m ON c.ItemId = m.ItemId 
                WHERE c.UserId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='cart-container'>";
            while($row = $result->fetch_assoc()){
                $cartId = $row['CartId'];
                $itemId = $row['ItemId'];
                $itemName = $row['ItemName'];
                $description = $row['ItemDescription'];
                $price = $row['ItemPrice'];
                $itemCategory = $row['ItemCategory'];
                $imageId = $row['ImageId'];
                
                // Initialize variables
                $imageFolderPath = '';
                $imageFileName = '';
                
                // Get image only if ImageId exists
                if ($imageId !== null) {
                    $imageStmt = $conn->prepare("SELECT ImageFolderPath, ImageFileName FROM Images WHERE ImageId = ?");
                    $imageStmt->bind_param("i", $imageId);
                    $imageStmt->execute();
                    $imageResult = $imageStmt->get_result();
                    
                    if ($imageRow = $imageResult->fetch_assoc()) {
                        $imageFolderPath = $imageRow['ImageFolderPath'];
                        $imageFileName = $imageRow['ImageFileName'];
                    }
                    $imageStmt->close();
                }
                
                echo "<br><br>";
                echo "<hr>";
                echo "<div class='cart-item'>";
                echo "<h3>" . htmlspecialchars($itemName) . "</h3>";
                echo "<p>" . htmlspecialchars($description) . "</p>";
                echo "<p>Price: \$" . number_format($price, 2) . "</p>";
                echo "<p>Category: " . htmlspecialchars($itemCategory) . "</p>";
                
                if (!empty($imageFileName)) {
                    echo "<img class='cart-image' src='" . htmlspecialchars($imageFolderPath . $imageFileName) . "' alt='" . htmlspecialchars($itemName) . "' width='100'>";
                } else {
                    echo "<p>No image available</p>";
                }
                
                // Get quantity
                $quantityStmt = $conn->prepare("SELECT Quantity FROM Cart WHERE CartId = ?");
                $quantityStmt->bind_param("i", $cartId);
                $quantityStmt->execute();
                $quantityResult = $quantityStmt->get_result();
                $quantity = 1;
                
                if ($quantityResult->num_rows > 0) {
                    $rowQuantity = $quantityResult->fetch_assoc();
                    $quantity = $rowQuantity['Quantity'];
                }
                $quantityStmt->close();
                
                // Handle quantity updates
                if(isset($_GET['update_cart_id']) && $_GET['update_cart_id'] == $cartId) {
                    if(isset($_GET['action']) && $_GET['action'] == 'increment') {
                        $quantity++;
                    } elseif(isset($_GET['action']) && $_GET['action'] == 'decrement' && $quantity > 1) {
                        $quantity--;
                    }
                    $updateStmt = $conn->prepare("UPDATE Cart SET Quantity = ? WHERE CartId = ?");
                    $updateStmt->bind_param("ii", $quantity, $cartId);
                    $updateStmt->execute();
                    $updateStmt->close();
                    header("Location: cart.php");
                    exit;
                }
                
                echo "<div class='quantity-container'>";
                echo "<p>Quantity: <button class='btn btn-secondary'><a href='cart.php?update_cart_id=$cartId&action=decrement' style='color: white; text-decoration: none;'>-</a></button> " . $quantity . " <button class='btn btn-secondary'><a href='cart.php?update_cart_id=$cartId&action=increment' style='color: white; text-decoration: none;'>+</a></button></p>";    
                echo "</div>";
                echo "<p>Total: \$" . number_format($price * $quantity, 2) . "</p>";
                echo "<button class='btn btn-danger'><a href='removeFromCart.php?cart_id=$cartId' style='color: white; text-decoration: none;'>Remove from Cart</a></button>";    
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        
        // Calculate totals
        $totalStmt = $conn->prepare("SELECT SUM(m.ItemPrice * c.Quantity) AS TotalPrice 
                    FROM Cart c 
                    JOIN Merch m ON c.ItemId = m.ItemId 
                    WHERE c.UserId = ?");
        $totalStmt->bind_param("i", $userId);
        $totalStmt->execute();
        $resultTotal = $totalStmt->get_result();
        $rowTotal = $resultTotal->fetch_assoc();
        $totalPrice = $rowTotal['TotalPrice'] ?? 0;
        $shippingCost = 5.00;
        
        $totalStmt->close();
        
        echo "<div class='container-fluid'>";
        echo "<table class='table'><tr><th>Total Price</th><th>Shipping Cost</th><th>Grand Total</th></tr>";
        echo "<tr><td>\$" . number_format($totalPrice, 2) . "</td><td>\$$shippingCost</td><td>\$" . number_format($totalPrice + $shippingCost, 2) . "</td></tr>";
        echo "</table>";

        echo "<button class='btn btn-success'><a href='checkout.php' style='color: white; text-decoration: none;'>Proceed to Checkout</a></button>";
        echo "</div>";

        mysqli_close($conn);
    ?>
    <?php include("footer.php"); ?>