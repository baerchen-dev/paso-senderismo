/* GALERÍA DE LA RUTA · visor a pantalla completa (<dialog id="visor-galeria">)
 * Pulsar una foto la abre; flechas (botones o teclado) pasan de foto, Escape o × cierran. Recorre las 6 fotos,
 * también las que el mosaico no enseña en móvil.
 */
const visor = document.getElementById( 'visor-galeria' );
const fotos = [ ...document.querySelectorAll( '[data-galeria] [data-foto]' ) ];

if ( visor && fotos.length ) {
  const imagen  = visor.querySelector( '[data-visor-imagen]' );
  const leyenda = visor.querySelector( '[data-visor-leyenda]' );
  let actual = 0;

  function mostrar( indice ) {
    actual = ( indice + fotos.length ) % fotos.length;
    const foto = fotos[ actual ];
    imagen.src = foto.dataset.foto;
    imagen.alt = foto.querySelector( 'img' )?.alt || '';
    leyenda.textContent = foto.dataset.leyenda || '';
  }

  fotos.forEach( ( foto, i ) => foto.addEventListener( 'click', () => {
    mostrar( i );
    visor.showModal();
  } ) );

  visor.querySelector( '[data-visor-cerrar]' ).addEventListener( 'click', () => visor.close() );
  visor.querySelector( '[data-visor-anterior]' ).addEventListener( 'click', () => mostrar( actual - 1 ) );
  visor.querySelector( '[data-visor-siguiente]' ).addEventListener( 'click', () => mostrar( actual + 1 ) );
  visor.addEventListener( 'keydown', ( evento ) => {
    if ( evento.key === 'ArrowLeft' ) mostrar( actual - 1 );
    if ( evento.key === 'ArrowRight' ) mostrar( actual + 1 );
  } );
  // Al cerrar, el foco vuelve a la foto que se estaba viendo
  visor.addEventListener( 'close', () => fotos[ actual ].focus() );
  // Pulsar fuera de la foto (en el fondo) también cierra
  visor.addEventListener( 'click', ( evento ) => {
    if ( evento.target === visor || evento.target === imagen.parentElement ) visor.close();
  } );
}
