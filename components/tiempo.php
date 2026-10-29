<?php
/**
 * TIEMPO · Figma: Weather / Forecast (en el hero de la próxima ruta, bajo la fecha)
 * Pastilla cristal (como la etiqueta del hero) con icono y «Sáb 9:30 · 12 °C · Lluvia 10 % · Viento 15 km/h»; debajo, la fuente.
 * $args['tiempo']: array de paso_ruta_tiempo() (inc/tiempo.php). Solo se pinta si hay previsión.
 */
$tiempo = $args['tiempo'] ?? null;
if ( ! $tiempo ) return;

// Iconos de trazo (24 × 24, currentColor)
$nube  = 'M8 19h9.5a3.5 3.5 0 0 0 .4-7 5 5 0 0 0-9.6 1.2A2.9 2.9 0 0 0 8 19Z';
$nube2 = 'M7.5 16h10a3.5 3.5 0 0 0 .4-7 5 5 0 0 0-9.6 1.2A2.9 2.9 0 0 0 7.5 16Z';
$iconos = array(
  'sol'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2.5v2M12 19.5v2M2.5 12h2M19.5 12h2M5.3 5.3l1.4 1.4M17.3 17.3l1.4 1.4M5.3 18.7l1.4-1.4M17.3 6.7l1.4-1.4"/>',
  'sol-nubes' => '<path d="M9 3v1.5M3.6 5.6l1.1 1.1M2 11h1.5M14.4 5.6l-1.1 1.1M5.5 11a3.5 3.5 0 0 1 6.6-1.6"/><path d="M8 20h9.5a3.5 3.5 0 0 0 .4-7 5 5 0 0 0-9.6 1.2A2.9 2.9 0 0 0 8 20Z"/>',
  'nubes'     => '<path d="' . $nube . '"/>',
  'lluvia'    => '<path d="' . $nube2 . '"/><path d="M9 19l-1 2.5M13 19l-1 2.5M17 19l-1 2.5"/>',
  'nieve'     => '<path d="' . $nube2 . '"/><path d="M9 19.5h.01M13 20.5h.01M17 19.5h.01"/>',
  'tormenta'  => '<path d="' . $nube2 . '"/><path d="M13 16.5l-2 3h3l-2 3"/>',
  'niebla'    => '<path d="M4 9h16M3 13h18M5 17h14"/>',
);
?>
<div class="flex flex-col items-start gap-1.5">
  <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-petroleum/60 py-1.5 pr-3.5 pl-3 text-caption text-piedra backdrop-blur-sm">
    <svg class="size-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
      <?php echo $iconos[ $tiempo['icono'] ] ?? $iconos['nubes']; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo del tema ?>
    </svg>
    <span><span class="sr-only"><?php esc_html_e( 'Previsión:', 'paso' ); ?> </span><?php echo esc_html( $tiempo['texto'] ); ?></span>
  </p>
  <p class="text-caption text-muted">
    <?php echo esc_html( $tiempo['cima'] ? __( 'Previsión en la cima', 'paso' ) : __( 'Previsión en el punto de encuentro', 'paso' ) ); ?> ·
    <a href="https://open-meteo.com/" target="_blank" rel="noopener" class="underline-offset-2 hover:text-piedra hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current">Open-Meteo<span class="sr-only"> <?php esc_html_e( '(se abre en una pestaña nueva)', 'paso' ); ?></span></a>
  </p>
</div>
