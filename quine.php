<html>
<?php
session_start();
$dec=0;
$bin=0;
$count=0;
$ZR=0;
$name=0;

$varM= $_POST["vars"]; // 2 wird später zur Variable aus dem Formular geändert, wie viele Eingangscariablen es gibt
$max = pow(2, $varM); // Eingangsvariablen zum Quadrat = Menge der Zeilen in der Wahrheitstabelle
$_SESSION["nummax"]= $max;
$_SESSION["varnum"]= $varM;
?>



<!--Erstellen der Tabelle-->
<form action="mccluskey.php" method="post"> <!--Damit die Checkboxen funktionieren wird um die Tabelle ein formular erstellt -->
<table border="1"> <!-- Öffnen der Tabelle mit sichtbaren grenzen und linien -->
<tr> <!--Erstellen der ersten Zeile (Kopfzeile)-->
<th></th><!--Leere Zelle über den Zeilennummerierungen (oben links) -->
<?php
//Diese Schleife erstellt den Kopf der Tabelle nach Variablenmenge
for($i=0;$i<$varM;$i++){
	echo "<th>X".$i." </th>"; //Die Kopfleiste der Tabelle wird nach mange der Variablen mit Xn gefüllt
}
?>
<th>__</th> <!--Eine Leerzeile zwischen den Variablen und der Zeile in der man 1 oder 0 definiert -->
<th>Y </th> <!--Nach der Leerzeile wird ein Y als Ausgabevariable ausgegeben -->
</tr>
<?php
//Diese Schleife erstellt die Zeilenbezeichnungen der Tabelle nach variablenmange
for($i=0;$i<$max;$i++){
	echo "<tr>"; //Neue Zeile (row)
	echo "<td><b>".$i."</b></td>"; //der inhalt der ersten Zelle ist die Nummerierung / wird mit dem zähler der Schlleife geführt (0->N)
	$dec=decbin($i); // Die Dezimale zahl der Zeilennummer wird in eine Binäre Zahl, in einem String umgewandelt (dec -> bin)
	$bin=str_split($dec); //Der String aus decbin wird mit str_split in die einzelenen Zahlen zerlegt und in einem array gespeichert FALSCHRUM!?
	$count=count($bin); //Die anzahl der Nullen und Einsen im Array wird gezählt
			if($count<$varM){ //Falls die Binäre zahl nicht die Tabelle bis zum __ voll macht, füllt die folgende schleife mit nullen auf
			$ZR=$varM-$count; // Zwischenrechnung = Spaltenanzahl - Gezählte zahlen im array
			for($a=0;$a<$ZR;$a++){ //Die Menge der Zwischenrechnung wird mit nullen gefüllt
				echo "<td>0</td>"; //Die nullen werden geschrieben
			}
		}
		for($e=0;$e<$count;$e++){ //For schleife die alle einträge des arrays in die tabelle schreibt
	echo "<td>".$bin[$e]."</td>"; //Die Ausgabe des Arrays von 0 bis zum ergebnis des Zählens, nach fortschritt der schleife
		}
	$name="check".$i;
	echo "<td></td>"; //Leere Spalte unter __ im header
	echo "<td><input type='checkbox' id='cb' name='$name' value='$i'></td>"; //Unter dem Y wird zum Ankreuzen, später als 1 indikator eine checkbox geschrieben
	echo "</tr>"; //Schließen der Zeile
}
?>
</table><!--Schliessen der Tabelle -->
<br><button type="submit">Absenden</button></a>
</form>
</html>
