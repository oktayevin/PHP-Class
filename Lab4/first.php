<?php
// lab4_ex1.php
declare(strict_types=1);
mb_internal_encoding('UTF-8');

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'GnomeJerome.txt';
$errors = [];
$content = '';
$totalLines = 0;
$emptyLines = 0;

if (!file_exists($filename)) {
    $errors[] = "Hata: 'GnomeJerome.txt' bulunamadı (".htmlspecialchars($filename).").";
} elseif (!is_readable($filename)) {
    $errors[] = "Hata: 'GnomeJerome.txt' okunamıyor. Dosya izinlerini kontrol edin.";
} else {
    // Dosyayı oku
    $raw = file_get_contents($filename);
    if ($raw === false) {
        $errors[] = "Hata: Dosya okunurken bir sorun oluştu.";
    } else {
        // Satır sonlarını normalize et (\r\n, \r -> \n)
        $normalized = str_replace(["\r\n", "\r"], "\n", $raw);

        // Satırları say
        $lines = explode("\n", $normalized);
        $totalLines = count($lines);

        // Boş satırları say (sadece whitespace içerenler dahil)
        foreach ($lines as $line) {
            if (trim($line) === '') {
                $emptyLines++;
            }
        }

        // Güvenli çıktı (HTML enjeksiyonunu önle)
        // <pre> içinde göstererek satır sonlarını/boşlukları koru
        $content = htmlspecialchars($raw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Lab 4: PHP and files — Exercise 1</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body>
  <h1>The story of the gnome Jerome</h1>

  <?php if ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <div class="error"><?= $e ?></div>
    <?php endforeach; ?>
  <?php else: ?>
    <!--<div class="meta">
      <strong>File:</strong> <?= htmlspecialchars(basename($filename)) ?>
      <span class="muted">|</span>
      <strong>Total lines:</strong> <?= $totalLines ?>
      <span class="muted">|</span>
      <strong>Empty lines:</strong> <?= $emptyLines ?>
    </div>-->

    <div class="card">
      <pre><?= $content ?></pre>
    </div>
  <?php endif; ?>

</body>
</html>
