<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content=" width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=14">
    <!-- Load font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="bookicon.png">
    <title>Eric Burch</title>
</head>
<!-- construct navbar with links to projects, music, and contact pages -->

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a id="home-header" class="navbar-brand" href="index.php"><strong>Eric Burch</strong></a>

            <button class="navbar-toggler collapsed" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="about.php">About</a>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Projects</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://merchstoredb.freehosting.dev/index.php">Merch Store</a></li>
                            <li><a class="dropdown-item" href="https://app.jotform.com/250634471293154?utm_source=jotform_pwa">Missouri Brinery Jotform Pages (Developed by Paul Weber)</a></li>
                            <li><a class="dropdown-item" href="drumMachine2/drumMachine2.html">JS-9 drumMachine</a></li>
                            <li><a class="dropdown-item" href="spotify/index.html">BandFinder</a></li>
                            <li><a class="dropdown-item" href="kimchiForm">Kimchi Calculator</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="news.php">News</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Music</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://eburch.bandcamp.com/album/eba01">E. Burch</a></li>
                            <li><a class="dropdown-item" href="https://zonerouter.bandcamp.com/album/live-at-hitt-records-june-14th-2025">Zoner Outer</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Contact</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="contact.php">Contact Form</a></li>
                            <li><a class="dropdown-item" href="resume.php">Resume</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>