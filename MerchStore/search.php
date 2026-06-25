<?php
// create search page with for to search description, name, category of merch
include("header.php");
?>
        <h1>Search</h1>
        <form id="search-container m-3 " method="get">
            <label class="form-label">Search:</label>
            <input type="text" name="keyword">
            <input type="submit">
        </form>
        <?php
            include("connDB.php");
            $conn = connDB();
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $keyword = $conn->real_escape_string($keyword);
            if($keyword) {

                $sql = "SELECT * FROM Merch WHERE ItemName LIKE '%{$keyword}%' OR ItemDescription LIKE '%{$keyword}%' OR ItemCategory LIKE '%{$keyword}%';";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    echo "<h2>Search results for '$keyword':</h2>";
                    echo "<div class='merch-container'>";
            while($row = mysqli_fetch_assoc($result)) 
                {
                    //define userId for add to cart link
                    $userId = $_SESSION['user_id'] ?? null; 
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
                    echo "<img class='merch-image' src='" . "images/" . $row['ImageURL'] . "' alt='" . $itemName . "' width='100'>";
                     echo "<button class='btn btn-primary'><a href='addToCart.php?item_id=$itemId&user_id=$userId' style='color: white; text-decoration: none;'>Add to Cart</a></button>";    
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found for '$keyword'.</p>";
            }
            } else {
                echo "<p>Please enter a search term.</p>";
            }
            mysqli_close($conn);
        ?>
        <?php include("footer.php"); ?>

