<html>
<?php 
$unit = $_GET[unit];
echo "<img src=graph.php?show=1&unit=month>"; 
echo "<img src=graph.php?show=1&unit=week>";
echo "<img src=graph.php?show=1&unit=day>";

?>
<hr>
<?php include 'graph.php'; ?>
<html>
