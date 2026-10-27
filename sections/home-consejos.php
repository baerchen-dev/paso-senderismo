<?php
/**
 * HOME · CONSEJOS · Figma: Section / Consejos (Desktop 1440 · Tablet 768 · Mobile 390)
 * Tres consejos en tarjetas: en fila desde lg, en columna hasta entonces. La marca va fija por posición (PR, GR, SL).
 * Campos ACF: acf-json/group_paso_home_consejos.json (Inicio → Home · Consejos); vacíos → textos de Figma.
 * Márgenes: 64 hasta lg, 96 en escritorio. «Ver todos los consejos» va a la cabecera en escritorio y al final hasta entonces.
 */
$consejos = array(
  array(
    'marca'  => 'pr',
    'titulo' => get_field( 'consejo_1_titulo' ) ?: __( 'Qué llevar', 'paso' ),
    'texto'  => get_field( 'consejo_1_texto' ) ?: __( 'Agua (1,5–2 l), calzado de montaña, gorra, capa de abrigo y algo de comer. En verano, salida temprana.', 'paso' ),
  ),
  array(
    'marca'  => 'gr',
    'titulo' => get_field( 'consejo_2_titulo' ) ?: __( 'Niveles de dificultad', 'paso' ),
    'texto'  => get_field( 'consejo_2_texto' ) ?: __( 'Fácil: menos de 10 km y 400 m de desnivel. Media: hasta 15 km y 900 m. Difícil: más, o con tramos técnicos.', 'paso' ),
  ),
  array(
    'marca'  => 'sl',
    'titulo' => get_field( 'consejo_3_titulo' ) ?: __( 'Normas del grupo', 'paso' ),
    'texto'  => get_field( 'consejo_3_texto' ) ?: __( 'Nadie se queda atrás: el ritmo lo marca la persona más lenta. Respeta los senderos y llévate tu basura.', 'paso' ),
  ),
);
$todos = array(
  'texto' => __( 'Ver todos los consejos', 'paso' ),
  'url'   => home_url( '/consejos/' ),
);
?>
<section class="border-y border-line bg-petroleum-raised py-16 lg:py-24" aria-labelledby="consejos-titulo">
  <div class="contenedor">

    <div class="lg:flex lg:items-end lg:justify-between lg:gap-12">
      <div class="flex flex-col gap-3 lg:gap-4">
        <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Consejos', 'paso' ); ?></p>
        <h2 id="consejos-titulo" class="text-display-section text-piedra"><?php esc_html_e( 'Antes de salir.', 'paso' ); ?></h2>
      </div>
      <div class="hidden shrink-0 lg:block"><?php get_template_part( 'components/enlace-flecha', null, $todos ); ?></div>
    </div>

    <ul class="mt-8 flex flex-col gap-8 lg:mt-12 lg:grid lg:grid-cols-3">
      <?php foreach ( $consejos as $consejo ) : ?>
        <li><?php get_template_part( 'components/consejo', null, $consejo ); ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="mt-8 lg:hidden"><?php get_template_part( 'components/enlace-flecha', null, $todos ); ?></div>

  </div>
</section>
