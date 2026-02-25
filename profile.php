<?php
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CDL Hub - Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt_teams = $conn->prepare("
    SELECT teams.team_name
    FROM team_favorites
    JOIN teams ON team_favorites.team_id = teams.team_id
    WHERE team_favorites.user_id = ?
");
$stmt_teams->bind_param("i", $_SESSION['user_id']);
$stmt_teams->execute();
$fav_teams = $stmt_teams->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_teams->close();

$stmt_players = $conn->prepare("
    SELECT players.gamer_tag, teams.team_name
    FROM player_favorites
    JOIN players ON player_favorites.player_id = players.player_id
    JOIN teams ON players.team_id = teams.team_id
    WHERE player_favorites.user_id = ?
");
$stmt_players->bind_param("i", $_SESSION['user_id']);
$stmt_players->execute();
$fav_players = $stmt_players->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_players->close();
?>
<div class="page-box">
    <h2>User Profile</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <h3>Your Favorite Teams</h3>
    <?php if(count($fav_teams) > 0): ?>
        <ul class="fav-list">
            <?php foreach($fav_teams as $team): ?>
                <li><?php echo htmlspecialchars($team['team_name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No favorite teams yet.</p>
    <?php endif; ?>

    <h3>Your Favorite Players</h3>
    <?php if(count($fav_players) > 0): ?>
        <ul class="fav-list">
            <?php foreach($fav_players as $player): ?>
                <li><?php echo htmlspecialchars($player['gamer_tag']); ?> (<?php echo htmlspecialchars($player['team_name']); ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No favorite players yet.</p>
    <?php endif; ?>

    <a class="btn" href="change_password.php">Change Password</a>
</div>

</body>
</html>
