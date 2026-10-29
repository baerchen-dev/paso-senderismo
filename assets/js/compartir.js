/* COMPARTIR LA RUTA · enlace [data-compartir] del cierre de inscripción
 * En el móvil (pantalla táctil con menú de compartir): abre el menú del sistema (WhatsApp, Telegram, mensajes…).
 * En el ordenador, o si no hay menú: sigue el enlace, WhatsApp Web con el mensaje ya escrito.
 */
document.querySelectorAll( 'a[data-compartir]' ).forEach( ( enlace ) => {
  enlace.addEventListener( 'click', async ( e ) => {
    if ( ! navigator.share || ! window.matchMedia( '(pointer: coarse)' ).matches ) return;
    e.preventDefault();
    try {
      await navigator.share( { title: document.title, text: enlace.dataset.compartir, url: enlace.dataset.compartirUrl } );
    } catch ( error ) {
      // Cancelado por la persona: no se hace nada
    }
  } );
} );
