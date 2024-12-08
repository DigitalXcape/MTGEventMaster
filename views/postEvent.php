<?php
require_once '../logger/Logger.php';

$logger = null;

session_start();

try {
    $logger = Logger::getInstance();
} catch (Exception $e) {
    echo "Logger error: " . $e->getMessage();
    exit();
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in. Redirecting to login page.";
    header('Location: ../views/login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Event</title>
</head>
<body>
<?php include '../php/navbar.php'; generateNavBar(); ?>
<div class="container my-5">
    <!-- Event Header -->
    <div class="text-center mb-4">
        <h1 class="display-4">Post a New Event</h1>
        <p class="text-muted">Fill in the details below to create a new event.</p>
    </div>

    <!-- Event Form -->
    <div class="mb-5">
        <form action="../controllers/createPostController.php" method="POST">
            <!-- Title Field -->
            <div class="mb-3">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" required placeholder="Enter event title">
            </div>

            <!-- Location Field -->
            <div class="mb-3">
                <label for="location" class="form-label">Event Location</label>
                <input type="text" name="location" id="location" class="form-control" required placeholder="Enter event location">
            </div>

            <!-- Date Held Field -->
            <div class="mb-3">
                <label for="date_held" class="form-label">Event Date</label>
                <input type="date" name="date_held" id="date_held" class="form-control" required>
            </div>

            <!-- Event Description Field -->
            <div class="mb-3">
                <label for="description" class="form-label">Event Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required placeholder="Enter event description"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-lg">Post Event</button>
        </form>
    </div>
</div>


</body>
</html>