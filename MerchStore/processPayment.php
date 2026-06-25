<?php
// collect payment info and process payment
include("header.php");
?>
<?php
// get user id from session variable
$userId = $_SESSION['user_id'];
if($userId) {
    // process payment and create order in database
    include("connDB.php");
    $conn = connDB();
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
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
    $grandTotal = $totalPrice + $shippingCost;
    $orderDate = date('Y-m-d H:i:s');
    $orderStatus = 'Processing';
    $items = ""; // Initialize items string
    // get items in cart to include in order
    $sqlItems = "SELECT m.ItemName, c.Quantity FROM Cart c JOIN Merch m ON c.ItemId = m.ItemId WHERE c.UserId = $userId;";
    $resultItems = mysqli_query($conn, $sqlItems);
    if (mysqli_num_rows($resultItems) > 0) {
        while($rowItems = mysqli_fetch_assoc($resultItems)) {
            $items .= $rowItems['ItemName'] . " (x" . $rowItems['Quantity'] . "), ";
        }
        $items = rtrim($items, ", "); // Remove trailing comma and space
    }

    // insert order into Orders table
    $stmt = $conn->prepare("INSERT INTO Orders (UserId, TotalPrice, ShippingCost, GrandTotal, OrderDate, OrderStatus, Items) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idddsss", $userId, $totalPrice, $shippingCost, $grandTotal, $orderDate, $orderStatus, $items);
    if ($stmt->execute()) {
        // mark cart items as ordered (could also delete from cart)
        $sqlUpdateCart = "DELETE FROM Cart WHERE UserId = $userId;";
        mysqli_query($conn, $sqlUpdateCart);
        echo "<p>Payment processed successfully! Your order has been placed.</p>";
    } else {
        echo "<p>Error processing payment: " . mysqli_error($conn) . "</p>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Please <a href='signin.php' style='color: blue; text-decoration: none;'>sign in</a> or <a href='signup.php' style='color: blue; text-decoration: none;'>sign up</a> to proceed to checkout.</p>";
    include("footer.php");
    exit;
    }


?>
    <h1>Processing Payment</h1>
    <p>Please wait while we process your payment...</p>
<?php
// redirect to order confirmation page after processing payment
header("Location: orderConfirmation.php");
exit;
?>
}