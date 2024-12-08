<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
    <?php include '../php/navbar.php'; ?>
</head>
<body>
    <?php generateNavBar(); ?>

    <div class="container mt-5">
        <h2 class="mb-4">All Events</h2>

        <?php
        require_once '../logger/Logger.php';

        $logger = Logger::getInstance();

        // API URL for fetching posts
        $apiUrl = 'http://localhost/MTGEventAPI/endpoints/getPosts.php';

        // Initialize cURL
        $logger->log("Initializing cURL request to API: $apiUrl");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute cURL request
        $apiResponse = curl_exec($ch);

        // Log the cURL execution result
        if (curl_errno($ch)) {
            $logger->log("CURL error: " . curl_error($ch));
            echo "<div class='text-danger'>Failed to retrieve posts. Error: " . curl_error($ch) . "</div>";
        } else {
            $logger->log("CURL executed successfully. Response received.");
        
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $logger->log("HTTP response code: $http_code");
            
            // Decode JSON response
            $data = json_decode($apiResponse, true);

            // Log the raw API response for debugging
            $logger->log("API Response: " . json_encode($data));

            if (json_last_error() === JSON_ERROR_NONE && isset($data['success']) && $data['success']) {
                if (!empty($data['data'])) {
                    $logger->log("Posts retrieved successfully. Displaying posts.");
                    // Display posts as cards
                    echo "<div class='container mt-5'>";
                    echo "<div class='row'>";
                    foreach ($data['data'] as $post) {
                        $post_id = $post['post_id'];
                        $description = htmlspecialchars($post['description']);
                        $location = htmlspecialchars($post['location']);
                        $dateHeld = htmlspecialchars($post['date_held']);
                        $title = $post['title'];
                        
                        echo "
                        <div class='col-md-4'>
                            <div class='card mb-4'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$title</h5>
                                    <p class='card-text'>$description</p>
                                    <p class='card-text'><small class='text-muted'>Location: $location</small></p>
                                    <p class='card-text'><small class='text-muted'>Date Held: $dateHeld</small></p>
                                    <a href='viewEvent.php?eventId=$post_id' class='btn btn-primary'>View</a>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    echo "</div>";
                    echo "</div>";
                } else {
                    $logger->log("No posts found in the response.");
                    echo "<div class='text-warning'>No posts found</div>";
                }
            } else {
                $message = isset($data['message']) ? $data['message'] : 'Unknown error occurred';
                $logger->log("API response error: $message");
                echo "<div class='text-danger'>" . htmlspecialchars($message) . "</div>";
            }
        }

        curl_close($ch);
        ?>
    </div>

</body>
</html>