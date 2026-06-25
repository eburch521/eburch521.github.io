<?php
// remove item from cart

include("header.php");
?>
    <h1>Your Cart</h1>
    <p>Here you can view the items in your cart and proceed to checkout.</p>
    <?php
        // get cart id from url parameter
        if (isset($_GET['cart_id'])) {
            $cartId = $_GET['cart_id'];
            include("connDB.php");
            $conn = connDB();
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }
            // delete item from cart table where cart id matches the cart id from the url parameter
            $sql = "DELETE FROM Cart WHERE CartId = $cartId;";
            if (mysqli_query($conn, $sql)) {
                echo "<p>Item removed from cart.</p>";
            } else {
                echo "<p>Error removing item from cart: " . mysqli_error($conn) . "</p>";
            }
        }
        /*
        // display all items in the cart with details and a link to checkout page
        // get user id from session variable
        $userId = $_SESSION['user_id'];
        $sql = "SELECT c.CartId, c.UserId, c.ItemId, m.ItemName, m.ItemDescription, m.ItemPrice, m.ItemCategory, m.ImageURL 
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
                echo "<br><br>";
                echo "<hr>";

                echo "<div class='cart-item'>";
                echo "<h3>$itemName</h3>";
                echo "<p>$description</p>";
                echo "<p>Price: \$" . number_format($price, 2) . "</p>";
                echo "<p>Category: " . $itemCategory . "</p>";
                echo "<img class='cart-image' src='" . "images/" . $row['ImageURL'] . "' alt='" . $itemName . "' width='100'>";
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
                    
                // add a link to remove item from cart that passes cartId as parameter to removeFromCart.php
                echo "<button class='btn btn-danger'><a href='removeFromCart.php?cart_id=$cartId' style='color: white; text-decoration: none;'>Remove from Cart</a></button>";    
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Your cart is empty.</p>";  
        }   
        mysqli_close($conn);
        */
        //redirect to cart page after removing item from cart
        header("Location: cart.php");
        exit;

        ?>
    <?php include("footer.php"); ?>