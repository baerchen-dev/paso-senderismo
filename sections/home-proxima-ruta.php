<?php
/**
 * HOME · PRÓXIMA RUTA · Figma: Section / Next Route (Desktop 1440 · Tablet 768 · Mobile 390)
 * La ruta la elige la fecha (paso_proxima_ruta, inc/rutas.php); sin ninguna, la tarjeta muestra «Sin ruta».
 * Márgenes: 48 arriba y abajo hasta lg, 96 en escritorio.
 * id="proxima-ruta": destino del botón «Ver la próxima ruta» del hero (baja con desplazamiento suave, ver header.php).
 */
?>
<section id="proxima-ruta" class="bg-petroleum py-12 lg:py-24" aria-label="<?php esc_attr_e( 'Próxima ruta', 'paso' ); ?>">
  <div class="contenedor">
    <?php get_template_part( 'components/next-route', null, array( 'ruta' => paso_proxima_ruta() ) ); ?>
  </div>
</section>
