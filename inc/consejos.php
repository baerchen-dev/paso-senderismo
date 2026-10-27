<?php
/**
 * CONSEJOS · textos de la página /consejos/ (page-consejos.php), en un solo sitio para que no se contradigan con las fichas.
 * Qué llevar usa la misma lista que la ficha de ruta: paso_llevar_siempre() (inc/rutas.php).
 * Los textos van con __() para traducirlos con Polylang.
 */

/* Niveles de dificultad: la clave es la del campo ruta_dificultad */
function paso_niveles() {
  return array(
    'facil'   => array( 'nombre' => __( 'Fácil', 'paso' ),   'texto' => __( 'Menos de 10 km y 400 m de desnivel. Para empezar o para un día tranquilo.', 'paso' ) ),
    'media'   => array( 'nombre' => __( 'Media', 'paso' ),   'texto' => __( 'Hasta 15 km y 900 m de desnivel. Hace falta algo de costumbre de caminar.', 'paso' ) ),
    'dificil' => array( 'nombre' => __( 'Difícil', 'paso' ), 'texto' => __( 'Más distancia o desnivel, o tramos técnicos. Solo con experiencia.', 'paso' ) ),
  );
}

/* MIDE: los cuatro criterios (la clave es la de los campos ruta_mide_*): qué miden en pocas palabras y qué significa la escala */
function paso_mide_criterios() {
  return array(
    'medio'          => array( 'nombre' => __( 'Medio', 'paso' ),          'mide' => __( 'Lo severo que es el entorno', 'paso' ),  'texto' => __( 'Aislamiento, frío o calor, posibilidad de mal tiempo y de quedarse sin cobertura.', 'paso' ) ),
    'itinerario'     => array( 'nombre' => __( 'Itinerario', 'paso' ),     'mide' => __( 'Lo difícil que es orientarse', 'paso' ), 'texto' => __( '1: camino evidente y señalizado. 5: sin camino, hay que navegar con mapa o GPS.', 'paso' ) ),
    'desplazamiento' => array( 'nombre' => __( 'Desplazamiento', 'paso' ), 'mide' => __( 'El tipo de terreno', 'paso' ),           'texto' => __( '1: pista o camino llano. 3: sendero con piedras. 5: hay que trepar con las manos.', 'paso' ) ),
    'esfuerzo'       => array( 'nombre' => __( 'Esfuerzo', 'paso' ),       'mide' => __( 'El cansancio', 'paso' ),                 'texto' => __( 'Según las horas de marcha sin paradas: cuantas más horas, más alto.', 'paso' ) ),
  );
}

/* Guía oficial del MIDE (Montaña Segura, que lo creó) */
const PASO_MIDE_GUIA = 'https://montanasegura.com/el-mide/';

/* Normas del grupo */
function paso_normas() {
  return array(
    __( 'Nadie se queda atrás: el ritmo lo marca la persona más lenta.', 'paso' ),
    __( 'Sigue al responsable de la ruta y no te adelantes en los cruces.', 'paso' ),
    __( 'Respeta los senderos, la fauna y el entorno: llévate tu basura.', 'paso' ),
    __( 'Si te encuentras mal o no puedes seguir, avisa: no pasa nada.', 'paso' ),
    __( 'Los perros, solo si la ruta lo permite.', 'paso' ),
  );
}

/* El día antes */
function paso_dia_antes() {
  return array(
    array( 'nombre' => __( 'El tiempo', 'paso' ),          'texto' => __( 'Mira la previsión de AEMET la noche anterior. Con aviso amarillo o lluvia fuerte, la ruta se aplaza.', 'paso' ) ),
    array( 'nombre' => __( 'Cancelaciones', 'paso' ),      'texto' => __( 'Si la ruta se cancela o cambia, se avisa en el grupo de WhatsApp del club.', 'paso' ) ),
    array( 'nombre' => __( 'Si no puedes venir', 'paso' ), 'texto' => __( 'Avisa en el grupo para que nadie te espere en el punto de encuentro.', 'paso' ) ),
  );
}
