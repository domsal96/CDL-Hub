<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    if (empty($current_password) || empty($new_password)) {
        $message = "Please fill in all fields.";
    } elseif (strlen($new_password) < 8) {
        $message = "New password must be at least 8 characters.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (password_verify($current_password, $user['password'])) {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
            $update_stmt->bind_param("si", $new_hashed, $_SESSION['user_id']);
            if ($update_stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password.";
            }
            $update_stmt->close();
        } else {
            $message = "Current password incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password - CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require 'header.php'; ?>
<div class="page-box">
    <h2>Change Password</h2>
    <?php if($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Current Password</label><br>
        <input type="password" name="current_password" required><br><br>
        <label>New Password</label><br>
        <input type="password" name="new_password" required><br><br>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>
</body>
</html>
