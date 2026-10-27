<?php
/**
 * GPX · lee el track de cada ruta (campo ruta_gpx) y saca sus cifras y el perfil de altitud.
 * Cifras: distancia, desnivel + y −, altitud máxima (y en qué km), salida, duración total y en movimiento.
 * El resultado se guarda en el propio adjunto (_paso_gpx) y solo se recalcula si cambia el archivo.
 * Puede haber discrepancias con Wikiloc (pérdidas de señal, otro filtrado): las cifras escritas a mano en la ruta mandan.
 */

const PASO_GPX_VERSION    = 3;     // subir si cambia el cálculo, para rehacer los datos guardados
const PASO_GPX_UMBRAL_ELE = 3;     // m: cambios de altitud menores se toman como ruido del GPS
const PASO_GPX_MOVIMIENTO = 0.5;   // km/h: por debajo, parado
const PASO_GPX_PERFIL     = 160;   // puntos del perfil de altitud
const PASO_GPX_TRAZADO    = 400;   // puntos del trazado para el mapa

/* Permitir subir .gpx a Medios (WordPress lo detecta como XML y lo rechazaría) */
function paso_gpx_tipos( $tipos ) {
  $tipos['gpx'] = 'application/gpx+xml';
  return $tipos;
}
add_filter( 'upload_mimes', 'paso_gpx_tipos' );

function paso_gpx_comprobar( $datos, $archivo, $nombre ) {
  if ( 'gpx' === strtolower( pathinfo( $nombre, PATHINFO_EXTENSION ) ) ) {
    $datos['ext']  = 'gpx';
    $datos['type'] = 'application/gpx+xml';
  }
  return $datos;
}
add_filter( 'wp_check_filetype_and_ext', 'paso_gpx_comprobar', 10, 3 );

/* Distancia en metros entre dos puntos (fórmula del haversine) */
function paso_gpx_distancia( $a, $b ) {
  $la1 = deg2rad( $a['lat'] );
  $la2 = deg2rad( $b['lat'] );
  $h   = sin( ( $la2 - $la1 ) / 2 ) ** 2 + cos( $la1 ) * cos( $la2 ) * sin( deg2rad( $b['lon'] - $a['lon'] ) / 2 ) ** 2;
  return 2 * 6371000 * asin( sqrt( $h ) );
}

/* Cifras y perfil de un archivo GPX. Sin puntos válidos → null */
function paso_gpx_calcular( $ruta_archivo ) {
  $xml = @simplexml_load_file( $ruta_archivo );
  if ( ! $xml ) return null;

  $puntos = array();
  foreach ( $xml->trk as $trk ) {
    foreach ( $trk->trkseg as $seg ) {
      foreach ( $seg->trkpt as $p ) {
        $puntos[] = array(
          'lat'  => (float) $p['lat'],
          'lon'  => (float) $p['lon'],
          'ele'  => isset( $p->ele ) ? (float) $p->ele : null,
          'hora' => isset( $p->time ) ? strtotime( (string) $p->time ) : null,
        );
      }
    }
  }
  if ( count( $puntos ) < 2 ) return null;

  $km = 0; $sube = 0; $baja = 0; $movimiento = 0;
  $ref     = $puntos[0]['ele'];
  $maxima  = $puntos[0];
  $km_max  = 0;
  $perfil  = array( array( 0, $puntos[0]['ele'] ) );

  for ( $i = 1; $i < count( $puntos ); $i++ ) {
    $a = $puntos[ $i - 1 ];
    $b = $puntos[ $i ];
    $tramo = paso_gpx_distancia( $a, $b );
    $km   += $tramo / 1000;

    if ( null !== $b['ele'] ) {
      // Desnivel con histéresis: solo cuenta cuando se aleja más del umbral del último punto tomado
      if ( $b['ele'] - $ref >= PASO_GPX_UMBRAL_ELE )     { $sube += $b['ele'] - $ref; $ref = $b['ele']; }
      elseif ( $ref - $b['ele'] >= PASO_GPX_UMBRAL_ELE ) { $baja += $ref - $b['ele']; $ref = $b['ele']; }
      if ( $b['ele'] > $maxima['ele'] ) { $maxima = $b; $km_max = $km; }
      $perfil[] = array( $km, $b['ele'] );
    }

    $segundos = ( $a['hora'] && $b['hora'] ) ? $b['hora'] - $a['hora'] : 0;
    if ( $segundos > 0 && ( $tramo / $segundos * 3.6 ) >= PASO_GPX_MOVIMIENTO ) $movimiento += $segundos;
  }

  // Perfil reducido a PASO_GPX_PERFIL puntos repartidos por distancia
  $reducido = array();
  $paso     = max( 1, count( $perfil ) / PASO_GPX_PERFIL );
  for ( $i = 0; $i < count( $perfil ); $i += $paso ) $reducido[] = $perfil[ (int) $i ];
  $reducido[] = end( $perfil );

  // Trazado reducido (latitud, longitud) para el mapa
  $trazado = array();
  $paso    = max( 1, count( $puntos ) / PASO_GPX_TRAZADO );
  for ( $i = 0; $i < count( $puntos ); $i += $paso ) $trazado[] = array( $puntos[ (int) $i ]['lat'], $puntos[ (int) $i ]['lon'] );
  $trazado[] = array( end( $puntos )['lat'], end( $puntos )['lon'] );

  $inicio = reset( $puntos );
  $fin    = end( $puntos );
  return array(
    'version'     => PASO_GPX_VERSION,
    'distancia'   => round( $km, 1 ),
    'desnivel'    => round( $sube ),
    'bajada'      => round( $baja ),
    'altitud'     => round( $maxima['ele'] ),
    'km_cima'     => round( $km_max, 1 ),
    'salida'      => null !== $inicio['ele'] ? round( $inicio['ele'] ) : null,
    'inicio'      => array( $inicio['lat'], $inicio['lon'] ),
    'cima'        => array( $maxima['lat'], $maxima['lon'] ),
    'duracion'    => ( $inicio['hora'] && $fin['hora'] ) ? round( ( $fin['hora'] - $inicio['hora'] ) / 3600 * 2 ) / 2 : null,   // horas, de media en media
    'movimiento'  => $movimiento ? round( $movimiento / 3600 * 2 ) / 2 : null,
    'perfil'      => $reducido,
    'trazado'     => $trazado,
  );
}

/* Datos del GPX de una ruta (guardados en el adjunto; se recalculan si cambia el archivo). Sin GPX → null */
function paso_ruta_gpx( $post = null ) {
  $adjunto = get_field( 'ruta_gpx', $post );
  $archivo = $adjunto ? get_attached_file( $adjunto ) : '';
  if ( ! $archivo || ! file_exists( $archivo ) ) return null;

  $guardado = get_post_meta( $adjunto, '_paso_gpx', true );
  $huella   = filemtime( $archivo ) . '-' . filesize( $archivo );
  if ( is_array( $guardado ) && ( $guardado['huella'] ?? '' ) === $huella && ( $guardado['version'] ?? 0 ) === PASO_GPX_VERSION ) {
    return $guardado;
  }

  $datos = paso_gpx_calcular( $archivo );
  if ( $datos ) {
    $datos['huella'] = $huella;
    update_post_meta( $adjunto, '_paso_gpx', $datos );
  }
  return $datos;
}

/* Cifra de la ruta: la escrita a mano en ACF o, si está vacía, la del GPX.
 * $campo: 'distancia' · 'desnivel' · 'altitud' · 'duracion' */
function paso_ruta_cifra( $campo, $post = null ) {
  $valor = get_field( 'ruta_' . $campo, $post );
  if ( '' !== $valor && null !== $valor ) return $valor;
  $gpx = paso_ruta_gpx( $post );
  return $gpx[ $campo ] ?? '';
}
