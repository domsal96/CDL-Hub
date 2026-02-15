<?php
session_start();
require 'config.php';
require 'header.php';


$search = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = trim($_GET['search']);
    
    $query = "SELECT * FROM teams WHERE team_name LIKE ?";
    $stmt = $conn->prepare($query);

    $search_param = "%" . $search . "%"; 
    $stmt->bind_param("s", $search_param);
    $stmt->execute();

    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM teams");
    $results = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teams - CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Search Teams</h2>

<form method="GET" class="search-form">
    <input class="search-input" type="text" name="search" placeholder="Search team name..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<hr>

<h3>Results:</h3>

<?php if (count($results) > 0): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Team Name</th>
            <th>City</th>
            <th>Wins</th>
            <th>Losses</th>
        </tr>
        <?php foreach ($results as $team): ?>
        <tr>
            <td><?php echo htmlspecialchars($team['team_name']); ?></td>
            <td><?php echo htmlspecialchars($team['city']); ?></td>
            <td><?php echo $team['wins']; ?></td>
            <td><?php echo $team['losses']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>

</body>
</html>
