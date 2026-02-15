<?php
require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
SELECT teams.team_name 
FROM favorites
JOIN teams ON favorites.team_id = teams.team_id
WHERE favorites.user_id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$favorites = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h2>User Profile</h2>

<p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

<h3>Your Favorite Teams:</h3>

<?php if (count($favorites) > 0): ?>
    <ul>
        <?php foreach ($favorites as $team): ?>
            <li><?php echo htmlspecialchars($team['team_name']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No favorite teams yet.</p>
<?php endif; ?>

<p><a href="change_password.php">Change Password</a></p>
