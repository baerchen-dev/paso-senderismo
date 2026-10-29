<?php
/**
 * ROUTE STATS · Figma: Route Stats / Mobile (2 columnas, líneas horizontales) · Route Stats / Desktop (lg: una fila, líneas verticales)
 * Cada dato = Route Stat (cifra + unidad + etiqueta); «Tipo de recorrido» = Route Type (sin unidad).
 * $args['datos']: array de paso_ruta_datos() → array( 'valor', 'unidad', 'etiqueta' ).
 * Lista de definiciones: el lector de pantalla lee «Distancia: 16,6 km».
 * Las cifras (data-contar) cuentan desde 0 al entrar en pantalla (assets/js/cifras.js); los textos («Difícil») no.
 */
?>
<dl class="grid grid-cols-2 lg:flex">
  <?php foreach ( $args['datos'] as $dato ) : ?>
    <div class="flex flex-col-reverse justify-end gap-2 border-t border-line py-4 lg:flex-1 lg:border-t-0 lg:border-l lg:px-6 lg:py-0 lg:first:border-l-0 lg:first:pl-0 lg:last:flex-none">
      <dt class="text-caption text-muted"><?php echo esc_html( $dato['etiqueta'] ); ?></dt>
      <dd class="flex flex-wrap items-baseline gap-x-1.5 text-piedra">
        <span class="text-data-value" data-contar><?php echo esc_html( $dato['valor'] ); ?></span>
        <?php if ( $dato['unidad'] ) : ?><span class="text-data-unit"><?php echo esc_html( $dato['unidad'] ); ?></span><?php endif; ?>
      </dd>
    </div>
  <?php endforeach; ?>
</dl>
