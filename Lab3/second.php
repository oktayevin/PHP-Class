<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second Activity</title>
    <style>
        table{border-collapse: collapse; border: 4px solid #000; margin: 20px 0px;}
        td, th {padding: 6px 12px; text-align:center;}
        tr {border-bottom: 1px solid #000;}
    </style>
</head>
<body>
    <?php
    $number = 5;
    echo "<h2>Multiplication table of {$number}<h2>";
    echo "<table>";
    for ($i = 1; $i <= 10; $i++) {
        echo "<tr>";
        echo "<td>{$number}</td>";
        echo "<td>&times;</td>";
        echo "<td>$i</td>";
        echo "<td>=</td>";
        echo "<td>" . ($number * $i) . "</td>";
        echo "</tr>";
    }
    ?>
</body>
</html>