<?php
include '../db/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $_POST['username']) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

try {
    $updates = [];
    $params = [];

    if (!empty($_POST['name'])) {
        $updates[] = "name = ?";
        $params[] = $_POST['name'];
    }

    if (!empty($_POST['mobile_num'])) {
        $updates[] = "mobile_number = ?";
        $params[] = $_POST['mobile_num'];
    }

    if (!empty($_POST['added_add'])) {
        $updates[] = "address = ?";
        $params[] = $_POST['added_add'];
    }

    if (!empty($_POST['new_password'])) {
        $updates[] = "password = ?";
        $params[] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    }

    if (empty($updates)) {
        echo json_encode(['status' => 'error', 'message' => 'No updates provided']);
        exit;
    }

    $params[] = $_POST['username'];
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE username = ?";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
