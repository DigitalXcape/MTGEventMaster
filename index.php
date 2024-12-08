<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTG Event Master</title>
    <?php include 'php/navbar.php'; ?>
</head>
<body>
    <!-- Navigation Bar -->
    <?php generateNavBar(); ?>

    <div class="container mt-5">
        <!-- Hero Section -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <h1 class="display-4 fw-bold">Welcome to MTG Event Master</h1>
                <p class="lead">Your ultimate platform to organize, track, and enhance your Magic: The Gathering events.</p>
                <p class="text-muted">
                    MTG Event Master makes it easy to create tournaments, manage participants, and share event results. Perfect for casual games and competitive play.
                </p>
                <a href="views/localEvents.php" class="btn btn-primary btn-lg">View events</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="http://localhost/MTGEventMaster/images/event.png" alt="MTG Event" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- Features Section -->
        <div id="about" class="mt-5">
            <h2 class="text-center fw-bold mb-4">Why Choose MTG Event Master?</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <h3 class="h5">Easy Event Setup</h3>
                    <p>Create events quickly with a user-friendly interface.</p>
                </div>
                <div class="col-md-4">
                    <h3 class="h5">Search Events</h3>
                    <p>Browse through local events in the area!</p>
                </div>
                <div class="col-md-4">
                    <h3 class="h5">Notifications</h3>
                    <p>Get notifications for people commenting.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-light text-center">
        <p class="mb-0">© 2024 MTG Event Master. All rights reserved.</p>
    </footer>
</body>
</html>