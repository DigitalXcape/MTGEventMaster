<?php
session_start();
require_once '../logger/Logger.php';

// Define the API endpoint for creating a user
define('CREATE_USER_API_URL', 'http://localhost/MTGEventAPI/endpoints/createUser.php');

$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $logger = Logger::getInstance();
    } catch (Exception $e) {
        echo "Logger error: " . $e->getMessage();
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Log the new user creation attempt
        $logger->log("Attempting to create user with email: $email");

        // Prepare the data for the API request
        $postData = http_build_query([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        // Set up the cURL request to the API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, CREATE_USER_API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Log any CURL errors
        if (curl_errno($ch)) {
            $logger->log("CURL error: " . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($apiResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger->log("JSON decode error: " . json_last_error_msg());
            $logger->log("Problematic response: " . $apiResponse);
            $responseMessage = 'An unexpected error occurred with the response format.';
        }

        // Handle API response
        if ($data['success']) {
            $responseMessage = "User created successfully!";
            $logger->log("User created successfully with email: $email");

            // Optionally, redirect to user list or a success page
            header('Location: ../index.php');
            exit();
        } else {
            $responseMessage = $data['message'];
            $logger->log("Failed to create user with email: $email - Message: " . $data['message']);
        }
    } catch (Exception $e) {
        $logger->log("Exception caught: " . $e->getMessage());
        $responseMessage = 'An unexpected error occurred.';
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $logger->log("HTTP Status Code: " . $httpCode);
}

// Display the response message or pass it to the view
$_SESSION['responseMessage'] = $responseMessage;
header('Location: ../views/createUser.php');
exit();
?>