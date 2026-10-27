<?php
/**
 * HOME · SOBRE PASO · Figma: Section / Sobre PASO (Desktop 1440 · Tablet 768 · Mobile 390)
 * Foto vertical y texto: uno debajo de otro en móvil, al lado desde tablet (foto 300 × 400; 458 × 520 en escritorio).
 * Enlace: «Más sobre PASO» (/sobre-paso/; la foto también lleva ahí). El crédito a baerchen.dev va solo en el footer.
 * Título a 40 hasta lg (en tablet, la columna de texto es estrecha para 64).
 * Campos ACF: acf-json/group_paso_home_sobre.json (Inicio → Home · Sobre PASO); vacíos → textos de Figma, sin foto → solo texto.
 * Márgenes: 64 / 72 hasta lg, 120 en escritorio.
 */
$foto   = get_field( 'sobre_foto' );
$titulo = get_field( 'sobre_titulo' ) ?: __( 'Organizo rutas para el club.', 'paso' );
$texto  = get_field( 'sobre_texto' ) ?: __( 'Soy Fran. Camino por las sierras de Málaga desde hace años y preparo rutas para mi grupo de senderismo: las elijo, las reconozco y las cuento aquí con todo lo que necesitas saber.', 'paso' );
$pagina = home_url( '/sobre-paso/' );
?>
<section class="pt-16 pb-18 lg:py-30" aria-labelledby="sobre-paso-titulo">
  <div class="contenedor flex flex-col gap-8 md:flex-row md:items-center md:gap-10 lg:gap-24">

    <?php if ( $foto ) : ?>
      <!-- La foto lleva a la misma página que «Más sobre PASO»: fuera del teclado y del lector de pantalla para no repetir el enlace -->
      <a href="<?php echo esc_url( $pagina ); ?>" class="shrink-0" tabindex="-1" aria-hidden="true">
        <?php echo wp_get_attachment_image( $foto, 'full', false, array(
          'class' => 'h-100 w-full rounded-2xl object-cover md:w-75 lg:h-130 lg:w-114.5',
          'sizes' => '(min-width: 1024px) 458px, (min-width: 768px) 300px, 100vw',
        ) ); ?>
      </a>
    <?php endif; ?>

    <div class="flex flex-col gap-4 md:flex-1 lg:max-w-140 lg:gap-6">
      <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Sobre PASO', 'paso' ); ?></p>
      <h2 id="sobre-paso-titulo" class="text-display-section-lg text-piedra"><?php echo esc_html( $titulo ); ?></h2>
      <p class="text-body text-piedra"><?php echo esc_html( $texto ); ?></p>
      <div><?php get_template_part( 'components/enlace-flecha', null, array( 'texto' => __( 'Más sobre PASO', 'paso' ), 'url' => $pagina ) ); ?></div>
    </div>

  </div>
</section>
