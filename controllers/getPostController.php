<?php
require_once '../logger/Logger.php';

class GetPostController
{
    private $logger;
    private $baseApiUrl = 'http://localhost/MTGEventAPI/endpoints/getPost.php';

    public function __construct()
    {
        $this->logger = Logger::getInstance();
    }

    //gets the with a specific id
    public function getPost($eventId)
    {
        if (!$eventId) {
            $this->logger->log("eventId parameter is missing.");
            return ['success' => false, 'message' => 'Event ID is missing.'];
        }

        // Build API URL with the event ID
        $apiUrl = $this->baseApiUrl . '?post_id=' . urlencode($eventId);
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
            return ['success' => false, 'message' => 'Failed to connect to the API.'];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->logger->log("API response code: $httpCode");

        // Decode JSON response
        $data = json_decode($apiResponse, true);

        curl_close($ch);

        if (json_last_error() === JSON_ERROR_NONE && isset($data['success']) && $data['success']) {
            $this->logger->log("API response received successfully: " . json_encode($data['post']));
            return ['success' => true, 'post' => $data['post']];
        } else {
            $this->logger->log("Failed to decode API response or API returned an error.");
            return ['success' => false, 'message' => 'Invalid response from the API.'];
        }
    }
}