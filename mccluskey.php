<?php
session_start(); //Beginn der session zum übertragen der varnum variable( Anzahl der Tabellenzeilen)

$input=array(); //Deklarieren des Arrays, in dem die Zeilennummern gespeichert werden, die laut user 1 sind
$sumnum=array(); //Deklarieren des Arrays, in dem die Zeilennummern und ihre Anzahl an 1en gespeichert werden
$groups=array();

$dec = 0; //Deklarierungen von Vars
$bin = 0;
$sum = 0;
$sumnumsort = 0;
$count=0;
$safearray=0;

$vmax=$_SESSION["nummax"]; //Session üernimmt die Anzahl der Tabellenzeilen(Prime)
$varM=$_SESSION["varnum"]; //Session übernimmt die Anzahl der Eingangsvariablen

foreach($_POST AS $zeile) //Die Post-Variable wird als zeile im foreach gespeichert
{ array_push($input, $zeile); //Alle $_Posts werden in $input gepusht
}

foreach($input as $i){ //Jedes Prim wird als $i in die Schleife gegeben
	echo "Gewählt:".$i."; ";
	$dec=decbin($i); //$i wird in eine Binärzah umgewandelt und in einem String gespeichert
	$bin=str_split($dec); //Der String mit der Binärzahl wird in ein array gespeichert, jede zahl als einzelner eintrag
	$sum=array_sum($bin); //Alle 0en und 1en des Arrays werden addiert 
	$sumnum[$i]=$sum; // Die Prime werden mit der Summe ihrer Binäre Zahlen im $sumnum array gespeichert
}

asort($sumnum);
echo_r($sumnum);
for($i=0;$i<$vmax;$i++){
	foreach($sumnum as $a=>$e){
		if($i==$e){
			$dec=decbin($a);
			$bin=str_split($dec);
			$count = count($bin);
			$safearray=array_reverse($bin);
			$nills=$varM-$count;
			for($u=0;$u<$nills;$u++){
				array_push($safearray, 0);
			}
			$safearray=array_reverse($safearray);
			$groups[$a]=$safearray;
		}
	}
}

echo_r($groups);

function echo_r($x)
{
	echo '<pre stlye="color:red">DEBUG:'.print_r($x, true).'</pre>';
}
?>
