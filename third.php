<?php
// third.php
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Third Exercise</title>
<style>
  body { font-family: Arial, sans-serif; }
  h2 { margin-bottom: 0; }
  p { margin-top: 4px; }
  table { border-collapse: collapse; margin-top: 10px; }
  th, td { border: 1px solid #000; padding: 6px 12px; text-align: right; }
  th { background: #eee; text-align: center; }
</style>
</head>
<body>
<h2>Profits calculation</h2>
<p>starting capital = 1000, duration = 10 and interest = 5</p>
<h3>Capital and interest per year</h3>

<?php
$capital = 1000.0;
$duration = 10;
$rate = 0.05;

echo "<table>";
echo "<tr><th>Year</th><th>Interest</th><th>Capital</th></tr>";

for ($year = 1; $year <= $duration; $year++) {
    $interest = $capital * $rate;
    $capital += $interest;
    // Format: iki ondalık basamak, nokta ayırıcı
    echo "<tr>";
    echo "<td style='text-align:center;'>$year</td>";
    echo "<td>" . number_format($interest, 2, '.', '') . "</td>";
    echo "<td>" . number_format($capital, 2, '.', '') . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
</body>
</html>