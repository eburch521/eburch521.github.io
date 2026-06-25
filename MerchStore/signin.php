<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    include("connDB.php");
    $conn = connDB();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT UserId, FirstName, LastName FROM users WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['UserId'];
        $_SESSION['first_name'] = $user['FirstName'];
        $_SESSION['last_name'] = $user['LastName'];
        $_SESSION['email'] = $user['email'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
include("header.php");
?>

  <h1>Sign In</h1>
  <div class="container-fluid">
    <form  action="signin.php" method="post" class="form-group col-lg-6 col-md-8 col-sm-10">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required class="form-control"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required class="form-control"><br><br>
        <input type="submit" value="Sign In" class="btn btn-primary">   
    </form>
    </div>

    <p>Don't have an account? <a href="signup.php">Sign up for an account here.</a></p>
   
<?php
// when user submits form, check if email and password match an entry in the database, if so, redirect to homepage, if not, show error message
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "MerchStoreDB");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM Users WHERE Email = '$email' AND Password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        // Login successful
        echo "<p style='color: green;'>Login successful.</p>";
        // Redirect to homepage
        header("Location: home.php");
       

        exit();
    } else {
        // Login failed
        echo "<p style='color: red;'>Invalid email or password.</p>";
    }
}
?>
<?php
include("footer.php");
?>