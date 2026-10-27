<?php
/**
 * NEXT ROUTE · Figma: Next Route / Mobile (también en tablet) · Next Route / Desktop (lg) · Theme=Dark
 * State=Programada: antetítulo con marca de sendero, nombre, fecha · hora · punto de encuentro, botón y los 6 datos.
 * State=Sin ruta: aviso y enlace a las rutas anteriores.
 * $args['ruta']: WP_Post de la ruta, o null → Sin ruta.
 * Nombre a 40 hasta lg (en tablet, como en Figma, el tamaño de móvil: a 64 se partiría dentro de la tarjeta).
 */
$ruta  = $args['ruta'] ?? null;
$fecha = $ruta ? paso_ruta_fecha( $ruta ) : null;
?>
<div class="rounded-2xl bg-petroleum-soft px-5 py-8 lg:rounded-3xl lg:p-14">

  <?php if ( $ruta ) : ?>

    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between lg:gap-12">
      <div class="flex flex-col gap-3 lg:max-w-225 lg:gap-4">
        <!-- Solo «Próxima ruta»: la fecha ya va en la línea de debajo -->
        <p class="flex items-center gap-3 text-eyebrow text-piedra">
          <?php get_template_part( 'components/trail-mark', null, array( 'tipo' => get_field( 'ruta_senal', $ruta ) ) ); ?>
          <span><?php esc_html_e( 'Próxima ruta', 'paso' ); ?></span>
        </p>
        <h2 class="text-display-section-lg text-piedra"><?php echo esc_html( get_the_title( $ruta ) ); ?></h2>
        <p class="text-body text-piedra"><?php echo esc_html( implode( ' · ', array_filter( array( $fecha['dia'] ?? '', $fecha['hora'] ?? '', get_field( 'ruta_encuentro', $ruta ) ) ) ) ); ?></p>
      </div>
      <?php get_template_part( 'components/boton', null, array(
        'texto' => __( 'Ver la ruta', 'paso' ),
        'url'   => get_permalink( $ruta ),
        'clase' => 'w-full lg:w-auto lg:shrink-0',
      ) ); ?>
    </div>

    <?php $datos = paso_ruta_datos( $ruta ); ?>
    <?php if ( $datos ) : ?>
      <div class="mt-10 lg:mt-12">
        <?php get_template_part( 'components/route-stats', null, array( 'datos' => $datos ) ); ?>
      </div>
    <?php endif; ?>

  <?php else : ?>

    <div class="flex flex-col items-start gap-3 lg:max-w-225 lg:gap-4">
      <p class="flex items-center gap-3 text-eyebrow text-piedra">
        <?php get_template_part( 'components/trail-mark' ); ?>
        <span><?php esc_html_e( 'Próxima ruta', 'paso' ); ?></span>
      </p>
      <h2 class="text-display-section-lg text-piedra"><?php esc_html_e( 'Ahora mismo no hay ninguna ruta programada.', 'paso' ); ?></h2>
      <p class="text-body text-piedra"><?php esc_html_e( 'Las nuevas rutas se anuncian en el grupo de WhatsApp del club. Mientras tanto, mira las que ya hemos hecho.', 'paso' ); ?></p>
      <?php get_template_part( 'components/enlace-flecha', null, array(
        'texto' => __( 'Ver rutas anteriores', 'paso' ),
        'url'   => get_post_type_archive_link( 'ruta' ),
      ) ); ?>
    </div>

  <?php endif; ?>

</div>
