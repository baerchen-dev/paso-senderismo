/* HEADER FIJO
 * Arriba del todo: transparente sobre el hero. Al bajar se esconde (data-oculto); al subir vuelve con fondo petróleo (data-fondo).
 * No se esconde con el menú móvil abierto ni cuando el foco del teclado está dentro.
 */
const header = document.querySelector( '.site-header' );

if ( header ) {
  const boton = header.querySelector( '.menu-toggle' );
  const MARGEN = 6;   // px de scroll mínimos para cambiar de estado (evita parpadeos con el trackpad)
  let ultimaY = window.scrollY;

  function actualizar() {
    const y = window.scrollY;
    const alto = header.offsetHeight;
    const menuAbierto = boton?.getAttribute( 'aria-expanded' ) === 'true';

    if ( y <= alto ) {
      // Arriba: como siempre, sobre el hero
      header.removeAttribute( 'data-oculto' );
      header.toggleAttribute( 'data-fondo', y > 0 );
    } else if ( Math.abs( y - ultimaY ) < MARGEN ) {
      return;
    } else if ( y > ultimaY && ! menuAbierto && ! header.contains( document.activeElement ) ) {
      header.setAttribute( 'data-oculto', '' );
    } else if ( y < ultimaY ) {
      header.removeAttribute( 'data-oculto' );
      header.setAttribute( 'data-fondo', '' );
    }
    ultimaY = y;
  }

  window.addEventListener( 'scroll', actualizar, { passive: true } );
  actualizar();

  // Con el teclado (tabulador) el header vuelve a verse
  header.addEventListener( 'focusin', () => header.removeAttribute( 'data-oculto' ) );
}
