<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 

function generateNavbar() {
    // Navbar items
    $navItems = [
        'Home' => 'http://localhost/MTGEventMaster/index.php',
        'Events' => 'http://localhost/MTGEventMaster/views/localEvents.php',
        'Post Event' => 'http://localhost/MTGEventMaster/views/postEvent.php'
    ];

    // Begin the navbar structure
    echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="http://localhost/MTGEventMaster/js/notificationUpdater.js"></script>'
    ;

    // Logo on the left (larger size)
    echo '<a class="navbar-brand" href="http://localhost/MTGEventMaster/index.php">';
    echo '<img src="http://localhost/MTGEventMaster/images/logo1.png" alt="Logo" style="height: 80px;">'; // Larger logo size
    echo '</a>';

    // Toggler for mobile view
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';

    // Navbar items in the center (responsive)
    echo '<div class="collapse navbar-collapse" id="navbarNav">';
    echo '<ul class="navbar-nav mx-auto">';
    foreach ($navItems as $name => $link) {
        echo '<li class="nav-item"><a class="nav-link" href="' . $link . '">' . $name . '</a></li>';
    }
    echo '</ul>';

    // Right-aligned buttons: Profile and Notifications
    echo '<ul class="navbar-nav">';

    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        // Notifications button
        echo '
        <li class="nav-item position-relative">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
            <a class="nav-link" href="http://localhost/MTGEventMaster/views/viewNotifications.php">
                <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
            </a>
            <span id="notification-number" class="position-absolute top-0 start-100 translate-middle-x badge rounded-pill bg-danger" style="font-size: 0.8rem;">
                0
            </span>
        </li>
        ';

        // Get the first letter of the username
        $userName = $_SESSION['username'];

        // Profile button with dropdown
        echo '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    ' . $userName . '
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="http://localhost/MTGEventMaster/controllers/logoutController.php">Logout</a></li>
                </ul>
            </li>
        ';
    } else {
        // Sign In button
        echo '<li class="nav-item"><a class="nav-link" href="http://localhost/MTGEventMaster/views/login.php">Sign In</a></li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</nav>';
}
?>