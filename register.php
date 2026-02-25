<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();
require 'config.php';

$message = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $message = "Username must be between 3 and 20 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Username or Email already exists.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require 'header.php'; ?>
<div class="login-wrapper">
    <form method="POST" class="login-form">
        <h2>Register</h2>
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
</body>
</html>
