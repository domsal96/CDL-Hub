<?php
require 'config.php';
require 'header.php';

$query = "
SELECT m.match_date,
       t1.team_name AS team1,
       t2.team_name AS team2,
       tw.team_name AS winner
FROM matches m
JOIN teams t1 ON m.team1_id = t1.team_id
JOIN teams t2 ON m.team2_id = t2.team_id
LEFT JOIN teams tw ON m.winner_id = tw.team_id
ORDER BY m.match_date DESC
";

$result = $conn->query($query);
$matches = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Matches - CDL Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Match Schedule</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Team 1</th>
    <th>Team 2</th>
    <th>Winner</th>
</tr>

<?php foreach ($matches as $match): ?>
<tr>
    <td><?php echo $match['match_date']; ?></td>
    <td><?php echo htmlspecialchars($match['team1']); ?></td>
    <td><?php echo htmlspecialchars($match['team2']); ?></td>
    <td><?php echo $match['winner'] ? htmlspecialchars($match['winner']) : "TBD"; ?></td>
</tr>
<?php endforeach; ?>
</table>
