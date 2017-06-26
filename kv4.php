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
echo_r( $mini_array );
//----------------------------------------------------------------- LETZTE MINIMIERUNG
//echo_r( $mini_array );
$einshoch = 0;
$count = 0;
$count = count($mini_array); //Zählt wie viele Boolsche Funktionen in der Endformel vorkommen (Vor der Aussortierung)
$countx=0;
$zuzaehlen=0;
$deczahlenx = array();
$deczahlen = array();
for($i=0; $i<$count; $i++){  //Für alle Arrays mit den Daten über die Boolschen Funktionen
$zuzaehlen = explode(":", $mini_array[$i]["deczahl"]); //Nimmt sich die deczahlen und speichert sie einzeln im array zuzaehlen 
$countx = count($zuzaehlen); //Zählt wie viele deczahlen zusammen minimiert wurden
$mini_array[$i]["deczahlen"] = $countx; //Speichert die menge der verwendeten Deczahlen ebenfalls im Datenarray unter dem Key "deczahlen"
array_push($deczahlenx, $i, $countx); //pusht die Nummer des verwendeten Datenarrays und die Menge der deczahlen dieses Datenarrays in deczahlenx
array_push($deczahlen, $deczahlenx); //pusht deczahlenx mit den infos in den deczahlen array
$deczahlenx=array(); //leert den deczahlenx array für die benutzung um nächsten durchlauf
}
for($e=0; $e<$count+1; $e++){
for($i=0; $i<$count; $i++){
$zaehler = 0;
$minzahl = 0;
	for($a=0; $a<$count; $a++){
		
		if($deczahlen[$i][1] <= $deczahlen[$a][1] && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
			$zaehler++;
			$minzahl = $i; // Minzahl ist der deczahlen array mit den wenigsten minimierten deczahlen
		#echo "Zaehler: ".$zaehler."<br>";
		#echo "DollaI(Basisarray): ".$i."<br>";
		#echo "DollaA(Vergleichsarray): ".$a."<br><br>";
		}
	
		if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
			break;
		}
	}
	if($zaehler==$count-$einshoch && $mini_array[$deczahlen[$i][0]]["benutzt"]==0){
		break;
	}
}
// Minzahl ist der deczahlen array mit den wenigsten minimierten deczahlen
$vergleichsbasis = 0;
$arrminzahl = $deczahlen[$minzahl][0]; // In $arrminzahl befindet sich die Nummer des Informationsarrays mit der kleinsten anzahl an minimierten deczahlen
$vergleichsbasis = explode(":", $mini_array[$arrminzahl]["deczahl"]);
//echo "vergleichsbasis: <br>";
//echo_r($vergleichsbasis);
$explodespeicher = 0;
$countspeicher = 0;
$vergleichswerte = array();
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
//echo "Vergleichswerte: <br>";
//echo_r($vergleichswerte);
if(array_intersect($vergleichsbasis, $vergleichswerte) == ($vergleichsbasis)){
	$mini_array[$arrminzahl]["benutzt"]=1;
	$einshoch++;
}
else{
	$mini_array[$arrminzahl]["benutzt"]=2;
	$einshoch++;
}
}
//echo_r($mini_array);
//echo_r($deczahlen); // ES KLAPPT! IM $Nichtdoppelt array unter dem Key deczahl sind die dezimalzahlen der richtigen gruppem, wenn der schlüssel benutzt = 2 ist !! :)
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
