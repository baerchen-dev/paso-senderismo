<?php
/**
 * MAPA DEL RECORRIDO · Figma: «Mapa del recorrido» del panel Recorrido (proporción 16:10 en todos los tamaños)
 * Imagen fija de inc/mapas.php y, encima: trazado amarillo con sombra petróleo, salida (círculo Piedra), cima (triángulo amarillo),
 * sus etiquetas, escala y brújula. El SVG usa el mismo sistema que la imagen (1000 × 625), así que todo coincide a cualquier ancho.
 * $args: 'mapa' (paso_mapa_recorrido), 'salida' y 'cima' (textos de las etiquetas),
 *        'salida_movil' (opcional: etiqueta de salida más corta hasta md, como en Figma Mobile).
 */
$mapa = $args['mapa'];
$pos  = fn( $p ) => sprintf( 'left: %s%%; top: %s%%', round( $p[0] / 10, 2 ), round( $p[1] / 6.25, 2 ) );
// Etiqueta encima de su marca: centrada, o alineada al lado de dentro si la marca está cerca de un borde
$lado = fn( $p ) => $p[0] < 200 ? '' : ( $p[0] > 800 ? '-translate-x-full' : '-translate-x-1/2' );
?>
<figure class="relative aspect-[16/10] overflow-hidden rounded-xl bg-petroleum-raised lg:rounded-2xl" role="img"
  aria-label="<?php echo esc_attr( sprintf( __( 'Mapa del recorrido, de %1$s a %2$s.', 'paso' ), $args['salida'], $args['cima'] ) ); ?>">
  <div aria-hidden="true">
    <img src="<?php echo esc_url( $mapa['url'] ); ?>" alt="" width="<?php echo esc_attr( PASO_MAPA_ANCHO ); ?>" height="<?php echo esc_attr( PASO_MAPA_ALTO ); ?>" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">

    <svg class="absolute inset-0 size-full" viewBox="0 0 1000 625" preserveAspectRatio="none">
      <path d="<?php echo esc_attr( $mapa['trazado'] ); ?>" fill="none" stroke="var(--color-petroleum)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
      <path d="<?php echo esc_attr( $mapa['trazado'] ); ?>" fill="none" stroke="var(--color-amarillo)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
    </svg>

    <!-- Marcas -->
    <span class="absolute size-4 -translate-1/2 rounded-full border-3 border-petroleum bg-piedra box-content" style="<?php echo esc_attr( $pos( $mapa['salida'] ) ); ?>"></span>
    <svg class="absolute h-4.75 w-5.5 -translate-x-1/2 -translate-y-2/3" viewBox="-2 -2 26 23" style="<?php echo esc_attr( $pos( $mapa['cima'] ) ); ?>">
      <path d="M11 0 L22 19 L0 19 Z" fill="var(--color-amarillo)" stroke="var(--color-petroleum)" stroke-width="2.5" stroke-linejoin="round"/>
    </svg>

    <!-- Etiquetas de salida y cima -->
    <?php foreach ( array( array( $mapa['salida'], $args['salida'], $args['salida_movil'] ?? '' ), array( $mapa['cima'], $args['cima'], '' ) ) as list( $punto, $texto, $movil ) ) : ?>
      <span class="absolute -mt-4 -translate-y-full whitespace-nowrap text-caption text-piedra [text-shadow:0_1px_3px_var(--color-petroleum)] <?php echo esc_attr( $lado( $punto ) ); ?>" style="<?php echo esc_attr( $pos( $punto ) ); ?>">
        <?php if ( $movil ) : ?><span class="md:hidden"><?php echo esc_html( $movil ); ?></span><span class="hidden md:inline"><?php echo esc_html( $texto ); ?></span><?php else : echo esc_html( $texto ); endif; ?>
      </span>
    <?php endforeach; ?>

    <!-- Escala -->
    <span class="absolute bottom-4 left-4 flex flex-col gap-1 lg:bottom-6 lg:left-6" style="width: <?php echo esc_attr( $mapa['escala']['ancho'] ); ?>%">
      <span class="text-caption text-piedra [text-shadow:0_1px_3px_var(--color-petroleum)]"><?php echo esc_html( $mapa['escala']['texto'] ); ?></span>
      <span class="h-1.5 border-x-2 border-b-2 border-piedra"></span>
    </span>

    <!-- Brújula · Figma: Icon / Compass (N) -->
    <svg class="absolute right-3.5 top-3.5 size-10 lg:right-6 lg:top-6 lg:size-14" viewBox="0 0 56 56">
      <circle cx="28" cy="28" r="18.5" fill="none" stroke="var(--color-amarillo)" stroke-width="1"/>
      <path d="M0 28.5 H56 M28.5 0 V56" stroke="var(--color-amarillo)" stroke-width="1"/>
      <circle cx="28" cy="28" r="9" fill="var(--color-petroleum)"/>
      <text x="28" y="32.5" text-anchor="middle" fill="var(--color-amarillo-hover)" font-family="var(--font-sans)" font-weight="600" font-size="13">N</text>
    </svg>

    <p class="absolute bottom-1 right-2 text-[0.625rem] leading-none text-muted">© colaboradores de OpenStreetMap</p>
  </div>
</figure>
