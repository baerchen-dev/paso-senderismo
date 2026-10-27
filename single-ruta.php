<?php
/**
 * FICHA DE RUTA · Figma: PASO / Próxima ruta (Desktop 1440 · Tablet 768 · Mobile 390)
 * Hero → datos, MIDE y «por qué esta ruta» → Recorrido / Qué llevar / Cómo llegar → galería → inscripción (solo la próxima).
 * Ruta realizada (Figma: PASO / Ruta realizada, Desktop 1440 · Mobile 390): la misma ficha sin MIDE, solo con Recorrido,
 * galería «Así fue la ruta.» y, al final, la tarjeta de la próxima ruta (la misma sección de la Home).
 */
get_header();

while ( have_posts() ) :
  the_post();
  $args = array( 'ruta' => get_post(), 'proxima' => paso_ruta_es_proxima() );

  get_template_part( 'sections/ruta-hero', null, $args );
  get_template_part( 'sections/ruta-informacion', null, $args );
  get_template_part( 'sections/ruta-galeria', null, $args );
  if ( $args['proxima'] ) get_template_part( 'sections/ruta-inscripcion', null, $args );
  else get_template_part( 'sections/home-proxima-ruta' );
endwhile;

get_footer();
