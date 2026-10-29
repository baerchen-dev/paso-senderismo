<?php
/**
 * PERFIL DE ALTITUD · Figma: «Perfil de altitud» del panel Recorrido (Desktop 468 × 160 · Mobile 342 × 120)
 * Dibujado a partir del GPX (inc/gpx.php): área en degradado amarillo, línea amarilla, cuadrícula discontinua y punto en la cima.
 * El SVG se estira a lo ancho (preserveAspectRatio="none"); textos y punto van en HTML para que no se deformen.
 * $args: 'gpx' (datos de paso_ruta_gpx), 'tipo' (p. ej. «Ida y vuelta»; solo desde lg).
 */
$gpx    = $args['gpx'];
$perfil = $gpx['perfil'];
$total  = max( 0.1, $gpx['distancia'] );
$alturas = array_column( $perfil, 1 );
$minima  = min( $alturas );
$maxima  = max( $alturas );
$suelo   = $minima - ( $maxima - $minima ) * 0.08;   // un poco de aire bajo el punto más bajo
$rango   = max( 1, $maxima - $suelo );

// Posición en % dentro de la zona del gráfico (x: km · y: altitud, 0 arriba)
$x = fn( $km ) => round( $km / $total * 100, 2 );
$y = fn( $ele ) => round( ( $maxima - $ele ) / $rango * 100, 2 );

// Líneas de altitud: el paso «redondo» más pequeño que deje como mucho 3 líneas
foreach ( array( 100, 200, 250, 400, 500, 1000 ) as $paso_ele ) {
  if ( floor( $maxima / $paso_ele ) - ceil( $suelo / $paso_ele ) + 1 <= 3 ) break;
}
$lineas = range( ceil( $suelo / $paso_ele ) * $paso_ele, floor( $maxima / $paso_ele ) * $paso_ele, $paso_ele );

// Kilómetros del eje: paso redondo que deje como mucho 4 marcas, y el total al final
foreach ( array( 1, 2, 4, 5, 10 ) as $paso_km ) {
  if ( $total / $paso_km <= 4.5 ) break;
}
$marcas = array();
for ( $km = 0; $km < $total - $paso_km * 0.6; $km += $paso_km ) $marcas[] = $km;
$marcas[] = $total;

// Trazados del SVG (viewBox 1000 × 100)
$puntos = array_map( fn( $p ) => ( $x( $p[0] ) * 10 ) . ',' . $y( $p[1] ), $perfil );
$linea  = 'M' . implode( ' L', $puntos );
$area   = $linea . ' L1000,100 L0,100 Z';

$etiqueta = sprintf(
  /* translators: 1: altitud de salida, 2: altitud máxima, 3: km de la cima, 4: desnivel positivo */
  __( 'Perfil de altitud: salida a %1$s m, máximo de %2$s m en el km %3$s, desnivel positivo de %4$s m.', 'paso' ),
  paso_cifra( $gpx['salida'] ), paso_cifra( $gpx['altitud'] ), paso_cifra( $gpx['km_cima'] ), paso_cifra( $gpx['desnivel'] )
);
?>
<figure class="relative h-30 overflow-hidden rounded-xl bg-petroleum lg:h-40 lg:rounded-2xl" role="img" aria-label="<?php echo esc_attr( $etiqueta ); ?>">
  <div aria-hidden="true">

    <p class="absolute left-3 top-2.5 text-caption text-muted lg:left-4 lg:top-3">
      <?php echo esc_html( mb_strtoupper( __( 'Perfil', 'paso' ) ) ); ?><?php if ( ! empty( $args['tipo'] ) ) : ?><span class="hidden lg:inline"> · <?php echo esc_html( mb_strtoupper( $args['tipo'] ) ); ?></span><?php endif; ?> · +<?php echo esc_html( paso_cifra( $gpx['desnivel'] ) ); ?> m
    </p>

    <!-- Zona del gráfico: los % de dentro son km (x) y altitud (y) -->
    <div class="absolute bottom-6 left-10 right-3 top-11 lg:bottom-6.5 lg:left-11 lg:right-4 lg:top-11">
      <svg class="absolute inset-0 size-full overflow-visible" viewBox="0 0 1000 100" preserveAspectRatio="none">
        <defs>
          <linearGradient id="perfil-area" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="var(--color-amarillo)" stop-opacity=".35"/>
            <stop offset="1" stop-color="var(--color-amarillo)" stop-opacity="0"/>
          </linearGradient>
          <!-- Se ensancha de 0 a 1000 al entrar en pantalla: el perfil se dibuja de la salida al final (assets/js/trazado.js) -->
          <clipPath id="perfil-revelar"><rect data-perfil-revelar width="1000" height="200" y="-50"/></clipPath>
        </defs>
        <?php foreach ( $lineas as $ele ) : ?>
          <line x1="0" x2="1000" y1="<?php echo esc_attr( $y( $ele ) ); ?>" y2="<?php echo esc_attr( $y( $ele ) ); ?>" stroke="var(--color-line)" stroke-dasharray="2 4" vector-effect="non-scaling-stroke"/>
        <?php endforeach; ?>
        <g clip-path="url(#perfil-revelar)">
          <path d="<?php echo esc_attr( $area ); ?>" fill="url(#perfil-area)"/>
          <path d="<?php echo esc_attr( $linea ); ?>" fill="none" stroke="var(--color-amarillo)" stroke-width="2" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
        </g>
      </svg>

      <?php foreach ( $lineas as $ele ) : ?>
        <span class="absolute -left-7.5 -translate-y-full whitespace-nowrap text-caption text-muted lg:-left-8" style="top: <?php echo esc_attr( $y( $ele ) ); ?>%"><?php echo esc_html( $ele ); ?> m</span>
      <?php endforeach; ?>

      <?php foreach ( $marcas as $i => $km ) : $ultima = $i === count( $marcas ) - 1; ?>
        <span class="absolute top-full mt-0.5 whitespace-nowrap text-caption text-muted <?php echo 0 === $i ? '' : ( $ultima ? '-translate-x-full' : '-translate-x-1/2' ); ?>" style="left: <?php echo esc_attr( $x( $km ) ); ?>%"><?php echo esc_html( paso_cifra( round( $km, 1 ) ) ); ?> km</span>
      <?php endforeach; ?>

      <!-- Cima: punto y cifra · data-perfil-cima (% del ancho): aparecen cuando la línea llega a la cima (assets/js/trazado.js) -->
      <span data-perfil-cima="<?php echo esc_attr( $x( $gpx['km_cima'] ) ); ?>" class="absolute transition-opacity duration-300 size-2 -translate-1/2 rounded-full border-2 border-petroleum bg-amarillo box-content" style="left: <?php echo esc_attr( $x( $gpx['km_cima'] ) ); ?>%; top: 0"></span>
      <!-- La cifra va a la derecha del punto; si la cima está al final, a la izquierda para no salirse -->
      <span data-perfil-cima="<?php echo esc_attr( $x( $gpx['km_cima'] ) ); ?>" class="absolute transition-opacity duration-300 -top-1.5 -translate-y-full whitespace-nowrap text-caption text-piedra <?php echo $x( $gpx['km_cima'] ) > 70 ? '-ml-1.5 -translate-x-full' : 'ml-1.5'; ?>" style="left: <?php echo esc_attr( $x( $gpx['km_cima'] ) ); ?>%">
        <?php echo esc_html( paso_cifra( $gpx['altitud'] ) ); ?> m<span class="hidden lg:inline"> · km <?php echo esc_html( paso_cifra( $gpx['km_cima'] ) ); ?></span>
      </span>
    </div>

  </div>
</figure>
