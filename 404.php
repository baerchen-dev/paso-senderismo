<?php
/**
 * 404 · Figma: PASO / 404 (Desktop 1440 · Mobile 390; tablet sin diseño: como escritorio)
 * Ilustración «Lost Trail» (assets/img/lost-trail.svg, exportada del componente Illustration / Lost Trail: 640×320, ancho completo en móvil)
 * → 404 → título → texto → botón «Ver la próxima ruta» (/proxima-ruta/: la ficha de la próxima, o /rutas/ si no hay) y enlace al inicio.
 * Móvil: alineado a la izquierda y el botón a todo el ancho; desde tablet, centrado y las acciones en fila.
 * Márgenes: 40 bajo el header y 96 abajo en móvil; 72 y 180 desde tablet.
 */
get_header();
?>

<section class="contenedor flex flex-col items-start gap-5 pt-31 pb-24 md:items-center md:gap-7 md:pt-44 md:pb-45 md:text-center">
  <img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/lost-trail.svg' ) ); ?>" alt="" width="640" height="320" class="h-auto w-full md:w-160">
  <p class="text-display-404 text-amarillo" aria-hidden="true">404</p>
  <h1 class="text-display-section text-piedra md:max-w-200"><?php esc_html_e( 'Te has salido del sendero', 'paso' ); ?></h1>
  <p class="text-body text-muted md:max-w-140"><?php esc_html_e( 'La página que buscas no existe o ha cambiado de sitio. Volvamos al camino.', 'paso' ); ?></p>
  <div class="flex w-full flex-col items-start gap-4 pt-3 md:w-auto md:flex-row md:items-center md:gap-8">
    <?php get_template_part( 'components/boton', null, array( 'texto' => __( 'Ver la próxima ruta', 'paso' ), 'url' => home_url( '/proxima-ruta/' ), 'clase' => 'w-full md:w-auto' ) ); ?>
    <?php get_template_part( 'components/enlace-flecha', null, array( 'texto' => __( 'Ir al inicio', 'paso' ), 'url' => home_url( '/' ) ) ); ?>
  </div>
</section>

<?php get_footer(); ?>
