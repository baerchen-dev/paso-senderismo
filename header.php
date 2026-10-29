<!DOCTYPE html>
<html <?php language_attributes(); ?> class="motion-safe:scroll-smooth">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'relative min-h-screen flex flex-col bg-petroleum text-piedra font-sans antialiased' ); ?>>
<?php wp_body_open(); ?>

<a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-60 focus:bg-amarillo focus:text-petroleum focus:px-4 focus:py-2 focus:text-link">
  <?php esc_html_e( 'Saltar al contenido', 'paso' ); ?>
</a>

<!-- ============================================================ HEADER ============================================================
 * Figma: Header / Desktop (también en tablet) y Header / Mobile.
 * Fijo. Arriba del todo, transparente encima del hero de cada página (los heros llevan degradado oscuro arriba).
 * Al bajar se esconde y al subir vuelve con fondo petróleo (assets/js/header.js: data-oculto / data-fondo).
 * Sin transform cuando se ve: el menú móvil (fixed) va dentro y se colocaría respecto al header.
 * Página actual y hover en amarillo.
-->
<header class="site-header fixed inset-x-0 top-0 z-50 border-b border-transparent text-white data-fondo:bg-petroleum data-fondo:border-line data-oculto:-translate-y-full transition-[translate,background-color,border-color] duration-300 motion-reduce:transition-none">

  <div class="contenedor relative z-10 h-21 md:h-26 flex items-center justify-between gap-6">

    <!-- LOGO · 36 px en móvil, 44 desde tablet -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-current">
      <span class="sr-only"><?php bloginfo( 'name' ); ?></span>
      <?php get_template_part( 'components/logo', null, array( 'clase' => 'h-9 md:h-11' ) ); ?>
    </a>

    <!-- NAVEGACIÓN · tablet y escritorio -->
    <nav class="hidden md:block" aria-label="<?php esc_attr_e( 'Navegación principal', 'paso' ); ?>">
      <ul class="flex items-center gap-12">
        <?php foreach ( paso_menu_principal() as $enlace ) : ?>
          <li>
            <a href="<?php echo esc_url( $enlace['url'] ); ?>" class="text-link hover:text-amarillo aria-[current=page]:text-amarillo transition-colors duration-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-current" <?php echo $enlace['actual'] ? 'aria-current="page"' : ''; ?>>
              <?php echo esc_html( $enlace['titulo'] ); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <!-- BOTÓN DE MENÚ · solo móvil. Dos líneas (30 y 19, la corta a la derecha) que se cruzan en ✕ de 24 al abrir -->
    <button type="button" class="menu-toggle group relative -mr-1.75 size-11 cursor-pointer md:hidden aria-expanded:text-piedra focus-visible:outline-2 focus-visible:outline-current" aria-controls="menu-movil" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menú', 'paso' ); ?>" data-label-abrir="<?php esc_attr_e( 'Abrir menú', 'paso' ); ?>" data-label-cerrar="<?php esc_attr_e( 'Cerrar menú', 'paso' ); ?>">
      <span class="absolute right-1.75 top-4.75 h-px w-7.5 bg-current transition-[top,right,width,height,rotate] duration-300 motion-reduce:transition-none group-aria-expanded:right-2.5 group-aria-expanded:top-5.25 group-aria-expanded:h-0.5 group-aria-expanded:w-6 group-aria-expanded:rotate-45" aria-hidden="true"></span>
      <span class="absolute right-1.75 top-6.25 h-px w-4.75 bg-current transition-[top,right,width,height,rotate] duration-300 motion-reduce:transition-none group-aria-expanded:right-2.5 group-aria-expanded:top-5.25 group-aria-expanded:h-0.5 group-aria-expanded:w-6 group-aria-expanded:-rotate-45" aria-hidden="true"></span>
    </button>

  </div>

  <?php get_template_part( 'components/menu-movil' ); ?>

</header>

<main id="contenido" class="flex-1">
