<?php
/**
 * RUTAS · tipo de contenido «ruta» (/rutas/ y /rutas/la-maroma/)
 * Próxima o realizada lo decide la fecha: la próxima es la de hoy en adelante más cercana; las de antes, realizadas.
 * Campos ACF: acf-json/group_paso_ruta.json (Rutas → cada ruta).
 */

function paso_registrar_rutas() {
  register_post_type( 'ruta', array(
    'labels'       => array(
      'name'          => __( 'Rutas', 'paso' ),
      'singular_name' => __( 'Ruta', 'paso' ),
      'add_new_item'  => __( 'Añadir ruta', 'paso' ),
      'edit_item'     => __( 'Editar ruta', 'paso' ),
      'all_items'     => __( 'Todas las rutas', 'paso' ),
    ),
    'public'       => true,
    'has_archive'  => 'rutas',
    'rewrite'      => array( 'slug' => 'rutas', 'with_front' => false ),
    'supports'     => array( 'title', 'editor', 'thumbnail' ),
    'show_in_rest' => true,
    'menu_icon'    => 'dashicons-location-alt',
  ) );
}
add_action( 'init', 'paso_registrar_rutas' );

/* Al activar el tema, WordPress tiene que aprender las direcciones /rutas/ */
function paso_rutas_activar() {
  paso_registrar_rutas();
  flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'paso_rutas_activar' );

/* Próxima ruta: la de fecha más cercana desde hoy (incluido). Sin ninguna → null */
function paso_proxima_ruta() {
  $rutas = get_posts( array(
    'post_type'      => 'ruta',
    'posts_per_page' => 1,
    'meta_key'       => 'ruta_fecha',
    'meta_type'      => 'DATETIME',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array( array(
      'key'     => 'ruta_fecha',
      'value'   => wp_date( 'Y-m-d 00:00:00' ),
      'compare' => '>=',
      'type'    => 'DATETIME',
    ) ),
  ) );
  return $rutas ? $rutas[0] : null;
}

/* Rutas realizadas (fecha anterior a hoy), de la más reciente a la más antigua. $cuantas = -1 → todas */
function paso_rutas_realizadas( $cuantas = 3 ) {
  return get_posts( array(
    'post_type'      => 'ruta',
    'posts_per_page' => $cuantas,
    'meta_key'       => 'ruta_fecha',
    'meta_type'      => 'DATETIME',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'meta_query'     => array( array(
      'key'     => 'ruta_fecha',
      'value'   => wp_date( 'Y-m-d 00:00:00' ),
      'compare' => '<',
      'type'    => 'DATETIME',
    ) ),
  ) );
}

/* /proxima-ruta/ (enlace del menú) → ficha de la próxima ruta, o /rutas/ si no hay */
function paso_redirigir_proxima_ruta() {
  if ( ! is_404() ) return;
  $ruta = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ), '/' );
  if ( 'proxima-ruta' !== $ruta ) return;

  $proxima = paso_proxima_ruta();
  wp_safe_redirect( $proxima ? get_permalink( $proxima ) : get_post_type_archive_link( 'ruta' ), 302 );
  exit;
}
add_action( 'template_redirect', 'paso_redirigir_proxima_ruta' );

/* Búsquedas (?s=…) → /rutas/: la web no tiene buscador ni página de resultados */
function paso_redirigir_busqueda() {
  if ( ! is_search() ) return;
  wp_safe_redirect( get_post_type_archive_link( 'ruta' ), 302 );
  exit;
}
add_action( 'template_redirect', 'paso_redirigir_busqueda' );

/* Cifras en formato español: 16.6 → «16,6» · 2071 → «2.071» · 5 → «5» */
function paso_cifra( $valor ) {
  if ( '' === $valor || null === $valor ) return '';
  return rtrim( rtrim( number_format( (float) $valor, 2, ',', '.' ), '0' ), ',' );
}

/* Fecha de la ruta (valor de ACF «Y-m-d H:i:s») → array( 'dia' => «Sábado 15 de noviembre», 'hora' => «9:00» ) */
function paso_ruta_fecha( $post = null ) {
  $valor = get_field( 'ruta_fecha', $post );
  $fecha = $valor ? date_create_immutable( $valor, wp_timezone() ) : false;
  if ( ! $fecha ) return null;

  $dia = wp_date( 'l j \d\e F', $fecha->getTimestamp() );
  return array(
    'dia'  => mb_strtoupper( mb_substr( $dia, 0, 1 ) ) . mb_substr( $dia, 1 ),
    'hora' => wp_date( 'G:i', $fecha->getTimestamp() ),
  );
}

/* Los 6 datos de la franja (Figma: Route Stats), solo los que tengan valor */
function paso_ruta_datos( $post = null ) {
  $dificultad = get_field_object( 'ruta_dificultad', $post );
  $tipo       = get_field_object( 'ruta_tipo', $post );
  $desnivel   = paso_cifra( paso_ruta_cifra( 'desnivel', $post ) );

  $datos = array(
    array( 'valor' => paso_cifra( paso_ruta_cifra( 'distancia', $post ) ), 'unidad' => 'km', 'etiqueta' => __( 'Distancia', 'paso' ) ),
    array( 'valor' => $desnivel ? '+' . $desnivel : '',                     'unidad' => 'm',  'etiqueta' => __( 'Desnivel positivo', 'paso' ) ),
    array( 'valor' => paso_cifra( paso_ruta_cifra( 'altitud', $post ) ),   'unidad' => 'm',  'etiqueta' => __( 'Altitud máxima', 'paso' ) ),
    array( 'valor' => paso_cifra( paso_ruta_cifra( 'duracion', $post ) ),  'unidad' => 'h',  'etiqueta' => __( 'Duración con paradas', 'paso' ) ),
    array( 'valor' => $dificultad['choices'][ $dificultad['value'] ] ?? '', 'unidad' => '', 'etiqueta' => __( 'Dificultad', 'paso' ) ),
    array( 'valor' => $tipo['choices'][ $tipo['value'] ] ?? '',             'unidad' => '', 'etiqueta' => __( 'Tipo de recorrido', 'paso' ) ),
  );
  return array_values( array_filter( $datos, fn( $dato ) => '' !== $dato['valor'] ) );
}

/* ¿La ruta es la próxima o una por venir? (fecha de hoy en adelante) */
function paso_ruta_es_proxima( $post = null ) {
  $valor = get_field( 'ruta_fecha', $post );
  return $valor && $valor >= wp_date( 'Y-m-d 00:00:00' );
}

/* Qué llevar · «Siempre»: lo básico, igual en todas las rutas (y en Consejos → Qué llevar) */
function paso_llevar_siempre() {
  return array(
    array( 'nombre' => __( 'Agua', 'paso' ),    'texto' => __( '1,5–2 litros por persona. En verano, más.', 'paso' ) ),
    array( 'nombre' => __( 'Calzado', 'paso' ), 'texto' => __( 'Botas o zapatillas de montaña con buena suela. Nada de suela lisa.', 'paso' ) ),
    array( 'nombre' => __( 'Capas', 'paso' ),   'texto' => __( 'Camiseta técnica y algo de abrigo: arriba siempre refresca.', 'paso' ) ),
    array( 'nombre' => __( 'Sol', 'paso' ),     'texto' => __( 'Gorra, gafas y crema, también en invierno.', 'paso' ) ),
    array( 'nombre' => __( 'Comida', 'paso' ),  'texto' => __( 'Bocadillo y algo energético: fruta, frutos secos.', 'paso' ) ),
    array( 'nombre' => __( 'Otros', 'paso' ),   'texto' => __( 'Móvil cargado, documentación y una bolsa para la basura.', 'paso' ) ),
  );
}

/* Lista de un campo de texto, una cosa por línea «Nombre: explicación» → array( 'nombre', 'texto' ) */
function paso_lista( $texto ) {
  $lista = array();
  foreach ( preg_split( '/\R/', (string) $texto ) as $linea ) {
    $partes = array_map( 'trim', explode( ':', $linea, 2 ) );
    if ( '' === $partes[0] ) continue;
    $lista[] = array( 'nombre' => $partes[0], 'texto' => $partes[1] ?? '' );
  }
  return $lista;
}

/* Rutas próximas (de hoy en adelante), de la más cercana a la más lejana */
function paso_rutas_proximas() {
  return get_posts( array(
    'post_type'      => 'ruta',
    'posts_per_page' => -1,
    'meta_key'       => 'ruta_fecha',
    'meta_type'      => 'DATETIME',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array( array(
      'key'     => 'ruta_fecha',
      'value'   => wp_date( 'Y-m-d 00:00:00' ),
      'compare' => '>=',
      'type'    => 'DATETIME',
    ) ),
  ) );
}
