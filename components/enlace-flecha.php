<?php
/**
 * ENLACE CON FLECHA · Figma: Link / Type=Arrow
 * Acción secundaria en amarillo; la flecha se adelanta al pasar el ratón.
 * $args: 'texto', 'url', 'clase' (opcional), 'nueva' (opcional: se abre en una pestaña nueva, para webs de fuera; se avisa al lector de pantalla),
 *        'atributos' (opcional: array nombre => valor, p. ej. array( 'data-compartir' => '' )).
 */
$nueva = ! empty( $args['nueva'] );
$extra = '';
foreach ( $args['atributos'] ?? array() as $nombre => $valor ) $extra .= ' ' . esc_attr( $nombre ) . '="' . esc_attr( $valor ) . '"';
?>
<a href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $nueva ? ' target="_blank" rel="noopener"' : ''; ?><?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput -- escapado arriba ?> class="group inline-flex items-center gap-2 py-1 text-link text-amarillo transition-colors duration-200 hover:text-amarillo-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current <?php echo esc_attr( $args['clase'] ?? '' ); ?>">
  <!-- Texto y aviso en un solo elemento: así el aviso oculto no suma otro hueco antes de la flecha -->
  <span><?php echo esc_html( $args['texto'] ); ?><?php if ( $nueva ) : ?><span class="sr-only"> <?php esc_html_e( '(se abre en una pestaña nueva)', 'paso' ); ?></span><?php endif; ?></span>
  <span class="inline-block transition-transform duration-300 ease-out motion-reduce:transition-none group-hover:translate-x-1" aria-hidden="true">→</span>
</a>
