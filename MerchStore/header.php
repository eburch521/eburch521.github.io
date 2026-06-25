<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cartCount = 0;
$userId = $_SESSION['user_id'] ?? null;
$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

if ($userId) {
    include("connDB.php");
    $conn = connDB();
    if (!$conn->connect_error) {
        //change header options to prevent caching so that cart count updates immediately after login
        header("Cache-Control: no-cache, no-store, must-revalidate");
        // Get cart count for logged in user
        $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM Cart WHERE UserId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $cartCount = $row['cnt'] ?? 0;
        $stmt->close();
        
        // If user is admin, set a session variable to indicate that they have admin privileges
        $stmt = $conn->prepare("SELECT isAdmin FROM users WHERE UserId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $_SESSION['is_admin'] = $row['isAdmin'] ?? false;
        // Admins will see inventory management options in the navbar, while regular users will not
        //store admin status in session variable so it can be checked on each page load to conditionally show/hide admin features
        $isAdmin = $_SESSION['is_admin'] ?? false;
        $stmt->close();
        $conn->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content=" width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="bookicon.png">
    <title>Merch Store</title>
    </head>

    <body class="d-flex flex-column min-vh-100">
        <!--navbar-->
<nav class="navbar navbar-expand-lg navbar-white fixed-top" style="background-color: #434343">
    
        <a id="home-header" class="navbar-brand text-white" href="home.php">Merch Store</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php 
                       
                        if ($loggedIn): ?>
                            <li class="nav-item">
                                <span class="nav-link text-white">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="signin.php">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="signup.php">Sign Up</a>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="cart.php?user_id=<?php echo $userId; ?>">My Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="search.php">Search</a>
                        </li>
                        <?php if ($loggedIn): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="logout.php">Logout</a>
                            </li>
                        <?php endif; ?>
                        <?php 
                        // code to conditionally show inventory management link in navbar if user is admin
                        // get user id from session variable and check if user is admin by querying database for isAdmin field in users table for that user id, then set a session variable to indicate whether user is admin or not, and use that session variable to conditionally show/hide inventory management link in navbar

                        if ($isAdmin): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="inventory.php">Inventory Management</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <br>
                    <ul class="navbar-nav ms-auto text-white">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="albums.php">Albums</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="clothing.php">Clothing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="posters.php">Posters</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="accessories.php">Accessories</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

