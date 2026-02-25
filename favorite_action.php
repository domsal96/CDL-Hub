<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit();
}

$user_id = $_SESSION['user_id'];
$type = $_POST['type'] ?? '';
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    exit();
}

if ($type === 'player') {
    $check = $conn->prepare("SELECT * FROM player_favorites WHERE user_id = ? AND player_id = ?");
    $check->bind_param("ii", $user_id, $id);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();

    if ($exists) {
        $stmt = $conn->prepare("DELETE FROM player_favorites WHERE user_id = ? AND player_id = ?");
    } else {
        $stmt = $conn->prepare("INSERT INTO player_favorites (user_id, player_id) VALUES (?, ?)");
    }
} elseif ($type === 'team') {
    $check = $conn->prepare("SELECT * FROM team_favorites WHERE user_id = ? AND team_id = ?");
    $check->bind_param("ii", $user_id, $id);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();

    if ($exists) {
        $stmt = $conn->prepare("DELETE FROM team_favorites WHERE user_id = ? AND team_id = ?");
    } else {
        $stmt = $conn->prepare("INSERT INTO team_favorites (user_id, team_id) VALUES (?, ?)");
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
    exit();
}

$stmt->bind_param("ii", $user_id, $id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'favorited' => !$exists]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
$stmt->close();
?>