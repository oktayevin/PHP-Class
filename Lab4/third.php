<?php
// admin.php
declare(strict_types=1);
mb_internal_encoding('UTF-8');

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'emails.txt';
$errors = [];
$success = '';
$action = (string)($_GET['action'] ?? '');
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

// Helper: read file into array of non-empty lines
function read_emails(string $fn): array {
  if (!file_exists($fn)) return [];
  $lines = @file($fn, FILE_IGNORE_NEW_LINES) ?: [];
  $out = [];
  foreach ($lines as $ln) {
    $t = trim((string)$ln);
    if ($t !== '') $out[] = $t;
  }
  return $out;
}

// Helper: write whole list atomically
function write_emails(string $fn, array $emails): bool {
  $toWrite = '';
  foreach ($emails as $e) { $toWrite .= $e . PHP_EOL; }
  return @file_put_contents($fn, $toWrite, LOCK_EX) !== false;
}

// Handle delete via GET link (as in sample)
if ($action === 'delete' && $id >= 0) {
    $emails = read_emails($filename);
    if (!isset($emails[$id])) {
        $errors[] = 'Silinecek e-posta bulunamadı.';
    } else {
        array_splice($emails, $id, 1);
        if (write_emails($filename, $emails)) {
          $success = 'Adres silindi.';
        } else {
          $errors[] = 'Dosya açılamıyor.';
        }
    }
}

// Show edit form (GET action=edit&id=...) or handle submit change (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_change'])) {
    $postId = isset($_POST['id']) ? (int)$_POST['id'] : -1;
    $newEmail = trim((string)($_POST['email'] ?? ''));
    if ($newEmail === '' || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir e-posta girin.';
    } else {
        $emails = read_emails($filename);
        if (!isset($emails[$postId])) {
            $errors[] = 'Düzenlenecek adres bulunamadı.';
        } else {
            $emails[$postId] = str_replace(["\r", "\n"], '', $newEmail);
            if (write_emails($filename, $emails)) {
              $success = 'Adres güncellendi.';
              // after change, show list
              $action = '';
            } else {
              $errors[] = 'Dosya açılamıyor.';
            }
        }
    }
}

// Load emails for display
$emailsList = read_emails($filename);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin page: The story of the gnome Jerome</title>
  <style>
    body{font-family:Arial,serif;margin:20px;max-width:760px}
    table{border-collapse:collapse;width:100%}
    th,td{padding:6px 8px;border-bottom:1px solid #ddd;text-align:left;font-size:14px}
    a{color:#06c;text-decoration:underline}
    .muted{color:#666;font-size:90%}
    .error{color:#900;margin:6px 0}
    .success{color:#060;margin:6px 0}
    .back{display:inline-block;margin-top:10px}
    input[type="text"]{width:300px;padding:4px}
    input[type="submit"]{padding:4px 8px}
  </style>
</head>
<body>
  <h1>Admin page: The story of the gnome Jerome</h1>
  <p>Here you can change or delete the saved email addresses.</p>

  <?php foreach ($errors as $e): ?>
    <div class="error"><?= htmlspecialchars($e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
  <?php endforeach; ?>
  <?php if ($success): ?><div class="success"><?= htmlspecialchars($success, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div><?php endif; ?>

  <?php if ($action === 'edit' && isset($emailsList[$id])): ?>
    <!-- edit form -->
    <form method="post" style="margin-top:12px">
      <label for="email">email</label><br>
      <input type="text" id="email" name="email" value="<?= htmlspecialchars($emailsList[$id], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="submit" name="submit_change" value="submit: change">
    </form>
    <p class="muted"><a href="admin.php">Back to the main page</a></p>

  <?php else: ?>
    <table>
      <thead>
        <tr><th>email</th><th>action</th></tr>
      </thead>
      <tbody>
        <?php if (empty($emailsList)): ?>
          <tr><td colspan="2" class="muted">No saved addresses.</td></tr>
        <?php else: ?>
          <?php foreach ($emailsList as $idx => $em): ?>
            <tr>
              <td><?= htmlspecialchars($em, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
              <td>
                <a href="admin.php?action=delete&id=<?= $idx ?>" onclick="return confirm('Delete this address?')">delete</a>
                 &nbsp;
                <a href="admin.php?action=edit&id=<?= $idx ?>">edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <p class="muted"><a href="second.php">Back to the main page</a></p>
  <?php endif; ?>
</body>
</html>
