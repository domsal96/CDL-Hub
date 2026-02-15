<?php
require 'config.php';
require 'header.php';

$search = "";
$results = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT players.*, teams.team_name 
                            FROM players 
                            JOIN teams ON players.team_id = teams.team_id 
                            WHERE gamer_tag LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $result = $conn->query("SELECT players.*, teams.team_name 
                            FROM players 
                            JOIN teams ON players.team_id = teams.team_id");
    $results = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Players - CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Search Players</h2>

<form method="GET" class="search-form">
    <input class="search-input" type="text" name="search" placeholder="Search gamer tag..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<hr>

<table border="1" cellpadding="5">
<tr>
    <th>Gamer Tag</th>
    <th>Team</th>
    <th>Role</th>
    <th>K/D</th>
</tr>

<?php foreach ($results as $player): ?>
<tr>
    <td><?php echo htmlspecialchars($player['gamer_tag']); ?></td>
    <td><?php echo htmlspecialchars($player['team_name']); ?></td>
    <td><?php echo htmlspecialchars($player['role']); ?></td>
    <td><?php echo $player['kd_ratio']; ?></td>
</tr>
<?php endforeach; ?>
</table>
