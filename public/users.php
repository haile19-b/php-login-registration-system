<?php
require_once __DIR__ . '/../app/Auth.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$users = $auth->getAllUsers();
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="max-width: 900px;">
    <h2>Registered Users</h2>
    
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="logout.php" class="btn btn-danger" style="text-decoration: none;">Logout</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Department</th>
                    <th>Gender</th>
                    <th>Hobbies</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['department']); ?></td>
                        <td><?php echo htmlspecialchars($user['gender']); ?></td>
                        <td>
                            <?php 
                            $hobbies = json_decode($user['hobbies'], true);
                            echo $hobbies ? htmlspecialchars(implode(', ', $hobbies)) : '';
                            ?>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
