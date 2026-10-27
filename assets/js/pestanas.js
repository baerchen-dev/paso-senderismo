/* FICHA DE RUTA · pestañas (desde lg) y acordeón (hasta lg) del detalle: Recorrido / Qué llevar / Cómo llegar
 * Pestañas: el panel activo lleva data-activo; flechas, Inicio y Fin mueven entre pestañas (patrón tablist de ARIA).
 * Acordeón: cada fila abre o cierra su panel (data-abierto); pueden estar varios abiertos.
 * Qué se ve en cada tamaño lo decide el CSS de main.css ([data-panel]).
 */
document.querySelectorAll( '[data-pestanas]' ).forEach( ( detalle ) => {
  const pestanas = [ ...detalle.querySelectorAll( '[role="tab"]' ) ];
  const filas    = [ ...detalle.querySelectorAll( '[data-acordeon]' ) ];
  const paneles  = [ ...detalle.querySelectorAll( '[data-panel]' ) ];

  function activar( indice, conFoco = false ) {
    pestanas.forEach( ( pestana, i ) => {
      const activa = i === indice;
      pestana.setAttribute( 'aria-selected', activa );
      pestana.tabIndex = activa ? 0 : -1;
      paneles[ i ].toggleAttribute( 'data-activo', activa );
    } );
    if ( conFoco ) pestanas[ indice ].focus();
  }

  pestanas.forEach( ( pestana, i ) => {
    pestana.addEventListener( 'click', () => activar( i ) );
    pestana.addEventListener( 'keydown', ( evento ) => {
      const ultima = pestanas.length - 1;
      const destino = { ArrowRight: i === ultima ? 0 : i + 1, ArrowLeft: i === 0 ? ultima : i - 1, Home: 0, End: ultima }[ evento.key ];
      if ( destino === undefined ) return;
      evento.preventDefault();
      activar( destino, true );
    } );
  } );

  filas.forEach( ( fila, i ) => {
    fila.addEventListener( 'click', () => {
      const abrir = fila.getAttribute( 'aria-expanded' ) !== 'true';
      fila.setAttribute( 'aria-expanded', abrir );
      paneles[ i ].toggleAttribute( 'data-abierto', abrir );
    } );
  } );
} );
