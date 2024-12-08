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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method. Only POST is allowed.";
    exit();
}

// Validate POST parameters
if (!isset($_POST['comment_body']) || !isset($_POST['post_id'])) {
    echo "Missing required POST parameters: comment_body or post_id.";
    exit();
}

// Assign variables from the session and POST data
$userId = $_SESSION['user_id'];
$commentBody = $_POST['comment_body'];
$postId = $_POST['post_id'];

// Prepare data for the API call
$apiUrl = 'http://localhost/MTGEventAPI/endpoints/createComment.php';
$postData = http_build_query([
    'creator_id' => $userId,
    'comment_body' => $commentBody,
    'post_id' => $postId,
    'token' => $_COOKIE['token'],
]);

$logger->log("Data being sent to API: " . json_encode($postData));

// Initialize cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$apiResponse = curl_exec($ch);

if ($apiResponse === false) {
    $curlError = curl_error($ch);
    $logger->log("CURL error: " . $curlError);
    echo "An error occurred while making the API request: " . $curlError;
    curl_close($ch);
    header('Location: ../views/error.php');
    exit();
}

// Check for cURL errors
if (curl_errno($ch)) {
    $curlError = curl_error($ch);
    $logger->log("CURL error: " . $curlError);
    echo "CURL error occurred: " . $curlError;
    curl_close($ch);
    header('Location: ../views/error.php');
    exit();
}

curl_close($ch);

$decodedResponse = json_decode($apiResponse, true);
if (isset($decodedResponse['success']) && $decodedResponse['success'] === true) {
    header('Location: ../views/viewEvent.php?eventId=' . urlencode($postId));
    exit();
} else {
    $errorMessage = $decodedResponse['message'] ?? 'Unknown error';
    $logger->log("API response error: " . $errorMessage);
    echo "API error: " . $errorMessage;
    header('Location: ../views/error.php');
    exit();
}