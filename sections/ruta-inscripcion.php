<?php
/**
 * RUTA · CIERRE DE INSCRIPCIÓN · Figma: CTA Inscripción (Mode=Info)
 * De momento las inscripciones son en el grupo de WhatsApp del club. Mode=Form (botón Apuntarme) cuando el club lo apruebe.
 * Solo en la próxima ruta. Debajo, dos Button / Secondary: «Añadir al calendario» (.ics, inc/calendario.php) y «Compartir la ruta»
 * (menú de compartir del móvil; en el ordenador, WhatsApp Web con el mensaje escrito: assets/js/compartir.js).
 * El mensaje no lleva ningún número ni enlace del grupo del club: cada uno elige a quién se lo manda.
 */
$ruta    = $args['ruta'];
$fecha   = date_create_immutable( (string) get_field( 'ruta_fecha', $ruta ), wp_timezone() );
$mensaje = sprintf( /* translators: 1: nombre de la ruta, 2: fecha y hora */ __( 'Próxima ruta: %1$s · %2$s', 'paso' ), get_the_title( $ruta ), wp_date( 'l j \d\e F, G:i', $fecha->getTimestamp() ) );
?>
<section class="bg-petroleum py-16 lg:py-30" aria-labelledby="inscripcion-titulo">
  <div class="contenedor flex flex-col items-center gap-3 text-center lg:gap-5">
    <h2 id="inscripcion-titulo" class="text-display-section text-piedra"><?php esc_html_e( '¿Te vienes?', 'paso' ); ?></h2>
    <p class="text-body text-piedra md:max-w-160"><?php esc_html_e( 'Las inscripciones se hacen en el grupo de WhatsApp del club. Allí se organizan también los coches compartidos.', 'paso' ); ?></p>
    <!-- Button / Secondary: a todo el ancho en móvil, uno debajo del otro; desde md, uno al lado del otro y centrados -->
    <div class="mt-5 flex w-full flex-col gap-3 md:mt-8 md:w-auto md:flex-row md:gap-4">
      <?php get_template_part( 'components/boton-secundario', null, array(
        'texto' => __( 'Añadir al calendario', 'paso' ),
        'url'   => paso_ruta_calendario_url( $ruta ),
        'icono' => 'calendario',
      ) ); ?>
      <?php get_template_part( 'components/boton-secundario', null, array(
        'texto'     => __( 'Compartir la ruta', 'paso' ),
        'icono'     => 'compartir',
        'url'       => 'https://wa.me/?text=' . rawurlencode( $mensaje . ' → ' . get_permalink( $ruta ) ),
        'nueva'     => true,
        'atributos' => array( 'data-compartir' => $mensaje, 'data-compartir-url' => get_permalink( $ruta ) ),
      ) ); ?>
    </div>
  </div>
</section>
