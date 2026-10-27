<?php
/**
 * MIDE PANEL · Figma: MIDE Panel (Layout=Mobile: 2 × 2 · Layout=Desktop: 4 en fila) + MIDE Rating (Value=1…5)
 * Valoración MIDE (Método de Información de Excursiones): medio, itinerario, desplazamiento y esfuerzo, de 1 a 5.
 * Cada criterio: nombre y cifra, escala de 5 segmentos (los rellenos en amarillo) y qué mide.
 * $args['ruta']: WP_Post. Sin ningún valor, no se pinta.
 */
$criterios = array(
  'medio'          => array( __( 'Medio', 'paso' ),          __( 'Severidad del medio natural', 'paso' ) ),
  'itinerario'     => array( __( 'Itinerario', 'paso' ),     __( 'Orientación en el itinerario', 'paso' ) ),
  'desplazamiento' => array( __( 'Desplazamiento', 'paso' ), __( 'Dificultad en el desplazamiento', 'paso' ) ),
  'esfuerzo'       => array( __( 'Esfuerzo', 'paso' ),       __( 'Cantidad de esfuerzo necesario', 'paso' ) ),
);
$valores = array();
foreach ( $criterios as $clave => $criterio ) {
  $valor = (int) get_field( 'ruta_mide_' . $clave, $args['ruta'] );
  if ( $valor ) $valores[ $clave ] = $valor;
}
if ( ! $valores ) return;
?>
<div class="flex flex-col gap-4 lg:gap-6">
  <div class="flex flex-col items-start gap-2 lg:flex-row lg:items-center lg:justify-between">
    <h2 class="text-eyebrow text-amarillo"><?php esc_html_e( 'Valoración MIDE · de 1 a 5', 'paso' ); ?></h2>
    <?php get_template_part( 'components/enlace-flecha', null, array( 'texto' => __( '¿Qué significa?', 'paso' ), 'url' => home_url( '/consejos/#mide' ) ) ); ?>
  </div>

  <!-- El lector de pantalla oye «Medio: 3 de 5. Severidad del medio natural»; la escala es solo visual -->
  <ul class="grid grid-cols-2 gap-5 lg:grid-cols-4 lg:gap-10">
    <?php foreach ( $valores as $clave => $valor ) : ?>
      <li class="flex flex-col gap-2.5">
        <p class="flex flex-wrap items-baseline justify-between gap-x-2">
          <span class="text-eyebrow text-piedra"><?php echo esc_html( $criterios[ $clave ][0] ); ?><span class="sr-only">:</span></span>
          <span class="text-display-title text-amarillo"><?php echo esc_html( $valor ); ?><span class="sr-only"> <?php esc_html_e( 'de 5', 'paso' ); ?>.</span></span>
        </p>
        <span class="flex gap-1" aria-hidden="true">
          <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
            <span class="h-2 flex-1 rounded-xs <?php echo $i <= $valor ? 'bg-amarillo' : 'bg-line'; ?>"></span>
          <?php endfor; ?>
        </span>
        <p class="text-caption text-muted"><?php echo esc_html( $criterios[ $clave ][1] ); ?></p>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
