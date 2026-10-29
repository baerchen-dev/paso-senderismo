<?php
/**
 * BOTÓN SECUNDARIO · Figma: Button / Secondary (State=Default / Hover)
 * Pastilla sin borde en Petroleum Raised (hover: Line), icono amarillo y texto Piedra. Mismo alto que el principal (55).
 * Para acciones de apoyo sobre fondo oscuro liso; nunca en amarillo (el amarillo es la acción protagonista).
 * $args: 'texto', 'url', 'icono' ('calendario' · 'compartir'), 'clase' (ancho y posición donde se usa),
 *        'nueva' (opcional: pestaña nueva, con aviso al lector de pantalla), 'atributos' (opcional: array nombre => valor).
 */
$iconos = array(
  'calendario' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
  'compartir'  => '<circle cx="18" cy="5.5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="18.5" r="2.5"/><path d="M8.2 10.8l7.6-4.1M8.2 13.2l7.6 4.1"/>',
);
$nueva = ! empty( $args['nueva'] );
$extra = '';
foreach ( $args['atributos'] ?? array() as $nombre => $valor ) $extra .= ' ' . esc_attr( $nombre ) . '="' . esc_attr( $valor ) . '"';
?>
<a href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $nueva ? ' target="_blank" rel="noopener"' : ''; ?><?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput -- escapado arriba ?> class="group inline-flex items-center justify-center gap-2.5 rounded-full bg-petroleum-raised py-4.5 pr-7.5 pl-6.5 text-link text-piedra transition-colors duration-200 hover:bg-line focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amarillo <?php echo esc_attr( $args['clase'] ?? '' ); ?>">
  <?php if ( isset( $iconos[ $args['icono'] ?? '' ] ) ) : ?>
    <svg class="size-5 shrink-0 text-amarillo transition-transform duration-300 ease-out motion-reduce:transition-none group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" focusable="false"><?php echo $iconos[ $args['icono'] ]; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo ?></svg>
  <?php endif; ?>
  <span><?php echo esc_html( $args['texto'] ); ?><?php if ( $nueva ) : ?><span class="sr-only"> <?php esc_html_e( '(se abre en una pestaña nueva)', 'paso' ); ?></span><?php endif; ?></span>
</a>
