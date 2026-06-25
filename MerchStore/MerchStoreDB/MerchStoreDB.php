<?php
// create MerchStoreDB and Merch table
include("connDB.php");
$conn = connDB();
mysqli_query($conn, "DROP DATABASE IF EXISTS MerchStoreDB;");
if($conn){
    echo "<p>Connection established</p>";
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS MerchStoreDB;");
    echo "<p>MerchStore database created</p>";
    mysqli_select_db($conn, "MerchStoreDB");


    $merchQuery = "CREATE TABLE IF NOT EXISTS Merch (
        ItemId INT NOT NULL AUTO_INCREMENT,
        ItemName VARCHAR(100),
        ItemDescription TEXT,
        ItemPrice DECIMAL(10,2),
        ItemCategory VARCHAR(50),
        ImageId INT,
        Quantity INT DEFAULT 0,
        CONSTRAINT pk_merch PRIMARY KEY (ItemId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if (mysqli_query($conn, $merchQuery)){
        echo "<p>Merch table created</p>";
    }
    
    // add table for images with image id, image folderpath, filename, and item id as foreign key
    $imagesQuery = "CREATE TABLE IF NOT EXISTS Images (
        ImageId INT NOT NULL AUTO_INCREMENT,
        ItemId INT,
        ImageFolderPath VARCHAR(255),
        ImageFileName VARCHAR(255),
        CONSTRAINT pk_images PRIMARY KEY (ImageId),
        FOREIGN KEY (ItemId) REFERENCES Merch(ItemId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if (mysqli_query($conn, $imagesQuery)){
        echo "<p>Images table created</p>"; 
    }
    

    //add table for users with first name, last name, password, email, and address
    $usersQuery = "CREATE TABLE IF NOT EXISTS Users (
        UserId INT NOT NULL AUTO_INCREMENT,
        FirstName VARCHAR(100) NOT NULL,
        LastName VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL UNIQUE,
        StreetAddress TEXT,
        City VARCHAR(50),
        State VARCHAR(2), 
        ZipCode VARCHAR(10),
        Password VARCHAR(255),
        PaymentMethod VARCHAR(50), 
        isAdmin BOOLEAN DEFAULT FALSE,
        CONSTRAINT pk_users PRIMARY KEY (UserId)

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if (mysqli_query($conn, $usersQuery)){
        echo "<p>Users table created</p>";
    }
        // add table for shopping cart with item id, quantity, and link to unique UserId from users table
    $cartQuery = "CREATE TABLE IF NOT EXISTS Cart (
        CartId INT NOT NULL AUTO_INCREMENT,
        ItemId INT,
        Quantity INT DEFAULT 1,
        UserId INT,
        CONSTRAINT pk_cart PRIMARY KEY (CartId),
        FOREIGN KEY (ItemId) REFERENCES Merch(ItemId),
        FOREIGN KEY (UserId) REFERENCES users(UserId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if (mysqli_query($conn, $cartQuery)){
        echo "<p>Cart table created</p>";
    }
    // add table for orders with UserId, TotalPrice, ShippingCost, GrandTotal, OrderDate, OrderStatus, Items
    $ordersQuery = "CREATE TABLE IF NOT EXISTS Orders (
        OrderId INT NOT NULL AUTO_INCREMENT,
        UserId INT,
        TotalPrice DECIMAL(10,2),
        ShippingCost DECIMAL(10,2),
        GrandTotal DECIMAL(10,2),
        PaymentMethod VARCHAR(50),
        OrderStatus VARCHAR(20) DEFAULT 'Processing',
        OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
        Items TEXT,
        CONSTRAINT pk_orders PRIMARY KEY (OrderId),
        FOREIGN KEY (UserId) REFERENCES users(UserId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
     if (mysqli_query($conn, $ordersQuery)){
        echo "<p>Orders table created</p>";
    }
mysqli_close($conn);
}
?>
