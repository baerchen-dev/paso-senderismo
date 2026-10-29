<?php

/* MÓDULOS */
require_once get_template_directory() . '/inc/menu.php';   // enlaces del menú principal (header, menú móvil, footer)
require_once get_template_directory() . '/inc/rutas.php';  // tipo de contenido Ruta, próxima ruta, formato de cifras y fechas
require_once get_template_directory() . '/inc/gpx.php';    // GPX de cada ruta: cifras y perfil de altitud
require_once get_template_directory() . '/inc/mapas.php';  // mapas fijos: recorrido y punto de encuentro
require_once get_template_directory() . '/inc/consejos.php'; // textos de Consejos: niveles, MIDE, normas y el día antes
require_once get_template_directory() . '/inc/tiempo.php';     // previsión del tiempo de la próxima ruta (Open-Meteo)
require_once get_template_directory() . '/inc/calendario.php'; // «Añadir al calendario»: .ics de la próxima ruta
require_once get_template_directory() . '/inc/compartir.php';  // Open Graph y descripción: imagen y texto al compartir un enlace

/* SETUP */
function paso_setup() {
  load_theme_textdomain( 'paso', get_template_directory() . '/languages' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
  register_nav_menus( array(
    'main-menu'   => __( 'Menú principal', 'paso' ),
    'footer-legal' => __( 'Footer · Legal', 'paso' ),
  ) );
}
add_action( 'after_setup_theme', 'paso_setup' );

/* ACF · CAMPOS
 * Los grupos de campos están en acf-json/ (ACF los carga solo). Se editan desde ACF → Grupos de campos;
 * al guardar, ACF reescribe el .json correspondiente.
 */

/* IMÁGENES · los tamaños que genera WordPress (300, 768, 1024, 1536, 2048 y los recortes del tema) se guardan en WebP,
 * aunque se suba un JPG o un PNG. El original subido no cambia: para que también sea WebP, subirlo ya en WebP.
 */
function paso_imagenes_webp( $formatos ) {
  $formatos['image/jpeg'] = 'image/webp';
  $formatos['image/png']  = 'image/webp';
  return $formatos;
}
add_filter( 'image_editor_output_format', 'paso_imagenes_webp' );

// Calidad del WebP: WordPress usa 86 por defecto y sale más pesado que los JPG ya comprimidos (75–80) que se suben
function paso_calidad_webp( $calidad, $mime ) {
  return 'image/webp' === $mime ? 75 : $calidad;
}
add_filter( 'wp_editor_set_quality', 'paso_calidad_webp', 10, 2 );

/* LIMPIAR HEAD */
function paso_limpiar_cabecera() {
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  add_filter( 'emoji_svg_url', '__return_false' );
  add_filter( 'the_generator', '__return_false' );
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action( 'after_setup_theme', 'paso_limpiar_cabecera' );

/* FAVICON · SVG para navegadores actuales, PNG de respaldo y 180 para iOS */
function paso_favicon() {
  if ( has_site_icon() ) return;   // si se sube un icono desde Personalizar, manda ese
  $img = get_template_directory_uri() . '/assets/img';
  echo '<link rel="icon" href="' . esc_url( $img . '/paso-favicon.svg' ) . '" type="image/svg+xml">' . "\n";
  echo '<link rel="icon" href="' . esc_url( $img . '/paso-favicon-32.png' ) . '" sizes="32x32" type="image/png">' . "\n";
  echo '<link rel="apple-touch-icon" href="' . esc_url( $img . '/paso-favicon-180.png' ) . '">' . "\n";
}
add_action( 'wp_head', 'paso_favicon' );

/* ESTILOS
 * assets/css/main.css se genera con Tailwind CLI (npm run dev / npm run build)
 */
function paso_enqueue_styles() {
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'classic-theme-styles' );
  wp_dequeue_style( 'global-styles' );

  // Fuentes: alojadas en el tema (assets/fonts/), declaradas con @font-face al principio de src/css/main.css
  $css = '/assets/css/main.css';
  wp_enqueue_style(
    'paso-main',
    get_template_directory_uri() . $css,
    array(),
    filemtime( get_template_directory() . $css )
  );
}
add_action( 'wp_enqueue_scripts', 'paso_enqueue_styles', 20 );

/* SCRIPTS */
function paso_enqueue_scripts() {
  $scripts = array(
    'paso-menu'     => '/assets/js/menu.js',       // menú móvil
    'paso-header'   => '/assets/js/header.js',     // header fijo: se esconde al bajar, vuelve al subir
    'paso-tarjetas' => '/assets/js/tarjetas.js',   // fotos de las tarjetas en color al entrar en pantalla (táctil)
    'paso-cifras'   => '/assets/js/cifras.js',     // cifras de las rutas que cuentan desde 0 al verse
  );
  if ( is_singular( 'ruta' ) ) {
    $scripts['paso-pestanas'] = '/assets/js/pestanas.js';   // ficha: pestañas / acordeón del detalle
    $scripts['paso-galeria']  = '/assets/js/galeria.js';    // ficha: visor de fotos a pantalla completa
    $scripts['paso-trazado']  = '/assets/js/trazado.js';    // ficha: trazado del mapa y perfil que se dibujan al verse
    $scripts['paso-compartir'] = '/assets/js/compartir.js'; // ficha: «Compartir la ruta» con el menú del móvil
  }
  foreach ( $scripts as $nombre => $js ) {
    wp_enqueue_script(
      $nombre,
      get_template_directory_uri() . $js,
      array(),
      filemtime( get_template_directory() . $js ),
      array( 'in_footer' => true, 'strategy' => 'defer' )
    );
  }
}
add_action( 'wp_enqueue_scripts', 'paso_enqueue_scripts' );
