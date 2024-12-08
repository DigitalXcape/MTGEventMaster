<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

</head>
<body>
    <?php include '../php/navbar.php'; generateNavBar(); ?>
    
    <div class="container mt-5">

        <h2>Log In</h2>
        <form id="loginForm" method="POST" action="../controllers/loginController.php">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary">Log In</button>
        </form>

        <p>Don't Have an Account? <a href="../views/createUser.php">Create One</a></p>

        <?php if (isset($_GET['error'])): ?>
            <div id="errorMessage" class="mt-3 text-danger">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>