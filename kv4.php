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
//----------------------------------------------------------------- LETZTE MINIMIERUNG ---------------------------------------------------------------------------

//Ab hier verwende ich den $mini_array von Joschka
//Im obigen Teil hat bereits eine minimierung stattgefunden. Es wurden jeweils einzelne Teile der funktion minimiert.
//Bsp. für ein Teil:	Ganzes: S1S3S4 v !S6S3S9 ein Teil wäre dann S1S3S4
//Jedes Teil hat ein eigenes unterarray im $mini_array, in dem die optimierten Zahlen dieses arrays gespeichert sind Bsp: 0,3,6,7 => Die Kombination der Eingänge in der Wahrheitstabelle in der Zeile 0,3,6 und 7 ergeben 1

//später verwendete Variablen deklarieren
$einshoch = 0; 
$count = 0;
$countx=0;
$zuzaehlen=0;
$vergleichsbasis = 0;
$explodespeicher = 0;
$countspeicher = 0;

//später verwendete arrays deklarieren
$deczahlenx = array();
$deczahlen = array();
$vergleichswerte = array();


//Zählt wie viele Teile die boolsche Funktionen in der Endformel hat (Vor der Aussortierung) und speichert die zahl in $count. !S1S2 v !S3S4 == 2 teile
$count = count($mini_array);

//Die folgende Schleife zählt wie viele Zahlen in den jeweiligen Teilen optimiert wurden und fügt diese Zahl in das dazugehörige unterarray ein
for($i=0; $i<$count; $i++){
	$zuzaehlen = explode(":", $mini_array[$i]["deczahl"]);		//Nimmt die verwendeten Zahlen und speichert jede als einzelnen Eintrag im $zuzahlen array
	$countx = count($zuzaehlen);								//Zählt wie viele Zahlen zusammen minimiert wurden
	$mini_array[$i]["deczahlen"] = $countx;						//Speichert die menge der optimierten Zahlen zusätzlich im Datenarray unter dem Key "deczahlen"
	array_push($deczahlenx, $i, $countx);						//pusht die Nummer des verwendeten Datenarrays und die Menge der deczahlen dieses Datenarrays in deczahlenx
	array_push($deczahlen, $deczahlenx);						//pusht deczahlenx mit den infos in den deczahlen array
	$deczahlenx=array(); 										//leert den deczahlenx array für die benutzung um nächsten durchlauf
}


//Öffnen der großen Schleife, die bis zum Ende der ganzen Aussortierung läuft und alles umfasst
for($e=0; $e<$count+1; $e++){

//Die folgende Schleife guckt welche Boolsche funktion in der ersten Aussortierung die wenigsten Zahlen minimiert hat

	for($i=0; $i<$count; $i++){			//Öffnen der ersten Schleife, dessen $i später den Zähler für die Vergleichsbasis darstellt
		$zaehler = 0;					//2 Variabeln für die kommende Schleife deklariert
		$minzahl = 0;
			for($a=0; $a<$count; $a++){		//Öffnen der zweiten Schleife, dessen $a später den Zähler für die Vergleichswerte darstellt
				if($deczahlen[$i][1] <= $deczahlen[$a][1] && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){			//Wenn die Anzahl an minimierten zahlen bei der Vergleichsbais kleiner oder gleich der Anzahl beim Vergleichswert ist, dann wird der Zähler um 1 erhöht
					$zaehler++;
					$minzahl = $i; 			//speichert welcher verglichene array verwendet wurde. Ist immer der / einer der Array/s mit den wenigsten minimierten zahlen
				}
	
				if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){ 	//Wenn der Zähler genauso hoch ist wie die Anzahl der unverglichenen arrays (Alles wurde verglichen), dann geh aus der Schleife
					break;
				}
			}
		if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){			//Das selbe wie ein IF weiter oben, um aus beiden Schleifen für den vergleich zu kommende
			break;
		}
	}

	
	$arrminzahl = $deczahlen[$minzahl][0]; // In $arrminzahl wird die Nummer des Arrays mit der kleinsten Anzahl an minimierten Zahlen gespeichert
	$vergleichsbasis = explode(":", $mini_array[$arrminzahl]["deczahl"]);	//Die im Array $mini_array gespeicherte Information welche Zahlen bis dorthin genau optimiert wurden, wird im Array $vergleichsbasis gespeichert (Jede Zahl als eigener Eintrag). Diese Zahlen werden gleich mit allen anderen Verglichen

//In der folgenden Schleife werden alle anderen minimierten Zahlen in einem Zweiten array gespeichert um mit $vergleichsbasis verglichen werden zu können

	for($i=0;$i<$count;$i++){ //Eine Schleife wird geöffnet, damit man mit $i wieder durch alle Arrays zählen kann
		if($i != $arrminzahl && ($mini_array[$i]["benutzt"]==0 || $mini_array[$i]["benutzt"]==2)){	//Alle anderen optimierungen (außer die in $vergleichsbasis und welche, die schon verglichen und aussortiert wurden), werden in der nächsten schleife verarbeitet
			$explodespeicher = explode(":", $mini_array[$i]["deczahl"]);		//Die minimierten Zahlen der arrays werden im explodespeicher gespeichert
			$countspeicher = count($explodespeicher);							//In Countspeicher wird gespeichert, wie viele minimierte zahlen in $explodespeicher sind
		
			for($a=0; $a<$countspeicher; $a++){									//Die Schleife pusht alle in $explodespeicher enthaltenen, minimierten Zahlen in den $vergleichswerte array
				array_push($vergleichswerte, $explodespeicher[$a]);
			}
		}
		$countspeicher = 0;														//Countspeicher und Explodespeicher werden für die wiederverwendung der Schleife geleert
		$explodespeicher = 0;
	}


//Wenn jede der minimierten Zahlen in der Vergleichsbasis auch in den Verlgeichswerten vorkommt, dann wird der Unterwert "benutzt" im Array aus dem die Vergleichsbasis kommt =1 gesetzt. 1 Gesetze Arrays sind aussortiert
	if(array_intersect($vergleichsbasis, $vergleichswerte) == ($vergleichsbasis)){
		$mini_array[$arrminzahl]["benutzt"]=1;
		$einshoch++; //Da ab jetzt ein array weniger verglichen werden muss, wird $einshoch inkrementiert um die Vergleichsschleife weiter oben jedes mal kleiner zu machen
	}


//Wenn nicht alle Zahlen der vergleichsbasis in den Vergleichswerten vorkommen, dann muss diese optimierung verwendet werden, sie wird mit 2 in "benutzt" markiert. Mit 2 markierte Arrays werden in der Finalen, minimierten Formel verwendet
	else{
		$mini_array[$arrminzahl]["benutzt"]=2;
		$einshoch++; //Da ab jetzt ein array weniger verglichen werden muss, wird $einshoch inkrementiert um die Vergleichsschleife weiter oben jedes mal kleiner zu machen
	}

} //Schluss der großen Schleife


for($i=0; $i<$count; $i++){
	
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
