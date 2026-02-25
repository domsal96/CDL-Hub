<?php
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CDL Hub - Players</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
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

$favorited_players = [];
if (isset($_SESSION['user_id'])) {
    $fav_stmt = $conn->prepare("SELECT player_id FROM player_favorites WHERE user_id = ?");
    $fav_stmt->bind_param("i", $_SESSION['user_id']);
    $fav_stmt->execute();
    $fav_result = $fav_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $favorited_players = array_column($fav_result, 'player_id');
    $fav_stmt->close();
}
?>
<div class="page-box" style="width:700px;">
    <h2>Players</h2>
    <form method="GET" class="search-form">
        <input class="search-input" type="text" name="search" placeholder="Search gamer tag..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn">Search</button>
    </form>
    <table class="styled-table">
        <tr>
            <th>Gamer Tag</th>
            <th>Team</th>
            <th>Role</th>
            <th>K/D</th>
            <?php if(isset($_SESSION['user_id'])): ?>
            <th>Favorite</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($results as $player): ?>
        <?php $is_favorited = in_array($player['player_id'], $favorited_players); ?>
        <tr>
            <td><?php echo htmlspecialchars($player['gamer_tag']); ?></td>
            <td><?php echo htmlspecialchars($player['team_name']); ?></td>
            <td><?php echo htmlspecialchars($player['role']); ?></td>
            <td><?php echo $player['kd_ratio']; ?></td>
            <?php if(isset($_SESSION['user_id'])): ?>
            <td>
                <button class="btn favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                        data-id="<?php echo $player['player_id']; ?>"
                        data-type="player">
                    <?php echo $is_favorited ? '✅ Favorited' : '⭐ Favorite'; ?>
                </button>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const type = btn.getAttribute('data-type');
        fetch('favorite_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'type=' + type + '&id=' + id + '&csrf_token=<?php echo $_SESSION['csrf_token'] ?? ''; ?>'
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.favorited) {
                    btn.textContent = '✅ Favorited';
                    btn.classList.add('favorited');
                } else {
                    btn.textContent = '⭐ Favorite';
                    btn.classList.remove('favorited');
                }
            } else {
                btn.textContent = '❌ Error';
            }
        });
    });
});
</script>

</body>
</html>
