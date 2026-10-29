<?php
/**
 * HOME · HERO · Figma: Section / Hero (Desktop 1440 × 710 · Tablet 768 × 760 · Mobile 390 × 556)
 * Alto = alto de pantalla menos lo justo para que asome la tarjeta de la próxima ruta con su etiqueta
 * («Próxima ruta» + marca) y se vea que hay más: −108 px en móvil (mínimo 500), −116 en tablet (máximo 760),
 * −192 en escritorio (entre 600 y 900). Indicador «Desliza» abajo a la derecha.
 * El texto se apoya abajo, a la distancia de Figma (44 · 114 · 176 px bajo el botón): cabe también en pantallas bajas.
 * Una sola foto fija (sin carrusel): vertical en móvil, horizontal desde tablet. Es lo primero que se ve → carga con prioridad.
 * El header (absoluto) queda encima; los velos oscurecen la foto bajo el header, detrás del texto y hacia abajo hasta petróleo.
 * Campos ACF: acf-json/group_paso_home_hero.json (Inicio → Home · Hero).
 */
$antetitulo   = get_field( 'hero_antetitulo' ) ?: __( 'Senderismo en Málaga, en grupo', 'paso' );
$titulo       = get_field( 'hero_titulo' ) ?: __( 'Disfruta las maravillas de la naturaleza juntos', 'paso' );
$imagen       = get_field( 'hero_imagen' );
$imagen_movil = get_field( 'hero_imagen_movil' ) ?: $imagen;
$coordenadas  = get_field( 'hero_coordenadas' );

// Botón: directo a la ficha de la próxima ruta, con su fecha («Próxima ruta · 14 nov»: sin el día de la semana, para que
// quepa junto a «Desliza» en móviles de 360); sin ruta programada, a Rutas
$proxima = paso_proxima_ruta();
$fecha   = $proxima ? get_field( 'ruta_fecha', $proxima ) : '';
$boton   = $proxima
  ? array(
      'texto' => $fecha
        ? sprintf( /* translators: %s: fecha corta de la ruta, p. ej. «14 nov» */ __( 'Próxima ruta · %s', 'paso' ), wp_date( 'j M', date_create_immutable( $fecha, wp_timezone() )->getTimestamp() ) )
        : __( 'Ver la próxima ruta', 'paso' ),
      'url'   => get_permalink( $proxima ),
    )
  : array( 'texto' => __( 'Ver las rutas', 'paso' ), 'url' => get_post_type_archive_link( 'ruta' ) );
?>
<section class="relative isolate h-[calc(100svh-6.75rem)] min-h-125 overflow-hidden bg-petroleum md:h-[min(calc(100svh-7.25rem),47.5rem)] md:min-h-150 lg:h-[calc(100svh-12rem)] lg:max-h-225">

  <!-- FOTO · <source> vertical por debajo de md -->
  <?php if ( $imagen ) : ?>
    <picture>
      <source media="(max-width: 767px)" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $imagen_movil, 'full' ) ); ?>" sizes="100vw">
      <?php echo wp_get_attachment_image( $imagen, 'full', false, array(
        'class'         => 'absolute inset-0 -z-10 size-full object-cover motion-safe:animate-zoom-hero',   // zoom muy lento (src/css/main.css)
        'sizes'         => '100vw',
        'loading'       => 'eager',
        'fetchpriority' => 'high',
      ) ); ?>
    </picture>
  <?php endif; ?>

  <!-- VELOS (decorativos)
   * General: móvil 50 → 20 % (35 %) → 95 % · tablet 18 → 14 % (48 %) → 100 % · escritorio 12 → 8 % (52 %) → 100 %
   * Texto: banda detrás del título (móvil 50 %, escritorio 40 %; en tablet no hace falta)
   * Cabecera: bajo el header, 180 px (240 en escritorio), 60 % → 0
  -->
  <div class="absolute inset-0 -z-10 bg-linear-to-b from-petroleum/50 via-petroleum/20 via-35% to-petroleum/95 md:from-petroleum/18 md:via-petroleum/14 md:via-48% md:to-petroleum lg:from-petroleum/12 lg:via-petroleum/8 lg:via-52%" aria-hidden="true"></div>
  <div class="absolute inset-x-0 top-24.5 h-108.25 -z-10 bg-linear-to-b from-transparent via-petroleum/50 via-45% to-transparent md:hidden lg:block lg:top-30 lg:h-105 lg:via-petroleum/40" aria-hidden="true"></div>
  <div class="absolute inset-x-0 top-0 h-45 -z-10 bg-linear-to-b from-petroleum/60 to-transparent lg:h-60" aria-hidden="true"></div>

  <div class="contenedor h-full">
    <div class="relative flex h-full flex-col items-start justify-end pt-26 pb-11 md:pb-28.5 lg:pb-44">

      <!-- ANTETÍTULO · etiqueta cristal (Figma: Petroleum · Cristal + desenfoque de fondo 16 + borde White · 15 %)
           Label/Eyebrow (14): el fondo de la etiqueta hace que el amarillo se lea sobre cualquier foto (cielo claro incluido).
           El desenfoque 16 de Figma equivale a blur(8px) en CSS → backdrop-blur-sm -->
      <p class="inline-flex rounded-full border border-white/15 bg-petroleum/60 px-2.5 py-1.5 text-eyebrow text-amarillo backdrop-blur-sm"><?php echo esc_html( $antetitulo ); ?></p>
      <!-- 800 px de ancho en escritorio: el título se parte en «maravillas / de la naturaleza juntos» -->
      <h1 class="mt-4 text-display-hero text-white lg:mt-5 lg:max-w-200"><?php echo esc_html( $titulo ); ?></h1>
      <!-- A la ficha de la próxima ruta (la tarjeta de debajo ya asoma: para bajar está «Desliza») -->
      <?php get_template_part( 'components/boton', null, $boton + array( 'clase' => 'mt-8' ) ); ?>

      <?php if ( $coordenadas ) : ?>
        <!-- COORDENADAS · solo escritorio, en vertical, alineadas al borde derecho de la última columna (= fin del contenido) -->
        <p class="absolute bottom-30 right-0 hidden text-caption text-white/82 [writing-mode:vertical-rl] lg:block"><?php echo esc_html( $coordenadas ); ?></p>
      <?php endif; ?>

      <!-- INDICADOR «DESLIZA» · abajo a la derecha, alineado al borde del contenido, bajo las coordenadas (Figma: Indicador / Desliza).
           Decorativo: la tarjeta que asoma ya dice que hay más. Sin animación con movimiento reducido -->
      <div class="pointer-events-none absolute bottom-6 right-0 flex flex-col items-center gap-2" aria-hidden="true">
        <span class="h-10 w-px origin-top bg-piedra/70 motion-safe:animate-desliza"></span>
        <span class="text-caption text-piedra"><?php esc_html_e( 'Desliza', 'paso' ); ?></span>
      </div>

    </div>
  </div>

</section>
