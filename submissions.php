<?php
session_start();

$dataFile = __DIR__ . '/submissions.json';

$rows = [];
if (file_exists($dataFile)) {
    $jsonContent = file_get_contents($dataFile);
    $decoded = json_decode($jsonContent, true);
    if (is_array($decoded)) {
        $rows = $decoded;
        usort($rows, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
    }
}
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
    .wrap { max-width: 1000px; margin: 0 auto; }
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
    table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    th, td { text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--line); }
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
    .empty { padding: 40px; text-align: center; color: var(--muted); }
    .count { font-size: 0.85rem; color: var(--muted); margin-bottom: 12px; }
    .copy-btn {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
    }
    .copy-btn:hover { opacity: 0.9; }
    .copy-btn.copied { background: #2e7d32; }
    .toolbar { display: flex; justify-content: flex-end; margin-bottom: 12px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="topbar">
      <div>
        <h1>Submissions</h1>
        <p class="sub">
          <?php if (isset($_SESSION['username'])): ?>
            Signed in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
          <?php else: ?>
            Data file se load kiya gaya
          <?php endif; ?>
        </p>
      </div>
      <a href="index.html">&larr; Back to form</a>
    </div>
    <p class="count"><?php echo count($rows); ?> total submission(s)</p>
    <div class="toolbar">
      <button class="copy-btn" id="copyBtn" onclick="copyTable()">Copy table</button>
    </div>
    <div class="card">
      <?php if (count($rows) > 0): ?>
        <table id="submissionsTable">
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

  <script>
    function copyTable() {
      const table = document.getElementById('submissionsTable');
      if (!table) return;
      let text = '';
      const rows = table.querySelectorAll('tr');
      rows.forEach(row => {
        const cells = row.querySelectorAll('th, td');
        const rowText = Array.from(cells).map(cell => cell.innerText.trim()).join('\t');
        text += rowText + '\n';
      });
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copyBtn');
        const original = btn.textContent;
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(() => {
          btn.textContent = original;
          btn.classList.remove('copied');
        }, 1500);
      }).catch(() => {
        alert('Copy fail ho gaya, browser permission check karein.');
      });
    }
  </script>
</body>
</html>
