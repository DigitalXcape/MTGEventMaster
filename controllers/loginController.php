<?php
require_once '../logger/Logger.php';
define('LOGIN_API_URL', 'http://localhost/MTGEventAPI/endpoints/login.php');

session_start();

$response = [
    'success' => false,
    'message' => 'Invalid email or password',
    'token' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $logger = Logger::getInstance();
    } catch (Exception $e) {
        echo "Logger error: " . $e->getMessage();
    }
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {

        // Send a request to the external login controller
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, LOGIN_API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['email' => $email, 'password' => $password]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Get the HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Log any CURL errors
        if (curl_errno($ch)) {
            $logger->log("CURL error: " . curl_error($ch));
        }

        curl_close($ch);

        // Log the raw API response
        $logger->log("API Response: " . $apiResponse);
        $logger->log("HTTP Status Code: " . $httpCode);

        // Decode the API response
        $data = json_decode($apiResponse, true);

        // Log JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger->log("JSON decode error: " . json_last_error_msg());
        }
            // Check if login was successful
            if ($data['success']) {
                // Store token in a cookie
                setcookie('token', $data['token'], time() + 9999, '/', '', false, true);

                // Store other user data in the session
                $_SESSION['username'] = $data['username'];
                $_SESSION['user_id'] = $data['user_id'];
                $_SESSION['role'] = $data['role'];

                $logger->log("Login successful for email: $email");
                header('Location: ../index.php');
                exit;
            } else {
                $logger->log("Login failed for email: $email - Message: " . $data['message']);
                header('Location: ../views/login.php?error=' . urlencode($data['message']));
                exit;
            }
        
    } catch (Exception $e) {
        $logger->log("Exception caught: " . $e->getMessage());
        header('Location: ../views/login.php?error=' . urlencode('An unexpected error occurred.'));
        exit;
    }
}

// Pass response data back to the view if needed
if (!empty($response['message'])) {
    echo json_encode($response);
}
?>