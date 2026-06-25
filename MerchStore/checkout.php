<?php
include("header.php");
// get user id from session variable
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='signin.php' style='color: blue; text-decoration: none;'>sign in</a> or <a href='signup.php' style='color: blue; text-decoration: none;'>sign up</a> to view your cart.</p>";
    include("footer.php");
    exit;
}  
?>
    <h1>Checkout</h1>
    <p>Review your order and proceed to payment.</p>
    <?php
        // get user id from session variable
        $userId = $_SESSION['user_id'];
        // display all items in the cart with details and a link to checkout page
        include("connDB.php");
        $conn = connDB();
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }   
        $sql = "SELECT c.CartId, c.UserId, c.ItemId, m.ItemName, m.ItemDescription, m.ItemPrice, m.ItemCategory, m.ImageId 
                FROM Cart c 
                JOIN Merch m ON c.ItemId = m.ItemId 
                WHERE c.UserId = $userId;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='cart-container'>";
            while($row = mysqli_fetch_assoc($result)){
                $cartId = $row['CartId'];
                $itemId = $row['ItemId'];
                $itemName = $row['ItemName'];
                $description = $row['ItemDescription'];
                $price = $row['ItemPrice'];
                $itemCategory = $row['ItemCategory'];
                $imageId = $row['ImageId'];
                $sql = "SELECT ImageFolderPath, ImageFileName FROM Images WHERE ImageId = $imageId;";
                $imageResult=mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($imageResult)){
                    $imageFolderPath = $row['ImageFolderPath'];
                    $imageFileName = $row['ImageFileName'];
                }
                echo "<br><br>";
                echo "<hr>";

                echo "<div class='cart-item'>";
                echo "<h3>$itemName</h3>";
                echo "<p>$description</p>";
                echo "<p>Price: \$" . number_format($price, 2) . "</p>";
                echo "<p>Category: " . $itemCategory . "</p>";
                echo "<img class='merch-image' src='" . $imageFolderPath . $imageFileName ."' alt='" . $itemName . "' width='100' height='100'>";
                // add quantity of item in cart and total price for that item
                    $sqlQuantity = "SELECT Quantity FROM Cart WHERE CartId = $cartId;";
                    $resultQuantity = mysqli_query($conn, $sqlQuantity);
                    $quantity = 1;
                    if (mysqli_num_rows($resultQuantity) > 0) {
                        $rowQuantity = mysqli_fetch_assoc($resultQuantity);
                        $quantity = $rowQuantity['Quantity'];
                    }
                    echo "<p>Quantity: " . $quantity . "</p>";
                    echo "<p>Total: \$" . number_format($price * $quantity, 2) . "</p>";
                echo "</div>";  
            }
            echo "</div>";
        } else {
             echo "<p>Your cart is empty.</p>";  
         }   
         // calculate total price of items in cart
         $sqlTotal = "SELECT SUM(m.ItemPrice * c.Quantity) AS TotalPrice 
                      FROM Cart c 
                      JOIN Merch m ON c.ItemId = m.ItemId 
                      WHERE c.UserId = $userId;";
         $resultTotal = mysqli_query($conn, $sqlTotal);
         $rowTotal = mysqli_fetch_assoc($resultTotal);
         $totalPrice = $rowTotal['TotalPrice'];
         $shippingCost = 5.00;
         echo "<div class='container-fluid'>";
        // form to get user input for payment information and submit to process payment
        echo "<table class='table'><tr><th>Total Price</th><th>Shipping Cost</th><th>Grand Total</th></tr>";
        echo "<tr><td>\$" . number_format($totalPrice, 2) . "</td><td>\$$shippingCost</td><td>\$" . number_format($totalPrice + $shippingCost, 2) . "</td></tr>";
        echo "</table>";
        echo "<button class='btn btn-success'><a href='processPayment.php' style='color: white; text-decoration: none;'>Proceed to Checkout</a></button>";
        echo "</div>";
         mysqli_close($conn);
        ?>
        <?php include("footer.php"); ?>