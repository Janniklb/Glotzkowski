<?php 
//------------------------------------------------------------------- Einstellung
# Minimierungsverfahren ( 1 oder 0 )
$mode = 1;
# Übergabeart und name der Variablen anzahl
$variablen_anzahl_mode = "_GET";
$variablen_anzahl_name = "anzahl";
# Übergabeart der Variablen
$variablen_mode = "_POST";
//------------------------------------------------------------------- Include
include 'function.php';
//--------------------------------------------------------------- Variablen
$anzahl_variablen = $$variablen_anzahl_mode[ $variablen_anzahl_name ];
//_____________________________________________________________________
$erstes_array = array();
foreach( $$variablen_mode as $nummer => $wert ){
        if( $wert == $mode ){
                $nummer_binaer = decbin( $nummer );
                $nummer_binaer_array = str_split( $nummer_binaer );
                $gruppe = array_sum( $nummer_binaer_array );
         
                $erstes_array[ $nummer ] = $gruppe;
        }
}
asort( $erstes_array );
//____________________________________________________________________
$mini_array0 = array();
foreach( $erstes_array as $nummer => $gruppe ){ // z.B. 5 => 101 (binär)
        
	$merken[ 'gruppe' ] = $gruppe;
        $merken[ 'benutzt' ] = 0;
        $merken[ 'deczahl' ] = $nummer;
	array_push( $mini_array0, $merken );
}
//-----------------------------------------------------------------------------------------
$mini_array = array();
$mini_array_zaehl_zahl = 0;
do{
	$ende = false;
	$mini_array_benutzt_name = "mini_array".$mini_array_zaehl_zahl;
	$mini_array_zaehl_zahl++;
	$mini_array_count = count( $$mini_array_benutzt_name );
	$mini_array_speicher_name = "mini_array".$mini_array_zaehl_zahl;
	$$mini_array_speicher_name = array();
	
	for( $spalte1 = 0; $spalte1 < $mini_array_count; $spalte1++ ){
		for( $spalte2 = 0; $spalte2 < $mini_array_count; $spalte2++ ){
			if( ( $$mini_array_benutzt_name[ $spalte1 ][ "gruppe" ] + 1 ) == $$mini_array_benutzt_name[ $spalte2 ][ "gruppe" ] ){
				$array_1 = binaer( $$mini_array_benutzt_name[ $spalte1 ][ "deczahl" ], $anzahl_variablen );
				$array_2 = binaer( $$mini_array_benutzt_name[ $spalte2 ][ "deczahl" ], $anzahl_variablen );
				$unterschied = array_diff_assoc( $array_1, $array_2 );
				if( count( $unterschied ) == 1 ){
					$kurz_array[ "gruppe" ] = $$mini_array_benutzt_name[ $spalte1 ][ "gruppe" ];
					$kurz_array[ "benutzt" ] = 0;
					$kurz_array[ "deczahl" ] = $$mini_array_benutzt_name[ $spalte1 ][ "deczahl" ].":".$$mini_array_benutzt_name[ $spalte2 ][ "deczahl" ];
					$ver1 = binaer( $kurz_array[ "deczahl" ], $anzahl_variablen );
				
					$speicher = true;
					foreach( $$mini_array_speicher_name as $key => $wert ){
						$ver2 = binaer( $$mini_array_speicher_name[ $key ][ "deczahl" ], $anzahl_variablen );
						if( empty( array_diff_assoc( $ver1, $ver2 ) ) ){
							$speicher = false;
						}
					}
					if( $speicher ){
						array_push( $$mini_array_speicher_name, $kurz_array );
					}
					$$mini_array_benutzt_name[ $spalte1 ][ "benutzt" ] = 1;
					$$mini_array_benutzt_name[ $spalte2 ][ "benutzt" ] = 1;
				}
			}
		}
	}
	
	foreach( $$mini_array_benutzt_name as $key => $wert ){
		if( $$mini_array_benutzt_name[ $key ][ "benutzt" ] == 0 ){
			array_push( $$mini_array_speicher_name, $$mini_array_benutzt_name[ $key ] );
		}else{
			$ende = true;
		}
	}
	
	if( $ende == false ){
		$mini_array = $$mini_array_speicher_name;
	}	
}while($ende);
//----------------------------------------------------------------- LETZTE MINIMIERUNG----------------------------------------------------------
//Ab hier verwende ich den $mini_array von Joschka
//Im obigen Code hat bereits eine minimierung stattgefunden. Zusammen minimierte Zahlen wurden in einem Array gespeichert
//Bsp. für ein Teil:	Ganzes: S1S3S4 v !S6S3S9 ein Teil wäre dann S1S3S4
//Jedes Teil hat ein eigenes unterarray im $mini_array, in dem die optimierten Zahlen dieses arrays gespeichert sind Bsp: 0,3,6,7 => Die Kombination der Eingänge in der Wahrheitstabelle in der Zeile 0,3,6 und 7 ergeben 1

//Variablen deklarieren
$einshoch = 0;
$count = 0;
$countx=0;
$zuzaehlen=0;
$zaehler = 0;
$minzahl = 0;
$vergleichsbasis = 0;
$explodespeicher = 0;
$countspeicher = 0;

//Arrays deklarieren
$deczahlenx = array();
$deczahlen = array();
$vergleichswerte = array();
	
//Zählt wie viele Teile die Funktionen in der Endformel haben würde (Vor der kommenden optimierung)
$count = count($mini_array);


//Diese Schleife zählt wie viele Zahlen pro Teil optimiert wurden. Die Info wird mit einer Identifikationsnummer für das Teil an den Mini_array angehängt
for($i=0; $i<$count; $i++){
	$zuzaehlen = explode(":", $mini_array[$i]["deczahl"]); 
	$countx = count($zuzaehlen); 
	$mini_array[$i]["deczahlen"] = $countx; 
	array_push($deczahlenx, $i, $countx); 
	array_push($deczahlen, $deczahlenx); 
	$deczahlenx=array(); 
}


//Hier wird eine Schleife geöffnet, die die ganze optimierung umfasst und läuft bis alle Teile mit den anderen verglichen wurden
//Teile, deren optimierte Zahlen auch in anderen vorkommen, werden aussortiert
for($e=0; $e<$count+1; $e++){
	
	//In der folgenden Doppelschleife wird überprüft, in welchem / welchen Teil der Formel die wenigsten Zahlen optimiert wurden.
	for($i=0; $i<$count; $i++){
		for($a=0; $a<$count; $a++){
			if($deczahlen[$i][1] <= $deczahlen[$a][1] && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
			$zaehler++;
			$minzahl = $i;					//Hier wird die Indentifikationsnummer des Arrays mit den wenigsten optimierten Zahlen gespeichert
			}
	
			if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
				break;
			}
		}
		if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
			break;
		}
	}
	

//Mit der Information in welchem Teil die wenigsten Zahlen optimiert wurden, werden eben diese Zahlen herausgefunden und in einem extra array gespeichert
	$arrminzahl = $deczahlen[$minzahl][0];
	$vergleichsbasis = explode(":", $mini_array[$arrminzahl]["deczahl"]);
	$vergleichswerte = array();
	
	
//Die optimierten Zahlen aller anderen Teile werden in einem Zweiten array gespeichert
	for($i=0;$i<$count;$i++){
		if($i != $arrminzahl && ($mini_array[$i]["benutzt"]==0 || $mini_array[$i]["benutzt"]==2)){
		$explodespeicher = explode(":", $mini_array[$i]["deczahl"]);
		$countspeicher = count($explodespeicher);
			for($a=0; $a<$countspeicher; $a++){
				array_push($vergleichswerte, $explodespeicher[$a]);
			}
		}
	$countspeicher = 0;
	$explodespeicher = 0;
	}

//Nachfolgend werden die beiden Arrays verglichen. Wenn alle Zahlen des einzelnen Teils irgendwo in den anderen Teilen vorkommen, wird das einzelne Teil nicht mehr benötigt
//Ist nur eine Zahl des einzelen Teils einzigartig, dann wird das Teil für die Endformel übernommen
	
	if(array_intersect($vergleichsbasis, $vergleichswerte) == ($vergleichsbasis)){
		$mini_array[$arrminzahl]["benutzt"]=1;	//Nicht einzigartige Teile werden mit 1 markiert und somit nicht in der finalen Formel verwendet
		$einshoch++;
	}
	else{
		$mini_array[$arrminzahl]["benutzt"]=2;	//Zahlen, welche eine einzigartige Zahl haben, werden mit 2 markiert und für die letzte Formel übernommen
		$einshoch++;
	}
}

$formel = "";
foreach( $mini_array as $key => $wert ){
	if( $mini_array[ $key ][ "benutzt" ] == 2 ){
	
		$intern_array_deczahl = $mini_array[ $key ][ "deczahl" ];
		$intern_array = binaer( $intern_array_deczahl, $anzahl_variablen );
		
		$intern_array = array_reverse( $intern_array );
		
		foreach( $intern_array as $key2 => $wert2 ){
			if( $wert2 == "1" ){
				$formel = $formel."S".$key2;
			}elseif( $wert2 == "0" ){
				$formel = $formel."!S".$key2;
			}
		}
		if( $key < (count( $mini_array )-1) ){
			$formel = $formel." v ";
		}
	}
}
echo $formel;
?>