<?php
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CDL Hub - Teams</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
require 'header.php';

$search = "";
$results = [];
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM teams WHERE team_name LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM teams");
    $results = $result->fetch_all(MYSQLI_ASSOC);
}

$favorited_teams = [];
if (isset($_SESSION['user_id'])) {
    $fav_stmt = $conn->prepare("SELECT team_id FROM team_favorites WHERE user_id = ?");
    $fav_stmt->bind_param("i", $_SESSION['user_id']);
    $fav_stmt->execute();
    $fav_result = $fav_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $favorited_teams = array_column($fav_result, 'team_id');
    $fav_stmt->close();
}
?>
<div class="page-box" style="width:700px;">
    <h2>Teams</h2>
    <form method="GET" class="search-form">
        <input class="search-input" type="text" name="search" placeholder="Search team name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn">Search</button>
    </form>
    <table class="styled-table">
        <tr>
            <th>Team Name</th>
            <th>City</th>
            <th>Wins</th>
            <th>Losses</th>
            <?php if(isset($_SESSION['user_id'])): ?>
            <th>Favorite</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($results as $team): ?>
        <?php $is_favorited = in_array($team['team_id'], $favorited_teams); ?>
        <tr>
            <td><?php echo htmlspecialchars($team['team_name']); ?></td>
            <td><?php echo htmlspecialchars($team['city']); ?></td>
            <td><?php echo $team['wins']; ?></td>
            <td><?php echo $team['losses']; ?></td>
            <?php if(isset($_SESSION['user_id'])): ?>
            <td>
                <button class="btn favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                        data-id="<?php echo $team['team_id']; ?>"
                        data-type="team">
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
