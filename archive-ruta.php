<?php
/**
 * RUTAS (/rutas/) · Figma: PASO / Rutas (Desktop 1440 · Mobile 390; tablet sin diseño: 2 por fila)
 * Cabecera, próximas (de la más cercana a la más lejana) y realizadas (de la más reciente a la más antigua), todas en una página.
 * Tarjetas: components/route-card.php. Un bloque sin rutas no se muestra.
 * Sin foto arriba: el contenido empieza bajo el header (que va superpuesto, 84 px; 104 desde tablet).
 * Filtros de dificultad: cuando haya unas 8 rutas (guía).
 */
get_header();

$proximas   = paso_rutas_proximas();
$realizadas = paso_rutas_realizadas( -1 );
$bloques    = array_filter( array(
  'proximas'   => array( __( 'Próxima', 'paso' ), $proximas, true ),
  'realizadas' => array( __( 'Realizadas', 'paso' ), $realizadas, false ),
), fn( $bloque ) => $bloque[1] );
?>

<section class="contenedor pt-31 pb-6 md:pt-46 lg:pb-12">
  <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Rutas', 'paso' ); ?></p>
  <h1 class="mt-3 text-display-hero text-piedra lg:mt-5"><?php esc_html_e( 'Rutas', 'paso' ); ?></h1>
  <p class="mt-3 text-body text-muted md:max-w-160 lg:mt-5"><?php esc_html_e( 'Todas las rutas que hemos hecho en grupo, con sus datos y las fotos del día. Cada ruta nueva se suma aquí.', 'paso' ); ?></p>
</section>

<?php foreach ( $bloques as $clave => list( $titulo, $rutas, $proxima ) ) : ?>
  <section class="contenedor <?php echo 'proximas' === $clave ? 'pt-10 pb-4 lg:pt-14 lg:pb-6' : 'pt-8 pb-16'; ?>" aria-labelledby="rutas-<?php echo esc_attr( $clave ); ?>">
    <h2 id="rutas-<?php echo esc_attr( $clave ); ?>" class="text-eyebrow text-muted"><?php echo esc_html( $titulo ); ?></h2>
    <ul class="<?php echo 'proximas' === $clave ? 'mt-4 lg:mt-6' : 'mt-6 lg:mt-8'; ?> grid gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
      <?php foreach ( $rutas as $ruta ) : ?>
        <li><?php get_template_part( 'components/route-card', null, array( 'ruta' => $ruta, 'proxima' => $proxima ) ); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endforeach; ?>

<?php get_footer(); ?>
