<?php
// fourth.php
define("school", "Thomas More, Campus De Nayer");
$participants = 260;
$absentees = 57;
$priceP = 25.80;
$priceC = 15.50;

$total = ($participants * $priceP) + ($absentees * $priceC);
echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Fourth Exercise</title>\n";
echo "</head>\n";
echo "<body>\n";
echo"    <h3>Teambuilding</h3>\n";

echo"    <h4>1. Total Price:</h4>\n";
echo"    <p>" . school . " has to pay " . $total . " euro to Sporta for this teambuilding activity.</p>\n";

echo"    <h4>2. Number of Paticipants:</h4>\n";
echo"    <p>" . $participants . " students from " . school . " participated in this teambuilding activity, " . $absentees . " students were absent.</p>\n";
echo "</body>\n";
echo "</html>";
?>