<?php
require_once __DIR__ . '/../app/Auth.php';
require_once __DIR__ . '/../app/Validation.php';

$auth = new Auth();
if ($auth->isLoggedIn()) {
    header("Location: users.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        if ($auth->login($username, $password)) {
            header("Location: users.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h2>Login Form</h2>
    
    <?php if ($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Login</button>
            <button type="button" class="btn btn-danger">Clear</button>
        </div>
        
        <a href="register.php" class="center-link">Create New Account</a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
