<?php

require_once '../logger/Logger.php';

$logger = null;

session_start();

// Ensure the session is started correctly
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//get the logger
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

// Validate post parameters
if (!isset($_POST['title'], $_POST['description'], $_POST['location'], $_POST['date_held'])) {
    echo "Missing required POST parameters: title, description, location, or date_held.";
    exit();
}

$title = $_POST['title'];
$description = $_POST['description'];
$location = $_POST['location'];
$dateHeld = $_POST['date_held'];
$creatorId = $_SESSION['user_id'];

//check if the token is set
if (!isset($_COOKIE['token'])){
    header('Location: ../views/login.php');
    exit();
}

// Prepare data for the API call
$apiUrl = 'http://localhost/MTGEventAPI/endpoints/createPost.php';
$postData = http_build_query([
    'creator_id' => $creatorId,
    'title' => $title,
    'description' => $description,
    'location' => $location,
    'date_held' => $dateHeld,
    'token' => $_COOKIE['token'],
]);

// Initialize cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$apiResponse = curl_exec($ch);

if ($apiResponse === false) {
    // Log and handle cURL error
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

// Decode the API response
$decodedResponse = json_decode($apiResponse, true);

// Check if the post creation was successful
if (isset($decodedResponse['success']) && $decodedResponse['success'] === true) {
    // Redirect to the event view page, passing the eventId as a URL parameter
    $postId = $decodedResponse['postId']; // Ensure this is correctly returned from the API
    header('Location: ../views/localEvents.php');
    exit();
} else {
    // Log and handle API error response
    $errorMessage = $decodedResponse['message'] ?? 'Unknown error';
    $logger->log("API response error: " . $errorMessage);
    echo "API error: " . $errorMessage;
    header('Location: ../views/error.php');
    exit();
}
?>