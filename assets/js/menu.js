/* MENÚ MÓVIL
 * Abre y cierra el menú a pantalla completa (#menu-movil). Mientras está abierto, la página no hace scroll.
 */
const menuBoton = document.querySelector( '.menu-toggle' );
const menuPanel = document.getElementById( 'menu-movil' );

if ( menuBoton && menuPanel ) {

  function abrirMenu( conTeclado = false ) {
    menuBoton.setAttribute( 'aria-expanded', 'true' );
    menuBoton.setAttribute( 'aria-label', menuBoton.dataset.labelCerrar );
    menuPanel.setAttribute( 'data-open', '' );
    document.documentElement.classList.add( 'overflow-hidden' );
    // Solo con teclado: con el ratón no hace falta y mostraría el recuadro de foco
    if ( conTeclado ) menuPanel.querySelector( 'a' )?.focus();
  }

  function cerrarMenu( devolverFoco = true ) {
    menuBoton.setAttribute( 'aria-expanded', 'false' );
    menuBoton.setAttribute( 'aria-label', menuBoton.dataset.labelAbrir );
    menuPanel.removeAttribute( 'data-open' );
    document.documentElement.classList.remove( 'overflow-hidden' );
    if ( devolverFoco ) menuBoton.focus();
  }

  const estaAbierto = () => menuBoton.getAttribute( 'aria-expanded' ) === 'true';
  const dentroDelMenu = ( el ) => menuPanel.contains( el ) || menuBoton.contains( el );

  // e.detail === 0 → el clic viene del teclado (Enter / Espacio), no del ratón
  menuBoton.addEventListener( 'click', ( e ) => estaAbierto() ? cerrarMenu( e.detail === 0 ) : abrirMenu( e.detail === 0 ) );

  // Escape cierra el menú
  document.addEventListener( 'keydown', ( e ) => {
    if ( e.key === 'Escape' && estaAbierto() ) cerrarMenu();
  } );

  // Si el foco sale del menú con el tabulador, se cierra
  menuPanel.addEventListener( 'focusout', ( e ) => {
    if ( estaAbierto() && e.relatedTarget && ! dentroDelMenu( e.relatedTarget ) ) cerrarMenu( false );
  } );

  // Al pulsar un enlace se cierra
  menuPanel.addEventListener( 'click', ( e ) => {
    if ( e.target.closest( 'a' ) ) cerrarMenu( false );
  } );

  // Al pasar a tablet o escritorio (girar el móvil, ventana más ancha) el menú desaparece: se cierra para liberar el scroll
  window.matchMedia( '(min-width: 48rem)' ).addEventListener( 'change', ( e ) => {
    if ( e.matches && estaAbierto() ) cerrarMenu( false );
  } );
}
