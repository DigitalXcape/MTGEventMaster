<?php
session_start();
require_once '../logger/Logger.php';

// Define the API endpoint for deleting a post
define('DELETE_POST_API_URL', 'http://localhost/MTGEventAPI/endpoints/deletePost.php');

$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $logger = Logger::getInstance();
    } catch (Exception $e) {
        echo "Logger error: " . $e->getMessage();
    }

    $postId = $_POST['eventId'];

    try {
        // Log the deletion attempt
        $logger->log("Attempting to delete post with ID: $postId");

        // Prepare the data for the API request
        $postData = http_build_query(['post_id' => $postId]);

        // Set up the cURL request to the API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, DELETE_POST_API_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Use DELETE method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Send data in the request body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Log any CURL errors
        if (curl_errno($ch)) {
            $logger->log("CURL error: " . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $logger->log("HTTP Status Code: " . $httpCode);

        curl_close($ch);

        $data = json_decode($apiResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger->log("JSON decode error: " . json_last_error_msg());
            $logger->log("Problematic response: " . $apiResponse);
            $responseMessage = 'An unexpected error occurred with the response format.';
        }

        // Handle API response
        if ($data['success']) {
            $responseMessage = "Post deleted successfully!";
            $logger->log("Post deleted successfully");

            header('Location: ../views/localEvents.php');
            exit();
        } else {
            $responseMessage = $data['message'];
            $logger->log("Failed to delete post: " . $data['message']);
        }
    } catch (Exception $e) {
        $logger->log("Exception caught: " . $e->getMessage());
        $responseMessage = 'An unexpected error occurred.';
    }
}

// Display the response message or pass it to the view
$_SESSION['responseMessage'] = $responseMessage;
header('Location: ../views/error.php');
exit();