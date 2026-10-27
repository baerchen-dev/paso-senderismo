<?php
/**
 * RUTA · HERO · Figma: Section / Hero ruta (Desktop 1440 × 760 · Tablet y Mobile × 720)
 * Foto a todo el ancho (vertical en móvil) con velos; abajo, marca + «Próxima ruta», nombre, fecha · hora · punto de encuentro
 * y aviso de inscripción (Inscription, Mode=Info). Pie de foto: arriba a la derecha hasta lg, abajo a la derecha en escritorio.
 * $args: 'ruta' (WP_Post), 'proxima' (bool).
 */
$ruta         = $args['ruta'];
$proxima      = $args['proxima'];
$imagen       = get_field( 'ruta_hero_imagen', $ruta ) ?: get_post_thumbnail_id( $ruta );
$imagen_movil = get_field( 'ruta_hero_imagen_movil', $ruta ) ?: $imagen;
$fecha        = paso_ruta_fecha( $ruta );
$encuentro    = get_field( 'ruta_encuentro', $ruta );
$pie          = get_field( 'ruta_pie_foto', $ruta );
?>
<section class="relative isolate h-180 overflow-hidden bg-petroleum lg:h-190">

  <?php if ( $imagen ) : ?>
    <picture>
      <source media="(max-width: 767px)" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $imagen_movil, 'full' ) ); ?>" sizes="100vw">
      <?php echo wp_get_attachment_image( $imagen, 'full', false, array(
        'class'         => 'absolute inset-0 -z-10 size-full object-cover',
        'sizes'         => '100vw',
        'loading'       => 'eager',
        'fetchpriority' => 'high',
      ) ); ?>
    </picture>
  <?php endif; ?>

  <!-- VELOS (decorativos) · general 55 → 20 % (45 %) → 90 % · cabecera 60 % → 0 (180 px; 240 en escritorio) -->
  <div class="absolute inset-0 -z-10 bg-linear-to-b from-petroleum/55 via-petroleum/20 via-45% to-petroleum/90" aria-hidden="true"></div>
  <div class="absolute inset-x-0 top-0 -z-10 h-45 bg-linear-to-b from-petroleum/60 to-transparent lg:h-60" aria-hidden="true"></div>

  <div class="contenedor relative flex h-full flex-col justify-end pb-14 md:pb-10 lg:pb-24">

    <div class="flex flex-col gap-4 lg:max-w-225 lg:gap-6">
      <p class="flex items-center gap-3 text-eyebrow text-amarillo">
        <?php get_template_part( 'components/trail-mark', null, array( 'tipo' => get_field( 'ruta_senal', $ruta ) ) ); ?>
        <span><?php echo esc_html( $proxima ? __( 'Próxima ruta', 'paso' ) : __( 'Ruta realizada', 'paso' ) ); ?></span>
      </p>
      <h1 class="text-display-route text-piedra"><?php echo esc_html( get_the_title( $ruta ) ); ?></h1>

      <!-- Fecha y hora en amarillo; el punto de encuentro debajo en móvil, en la misma línea desde tablet.
           Ruta realizada: sin hora y todo en amarillo, «Sábado 2 de mayo · Salida desde el parking de Haza del Río» -->
      <p class="flex flex-col gap-0.5 text-body md:flex-row md:flex-wrap md:gap-x-2">
        <?php if ( $fecha ) : ?><span class="text-amarillo"><?php echo esc_html( $proxima ? $fecha['dia'] . ' · ' . $fecha['hora'] : $fecha['dia'] ); ?></span><?php endif; ?>
        <?php if ( $fecha && $encuentro ) : ?><span class="hidden md:inline <?php echo $proxima ? 'text-piedra' : 'text-amarillo'; ?>" aria-hidden="true">·</span><?php endif; ?>
        <?php if ( $encuentro ) : ?><span class="<?php echo $proxima ? 'text-piedra' : 'text-amarillo'; ?>"><?php echo esc_html( $proxima ? $encuentro : sprintf( __( 'Salida desde %s', 'paso' ), $encuentro ) ); ?></span><?php endif; ?>
      </p>

      <?php if ( $proxima ) : ?>
        <!-- INSCRIPCIÓN · Mode=Info (de momento, en el grupo de WhatsApp del club; Mode=Form cuando el club lo apruebe) -->
        <p class="mt-2 border-l-3 border-amarillo py-1 pl-4 text-body text-piedra lg:mt-0"><?php esc_html_e( 'Inscripciones en el grupo de WhatsApp del club', 'paso' ); ?></p>
      <?php endif; ?>
    </div>

    <?php if ( $pie ) : ?>
      <p class="absolute right-6 top-25 text-caption text-piedra md:right-10 lg:top-auto lg:bottom-10 xl:right-20"><?php echo esc_html( $pie ); ?></p>
    <?php endif; ?>

  </div>
</section>
