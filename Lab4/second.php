<?php
// lab4_ex2.php
declare(strict_types=1);
mb_internal_encoding('UTF-8');

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'emails.txt';
${''}
// helpers
function read_emails(string $path): array {
    if (!is_readable($path)) return [];
    $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    return array_map(static fn($l) => trim((string)$l), $lines);
}

function prepend_email(string $path, string $email): bool {
    $existing = @file_get_contents($path);
    if ($existing === false) { $existing = ''; }
    $existing = rtrim(str_replace(["\r\n", "\r"], "\n", $existing), "\n");
    $content = $email . PHP_EOL . ($existing !== '' ? $existing . PHP_EOL : '');
    return @file_put_contents($path, $content, LOCK_EX) !== false;
}

$errors = [];
$success = null;
$submittedEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedEmail = trim((string)($_POST['email'] ?? ''));
    if ($submittedEmail === '') {
        $errors[] = 'Lütfen bir e-posta adresi girin.';
    } elseif (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçersiz e-posta adresi formatı.';
    } elseif (mb_strlen($submittedEmail) > 256) {
        $errors[] = 'E-posta adresi çok uzun.';
    } else {
        $emailToStore = str_replace(["\r", "\n"], '', $submittedEmail);
        if (prepend_email($filename, $emailToStore)) {
            $success = 'E-posta kaydedildi.';
            $submittedEmail = '';
        } else {
            $errors[] = 'Dosyaya yazma sırasında hata oluştu.';
        }
    }
}

$emailsList = read_emails($filename);
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Exercise 2 — Email signup</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;max-width:900px}
    .card{border:1px solid #ddd;border-radius:8px;padding:1rem;margin-top:1rem}
    form {display:flex; gap:.5rem; align-items:center}
    input[type="email"]{flex:1;padding:.4rem;border:1px solid #bbb;border-radius:4px}
    button{padding:.4rem .6rem;border-radius:4px;border:1px solid #666;background:#f3f3f3;cursor:pointer}
    .error{background:#fff0f0;border:1px solid #ffc9c9;padding:.6rem;border-radius:6px;color:#900;margin-bottom:.6rem}
    .success{background:#f0fff4;border:1px solid #b9f0c9;padding:.6rem;border-radius:6px;color:#064;margin-bottom:.6rem}
    pre{white-space:pre-wrap;word-break:break-word}
    ul.list{padding-left:1.1rem}
    .muted{color:#666;font-size:.9rem}
  </style>
</head>
<body>
  <h1>The story of the gnome Jerome</h1>
  <p>Do you want to continue to follow the adventures of gnome Jerome? Then enter your email address!</p>

  <?php if ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <div class="error"><?= htmlspecialchars($e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" novalidate>
      <label for="email" class="visually-hidden" style="display:none">Email</label>
      <input
        id="email"
        name="email"
        type="email"
        placeholder="your@example.com"
        value="<?= htmlspecialchars($submittedEmail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
        required
        autocomplete="email"
      >
      <button type="submit">save email</button>
    </form>
    <p class="muted">Sadece geçerli e-posta adresleri kabul edilir. Yeni adres dosyanın en üstüne eklenir.</p>
  </div>

  <h2>Saved addresses (most recent first)</h2>
  <?php if (empty($emailsList)): ?>
    <p class="muted">Henüz kayıtlı adres yok.</p>
  <?php else: ?>
    <ul class="list">
      <?php foreach ($emailsList as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</body>
</html>
