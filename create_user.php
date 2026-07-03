<?php
require_once 'config.php';

$username = isset($_GET['username']) ? trim($_GET['username']) : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Usage: create_user.php?username=xxx&password=yyy']);
    exit;
}

$pdo = getDbConnection();
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:u, :p)');
    $stmt->execute(['u' => $username, 'p' => $hash]);
    echo json_encode(['success' => true, 'message' => "User '$username' ban gaya."]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'User pehle se maujood hai ya error aayi.']);
}
