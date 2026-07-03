<?php
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, name, email, address, submitted_by, created_at FROM submissions ORDER BY created_at DESC');
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Submissions Data</title>
  <style>
    :root {
      --ink: #1c2430;
      --muted: #5c6675;
      --line: #d5dae2;
      --accent: #1a5fb4;
      --bg: #eef1f5;
      --card: #ffffff;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background: var(--bg);
      color: var(--ink);
      padding: 24px 16px;
    }
    .wrap {
      max-width: 1000px;
      margin: 0 auto;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .topbar a {
      color: var(--accent);
      font-weight: 600;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .topbar a:hover { text-decoration: underline; }
    h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: 4px; }
    .sub { color: var(--muted); font-size: 0.9rem; margin-bottom: 20px; }
    .card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(28, 36, 48, 0.06);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }
    th, td {
      text-align: left;
      padding: 12px 16px;
      border-bottom: 1px solid var(--line);
    }
    th {
      background: #f6f8fa;
      font-weight: 600;
      color: var(--muted);
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fbfcfe; }
    .empty {
      padding: 40px;
      text-align: center;
      color: var(--muted);
    }
    .count {
      font-size: 0.85rem;
      color: var(--muted);
      margin-bottom: 12px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="topbar">
      <div>
        <h1>Submissions</h1>
        <p class="sub">Signed in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
      </div>
      <a href="index.html">&larr; Back to form</a>
    </div>

    <p class="count"><?php echo count($rows); ?> total submission(s)</p>

    <div class="card">
      <?php if (count($rows) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Address</th>
              <th>Submitted by</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['submitted_by']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty">Abhi tak koi submission nahi hai.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
