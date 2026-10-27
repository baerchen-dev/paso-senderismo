<?php
/**
 * TRAIL MARK · Figma: Trail Mark (Type=PR / GR / SL)
 * Marca de pintura de sendero, 30 × 16: franja blanca encima y la de color debajo, con borde petróleo.
 * $args['tipo']: 'pr' (amarillo, por defecto) · 'gr' (rojo) · 'sl' (verde). Decorativa: el tipo no se anuncia.
 */
$colores = array(
  'pr' => 'bg-amarillo',
  'gr' => 'bg-trail-gr',
  'sl' => 'bg-trail-sl',
);
$color = $colores[ $args['tipo'] ?? 'pr' ] ?? $colores['pr'];
?>
<span class="flex h-4 w-7.5 shrink-0 flex-col border border-petroleum" aria-hidden="true">
  <span class="flex-1 bg-white"></span>
  <span class="flex-1 <?php echo esc_attr( $color ); ?>"></span>
</span>
