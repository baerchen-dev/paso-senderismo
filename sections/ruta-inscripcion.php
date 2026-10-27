<?php
/**
 * RUTA · CIERRE DE INSCRIPCIÓN · Figma: CTA Inscripción (Mode=Info)
 * De momento las inscripciones son en el grupo de WhatsApp del club. Mode=Form (botón Apuntarme) cuando el club lo apruebe.
 * Solo en la próxima ruta.
 */
?>
<section class="bg-petroleum py-16 lg:py-30" aria-labelledby="inscripcion-titulo">
  <div class="contenedor flex flex-col items-center gap-3 text-center lg:gap-5">
    <h2 id="inscripcion-titulo" class="text-display-section text-piedra"><?php esc_html_e( '¿Te vienes?', 'paso' ); ?></h2>
    <p class="text-body text-piedra md:max-w-160"><?php esc_html_e( 'Las inscripciones se hacen en el grupo de WhatsApp del club. Allí se organizan también los coches compartidos.', 'paso' ); ?></p>
  </div>
</section>
