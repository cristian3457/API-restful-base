<?php

function capitalizar_todo( $data_cruda ){

    return capitalizarArreglo( $data_cruda, array(), TRUE );
}
function obtener_mes( $mes ){
    $mes -= 1;
    $meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

    return $meses[$mes];
}            

function capitalizarArreglo( $data_cruda, $campos_capitalizar = array(), $todos = FALSE ){

    $data_lista = $data_cruda;

    foreach( $data_cruda as $nombre_campo => $valor_campo) {

        if( in_array( $nombre_campo, array_values( $campos_capitalizar) ) OR $todos ){

            $data_lista[ $nombre_campo ] = strtoupper( $valor_campo );
        }
    }

    return $data_lista;
}
?>