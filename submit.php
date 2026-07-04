<?php
session_start();

// Agar login zaroori hai to yeh check rakhein, warna hata dein
$submitted_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $email === '') {
    die('Name aur Email zaroori hain.');
}

$dataFile = __DIR__ . '/submissions.json';

// Agar file nahi hai to khaali array se banayein
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

// Purana data padhein
$jsonContent = file_get_contents($dataFile);
$rows = json_decode($jsonContent, true);
if (!is_array($rows)) {
    $rows = [];
}

// Naya entry add karein
$newId = count($rows) > 0 ? (end($rows)['id'] + 1) : 1;
$rows[] = [
    'id'            => $newId,
    'name'          => $name,
    'email'         => $email,
    'address'       => $address,
    'submitted_by'  => $submitted_by,
    'created_at'    => date('Y-m-d H:i:s'),
];

// File lock ke saath wapas save karein (taaki ek saath do submissions clash na karein)
$fp = fopen($dataFile, 'c+');
if (flock($fp, LOCK_EX)) {
    ftruncate($fp, 0);
    fwrite($fp, json_encode($rows, JSON_PRETTY_PRINT));
    fflush($fp);
    flock($fp, LOCK_UN);
}
fclose($fp);

header('Location: submissions.php');
exit;
