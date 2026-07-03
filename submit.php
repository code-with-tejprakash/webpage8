<?php
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please pehle login karein.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name    = isset($data['name']) ? trim($data['name']) : '';
$email   = isset($data['email']) ? trim($data['email']) : '';
$address = isset($data['address']) ? trim($data['address']) : '';

if ($name === '' || $email === '' || $address === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Saari fields bharni zaroori hain.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Valid email address daalein.']);
    exit;
}

$pdo = getDbConnection();

$stmt = $pdo->prepare(
    'INSERT INTO submissions (name, email, address, submitted_by) VALUES (:name, :email, :address, :submitted_by)'
);
$stmt->execute([
    'name'         => $name,
    'email'        => $email,
    'address'      => $address,
    'submitted_by' => $_SESSION['username'],
]);

echo json_encode([
    'success' => true,
    'message' => 'Aapka data safaltapoorvak save ho gaya hai.',
    'id'      => $pdo->lastInsertId(),
]);
