<html>
<form method="post" action="kv4.php?anzahl=<?php echo $_POST['anzahl']; ?>">
<table>

<?php

$zeihlen = pow( 2, $_POST['anzahl']);
$zegativ = $_POST['anzahl'];

//---------------------------------------------- Überschrift------------------

echo "<tr><th>Nummer</th>";

$i = 0;
while( $i < $_POST['anzahl']){
	$a = $zegativ - $i;
	echo "<th>S".$a."</th>";
	$i++;
}

echo "<th>0</th><th>1</th></tr>";



$i=0;
while($i < $zeihlen){
	$v = "%0".$_POST['anzahl']."d";
	$bin = sprintf( $v , decbin($i));
	echo "<tr><td>".$i."</td>";
		for($y = 0; $y < $zegativ; $y++){
			echo "<td>".$bin[$y]."</td>";
		}
	echo '<td><input type="radio" name="'.$i.'" value="0" checked/></td><td><input type="radio" name="'.$i.'" value="1" /></td>';

	$i++;
}
?>

</table>

<input type="submit" value="Absenden" />

</form>
</html>
