<?php
/**
 * ROUTE CARD · Figma: Route Card (Status=Realizada / Próxima × State=Default / Hover)
 * Foto de 240 de alto (la imagen destacada de la ruta) con la etiqueta de estado; debajo, marca + mes, nombre, 3 datos y enlace.
 * Foto casi en color en reposo (saturate .85 + brightness .95); en color al pasar el ratón o, en pantallas táctiles,
 * cuando la tarjeta entra en pantalla (data-en-pantalla, assets/js/tarjetas.js).
 * Toda la tarjeta es clicable: el enlace se estira sobre ella (after:inset-0), y sigue siendo un solo enlace.
 * $args: 'ruta' (WP_Post), 'proxima' (true → Status=Próxima), 'clase' (opcional).
 */
$ruta      = $args['ruta'];
$proxima   = ! empty( $args['proxima'] );
$fecha     = get_field( 'ruta_fecha', $ruta );
$mes       = $fecha ? wp_date( 'F Y', date_create_immutable( $fecha, wp_timezone() )->getTimestamp() ) : '';
$distancia = paso_cifra( paso_ruta_cifra( 'distancia', $ruta ) );
$desnivel  = paso_cifra( paso_ruta_cifra( 'desnivel', $ruta ) );
$dificultad = get_field_object( 'ruta_dificultad', $ruta );
$dificultad = $dificultad['choices'][ $dificultad['value'] ] ?? '';
?>
<article class="tarjeta-ruta group relative flex h-full flex-col overflow-hidden rounded-2xl bg-petroleum-soft <?php echo esc_attr( $args['clase'] ?? '' ); ?>">

  <div class="relative h-60 bg-petroleum-raised">
    <?php echo get_the_post_thumbnail( $ruta, 'large', array(
      'class' => 'size-full object-cover saturate-85 brightness-95 transition-[filter] duration-400 ease-out motion-reduce:transition-none group-hover:saturate-100 group-hover:brightness-100 group-focus-within:saturate-100 group-focus-within:brightness-100 group-data-en-pantalla:saturate-100 group-data-en-pantalla:brightness-100',
      'sizes' => '(min-width: 1024px) 405px, 300px',
      'alt'   => '',
    ) ); ?>
    <?php if ( $proxima ) : ?>
      <p class="absolute left-4 top-4 rounded-full bg-amarillo px-2.5 py-1.5 text-eyebrow text-petroleum"><?php esc_html_e( 'Próxima ruta', 'paso' ); ?></p>
    <?php else : ?>
      <p class="absolute left-4 top-4 rounded-full bg-petroleum/85 px-2.5 py-1.5 text-eyebrow text-piedra"><?php esc_html_e( 'Realizada', 'paso' ); ?></p>
    <?php endif; ?>
  </div>

  <div class="flex flex-1 flex-col gap-3 p-6">
    <p class="flex items-center gap-2.5 text-eyebrow text-amarillo">
      <?php get_template_part( 'components/trail-mark', null, array( 'tipo' => get_field( 'ruta_senal', $ruta ) ) ); ?>
      <span><?php echo esc_html( $mes ); ?></span>
    </p>
    <h3 class="text-display-title text-piedra"><?php echo esc_html( get_the_title( $ruta ) ); ?></h3>

    <dl class="flex gap-5">
      <?php if ( $distancia ) : ?>
        <div class="flex flex-col-reverse gap-0.5"><dt class="text-caption text-muted"><?php esc_html_e( 'Distancia', 'paso' ); ?></dt><dd class="text-link text-piedra"><?php echo esc_html( $distancia ); ?> km</dd></div>
      <?php endif; ?>
      <?php if ( $desnivel ) : ?>
        <div class="flex flex-col-reverse gap-0.5"><dt class="text-caption text-muted"><?php esc_html_e( 'Desnivel', 'paso' ); ?></dt><dd class="text-link text-piedra">+<?php echo esc_html( $desnivel ); ?> m</dd></div>
      <?php endif; ?>
      <?php if ( $dificultad ) : ?>
        <div class="flex flex-col-reverse gap-0.5"><dt class="text-caption text-muted"><?php esc_html_e( 'Dificultad', 'paso' ); ?></dt><dd class="text-link text-amarillo"><?php echo esc_html( $dificultad ); ?></dd></div>
      <?php endif; ?>
    </dl>

    <!-- Enlace abajo del todo (mt-auto): en una fila de tarjetas quedan alineados aunque un nombre ocupe más líneas -->
    <div class="mt-auto pt-1">
      <a href="<?php echo esc_url( get_permalink( $ruta ) ); ?>" class="inline-flex items-center gap-2 py-1 text-link text-amarillo transition-colors duration-200 group-hover:text-amarillo-hover after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-amarillo">
        <?php echo esc_html( $proxima ? __( 'Ver la ruta', 'paso' ) : __( 'Ver ruta y fotos', 'paso' ) ); ?>
        <span class="sr-only">· <?php echo esc_html( get_the_title( $ruta ) ); ?></span>
        <span class="inline-block transition-transform duration-300 ease-out motion-reduce:transition-none group-hover:translate-x-1" aria-hidden="true">→</span>
      </a>
    </div>
  </div>

</article>
