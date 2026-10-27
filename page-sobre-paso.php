<?php
/**
 * SOBRE PASO (/sobre-paso/) · Figma: PASO / Sobre PASO (Desktop 1440 · Mobile 390; tablet sin diseño: como escritorio)
 * Presentación (texto + foto: foto arriba en móvil, al lado desde tablet) → Cómo funciona (tres pasos) → El nombre → próxima ruta.
 * Foto: «sobre-paso-presentacion» de Medios (la foto de apoyo, de momento, no se usa).
 * El crédito a baerchen.dev va solo en el footer, como en la Home.
 * Márgenes: presentación 40 / 56 en móvil, 96 / 120 en escritorio; Cómo funciona 56 → 96; El nombre 56 / 64 → 96 / 120.
 */
get_header();

$foto  = get_page_by_path( 'sobre-paso-presentacion', OBJECT, 'attachment' );
$pasos = array(
  array( __( 'Anuncio la ruta', 'paso' ),        __( 'Aquí y en el grupo de WhatsApp del club, con todos los datos para decidir.', 'paso' ) ),
  array( __( 'Te apuntas en el grupo', 'paso' ), __( 'La inscripción y los coches se organizan allí.', 'paso' ) ),
  array( __( 'Nos vemos en el camino', 'paso' ), __( 'En el punto de encuentro, a la hora indicada. El ritmo lo marca el grupo.', 'paso' ) ),
);
?>

<!-- PRESENTACIÓN -->
<section class="pt-31 pb-14 md:pt-40 lg:pt-50 lg:pb-30">
  <div class="contenedor flex flex-col gap-8 md:flex-row md:items-center md:gap-10 lg:gap-24">

    <div class="flex flex-col gap-4 md:flex-1 lg:max-w-140 lg:gap-6">
      <p class="flex items-center gap-3 text-eyebrow text-amarillo">
        <?php get_template_part( 'components/trail-mark' ); ?>
        <span><?php esc_html_e( 'Sobre PASO', 'paso' ); ?></span>
      </p>
      <h1 class="text-display-hero-lg text-piedra"><?php esc_html_e( 'PASO nace de las ganas de compartir el monte.', 'paso' ); ?></h1>
      <p class="text-body text-piedra"><?php esc_html_e( 'Organizo rutas por las sierras de Málaga para mi grupo de senderismo, de forma desinteresada. Esta web reúne la información de cada salida para que sea fácil decidir si te apuntas y llegar preparado.', 'paso' ); ?></p>
      <p class="text-eyebrow text-amarillo"><?php esc_html_e( '— Fran', 'paso' ); ?></p>
    </div>

    <?php if ( $foto ) : ?>
      <?php echo wp_get_attachment_image( $foto->ID, 'full', false, array(
        'class'         => 'order-first aspect-[342/420] w-full shrink-0 rounded-2xl object-cover md:order-none md:aspect-[4/5] md:w-[45%] lg:w-120',
        'sizes'         => '(min-width: 1024px) 480px, (min-width: 768px) 45vw, 100vw',
        'loading'       => 'eager',
        'fetchpriority' => 'high',
      ) ); ?>
    <?php endif; ?>

  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="border-y border-line bg-petroleum-raised pt-14 pb-14 lg:pt-24 lg:pb-24" aria-labelledby="como-funciona">
  <div class="contenedor">
    <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Cómo funciona', 'paso' ); ?></p>
    <h2 id="como-funciona" class="mt-3 text-display-section text-piedra"><?php esc_html_e( 'Tres pasos.', 'paso' ); ?></h2>
    <ol class="mt-6 grid md:grid-cols-3 md:gap-8 lg:mt-12">
      <?php foreach ( $pasos as $i => list( $titulo, $texto ) ) : ?>
        <li class="flex flex-col gap-2.5 border-t border-line pt-6 pb-6 md:pb-0">
          <span class="text-data-value text-amarillo" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
          <h3 class="text-display-title text-piedra"><?php echo esc_html( $titulo ); ?></h3>
          <p class="text-body text-muted"><?php echo esc_html( $texto ); ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- EL NOMBRE · a la izquierda en móvil, centrado desde tablet -->
<section class="pt-14 pb-16 lg:pt-24 lg:pb-30" aria-labelledby="el-nombre">
  <div class="contenedor md:text-center">
    <p class="text-eyebrow text-muted"><?php esc_html_e( 'El nombre', 'paso' ); ?></p>
    <h2 id="el-nombre" class="mt-3 text-display-section text-balance text-piedra"><?php esc_html_e( 'Cada ruta empieza por un paso.', 'paso' ); ?></h2>
    <p class="mt-3 text-body text-muted md:mx-auto md:max-w-140 md:text-balance lg:max-w-180"><?php esc_html_e( 'Un paso hacia la igualdad, hacia superarse cada día y mirar el futuro con optimismo. Sentirte bien en un entorno seguro y disfrutar del camino: paso a paso, pero con decisión.', 'paso' ); ?></p>
  </div>
</section>

<?php get_template_part( 'sections/home-proxima-ruta' ); ?>

<?php get_footer(); ?>
