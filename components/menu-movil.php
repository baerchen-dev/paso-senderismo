<!-- MENÚ MÓVIL · Figma: Mobile Menu / Open
 * Pantalla completa en petróleo, solo por debajo de md (tablet y escritorio llevan la navegación en el header).
 * La fila del logo y el botón (que se convierte en ✕) es la del header, que queda por encima (z-10 dentro del header).
 * Se abre con .menu-toggle (JS en assets/js/menu.js). Language Switch: pendiente de Polylang.
-->
<div id="menu-movil" class="fixed inset-0 flex flex-col overflow-y-auto bg-petroleum pt-21 invisible opacity-0 data-open:visible data-open:opacity-100 transition-[opacity,visibility] duration-300 motion-reduce:transition-none md:hidden">

  <nav class="px-6 pt-10" aria-label="<?php esc_attr_e( 'Navegación principal', 'paso' ); ?>">
    <p class="text-eyebrow text-muted"><?php esc_html_e( 'Explora', 'paso' ); ?></p>
    <ul class="mt-6">
      <?php foreach ( paso_menu_principal() as $i => $enlace ) : ?>
        <li class="border-b border-line">
          <a href="<?php echo esc_url( $enlace['url'] ); ?>" class="group flex items-center gap-4 py-3.5 text-piedra aria-[current=page]:text-amarillo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current" <?php echo $enlace['actual'] ? 'aria-current="page"' : ''; ?>>
            <span class="text-eyebrow text-muted group-aria-[current=page]:text-amarillo" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
            <span class="text-display-section"><?php echo esc_html( $enlace['titulo'] ); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- PIE DEL MENÚ · Cómo apuntarse + crédito -->
  <div class="mt-auto border-t border-line px-6 pt-6 pb-8 flex flex-col gap-3">
    <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Cómo apuntarse', 'paso' ); ?></p>
    <p class="text-display-callout text-piedra"><?php esc_html_e( 'Las rutas se organizan en el grupo de WhatsApp del club.', 'paso' ); ?></p>
    <a href="https://baerchen.dev" class="self-start pt-3 text-caption text-muted hover:text-piedra transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"><?php esc_html_e( 'Diseño: baerchen.dev', 'paso' ); ?></a>
  </div>

</div>
