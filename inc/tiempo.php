<?php
/**
 * TIEMPO · previsión de la próxima ruta (Figma: Weather / Forecast) con Open-Meteo (gratis, sin clave; atribución obligatoria)
 * Solo desde 7 días antes. Tiempo a la hora de salida en la cima (coordenadas y altitud del GPX; sin GPX, el punto de encuentro).
 * La pide el servidor y la guarda 3 horas (un fallo, 30 minutos): las visitas no llaman a nadie. Sin datos → null y no se muestra.
 */

/* Código WMO de Open-Meteo → icono del tema (components/tiempo.php) */
function paso_tiempo_icono( $codigo ) {
  if ( $codigo <= 1 ) return 'sol';
  if ( 2 === $codigo ) return 'sol-nubes';
  if ( 3 === $codigo ) return 'nubes';
  if ( in_array( $codigo, array( 45, 48 ), true ) ) return 'niebla';
  if ( ( $codigo >= 71 && $codigo <= 77 ) || in_array( $codigo, array( 85, 86 ), true ) ) return 'nieve';
  if ( $codigo >= 95 ) return 'tormenta';
  return 'lluvia';
}

/* Previsión de una ruta → array( 'icono', 'texto' ) o null */
function paso_ruta_tiempo( $post = null ) {
  $post  = get_post( $post );
  $valor = get_field( 'ruta_fecha', $post );
  if ( ! $post || ! $valor ) return null;

  $salida = date_create_immutable( $valor, wp_timezone() );
  $dias   = ( $salida->getTimestamp() - time() ) / DAY_IN_SECONDS;
  if ( $dias < -0.25 || $dias > 7 ) return null;    // hasta unas horas después de salir

  $gpx = paso_ruta_gpx( $post );
  if ( $gpx ) {
    list( $lat, $lon ) = $gpx['cima'];
    $altitud = $gpx['altitud'];
  } else {
    $coords = array_map( 'floatval', explode( ',', (string) get_field( 'ruta_coordenadas', $post ) ) );
    if ( count( $coords ) !== 2 ) return null;
    list( $lat, $lon ) = $coords;
    $altitud = null;
  }

  $clave    = 'paso_tiempo_' . $post->ID . '_' . md5( $valor . $lat . $lon );
  $guardado = get_transient( $clave );
  if ( false === $guardado ) {
    $url = add_query_arg( array_filter( array(
      'latitude'        => round( $lat, 4 ),
      'longitude'       => round( $lon, 4 ),
      'elevation'       => $altitud,   // temperatura corregida a la altura de la cima
      'hourly'          => 'temperature_2m,precipitation_probability,wind_speed_10m,weather_code',
      'timezone'        => 'Europe/Madrid',
      'start_date'      => $salida->format( 'Y-m-d' ),
      'end_date'        => $salida->format( 'Y-m-d' ),
      'wind_speed_unit' => 'kmh',
    ), fn( $v ) => null !== $v ), 'https://api.open-meteo.com/v1/forecast' );

    $respuesta = wp_remote_get( $url, array( 'timeout' => 4 ) );
    $datos     = is_wp_error( $respuesta ) ? null : json_decode( wp_remote_retrieve_body( $respuesta ), true );
    $guardado  = array( 'ok' => false );
    if ( ! empty( $datos['hourly']['time'] ) ) {
      $i = array_search( $salida->format( 'Y-m-d\TH:00' ), $datos['hourly']['time'], true );
      if ( false !== $i ) {
        $h        = $datos['hourly'];
        $guardado = array(
          'ok'     => true,
          'temp'   => $h['temperature_2m'][ $i ],
          'lluvia' => $h['precipitation_probability'][ $i ],
          'viento' => $h['wind_speed_10m'][ $i ],
          'codigo' => (int) $h['weather_code'][ $i ],
        );
      }
    }
    set_transient( $clave, $guardado, $guardado['ok'] ? 3 * HOUR_IN_SECONDS : 30 * MINUTE_IN_SECONDS );
  }
  if ( empty( $guardado['ok'] ) || null === $guardado['temp'] ) return null;

  $dia   = wp_date( 'D', $salida->getTimestamp() );
  $trozos = array(
    mb_strtoupper( mb_substr( $dia, 0, 1 ) ) . mb_substr( $dia, 1 ) . ' ' . wp_date( 'G:i', $salida->getTimestamp() ),
    sprintf( '%s °C', number_format_i18n( round( $guardado['temp'] ) ) ),
  );
  if ( null !== $guardado['lluvia'] ) $trozos[] = sprintf( /* translators: %s: probabilidad de lluvia */ __( 'Lluvia %s %%', 'paso' ), round( $guardado['lluvia'] ) );
  if ( null !== $guardado['viento'] ) $trozos[] = sprintf( /* translators: %s: velocidad del viento */ __( 'Viento %s km/h', 'paso' ), round( $guardado['viento'] ) );

  return array(
    'icono' => paso_tiempo_icono( $guardado['codigo'] ),
    'texto' => implode( ' · ', $trozos ),
    'cima'  => (bool) $gpx,
  );
}
