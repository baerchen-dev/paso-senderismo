<?php
/**
 * LISTA DE QUÉ LLEVAR · Figma: tarjetas «Siempre» y «Para esta ruta» del panel Qué llevar
 * Título, entradilla y una fila por cosa (nombre + explicación): uno debajo de otro en móvil, en dos columnas desde lg.
 * $args: 'titulo', 'intro', 'lista' (array de array( 'nombre', 'texto' )).
 */
?>
<div class="rounded-2xl bg-petroleum p-5 lg:p-7">
  <h3 class="text-display-title text-piedra"><?php echo esc_html( $args['titulo'] ); ?></h3>
  <?php if ( $args['intro'] ) : ?><p class="mt-1 text-caption text-muted"><?php echo esc_html( $args['intro'] ); ?></p><?php endif; ?>
  <dl class="mt-4">
    <?php foreach ( $args['lista'] as $cosa ) : ?>
      <div class="flex flex-col gap-1 border-t border-line py-3 lg:flex-row lg:gap-4 lg:py-3.5">
        <dt class="text-link text-piedra lg:w-32.5 lg:shrink-0 lg:pt-1"><?php echo esc_html( $cosa['nombre'] ); ?></dt>
        <dd class="text-body text-muted"><?php echo esc_html( $cosa['texto'] ); ?></dd>
      </div>
    <?php endforeach; ?>
  </dl>
</div>
