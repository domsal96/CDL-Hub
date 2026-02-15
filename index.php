<?php require 'header.php';?>
<!DOCTYPE html>
<html>
<head>
    <title>CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Welcome to CDL Hub</h1>
<p>Your home for Call of Duty League stats, teams, and matches.</p>

<?php if (isset($_SESSION['username'])): ?>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
<?php endif; ?>

</body>
</html>
