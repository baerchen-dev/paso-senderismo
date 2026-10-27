/* TARJETAS DE RUTA · foto en color al entrar en pantalla
 * Solo en pantallas táctiles (sin ratón no hay hover): la tarjeta que se ve casi entera recibe data-en-pantalla
 * y su foto pasa a color (clases group-data-en-pantalla: en components/route-card.php). Al salir, vuelve a apagarse.
 */
const tarjetas = document.querySelectorAll( '.tarjeta-ruta' );

if ( tarjetas.length && 'IntersectionObserver' in window && window.matchMedia( '(hover: none)' ).matches ) {
  const observador = new IntersectionObserver( ( entradas ) => {
    for ( const entrada of entradas ) {
      entrada.target.toggleAttribute( 'data-en-pantalla', entrada.isIntersecting );
    }
  }, { threshold: 0.75 } );

  tarjetas.forEach( ( tarjeta ) => observador.observe( tarjeta ) );
}
