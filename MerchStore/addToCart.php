<?php
// adds item to users cart and links to cart.php to display cart contents
include("header.php");
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo "<p>Please <a href='signin.php' style='color: blue; text-decoration: none;'>sign in</a> or <a href='signup.php' style='color: blue; text-decoration: none;'>sign up</a> to add items to your cart.</p>";
    include("footer.php");
    exit;
}

if (!isset($_GET['item_id'])) {
    echo "<p>No item specified.</p>";
    include("footer.php");
    exit;
}

$itemId = (int)$_GET['item_id'];

// connect
include("connDB.php");
$conn = connDB();
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// ensure user exists (prevents FK error)
$stmt = $conn->prepare("SELECT 1 FROM users WHERE UserId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    $conn->close();
    echo "<p>Invalid user. Please log in again.</p>";
    include("footer.php");
    exit;
}
$stmt->close();

// if item already in cart, increment quantity; otherwise insert
$stmt = $conn->prepare("SELECT CartId, Quantity FROM Cart WHERE UserId = ? AND ItemId = ?");
$stmt->bind_param("ii", $userId, $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $cartId = $row['CartId'];
    $newQty = $row['Quantity'] + 1;
    $stmt->close();
    $upd = $conn->prepare("UPDATE Cart SET Quantity = ? WHERE CartId = ?");
    $upd->bind_param("ii", $newQty, $cartId);
    $upd->execute();
    $upd->close();
    echo "<p>Added another of item ID $itemId to cart (now $newQty).</p>";
} else {
    $stmt->close();
    $ins = $conn->prepare("INSERT INTO Cart (UserId, ItemId, Quantity) VALUES (?, ?, 1)");
    $ins->bind_param("ii", $userId, $itemId);
    if ($ins->execute()) {
        echo "<p>Item with ID $itemId added to cart.</p>";
    } else {
        echo "<p>Error adding item to cart.</p>";
    }
    $ins->close();
}
/*
// display cart items
$show = $conn->prepare(
    "SELECT c.CartId, c.UserId, c.ItemId, c.Quantity, m.ItemName, m.ItemDescription, m.ItemPrice, m.ItemCategory, m.ImageURL
     FROM Cart c
     JOIN Merch m ON c.ItemId = m.ItemId
     WHERE c.UserId = ?"
);
$show->bind_param("i", $userId);
$show->execute();
$res = $show->get_result();

if ($res->num_rows > 0) {
    echo "<h1>Your Cart</h1><div class='cart-container'>";
    while ($r = $res->fetch_assoc()) {
        $cartId = $r['CartId'];
        $itemName = htmlspecialchars($r['ItemName']);
        $description = htmlspecialchars($r['ItemDescription']);
        $price = $r['ItemPrice'];
        $quantity = $r['Quantity'];
        $itemCategory = htmlspecialchars($r['ItemCategory']);
        $img = htmlspecialchars($r['ImageURL']);

        echo "<div class='cart-item'><h3>{$itemName}</h3>";
        echo "<p>{$description}</p>";
        echo "<p>Price: \$" . number_format($price, 2) . "</p>";
        echo "<p>Category: {$itemCategory}</p>";
        echo "<img class='cart-image' src='images/{$img}' alt='{$itemName}' width='100'>";
        echo "<p>Quantity: {$quantity}</p>";
        echo "<p>Total: \$" . number_format($price * $quantity, 2) . "</p>";
        echo "<button class='btn btn-danger'><a href='removeFromCart.php?cart_id={$cartId}' style='color:white;text-decoration:none;'>Remove from Cart</a></button>";
        echo "</div><hr>";
    }
    echo "</div>";
    //redirect to cart page after adding item to cart
    //header("Location: cart.php");
    exit;
} else {
    echo "<p>Your cart is empty.</p>";
}

$show->close();
$conn->close();*/
//redirect to cart page after adding item to cart
header("Location: cart.php");
?>
<?php
include("footer.php");
?>