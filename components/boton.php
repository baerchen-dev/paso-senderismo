<?php
/**
 * BOTÓN · Figma: Button / Primary (State=Default / Hover)
 * Pastilla amarilla: la única acción protagonista de cada pantalla. La flecha se adelanta al pasar el ratón.
 * $args: 'texto', 'url', 'clase' (ancho y posición donde se usa, p. ej. 'w-full lg:w-auto').
 */
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="group inline-flex items-center justify-center gap-2 rounded-full bg-amarillo px-8 py-4.5 text-link text-petroleum transition-colors duration-200 hover:bg-amarillo-hover focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amarillo <?php echo esc_attr( $args['clase'] ?? '' ); ?>">
  <?php echo esc_html( $args['texto'] ); ?>
  <span class="inline-block transition-transform duration-300 ease-out motion-reduce:transition-none group-hover:translate-x-1" aria-hidden="true">→</span>
</a>
