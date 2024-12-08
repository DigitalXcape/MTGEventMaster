<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/VerificationLibrary.js" defer></script>
    <?php include '../php/navbar.php'; ?>
    <title>Sign Up</title>
</head>
<body>
<?php generateNavBar(); ?>

<div class="container mt-5">
        <h2>Create New User</h2>
        <form action="../controllers/createUserController.php" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary">Create User!</button>
        </form>
    </div>
</body>
</html>