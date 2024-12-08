<?php
require_once '../logger/Logger.php';

class NotificationsController
{
    private $logger;


    public function __construct()
    {
        $this->logger = Logger::getInstance();
    }

    public function getNotifications()
    {
        $baseApiUrl = 'http://localhost/MTGEventAPI/endpoints/getNotifications.php';        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->logger->log("UserID was not set in the session.");
            return []; // Return an empty array for consistency
        }

        // Build API URL with the user ID
        $apiUrl = $baseApiUrl . '?user_id=' . urlencode($_SESSION['user_id']);
        $this->logger->log("Making API request to: $apiUrl");

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute cURL request
        $apiResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->log("cURL error: " . curl_error($ch));
            curl_close($ch);
            return []; // Return an empty array on failure
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->log("API response code: $httpCode");

        // Decode JSON response
        $data = json_decode($apiResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->log("Failed to decode JSON response: " . json_last_error_msg());
            return [];
        }

        // Check for success in the API response
        if (isset($data['success']) && $data['success'] && isset($data['notifications'])) {
            $this->logger->log("API response received successfully: " . json_encode($data['notifications']));
            return $data['notifications'];
        } else {
            $this->logger->log("API returned an error or missing notifications data.");
            return [];
        }
    }

    public function deleteNotifications()
    {
        $baseApiUrl = 'http://localhost/MTGEventAPI/endpoints/deleteNotifications.php';        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->logger->log("UserID was not set in the session.");
            return []; // Return an empty array for consistency
        }

        // Build API URL with the user ID
        $apiUrl = $baseApiUrl . '?user_id=' . urlencode($_SESSION['user_id']);
        $this->logger->log("Making API request to: $apiUrl");

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute cURL request
        $apiResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->log("cURL error: " . curl_error($ch));
            curl_close($ch);
            return []; // Return an empty array on failure
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->log("API response code: $httpCode");

        // Decode JSON response
        $data = json_decode($apiResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->log("Failed to decode JSON response: " . json_last_error_msg());
            return [];
        }

        // Check for success in the API response
        if (isset($data['success']) && $data['success']) {
            $this->logger->log("Notifications deleted successfully");
            return;
        } else {
            $this->logger->log("API returned an error");
            return;
        }
    }
}