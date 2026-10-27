<?php
/**
 * MAPA DEL PUNTO DE ENCUENTRO · Figma: «Mapa del punto de encuentro» del panel Cómo llegar
 * Imagen fija centrada en las coordenadas (inc/mapas.php) con la chincheta «● Punto de encuentro» en el centro.
 * $args: 'mapa' (paso_mapa_encuentro), 'lugar' (texto para el lector de pantalla).
 */
$mapa = $args['mapa'];
?>
<figure class="relative aspect-[16/10] overflow-hidden rounded-2xl bg-petroleum-raised" role="img"
  aria-label="<?php echo esc_attr( sprintf( __( 'Mapa del punto de encuentro: %s.', 'paso' ), $args['lugar'] ) ); ?>">
  <div aria-hidden="true">
    <img src="<?php echo esc_url( $mapa['url'] ); ?>" alt="" width="<?php echo esc_attr( PASO_MAPA_ANCHO ); ?>" height="<?php echo esc_attr( PASO_MAPA_ALTO ); ?>" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">
    <!-- Chincheta: el punto exacto es el centro del mapa -->
    <span class="absolute left-1/2 top-1/2 size-3 -translate-1/2 rounded-full border-2 border-petroleum bg-amarillo box-content"></span>
    <p class="absolute left-1/2 top-1/2 -mt-3 -translate-x-1/2 -translate-y-full whitespace-nowrap rounded-full bg-amarillo px-3.5 py-2.5 text-eyebrow text-petroleum"><?php esc_html_e( 'Punto de encuentro', 'paso' ); ?></p>
    <p class="absolute bottom-1 right-2 text-[0.625rem] leading-none text-muted">© colaboradores de OpenStreetMap</p>
  </div>
</figure>
