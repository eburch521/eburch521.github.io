<?php
   // log user out by destroying session and redirecting to home page
//prompt user to confirm logout
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_start();
    session_unset();
    session_destroy();
    $loggedIn = false;
    header("Location: home.php");   
    exit;
} elseif (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
    header("Location: home.php");
    exit;
} else {
    echo "<p>Are you sure you want to log out?</p>";
    echo "<a href='logout.php?confirm=yes' class='btn btn-danger'>Yes</a> ";
    echo "<a href='logout.php?confirm=no' class='btn btn-secondary'>No</a>";
     include("footer.php");
     exit;
}

?>  