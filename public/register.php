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
    $data = [
        'first_name' => sanitizeInput($_POST['first_name'] ?? ''),
        'last_name' => sanitizeInput($_POST['last_name'] ?? ''),
        'department' => sanitizeInput($_POST['department'] ?? ''),
        'gender' => sanitizeInput($_POST['gender'] ?? ''),
        'hobbies' => isset($_POST['hobbies']) ? sanitizeInput($_POST['hobbies']) : [],
        'others' => sanitizeInput($_POST['others'] ?? ''),
        'username' => sanitizeInput($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? ''
    ];

    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['username']) || empty($data['password'])) {
        $error = "First name, last name, username, and password are required.";
    } else {
        try {
            if ($auth->register($data)) {
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                $error = "Username already exists.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h2>Registration Form</h2>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-row">
            <div class="form-col">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-col">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-col">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Business">Business</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Gender</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Male" required> Male</label><br>
                    <label><input type="radio" name="gender" value="Female"> Female</label><br>
                    <label><input type="radio" name="gender" value="Other"> Other</label>
                </div>
            </div>
            <div class="form-col">
                <label>Hobbies</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="hobbies[]" value="Reading"> Reading</label><br>
                    <label><input type="checkbox" name="hobbies[]" value="Sports"> Sports</label><br>
                    <label><input type="checkbox" name="hobbies[]" value="Music"> Music</label><br>
                    <label><input type="checkbox" name="hobbies[]" value="Travel"> Travel</label>
                </div>
            </div>
            <div class="form-col">
                <label for="others">Others</label>
                <textarea id="others" name="others"></textarea>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-col">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="button" class="btn btn-danger">Clear</button>
        </div>
        
        <a href="login.php" class="center-link">Already have an account? Login</a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
