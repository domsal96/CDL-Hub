<?php
require 'config.php';
require 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>CDL Hub</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1 style="text-align:center;">Welcome to CDL Hub</h1>

<div class="card">
<h2>Top Players of the Week</h2>

<?php
$result = $conn->query("
SELECT gamer_tag, kd_ratio 
FROM players 
ORDER BY kd_ratio DESC 
LIMIT 5
");

while($row = $result->fetch_assoc()){
    echo "<p><strong>{$row['gamer_tag']}</strong> — KD: {$row['kd_ratio']}</p>";
}
?>
</div>


<div class="card">
<h2>Featured Match</h2>

<?php
$result = $conn->query("
SELECT m.match_date, t1.team_name AS team1, t2.team_name AS team2
FROM matches m
JOIN teams t1 ON m.team1_id = t1.team_id
JOIN teams t2 ON m.team2_id = t2.team_id
ORDER BY m.match_date DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    echo "<p>{$row['team1']} vs {$row['team2']}</p>";
    echo "<p>Date: {$row['match_date']}</p>";
}
?>
</div>


<div class="card">
<h2>Upcoming Events</h2>

<p>Major 1</p>
<p>Major 2</p>
<p>Major 3</p>
<p>Major 4</p>
<p>Champs</p>

</div>

</body>
</html>
