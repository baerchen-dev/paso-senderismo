<?php
/**
 * MAPAS · imagen fija del mapa (recorrido y punto de encuentro), generada por el tema y servida desde la propia web.
 * Base: teselas de OpenStreetMap (o de OpenTopoMap, con curvas de nivel: PASO_MAPA_FUENTE) oscurecidas por el tema:
 * gris invertido y teñido de petróleo a Piedra. Atribución obligatoria: «© colaboradores de OpenStreetMap».
 * Se descargan una sola vez, al guardar la ruta (o en la primera visita si falta), y se guardan en
 * uploads/paso-mapas/ en WebP: las visitas no piden nada a servidores externos.
 * Lo de encima (trazado, marcas, escala, brújula) se pinta en SVG/HTML en components/mapa-*.php, con las posiciones de aquí.
 */

const PASO_MAPA_ANCHO   = 1000;   // px de la imagen (16:10). Lo de encima (trazado, textos) es vectorial y se ve nítido igualmente
const PASO_MAPA_ALTO    = 625;
const PASO_MAPA_TESELA  = 256;
const PASO_MAPA_VERSION = 2;      // subir si cambia el aspecto, para rehacer los mapas guardados
const PASO_MAPA_FUENTE  = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';   // OpenTopoMap: https://a.tile.opentopomap.org/{z}/{x}/{y}.png

/* Coordenadas → píxel del mundo en un zoom (proyección Web Mercator, la de los mapas web) */
function paso_mapa_pixel( $lat, $lon, $zoom ) {
  $mundo = PASO_MAPA_TESELA * ( 2 ** $zoom );
  $lat   = deg2rad( max( -85, min( 85, $lat ) ) );
  return array(
    ( $lon + 180 ) / 360 * $mundo,
    ( 1 - log( tan( $lat ) + 1 / cos( $lat ) ) / M_PI ) / 2 * $mundo,
  );
}

/* Compone la imagen del mapa centrada en un píxel del mundo y la guarda en WebP. Devuelve ruta y URL, o null si falla */
function paso_mapa_componer( $centro_x, $centro_y, $zoom, $nombre ) {
  $izquierda = $centro_x - PASO_MAPA_ANCHO / 2;
  $arriba    = $centro_y - PASO_MAPA_ALTO / 2;
  $lienzo    = imagecreatetruecolor( PASO_MAPA_ANCHO, PASO_MAPA_ALTO );
  imagefill( $lienzo, 0, 0, imagecolorallocate( $lienzo, 16, 50, 60 ) );   // Petroleum Raised si falta alguna tesela

  for ( $tx = (int) floor( $izquierda / PASO_MAPA_TESELA ); $tx * PASO_MAPA_TESELA < $izquierda + PASO_MAPA_ANCHO; $tx++ ) {
    for ( $ty = (int) floor( $arriba / PASO_MAPA_TESELA ); $ty * PASO_MAPA_TESELA < $arriba + PASO_MAPA_ALTO; $ty++ ) {
      // La política de uso de OpenStreetMap pide identificarse (user-agent y referer); aquí se piden unas 20 teselas por mapa, una vez
      $respuesta = wp_remote_get( str_replace( array( '{z}', '{x}', '{y}' ), array( $zoom, $tx, $ty ), PASO_MAPA_FUENTE ), array(
        'timeout'    => 15,
        'user-agent' => 'PASO/1.0 (+' . home_url( '/' ) . ')',
        'headers'    => array( 'Referer' => home_url( '/' ) ),
      ) );
      if ( is_wp_error( $respuesta ) || 200 !== wp_remote_retrieve_response_code( $respuesta ) ) continue;
      $tesela = @imagecreatefromstring( wp_remote_retrieve_body( $respuesta ) );
      if ( ! $tesela ) continue;
      imagecopy( $lienzo, $tesela, (int) round( $tx * PASO_MAPA_TESELA - $izquierda ), (int) round( $ty * PASO_MAPA_TESELA - $arriba ), 0, 0, PASO_MAPA_TESELA, PASO_MAPA_TESELA );
      imagedestroy( $tesela );
    }
  }

  paso_mapa_oscurecer( $lienzo );

  $subidas = wp_upload_dir();
  $carpeta = trailingslashit( $subidas['basedir'] ) . 'paso-mapas';
  wp_mkdir_p( $carpeta );
  $ok = imagewebp( $lienzo, "{$carpeta}/{$nombre}.webp", 80 );
  imagedestroy( $lienzo );
  if ( ! $ok ) return null;

  return array(
    'archivo' => "{$carpeta}/{$nombre}.webp",
    'url'     => trailingslashit( $subidas['baseurl'] ) . "paso-mapas/{$nombre}.webp",
  );
}

/* Oscurece la imagen: gris invertido (lo claro pasa a oscuro) y teñido de Petroleum (#071E25) a un Piedra apagado */
function paso_mapa_oscurecer( $imagen ) {
  imagefilter( $imagen, IMG_FILTER_GRAYSCALE );
  imagefilter( $imagen, IMG_FILTER_NEGATE );
  $tabla = array();
  for ( $v = 0; $v < 256; $v++ ) {
    $t           = max( 0, min( 1, ( 128 + ( $v - 128 ) * 0.75 ) / 255 ) );   // un poco menos de contraste
    $tabla[ $v ] = ( (int) ( 7 + ( 170 - 7 ) * $t ) << 16 ) | ( (int) ( 30 + ( 190 - 30 ) * $t ) << 8 ) | (int) ( 37 + ( 190 - 37 ) * $t );
  }
  $ancho = imagesx( $imagen );
  $alto  = imagesy( $imagen );
  for ( $y = 0; $y < $alto; $y++ ) {
    for ( $x = 0; $x < $ancho; $x++ ) {
      imagesetpixel( $imagen, $x, $y, $tabla[ imagecolorat( $imagen, $x, $y ) & 0xFF ] );
    }
  }
}

/* Escala: distancia redonda que ocupe entre el 10 y el 20 % del ancho. Devuelve array( 'texto', 'ancho' en % ) */
function paso_mapa_escala( $lat, $zoom ) {
  $metros_px = 156543.03392 * cos( deg2rad( $lat ) ) / ( 2 ** $zoom ) / ( PASO_MAPA_TESELA / 256 );
  foreach ( array( 200, 500, 1000, 2000, 5000, 10000 ) as $metros ) {
    $ancho = $metros / $metros_px / PASO_MAPA_ANCHO * 100;
    if ( $ancho >= 10 ) break;
  }
  return array( 'texto' => $metros >= 1000 ? paso_cifra( $metros / 1000 ) . ' km' : $metros . ' m', 'ancho' => round( $ancho, 2 ) );
}

/* Mapa del recorrido de una ruta (a partir de su GPX). Guardado en la ruta; se rehace si cambia el GPX. Sin GPX → null */
function paso_mapa_recorrido( $post = null ) {
  $post = get_post( $post );
  $gpx  = paso_ruta_gpx( $post );
  if ( ! $gpx || empty( $gpx['trazado'] ) ) return null;

  $clave    = md5( PASO_MAPA_VERSION . '|' . $gpx['huella'] );
  $guardado = get_post_meta( $post->ID, '_paso_mapa_recorrido', true );
  if ( is_array( $guardado ) && $guardado['clave'] === $clave && file_exists( $guardado['archivo'] ) ) return $guardado;

  // Zoom más cercano en el que el recorrido cabe dejando margen para etiquetas, brújula y escala
  $lats = array_column( $gpx['trazado'], 0 );
  $lons = array_column( $gpx['trazado'], 1 );
  for ( $zoom = 16; $zoom > 8; $zoom-- ) {
    list( $x1, $y1 ) = paso_mapa_pixel( max( $lats ), min( $lons ), $zoom );
    list( $x2, $y2 ) = paso_mapa_pixel( min( $lats ), max( $lons ), $zoom );
    if ( $x2 - $x1 <= PASO_MAPA_ANCHO * 0.66 && $y2 - $y1 <= PASO_MAPA_ALTO * 0.62 ) break;
  }
  $centro_x = ( $x1 + $x2 ) / 2;
  $centro_y = ( $y1 + $y2 ) / 2;

  $imagen = paso_mapa_componer( $centro_x, $centro_y, $zoom, 'ruta-' . $post->ID . '-' . substr( $clave, 0, 8 ) );
  if ( ! $imagen ) return null;

  // Posiciones en el sistema del SVG (1000 × 625, la misma proporción que la imagen)
  $a_svg = function ( $lat, $lon ) use ( $zoom, $centro_x, $centro_y ) {
    list( $x, $y ) = paso_mapa_pixel( $lat, $lon, $zoom );
    return array(
      round( ( $x - $centro_x + PASO_MAPA_ANCHO / 2 ) / PASO_MAPA_ANCHO * 1000, 1 ),
      round( ( $y - $centro_y + PASO_MAPA_ALTO / 2 ) / PASO_MAPA_ALTO * 625, 1 ),
    );
  };
  $puntos = array_map( fn( $p ) => implode( ',', $a_svg( $p[0], $p[1] ) ), $gpx['trazado'] );

  $mapa = $imagen + array(
    'clave'   => $clave,
    'trazado' => 'M' . implode( ' L', $puntos ),
    'salida'  => $a_svg( $gpx['inicio'][0], $gpx['inicio'][1] ),
    'cima'    => $a_svg( $gpx['cima'][0], $gpx['cima'][1] ),
    'escala'  => paso_mapa_escala( ( max( $lats ) + min( $lats ) ) / 2, $zoom ),
  );
  update_post_meta( $post->ID, '_paso_mapa_recorrido', $mapa );
  return $mapa;
}

/* Mapa del punto de encuentro (centrado en las coordenadas). Se rehace si cambian. Sin coordenadas → null */
function paso_mapa_encuentro( $post = null ) {
  $post   = get_post( $post );
  $coords = array_map( 'floatval', explode( ',', (string) get_field( 'ruta_coordenadas', $post ) ) );
  if ( count( $coords ) !== 2 || ! $coords[0] ) return null;

  $zoom     = 15;
  $clave    = md5( PASO_MAPA_VERSION . '|' . implode( ',', $coords ) . '|' . $zoom );
  $guardado = get_post_meta( $post->ID, '_paso_mapa_encuentro', true );
  if ( is_array( $guardado ) && $guardado['clave'] === $clave && file_exists( $guardado['archivo'] ) ) return $guardado;

  list( $x, $y ) = paso_mapa_pixel( $coords[0], $coords[1], $zoom );
  $imagen = paso_mapa_componer( $x, $y, $zoom, 'encuentro-' . $post->ID . '-' . substr( $clave, 0, 8 ) );
  if ( ! $imagen ) return null;

  $mapa = $imagen + array( 'clave' => $clave );
  update_post_meta( $post->ID, '_paso_mapa_encuentro', $mapa );
  return $mapa;
}

/* Al guardar una ruta, se preparan sus mapas (así la primera visita no espera a las teselas) */
function paso_mapas_al_guardar( $post_id ) {
  if ( 'ruta' !== get_post_type( $post_id ) || wp_is_post_revision( $post_id ) ) return;
  paso_mapa_recorrido( $post_id );
  paso_mapa_encuentro( $post_id );
}
add_action( 'acf/save_post', 'paso_mapas_al_guardar', 20 );
