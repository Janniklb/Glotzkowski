<?php
//------------------------------------------------------------------- Einstellung
# Minimierungsverfahren ( 1 oder 0 )
$mode = 1;

# Übergabeart und name der Variablen anzahl
$variablen_anzahl_mode = "_GET";
$variablen_anzahl_name = "anzahl";

# Übergabeart der Variablen

$variablen_mode = "_POST";

//-------------------------------------------------------------------------------


//--------------------------------------------------------------- Variablen

$anzahl_variablen = $$variablen_anzahl_mode[ $variablen_anzahl_name ];
$anzahl_zeihlen = pow( 2, $anzahl_variablen );

#echo $anzahl_variablen.":".$anzahl_zeihlen;

//-------------------------------------------------------------- Erstes Array ( NUMMER => GRUPPE )

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

#echo_r( $erstes_array );

//------------------------------------------------------------ MINIMIERUNGSARRAY Erstellen

$mini_array0 = array();
$b = 0;

foreach( $erstes_array as $nummer => $gruppe ){	// z.B. 5 => 101 (binär)
	$nummer_binaer = decbin( $nummer );	// 101
	$nummer_binaer_array = str_split( $nummer_binaer ); // 1,0,1
	
	$merken = array_reverse( $nummer_binaer_array );// 1,0,1
	
	$nummer_binaer_count = count( $nummer_binaer_array );	// 1,0,1 => 3
	$nummer_fehlende_nullen = $anzahl_variablen - $nummer_binaer_count;	// 4 - 3 = 1

	for( $i = 0; $i < $nummer_fehlende_nullen; $i++ ){	// 1mal
		array_push( $merken, 0 );	// 1,0,1 + 0 = 1,0,1,0
	}
	$merken = array_reverse( $merken );

	$merken[ 'gruppe' ] = $gruppe;
	$merken[ 'benutzt' ] = 0;	
	$merken[ 'deczahl' ] = $nummer;
	$mini_array0[ $b ] = $merken;
	$b++;
}

//echo_r( $mini_array0 );

//------------------------------------------------------------ MINIMIERUNGSSCHLEIFE 

$mini_array_ende = array();
$mini_array_zahl = 0;
do{
	$speicher_nummer = 0;
	$ende = false;
	$mini_array_name = "mini_array".$mini_array_zahl;
	$mini_array_zahl++;
	$mini_array_speicher_name = "mini_array".$mini_array_zahl;
	$$mini_array_speicher_name = array();
	
	for( $column = 0; $column < count( $$mini_array_name ); $column++){
		for( $column2 = 0; $column2 < count( $$mini_array_name ); $column2++){

			if(( $$mini_array_name[ $column ][ "gruppe" ] + 1 ) == $$mini_array_name[ $column2 ][ "gruppe" ]  ){
				$binaer_laenge = ( count( $$mini_array_name, COUNT_RECURSIVE ) - count( $$mini_array_name ) ) / count( $$mini_array_name ) - 2;
				$nicht_gleich = 0;
				$merken = "";

				for( $row = 0; $row < $binaer_laenge; $row++ ){
					if( $$mini_array_name[ $column ][ $row ] != $$mini_array_name[ $column2 ][ $row ] ){
					
						if( $nicht_gleich >= 1 ){
							$nicht_gleich++;
							continue 2;
						}else{
							$merken = $merken."-";
							$nicht_gleich++;
						}
					}else{
						$merken = $merken.$$mini_array_name[ $column ][ $row ];
					}
				}
				if( $nicht_gleich <= 1 ){
					$kurz_array = str_split( $merken );
					$kurz_array[ "gruppe" ] = $$mini_array_name[ $column ][ "gruppe" ];
					$kurz_array[ "benutzt" ] = 0;
					$kurz_array[ "deczahl" ] = $$mini_array_name[ $column ][ "deczahl" ].":".$$mini_array_name[ $column2 ][ "deczahl" ];
					
					$$mini_array_speicher_name[ $speicher_nummer ] = $kurz_array;
					$speicher_nummer++;
					
					$$mini_array_name[ $column ][ "benutzt" ] = 1;
					$$mini_array_name[ $column2 ][ "benutzt" ] = 1;
				}
			}
		}
	}
	for( $column = 0; $column < count( $$mini_array_name ); $column++){
		if( $$mini_array_name[ $column ][ "benutzt" ] == 0 ){
			array_push( $$mini_array_speicher_name, $$mini_array_name[ $column ] );
		}else{
			$ende = true;
		}
	}
	if( $ende == false ){
		$mini_array_ende = $$mini_array_speicher_name;
	}
	
}while($ende);

//------------------------------------------------------------------- Doppelte Rausfiltern

//echo_r( $mini_array_ende );
echo "------------------------------------- END FORMEL -------------------------------------------<br><br>";
$nichtdoppelt = array();
for( $i = 0; $i < count($mini_array_ende); $i++ ){
        $keinefreunde = 0;
        for( $b = 0; $b < count($mini_array_ende); $b++ ){
                $neu1 = $mini_array_ende[$i];
                $neu2 = $mini_array_ende[$b];
                $ver = array_diff_assoc( $neu1, $neu2 );
		unset( $ver[ "deczahl" ] );
                if(empty( $ver )){

                        if( $i != $b ){
				$gleich = false;
                        	foreach( $nichtdoppelt as $key => $wert ){
					$neu3 = $nichtdoppelt[$key];
					$vergleich = array_diff_assoc( $neu1, $neu3 );
					unset( $vergleich[ "deczahl" ]);
					if(empty( $vergleich )){
						$gleich = true;
					}
				}
				if( $gleich == false ){
					array_push( $nichtdoppelt, $neu1 );
				}
			}
                }else{
                        $keinefreunde++;
                }
        }
        if( $keinefreunde == (count($mini_array_ende)-1) ){
                $nichtdoppelt[$i] = $mini_array_ende[$i];
        }
}
sort($nichtdoppelt);

//----------------------------------------------------------------- LETZTE MINIMIERUNG
//echo_r( $nichtdoppelt );

$einshoch = 0;

$count = 0;
$count = count($nichtdoppelt); //Zählt wie viele Boolsche Funktionen in der Endformel vorkommen (Vor der Aussortierung)

$countx=0;
$zuzaehlen=0;

$deczahlenx = array();
$deczahlen = array();

for($i=0; $i<$count; $i++){  //Für alle Arrays mit den Daten über die Boolschen Funktionen
$zuzaehlen = explode(":", $nichtdoppelt[$i]["deczahl"]); //Nimmt sich die deczahlen und speichert sie einzeln im array zuzaehlen 
$countx = count($zuzaehlen); //Zählt wie viele deczahlen zusammen minimiert wurden
$nichtdoppelt[$i]["deczahlen"] = $countx; //Speichert die menge der verwendeten Deczahlen ebenfalls im Datenarray unter dem Key "deczahlen"

array_push($deczahlenx, $i, $countx); //pusht die Nummer des verwendeten Datenarrays und die Menge der deczahlen dieses Datenarrays in deczahlenx
array_push($deczahlen, $deczahlenx); //pusht deczahlenx mit den infos in den deczahlen array
$deczahlenx=array(); //leert den deczahlenx array für die benutzung um nächsten durchlauf
}


for($e=0; $e<$count+1; $e++){


for($i=0; $i<$count; $i++){
$zaehler = 0;
$minzahl = 0;

	for($a=0; $a<$count; $a++){
		
		if($deczahlen[$i][1] <= $deczahlen[$a][1] && $nichtdoppelt[$deczahlen[$i][0]]["benutzt"]==0){
			$zaehler++;
			$minzahl = $i; // Minzahl ist der deczahlen array mit den wenigsten minimierten deczahlen
		#echo "Zaehler: ".$zaehler."<br>";
		#echo "DollaI(Basisarray): ".$i."<br>";
		#echo "DollaA(Vergleichsarray): ".$a."<br><br>";
		}
	
		if($zaehler==$count-$einshoch && $nichtdoppelt[$deczahlen[$i][0]]["benutzt"]==0){
			break;
		}
	}
	if($zaehler==$count-$einshoch && $nichtdoppelt[$deczahlen[$i][0]]["benutzt"]==0){
		break;
	}
}

// Minzahl ist der deczahlen array mit den wenigsten minimierten deczahlen

$vergleichsbasis = 0;

$arrminzahl = $deczahlen[$minzahl][0]; // In $arrminzahl befindet sich die Nummer des Informationsarrays mit der kleinsten anzahl an minimierten deczahlen

$vergleichsbasis = explode(":", $nichtdoppelt[$arrminzahl]["deczahl"]);


//echo "vergleichsbasis: <br>";
//echo_r($vergleichsbasis);

$explodespeicher = 0;
$countspeicher = 0;
$vergleichswerte = array();

for($i=0;$i<$count;$i++){
	if($i != $arrminzahl && ($nichtdoppelt[$i]["benutzt"]==0 || $nichtdoppelt[$i]["benutzt"]==2)){
		$explodespeicher = explode(":", $nichtdoppelt[$i]["deczahl"]);
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
	$nichtdoppelt[$arrminzahl]["benutzt"]=1;
	$einshoch++;
}
else{
	$nichtdoppelt[$arrminzahl]["benutzt"]=2;
	$einshoch++;
}
}

//echo_r($nichtdoppelt);
//echo_r($deczahlen); // ES KLAPPT! IM $Nichtdoppelt array unter dem Key deczahl sind die dezimalzahlen der richtigen gruppem, wenn der schlüssel benutzt = 2 ist !! :)

for($i=0; $i<$count; $i++){
	
}

$formel = "";
foreach( $nichtdoppelt as $key => $wert ){
	if( $nichtdoppelt[ $key ][ "benutzt" ] == 2 ){
	
		$intern_array = $nichtdoppelt[ $key ];
	
		unset( $intern_array[ "benutzt" ] );
		unset( $intern_array[ "gruppe" ] );
		unset( $intern_array[ "deczahl" ] );
		unset( $intern_array[ "deczahlen" ] );
		
		$intern_array = array_reverse( $intern_array );
		
		foreach( $intern_array as $key2 => $wert2 ){
			if( $wert2 == "1" ){
				$formel = $formel."S".$key2;
			}elseif( $wert2 == "0" ){
				$formel = $formel."!S".$key2;
			}
		}
		if( $key < (count( $nichtdoppelt )-1) ){
			$formel = $formel." v ";
		}
	}
}

echo $formel;



function echo_r($x){
        echo '<pre style="color:red">DEBUG ('.$x.'):'.print_r($x, true).'</pre>';
}

?>
