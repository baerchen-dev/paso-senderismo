/* CIFRAS QUE CUENTAN · Route Stats (tarjeta de la próxima ruta y ficha)
 * Al entrar en pantalla, cada cifra ([data-contar]: «15,6», «+930», «2.071») sube desde 0 hasta su valor en 1,2 s.
 * Mismo formato que el tema (miles con punto, decimales con coma). Los textos («Difícil», «Circular») no se tocan.
 * El ancho se fija al final antes de empezar, para que nada se mueva. Sin animación con «reducir movimiento».
 */
const DURACION_CIFRAS = 1200;
const salida = ( t ) => 1 - Math.pow( 1 - t, 3 );   // rápido al principio, se posa al final

function formatear( n, decimales ) {
  const [ entero, dec ] = n.toFixed( decimales ).split( '.' );
  return entero.replace( /\B(?=(\d{3})+(?!\d))/g, '.' ) + ( dec ? ',' + dec : '' );
}

function contar( el ) {
  const m = el.textContent.trim().match( /^([+−-]?)(\d{1,3}(?:\.\d{3})*|\d+)(?:,(\d+))?$/ );
  if ( ! m ) return;
  const [ , signo, entero, dec = '' ] = m;
  const final = parseFloat( entero.replace( /\./g, '' ) + '.' + ( dec || '0' ) );
  const original = el.textContent;
  el.style.minWidth = el.getBoundingClientRect().width + 'px';
  const inicio = performance.now();
  const paso = ( ahora ) => {
    const t = Math.min( 1, ( ahora - inicio ) / DURACION_CIFRAS );
    el.textContent = t < 1 ? signo + formatear( final * salida( t ), dec.length ) : original;
    if ( t < 1 ) requestAnimationFrame( paso );
    else el.style.removeProperty( 'min-width' );
  };
  requestAnimationFrame( paso );
}

const listas = [ ...document.querySelectorAll( 'dl:has([data-contar])' ) ];

if ( listas.length && 'IntersectionObserver' in window && ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
  const observador = new IntersectionObserver( ( entradas ) => {
    for ( const entrada of entradas ) {
      if ( ! entrada.isIntersecting ) continue;
      observador.unobserve( entrada.target );
      entrada.target.querySelectorAll( '[data-contar]' ).forEach( contar );
    }
  }, { threshold: 0.6 } );
  listas.forEach( ( dl ) => observador.observe( dl ) );
}
