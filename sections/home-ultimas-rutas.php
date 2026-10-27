<?php
/**
 * HOME · ÚLTIMAS RUTAS · Figma: Section / Últimas rutas (Desktop 1440 · Tablet 768 · Mobile 390)
 * Las 3 rutas realizadas más recientes (paso_rutas_realizadas, inc/rutas.php). Sin ninguna, la sección no sale.
 * Móvil y tablet: carril deslizable (scroll-snap) que llega a los bordes de la pantalla; tarjetas de 300, asoma la siguiente.
 * Escritorio (lg): las 3 en fila; «Ver todas las rutas» pasa a la cabecera.
 * Márgenes: 64 / 72 hasta lg; en escritorio 24 arriba (con los 96 de Próxima ruta suman 120) y 120 abajo.
 */
$rutas = paso_rutas_realizadas( 3 );
if ( ! $rutas ) return;

$todas = array(
  'texto' => __( 'Ver todas las rutas', 'paso' ),
  'url'   => get_post_type_archive_link( 'ruta' ),
);
?>
<section class="bg-petroleum pt-16 pb-18 lg:pt-6 lg:pb-30" aria-labelledby="ultimas-rutas-titulo">
  <div class="contenedor">

    <div class="lg:flex lg:items-end lg:justify-between lg:gap-12">
      <div class="flex flex-col gap-3 lg:max-w-160 lg:gap-4">
        <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Rutas anteriores', 'paso' ); ?></p>
        <h2 id="ultimas-rutas-titulo" class="text-display-section text-piedra"><?php esc_html_e( 'Lo que ya hemos caminado.', 'paso' ); ?></h2>
        <p class="text-body text-muted"><?php esc_html_e( 'Cada ruta, al terminar, pasa a este archivo con sus datos y las fotos del grupo.', 'paso' ); ?></p>
      </div>
      <div class="hidden shrink-0 lg:block"><?php get_template_part( 'components/enlace-flecha', null, $todas ); ?></div>
    </div>

    <!-- CARRIL · -mx/px = margen del contenedor: las tarjetas salen por los bordes de la pantalla y la primera y la última quedan alineadas con el margen -->
    <ul class="-mx-6 mt-8 flex snap-x snap-mandatory scroll-px-6 gap-4 overflow-x-auto px-6 scrollbar-none md:-mx-10 md:scroll-px-10 md:gap-6 md:px-10 lg:mx-0 lg:mt-12 lg:grid lg:grid-cols-3 lg:gap-8 lg:overflow-visible lg:px-0 [&::-webkit-scrollbar]:hidden">
      <?php foreach ( $rutas as $ruta ) : ?>
        <li class="w-75 shrink-0 snap-start lg:w-auto">
          <?php get_template_part( 'components/route-card', null, array( 'ruta' => $ruta ) ); ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="mt-8 lg:hidden"><?php get_template_part( 'components/enlace-flecha', null, $todas ); ?></div>

  </div>
</section>
