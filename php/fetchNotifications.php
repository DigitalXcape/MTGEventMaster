<?php
require_once '../controllers/notificationsController.php';
require_once '../logger/Logger.php';

$notificationsController = new NotificationsController();
$logger = Logger::getInstance();
$notifications = $notificationsController->getNotifications();

// Return the notification count as JSON
echo json_encode(['count' => count($notifications)]);

$logger->log(json_encode($notifications));