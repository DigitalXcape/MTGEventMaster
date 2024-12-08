<?php
require_once '../controllers/getPostController.php';
require_once '../controllers/getCommentsController.php';

$eventId = isset($_GET['eventId']) ? $_GET['eventId'] : null;

$controller = new GetPostController();
$commentsController = new GetCommentsController();

//get the post
$response = $controller->getPost($eventId);

$post = null;
if ($response['success']) {
    $post = $response['post'];
} else {
    $error = $response['message'];
}

//get the comments
$commentResponse = $commentsController->getComments($eventId);

$comments = null;
if ($commentResponse['success']) {
    $comments = $commentResponse['comments'];
} else {
    $error = $commentResponse['message'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event</title>
</head>
<body>
<?php include '../php/navbar.php'; generateNavBar(); ?>
<div class="container my-5">
    <!-- Event Header -->
    <div class="text-center mb-4">
        <h1 class="display-4">
            <?php echo $post ? htmlspecialchars($post['Title']) : 'Event Title'; ?>
        </h1>
        <p class="text-muted">
            Event Date:
            <span id="event-date">
                <?php echo $post ? htmlspecialchars($post['DateHeld']) : 'TBD'; ?>
            </span>
        </p>
    </div>

   <!-- Event Details -->
<?php if ($post): ?>
    <div class="card mb-5">
        <div class="card-body">
            <h2 class="card-title">Event Details</h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($post['Location']); ?></p>
            <p><strong>Created By:</strong> <?php echo htmlspecialchars($post['Username']); ?></p>
            <p><strong>Created On:</strong> <?php echo htmlspecialchars($post['DateCreated']); ?></p>

            <!-- Event Description -->
            <h4>Description:</h4>
            <p><?php echo htmlspecialchars($post['Description']); ?></p>

            <!-- Delete Button for Admin -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <form action="../controllers/deletePostController.php" method="POST" class="mt-3">
                    <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($eventId); ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete Post</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <?php echo htmlspecialchars($error ?? 'Event details could not be loaded.'); ?>
    </div>
<?php endif; ?>

    <!-- Comments Section -->
    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Comments</h3>

            <!-- Comment Form -->
            <form action="../controllers/createCommentController.php" method="POST" class="mb-4">
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($eventId); ?>">
                <textarea name="comment_body" class="form-control form-control-sm mb-2" placeholder="Write a comment..." required></textarea>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </form>

            <!-- Display Comments -->
            <div id="comments-section">
                <ul class="list-group">
                    <?php 
                        if (is_array($comments) && !empty($comments)) {
                            foreach ($comments as $comment) {
                                $body = htmlspecialchars($comment['Body']);
                                $creatorId = htmlspecialchars($comment['CreatorID']);
                                $userName = htmlspecialchars($comment['Username']);
                                $likes = htmlspecialchars($comment['Likes']);
                                $dateCreated = htmlspecialchars($comment['DateCreated']);
                                $commentId = htmlspecialchars($comment['CommentID']);

                                echo "
                                <li class='list-group-item'>
                                    <div class='d-flex justify-content-between align-items-start'>
                                        <div>
                                            <strong>$userName</strong>
                                            <br><span class='text-muted'>Posted On $dateCreated</span>
                                            <p class='mt-2 mb-0 comment-body'>$body</p>
                                        </div>
                                    </div>
                                </li>
                                ";
                            }
                        } else {
                            echo "<li class='list-group-item'>No comments available.</li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>