<?php

require_once '../controllers/notificationsController.php';

$controller = new NotificationsController();
$notifications = $controller->getNotifications();

// clear notifs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_notifications'])) {
    $controller->deleteNotifications();
    $notifications = [];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notifications</title>
</head>
<body>
    <?php include '../php/navbar.php'; generateNavbar(); ?>

    <div class="container mt-5">
        <h1>Notifications</h1>
        
        <!-- Clear Notifications Button -->
        <form method="POST">
            <button type="submit" name="clear_notifications" class="btn btn-danger mb-3">Clear All Notifications</button>
        </form>

        <!-- Display success or error message -->
        <?php if (isset($clearMessage)): ?>
            <div class="alert <?= $clearSuccess ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($clearMessage) ?>
            </div>
        <?php endif; ?>

        <ul class="list-group">
            <?php if (empty($notifications)): ?>
                <li class="list-group-item">You have no new notifications.</li>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item"><?= htmlspecialchars($notification['Message']) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

</body>
</html>