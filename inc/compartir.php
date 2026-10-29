<?php
/**
 * COMPARTIR · Open Graph (WhatsApp, Telegram, LinkedIn…) y descripción de cada página
 * Imagen: en las rutas, su foto del hero recortada a 1200 × 630 (JPG en uploads/paso-compartir/, se hace la primera vez
 * que hace falta y al guardar la ruta); en el resto, assets/img/compartir.jpg (Figma: PASO / Compartir · General).
 * Descripción: en las rutas, fecha y cifras («Sábado 14 de noviembre · 9:30 · 15,6 km · +930 m · Difícil»).
 */

define( 'PASO_COMPARTIR_ANCHO', 1200 );
define( 'PASO_COMPARTIR_ALTO', 630 );

/* Imagen de una ruta para compartir → array( 'url', 'ancho', 'alto' ) o null (sin foto) */
function paso_ruta_imagen_compartir( $post = null ) {
  $post    = get_post( $post );
  $adjunto = get_field( 'ruta_hero_imagen', $post ) ?: get_post_thumbnail_id( $post );
  $origen  = $adjunto ? get_attached_file( $adjunto ) : '';
  if ( ! $origen || ! file_exists( $origen ) ) return null;

  $subidas = wp_upload_dir();
  $nombre  = sprintf( '%d-%d-%d.jpg', $post->ID, $adjunto, filemtime( $origen ) );   // cambia la foto → archivo nuevo
  $carpeta = trailingslashit( $subidas['basedir'] ) . 'paso-compartir/';
  $archivo = $carpeta . $nombre;

  if ( ! file_exists( $archivo ) ) {
    wp_mkdir_p( $carpeta );
    foreach ( glob( $carpeta . $post->ID . '-*.jpg' ) ?: array() as $viejo ) wp_delete_file( $viejo );
    $editor = wp_get_image_editor( $origen );
    if ( is_wp_error( $editor ) ) return null;
    $editor->resize( PASO_COMPARTIR_ANCHO, PASO_COMPARTIR_ALTO, true );   // recorte centrado
    $editor->set_quality( 80 );
    // En JPG (no en WebP como el resto de tamaños del tema): lo entienden todas las apps al previsualizar enlaces
    remove_filter( 'image_editor_output_format', 'paso_imagenes_webp' );
    $guardado = $editor->save( $archivo, 'image/jpeg' );
    add_filter( 'image_editor_output_format', 'paso_imagenes_webp' );
    if ( is_wp_error( $guardado ) ) return null;
  }

  return array(
    'url'   => trailingslashit( $subidas['baseurl'] ) . 'paso-compartir/' . $nombre,
    'ancho' => PASO_COMPARTIR_ANCHO,
    'alto'  => PASO_COMPARTIR_ALTO,
  );
}

/* Al guardar una ruta, su imagen queda hecha (así la primera visita de WhatsApp no espera) */
function paso_ruta_guardar_compartir( $post_id ) {
  if ( 'ruta' === get_post_type( $post_id ) && 'publish' === get_post_status( $post_id ) ) paso_ruta_imagen_compartir( $post_id );
}
add_action( 'acf/save_post', 'paso_ruta_guardar_compartir', 20 );

/* Descripción de una ruta: fecha (y hora si es próxima) y cifras principales */
function paso_ruta_descripcion( $post = null ) {
  $fecha   = paso_ruta_fecha( $post );
  $proxima = paso_ruta_es_proxima( $post );
  $trozos  = array();
  if ( $fecha ) $trozos[] = $proxima ? $fecha['dia'] . ' · ' . $fecha['hora'] : sprintf( __( 'Ruta realizada el %s', 'paso' ), mb_strtolower( $fecha['dia'] ) );
  foreach ( paso_ruta_datos( $post ) as $dato ) {
    if ( in_array( $dato['etiqueta'], array( __( 'Distancia', 'paso' ), __( 'Desnivel positivo', 'paso' ), __( 'Dificultad', 'paso' ) ), true ) ) {
      $trozos[] = trim( $dato['valor'] . ' ' . $dato['unidad'] );
    }
  }
  return implode( ' · ', $trozos );
}

/* Etiquetas en el <head> */
function paso_compartir_etiquetas() {
  $general = array(
    'url'   => get_template_directory_uri() . '/assets/img/compartir.jpg',
    'ancho' => PASO_COMPARTIR_ANCHO,
    'alto'  => PASO_COMPARTIR_ALTO,
  );
  // Textos de marca fijos en el tema (no dependen del nombre y la descripción de Ajustes → Generales de cada instalación)
  $marca       = 'PASO';
  $titulo      = wp_get_document_title();
  $descripcion = __( 'Senderismo en Málaga, en grupo: la próxima ruta con todos sus datos, el archivo de rutas y consejos para salir al monte.', 'paso' );
  $imagen      = $general;
  $tipo        = 'website';
  $url         = home_url( add_query_arg( array() ) );

  if ( is_singular( 'ruta' ) ) {
    $titulo      = get_the_title();
    $descripcion = paso_ruta_descripcion() ?: $descripcion;
    $imagen      = paso_ruta_imagen_compartir() ?: $general;
    $tipo        = 'article';
    $url         = get_permalink();
  } elseif ( is_front_page() ) {
    $titulo = $marca . ' · ' . __( 'Senderismo en Málaga, en grupo', 'paso' );
    $url    = home_url( '/' );
  } elseif ( is_singular() ) {
    $titulo = get_the_title() . ' · ' . $marca;
    $url    = get_permalink();
    if ( has_excerpt() ) $descripcion = get_the_excerpt();
  } elseif ( is_post_type_archive( 'ruta' ) ) {
    $titulo = __( 'Rutas', 'paso' ) . ' · ' . $marca;
    $url    = get_post_type_archive_link( 'ruta' );
  }

  $etiquetas = array(
    'description'         => $descripcion,
    'og:site_name'        => $marca,
    'og:locale'           => 'es_ES',
    'og:type'             => $tipo,
    'og:title'            => $titulo,
    'og:description'      => $descripcion,
    'og:url'              => $url,
    'og:image'            => $imagen['url'],
    'og:image:width'      => $imagen['ancho'],
    'og:image:height'     => $imagen['alto'],
    'og:image:alt'        => $titulo,
    'twitter:card'        => 'summary_large_image',
  );
  foreach ( $etiquetas as $nombre => $valor ) {
    $atributo = ( 'description' === $nombre || str_starts_with( $nombre, 'twitter:' ) ) ? 'name' : 'property';
    printf( '<meta %s="%s" content="%s">' . "\n", $atributo, esc_attr( $nombre ), esc_attr( $valor ) );
  }
}
add_action( 'wp_head', 'paso_compartir_etiquetas', 5 );
