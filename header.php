<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <a href="index.php">Home</a> |
    <a href="teams.php">Teams</a> |
    <a href="players.php">Players</a> |
    <a href="matches.php">Matches</a> |

    <?php if (isset($_SESSION['username'])): ?>
        <a href="profile.php">Profile</a> |
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a> |
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<hr>