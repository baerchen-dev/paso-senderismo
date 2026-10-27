</main>

<!-- ============================================================ FOOTER ============================================================
 * Figma: Footer / Mobile (una columna) · Footer / Tablet (md: marca + columna con Apuntarse y Explora)
 *        · Footer / Desktop (lg: tres bloques repartidos; la columna derecha se deshace con lg:contents).
 * Las líneas van dentro del contenedor, no a todo el ancho.
-->
<footer class="site-footer contenedor pb-8">

  <div class="border-t border-line pt-14 pb-14 md:pt-16 md:pb-16 lg:pt-24 lg:pb-30 flex flex-col gap-12 md:flex-row md:gap-6 lg:justify-between">

    <!-- MARCA · logo 56 en móvil, 72 desde tablet -->
    <div class="flex flex-col gap-4 md:w-83 md:gap-6 lg:w-auto">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="self-start text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-current">
        <span class="sr-only"><?php bloginfo( 'name' ); ?></span>
        <?php get_template_part( 'components/logo', null, array( 'clase' => 'h-14 md:h-18' ) ); ?>
      </a>
      <p class="text-body text-muted"><?php echo wp_kses( __( 'Senderismo en Málaga,<br>en grupo.', 'paso' ), array( 'br' => array() ) ); ?></p>
    </div>

    <div class="flex flex-col gap-12 md:flex-1 md:gap-10 lg:contents">

      <!-- CÓMO APUNTARSE -->
      <div class="flex flex-col gap-3 lg:w-105">
        <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Cómo apuntarse', 'paso' ); ?></p>
        <p class="text-display-callout text-piedra"><?php esc_html_e( 'Las rutas se organizan en el grupo de WhatsApp del club.', 'paso' ); ?></p>
      </div>

      <!-- EXPLORA · mismos enlaces que el menú principal -->
      <nav class="flex flex-col gap-3" aria-label="<?php esc_attr_e( 'Explora', 'paso' ); ?>">
        <p class="text-eyebrow text-muted"><?php esc_html_e( 'Explora', 'paso' ); ?></p>
        <ul class="flex flex-col gap-3">
          <?php foreach ( paso_menu_principal() as $enlace ) : ?>
            <li>
              <a href="<?php echo esc_url( $enlace['url'] ); ?>" class="text-body text-piedra hover:text-amarillo aria-[current=page]:text-amarillo transition-colors duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current" <?php echo $enlace['actual'] ? 'aria-current="page"' : ''; ?>>
                <?php echo esc_html( $enlace['titulo'] ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>

    </div>
  </div>

  <!-- LEGAL Y CRÉDITO · en columna en móvil, en una línea desde tablet -->
  <div class="border-t border-line pt-6 md:pt-7 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

    <nav aria-label="<?php esc_attr_e( 'Legal', 'paso' ); ?>">
      <ul class="flex flex-wrap items-center text-caption uppercase text-muted">
        <?php foreach ( paso_menu_legal() as $i => $enlace ) : ?>
          <li>
            <?php if ( $i ) : ?><span class="px-1.5" aria-hidden="true">·</span><?php endif; ?><a href="<?php echo esc_url( $enlace['url'] ); ?>" class="hover:text-piedra aria-[current=page]:text-piedra transition-colors duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current" <?php echo $enlace['actual'] ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $enlace['titulo'] ); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <p class="text-caption text-muted">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> PASO</p>

    <a href="https://baerchen.dev" class="group self-start md:self-auto text-link text-amarillo hover:text-amarillo-hover transition-colors duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current">
      <?php esc_html_e( 'Diseño y desarrollo: baerchen.dev', 'paso' ); ?>
      <span class="inline-block transition-transform duration-300 ease-out motion-reduce:transition-none group-hover:translate-x-1" aria-hidden="true">→</span>
    </a>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
