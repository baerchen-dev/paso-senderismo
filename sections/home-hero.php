<?php
/**
 * HOME · HERO · Figma: Section / Hero (Desktop 1440 × 900 · Tablet 768 × 760 · Mobile 390 × 760)
 * Una sola foto fija (sin carrusel): vertical en móvil, horizontal desde tablet. Es lo primero que se ve → carga con prioridad.
 * El header (absoluto) queda encima; los velos oscurecen la foto bajo el header, detrás del texto y hacia abajo hasta petróleo.
 * Campos ACF: acf-json/group_paso_home_hero.json (Inicio → Home · Hero).
 */
$antetitulo   = get_field( 'hero_antetitulo' ) ?: __( 'Senderismo en Málaga, en grupo', 'paso' );
$titulo       = get_field( 'hero_titulo' ) ?: __( 'Disfruta las maravillas de la naturaleza juntos', 'paso' );
$imagen       = get_field( 'hero_imagen' );
$imagen_movil = get_field( 'hero_imagen_movil' ) ?: $imagen;
$coordenadas  = get_field( 'hero_coordenadas' );
?>
<section class="relative isolate h-190 overflow-hidden bg-petroleum lg:h-svh lg:min-h-160 lg:max-h-225">

  <!-- FOTO · <source> vertical por debajo de md -->
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

  <!-- VELOS (decorativos)
   * General: móvil 50 → 20 % (35 %) → 95 % · tablet 18 → 14 % (48 %) → 100 % · escritorio 12 → 8 % (52 %) → 100 %
   * Texto: banda detrás del título (móvil 50 %, escritorio 40 %; en tablet no hace falta)
   * Cabecera: bajo el header, 180 px (240 en escritorio), 60 % → 0
  -->
  <div class="absolute inset-0 -z-10 bg-linear-to-b from-petroleum/50 via-petroleum/20 via-35% to-petroleum/95 md:from-petroleum/18 md:via-petroleum/14 md:via-48% md:to-petroleum lg:from-petroleum/12 lg:via-petroleum/8 lg:via-52%" aria-hidden="true"></div>
  <div class="absolute inset-x-0 top-24.5 h-108.25 -z-10 bg-linear-to-b from-transparent via-petroleum/50 via-45% to-transparent md:hidden lg:block lg:top-30 lg:h-105 lg:via-petroleum/40" aria-hidden="true"></div>
  <div class="absolute inset-x-0 top-0 h-45 -z-10 bg-linear-to-b from-petroleum/60 to-transparent lg:h-60" aria-hidden="true"></div>

  <div class="contenedor h-full">
    <div class="relative h-full pt-54.5 md:pt-59.5 lg:pt-53.5">

      <!-- ANTETÍTULO · etiqueta cristal (Figma: Petroleum · Cristal + desenfoque de fondo 16 + borde White · 15 %)
           Label/Eyebrow (14): el fondo de la etiqueta hace que el amarillo se lea sobre cualquier foto (cielo claro incluido).
           El desenfoque 16 de Figma equivale a blur(8px) en CSS → backdrop-blur-sm -->
      <p class="inline-flex rounded-full border border-white/15 bg-petroleum/60 px-2.5 py-1.5 text-eyebrow text-amarillo backdrop-blur-sm"><?php echo esc_html( $antetitulo ); ?></p>
      <!-- 800 px de ancho en escritorio: el título se parte en «maravillas / de la naturaleza juntos» -->
      <h1 class="mt-4 text-display-hero text-white lg:mt-5 lg:max-w-200"><?php echo esc_html( $titulo ); ?></h1>
      <!-- Baja a la tarjeta de la próxima ruta (#proxima-ruta, sections/home-proxima-ruta.php) -->
      <?php get_template_part( 'components/boton', null, array(
        'texto' => __( 'Ver la próxima ruta', 'paso' ),
        'url'   => '#proxima-ruta',
        'clase' => 'mt-8',
      ) ); ?>

      <?php if ( $coordenadas ) : ?>
        <!-- COORDENADAS · solo escritorio, en vertical, alineadas al borde derecho de la última columna (= fin del contenido) -->
        <p class="absolute bottom-30 right-0 hidden text-caption text-white/82 [writing-mode:vertical-rl] lg:block"><?php echo esc_html( $coordenadas ); ?></p>
      <?php endif; ?>

    </div>
  </div>

</section>
