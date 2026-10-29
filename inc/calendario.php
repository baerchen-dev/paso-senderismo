<?php
/**
 * CALENDARIO · «Añadir al calendario» de la próxima ruta: ?calendario en la ficha descarga un .ics
 * (nombre, día y hora, duración con paradas —5 h si no hay—, punto de encuentro con coordenadas y enlace a la ficha).
 * El iPhone lo abre en Calendario; en Android, con Google Calendar. Solo para rutas próximas.
 */

/* Enlace de descarga */
function paso_ruta_calendario_url( $post = null ) {
  return add_query_arg( 'calendario', '1', get_permalink( $post ) );
}

/* Texto escapado para .ics (comas, punto y coma, barras y saltos de línea) */
function paso_ics_texto( $texto ) {
  return str_replace( array( '\\', ';', ',', "\r\n", "\n" ), array( '\\\\', '\;', '\,', '\n', '\n' ), wp_strip_all_tags( $texto ) );
}

/* Líneas de más de 75 bytes, partidas como pide el formato (la continuación empieza con un espacio) */
function paso_ics_plegar( $linea ) {
  $salida = '';
  while ( strlen( $linea ) > 75 ) {
    $corte = 75;
    while ( $corte > 0 && ( ord( $linea[ $corte ] ) & 0xC0 ) === 0x80 ) $corte--;   // sin partir una letra con tilde
    $salida .= substr( $linea, 0, $corte ) . "\r\n ";
    $linea   = substr( $linea, $corte );
  }
  return $salida . $linea . "\r\n";
}

function paso_ruta_calendario() {
  if ( ! isset( $_GET['calendario'] ) || ! is_singular( 'ruta' ) ) return;
  $ruta  = get_queried_object();
  $valor = get_field( 'ruta_fecha', $ruta );
  if ( ! $valor || ! paso_ruta_es_proxima( $ruta ) ) {
    wp_safe_redirect( get_permalink( $ruta ) );
    exit;
  }

  $inicio   = date_create_immutable( $valor, wp_timezone() );
  $horas    = (float) str_replace( ',', '.', (string) paso_ruta_cifra( 'duracion', $ruta ) ) ?: 5;
  $fin      = $inicio->modify( '+' . round( $horas * 60 ) . ' minutes' );
  $utc      = fn( $f ) => $f->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Ymd\THis\Z' );
  $lugar    = implode( ', ', array_filter( array( get_field( 'ruta_encuentro', $ruta ), get_field( 'ruta_localidad', $ruta ) ) ) );
  $coords   = preg_replace( '/\s+/', '', (string) get_field( 'ruta_coordenadas', $ruta ) );
  $url      = get_permalink( $ruta );

  $lineas = array(
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//PASO//' . wp_parse_url( home_url(), PHP_URL_HOST ) . '//ES',
    'CALSCALE:GREGORIAN',
    'METHOD:PUBLISH',
    'BEGIN:VEVENT',
    'UID:ruta-' . $ruta->ID . '@' . wp_parse_url( home_url(), PHP_URL_HOST ),
    'DTSTAMP:' . $utc( new DateTimeImmutable() ),
    'DTSTART:' . $utc( $inicio ),
    'DTEND:' . $utc( $fin ),
    'SUMMARY:' . paso_ics_texto( 'PASO · ' . get_the_title( $ruta ) ),
    'DESCRIPTION:' . paso_ics_texto( ( $lugar ? sprintf( __( 'Punto de encuentro: %s', 'paso' ), $lugar ) . "\n" : '' ) . sprintf( __( 'Toda la información: %s', 'paso' ), $url ) ),
    'URL:' . $url,
  );
  if ( $lugar ) $lineas[] = 'LOCATION:' . paso_ics_texto( $lugar );
  if ( preg_match( '/^(-?[\d.]+),(-?[\d.]+)$/', $coords, $m ) ) $lineas[] = 'GEO:' . $m[1] . ';' . $m[2];
  $lineas[] = 'END:VEVENT';
  $lineas[] = 'END:VCALENDAR';

  nocache_headers();
  header( 'Content-Type: text/calendar; charset=utf-8' );
  header( 'Content-Disposition: attachment; filename="paso-' . $ruta->post_name . '.ics"' );
  echo implode( '', array_map( 'paso_ics_plegar', $lineas ) );   // phpcs:ignore WordPress.Security.EscapeOutput
  exit;
}
add_action( 'template_redirect', 'paso_ruta_calendario' );
