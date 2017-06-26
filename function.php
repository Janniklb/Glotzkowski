<?php
function binaer( $zahlen_string, $anzahl_variablen ){
        $zahlen = explode( ":", $zahlen_string );
        sort( $zahlen );
        $zahl_1 = $zahlen[ 0 ];
        rsort( $zahlen );
        $zahl_2 = $zahlen[ 0 ];
        $binaer_array_1 = array_reverse( str_split( decbin( $zahl_1 ) ) );
        $schleife = $anzahl_variablen - ( count( $binaer_array_1 ) );
        for( $i = 0; $i < $schleife; $i++ ){
                array_push( $binaer_array_1, 0 );
        }
        $binaer_array_1 = array_reverse( $binaer_array_1 );
        $binaer_array_2 = array_reverse( str_split( decbin( $zahl_2 ) ) );
        $schleife = $anzahl_variablen - ( count( $binaer_array_2 ) );
        for( $i = 0; $i < $schleife; $i++ ){
                array_push( $binaer_array_2, 0 );
        }
        $binaer_array_2 = array_reverse( $binaer_array_2 );
        $unterschied = array_diff_assoc( $binaer_array_1, $binaer_array_2 );
        foreach( $unterschied as $key => $wert ){
                $binaer_array_1[ $key ] = "-";
        }
        return $binaer_array_1;
}
function echo_r($x){
        echo '<pre style="color:red">DEBUG ('.$x.'):'.print_r($x, true).'</pre>';
}
?>
