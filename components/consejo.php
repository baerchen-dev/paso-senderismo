<?php
/**
 * CONSEJO · Figma: tarjeta de Section / Consejos
 * Marca de sendero, título y texto sobre fondo Petroleum (la sección va en Petroleum Raised).
 * $args: 'marca' ('pr' / 'gr' / 'sl'), 'titulo', 'texto'.
 */
?>
<div class="flex h-full flex-col gap-3 rounded-2xl bg-petroleum p-6 md:px-10 lg:gap-4 lg:p-7">
  <?php get_template_part( 'components/trail-mark', null, array( 'tipo' => $args['marca'] ) ); ?>
  <h3 class="text-display-title text-piedra"><?php echo esc_html( $args['titulo'] ); ?></h3>
  <p class="text-body text-muted"><?php echo esc_html( $args['texto'] ); ?></p>
</div>
