<?php
/**
 * MENÚS · enlaces de las ubicaciones de Apariencia → Menús
 * Devuelven los datos y cada sitio pinta su HTML: header (escritorio), menú móvil (con números), footer.
 * Cada enlace: array( 'titulo', 'url', 'actual' ) · 'actual' = es la página que se está viendo (va en amarillo).
 */

/* Enlaces de primer nivel de una ubicación; sin menú asignado, los de $previstos */
function paso_menu_enlaces( $ubicacion, $previstos ) {
  $enlaces     = array();
  $ubicaciones = get_nav_menu_locations();

  if ( ! empty( $ubicaciones[ $ubicacion ] ) ) {
    foreach ( (array) wp_get_nav_menu_items( $ubicaciones[ $ubicacion ] ) as $item ) {
      if ( $item->menu_item_parent ) continue;   // solo primer nivel
      $enlaces[] = array( 'titulo' => $item->title, 'url' => $item->url );
    }
  }
  if ( ! $enlaces ) $enlaces = $previstos;

  // Página actual: se compara la ruta de la URL (sin dominio), así funcionan también los enlaces personalizados «/rutas/»
  $ruta_actual = trailingslashit( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ), PHP_URL_PATH ) );
  foreach ( $enlaces as &$enlace ) {
    $ruta             = trailingslashit( (string) wp_parse_url( $enlace['url'], PHP_URL_PATH ) );
    $enlace['actual'] = ( '/' !== $ruta && $ruta === $ruta_actual );
  }
  unset( $enlace );

  return $enlaces;
}

/* Menú principal · header, menú móvil y «Explora» del footer */
function paso_menu_principal() {
  return paso_menu_enlaces( 'main-menu', array(
    array( 'titulo' => __( 'Próxima ruta', 'paso' ), 'url' => home_url( '/proxima-ruta/' ) ),
    array( 'titulo' => __( 'Rutas', 'paso' ),        'url' => home_url( '/rutas/' ) ),
    array( 'titulo' => __( 'Consejos', 'paso' ),     'url' => home_url( '/consejos/' ) ),
    array( 'titulo' => __( 'Sobre PASO', 'paso' ),   'url' => home_url( '/sobre-paso/' ) ),
  ) );
}

/* Footer · Legal · franja inferior del footer */
function paso_menu_legal() {
  return paso_menu_enlaces( 'footer-legal', array(
    array( 'titulo' => __( 'Aviso legal', 'paso' ), 'url' => home_url( '/aviso-legal/' ) ),
    array( 'titulo' => __( 'Privacidad', 'paso' ),  'url' => home_url( '/privacidad/' ) ),
    array( 'titulo' => __( 'Cookies', 'paso' ),     'url' => home_url( '/cookies/' ) ),
  ) );
}
