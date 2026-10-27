<?php
/**
 * Template Name: Legal
 * PÁGINAS LEGALES (/aviso-legal/, /privacidad/, /cookies/) · Figma: PASO / Legal (plantilla) / Desktop 1440 (móvil y tablet sin diseño)
 * Cabecera (antetítulo, título, fecha de la última actualización = última vez que se guardó la página)
 * → índice de las tres páginas (menú Legal del footer) y el texto, escrito en el editor de WordPress (estilos: .texto-legal en main.css).
 * Índice: a la izquierda desde lg (la página actual con raya amarilla); hasta lg, pestañas en fila encima del texto.
 */
get_header();

while ( have_posts() ) :
  the_post();
  ?>

  <section class="contenedor pt-31 pb-8 md:pt-46 lg:pb-14">
    <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Información legal', 'paso' ); ?></p>
    <h1 class="mt-3 text-display-hero text-piedra lg:mt-4"><?php the_title(); ?></h1>
    <p class="mt-3 text-caption text-muted lg:mt-4"><?php echo esc_html( sprintf( __( 'Última actualización: %s', 'paso' ), get_the_modified_date( 'j \d\e F \d\e Y' ) ) ); ?></p>
  </section>

  <div class="contenedor flex flex-col gap-8 pb-16 lg:flex-row lg:items-start lg:gap-24 lg:pb-30">

    <nav class="lg:w-65 lg:shrink-0" aria-label="<?php esc_attr_e( 'Información legal', 'paso' ); ?>">
      <ul class="flex gap-6 border-b border-line lg:flex-col lg:gap-0 lg:border-b-0 lg:border-l">
        <?php foreach ( paso_menu_legal() as $enlace ) : ?>
          <li>
            <a href="<?php echo esc_url( $enlace['url'] ); ?>" <?php echo $enlace['actual'] ? 'aria-current="page"' : ''; ?>
              class="-mb-px block border-b-3 border-transparent py-3.5 text-link text-muted transition-colors duration-200 hover:text-piedra aria-[current=page]:border-amarillo aria-[current=page]:text-piedra focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amarillo lg:mb-0 lg:-ml-px lg:border-b-0 lg:border-l-3 lg:px-5">
              <?php echo esc_html( $enlace['titulo'] ); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="texto-legal lg:max-w-180 lg:flex-1"><?php the_content(); ?></div>

  </div>

<?php
endwhile;

get_footer();
