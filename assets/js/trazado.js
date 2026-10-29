/* TRAZADO QUE SE DIBUJA · ficha de ruta
 * Al entrar en pantalla, el trazado del mapa (path[data-trazado]) se dibuja de la salida a la llegada y el perfil de altitud
 * se descubre de izquierda a derecha (rect[data-perfil-revelar] del clipPath), en 3 s y a paso casi constante; el punto y
 * la cifra de la cima del perfil ([data-perfil-cima]) aparecen cuando la línea llega a ellos. Una sola vez.
 * Sin animación con «reducir movimiento» o sin IntersectionObserver: se ve todo desde el principio.
 * Los paneles cerrados (display: none) no entran en pantalla: se dibujan al abrirlos.
 */
const DURACION = 3000;
const suave = ( t ) => ( 1 - Math.cos( Math.PI * t ) ) / 2;   // arranca y termina suave, sin acelerón en el centro

// Longitud del trazado en píxeles de pantalla (el trazo no escala: vector-effect="non-scaling-stroke")
function longitudEnPantalla( path ) {
  const matriz = path.getScreenCTM();
  const total = path.getTotalLength();
  let longitud = 0, anterior = null;
  for ( let i = 0; i <= 200; i++ ) {
    const p = path.getPointAtLength( total * i / 200 ).matrixTransform( matriz );
    if ( anterior ) longitud += Math.hypot( p.x - anterior.x, p.y - anterior.y );
    anterior = p;
  }
  return longitud;
}

function animar( pintar ) {
  const inicio = performance.now();
  const paso = ( ahora ) => {
    const t = Math.min( 1, ( ahora - inicio ) / DURACION );
    pintar( suave( t ) );
    if ( t < 1 ) requestAnimationFrame( paso );
  };
  requestAnimationFrame( paso );
}

function dibujarMapa( svg ) {
  const paths = [ ...svg.querySelectorAll( 'path[data-trazado]' ) ];
  const longitudes = paths.map( longitudEnPantalla );
  paths.forEach( ( p, i ) => { p.style.strokeDasharray = `${ longitudes[ i ] } ${ longitudes[ i ] }`; } );
  animar( ( t ) => {
    paths.forEach( ( p, i ) => { p.style.strokeDashoffset = longitudes[ i ] * ( 1 - t ); } );
    if ( t === 1 ) paths.forEach( ( p ) => {   // al terminar, sin guiones: si cambia el ancho, el trazado sigue entero
      p.style.removeProperty( 'stroke-dasharray' );
      p.style.removeProperty( 'stroke-dashoffset' );
    } );
  } );
}

const cimaDe = ( rect ) => [ ...rect.ownerSVGElement.parentElement.querySelectorAll( '[data-perfil-cima]' ) ];

function dibujarPerfil( rect ) {
  const cima = cimaDe( rect );
  animar( ( t ) => {
    rect.setAttribute( 'width', 1000 * t );
    cima.forEach( ( el ) => { if ( t * 100 >= parseFloat( el.dataset.perfilCima ) ) el.style.removeProperty( 'opacity' ); } );
  } );
}

const movimiento = ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
const mapas = [ ...document.querySelectorAll( 'svg:has(path[data-trazado])' ) ];
const perfiles = [ ...document.querySelectorAll( 'rect[data-perfil-revelar]' ) ];

if ( movimiento && 'IntersectionObserver' in window && ( mapas.length || perfiles.length ) ) {
  // Estado de partida: trazado oculto (desplazamiento enorme hasta medirlo) y perfil de ancho 0
  mapas.forEach( ( svg ) => svg.querySelectorAll( 'path[data-trazado]' ).forEach( ( p ) => {
    p.style.strokeDasharray = '100000 100000';
    p.style.strokeDashoffset = '100000';
  } ) );
  perfiles.forEach( ( rect ) => {
    rect.setAttribute( 'width', 0 );
    cimaDe( rect ).forEach( ( el ) => { el.style.opacity = 0; } );
  } );

  const observador = new IntersectionObserver( ( entradas ) => {
    for ( const entrada of entradas ) {
      if ( ! entrada.isIntersecting ) continue;
      observador.unobserve( entrada.target );
      if ( entrada.target.tagName === 'svg' && entrada.target.querySelector( 'path[data-trazado]' ) ) dibujarMapa( entrada.target );
      else dibujarPerfil( entrada.target.querySelector( 'rect[data-perfil-revelar]' ) );
    }
  }, { threshold: 0.5 } );

  mapas.forEach( ( svg ) => observador.observe( svg ) );
  perfiles.forEach( ( rect ) => observador.observe( rect.ownerSVGElement ) );
}
