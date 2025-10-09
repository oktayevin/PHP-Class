<?php
// lab4_ex2.php
declare(strict_types=1);
mb_internal_encoding('UTF-8');

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'emails.txt';
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
        $errors[] = 'Please Enter an e-mail address.';
    } elseif (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid e-mail address.';
    } elseif (mb_strlen($submittedEmail) > 256) {
        $errors[] = 'E-mail is too long.';
    } else {
        $emailToStore = str_replace(["\r", "\n"], '', $submittedEmail);
        if (prepend_email($filename, $emailToStore)) {
            $success = 'E-mail saved.';
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
</head>
<body>
  <h1>The story of the gnome Jerome</h1>
  <p>Do you want to continue to follow the adventures of gnome Jerome? Then enter your email address!</p>
  <p>A new episode from the story of the gnome Jerome will be sent to you everyday!</p>

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
        placeholder="mail@example.com"
        value="<?= htmlspecialchars($submittedEmail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
        required
        autocomplete="email"
      >
      <button type="submit">save email</button>
    </form>
  </div>


  <div style="margin-top:2rem;">
    <form action="third.php" method="get">
      <button type="submit">Admin module</button>
    </form>
  </div>

</body>
</html>
