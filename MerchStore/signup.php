<?php
include("header.php");
?>
    =<h1>Sign Up</h1>
    <p>Fill out the form below to sign up for an account.</p>
<div class="container-fluid col-lg-6 col-md-8 col-sm-10">
    <form action="signup.php" method="post" class="form-group col-lg-12 col-md-12 col-sm-12">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" class="form-control" required><br><br>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" class="form-control" required><br><br>
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" class="form-control" required><br><br>
        <label for="streetAddress">Street Address:</label>
        <input type="text" id="streetAddress" name="streetAddress" class="form-control" required><br><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" class="form-control" required><br><br>
        <label for="state">State:</label>
        <input type="text" id="state" name="state" class="form-control" required><br><br>
        <label for="zipCode">Zip Code:</label>
        <input type="text" id="zipCode" name="zipCode" class="form-control" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required><br><br>
        <label for="paymentMethod">Payment Method:</label>
        <select id="paymentMethod" name="paymentMethod" class="form-control" required>
            <option value="creditCard">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="applePay">Apple Pay</option>
        </select><br><br>
        <input type="submit" value="Sign Up">
    </form>
</div>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include("connDB.php");
        $conn = connDB();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $streetAddress = mysqli_real_escape_string($conn, $_POST['streetAddress']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $zipCode = mysqli_real_escape_string($conn, $_POST['zipCode']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

        // insert input validation here (e.g. check if email is valid, password is strong enough, etc.)

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color: red;'>Invalid email format.</p>";
            exit();
        }
        if(strlen($firstName) <=0 || strlen($lastName) <= 0){
            echo "<p style='color: red;'>Please enter a valid first name.</p>";
            exit();
        }   
        if (strlen($password) < 8) {
            echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
            exit();
        }
        if (!preg_match("/[A-Z]/", $password)) {
            echo "<p style='color: red;'>Password must contain at least one uppercase letter.</p>";
            exit();
        }
        if (!preg_match("/[a-z]/", $password)) {
            echo "<p style='color: red;'>Password must contain at least one lowercase letter.</p>";
            exit();
        }
        if (!preg_match("/[0-9]/", $password)) {
            echo "<p style='color: red;'>Password must contain at least one number.</p>";
            exit();
        }
        if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
            echo "<p style='color: red;'>Password must contain at least one special character.</p>";
            exit();
        }
        if (empty($streetAddress) || empty($city) || empty($state) || empty($zipCode)) {
            echo "<p style='color: red;'>Please provide a complete address.</p>";
            exit();
        }
        if (!preg_match("/^\d{5}(-\d{4})?$/", $zipCode)) {
            echo "<p style='color: red;'>Invalid zip code format.</p>";
            exit();
        }
        if (!in_array($paymentMethod, ['creditCard', 'paypal', 'applePay'])) {
            echo "<p style='color: red;'>Invalid payment method selected.</p>";
            exit();
        }
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'")) > 0) {
            echo "<p style='color: red;'>An account with this email already exists.</p>";
            exit();
        }

        $sql = "INSERT INTO users (firstName, lastName, email, streetAddress, city, state, zipCode, password, paymentMethod) VALUES ('$firstName', '$lastName', '$email', '$streetAddress', '$city', '$state', '$zipCode', '$password', '$paymentMethod')";

        if (mysqli_query($conn, $sql)) {
            echo "<p>Account created successfully!</p>";
        } else {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
        mysqli_close($conn);
    }
    // create cart for user after account creation
    if (isset($email)) {
        $conn = mysqli_connect("localhost", "root", "", "MerchStoreDB");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT UserId FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $userId = $row['UserId'];
            $cartSql = "INSERT INTO cart (UserId) VALUES ('$userId')";
            if (mysqli_query($conn, $cartSql)) {
                echo "<p>Cart created successfully!</p>";
            } else {
                echo "<p>Error creating cart: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p>Error retrieving user ID: " . mysqli_error($conn) . "</p>";
        }
        mysqli_close($conn);
    }
?>
<?php include("footer.php"); ?>