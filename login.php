<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = isset($data['username']) ? trim($data['username']) : '';
$password = isset($data['password']) ? $data['password'] : '';

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Login ID aur password dono zaroori hain.']);
    exit;
}

$pdo = getDbConnection();

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode([
        'success'  => true,
        'username' => $user['username'],
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username ya password.']);
}
