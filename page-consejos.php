<?php
/**
 * CONSEJOS (/consejos/) · Figma: PASO / Consejos (Desktop 1440 · Mobile 390; tablet sin diseño: tarjetas de 2 en 2)
 * Cabecera → 01 Qué llevar → 02 Niveles de dificultad + MIDE (#mide, destino de «¿Qué significa?» de la ficha)
 * → 03 Normas del grupo → 04 El día antes → próxima ruta (la misma sección de la Home).
 * Los apartados alternan fondo Petroleum y Petroleum Raised; cada uno con su marca de sendero (PR, GR, SL, PR) y número.
 * Textos: inc/consejos.php; Qué llevar es la misma lista «Siempre» de la ficha de ruta (paso_llevar_siempre).
 * Márgenes de cada apartado: 48 arriba y 56 abajo hasta lg; 80 y 96 en escritorio.
 */
get_header();

// Cabecera de apartado: marca de sendero + número, y título
$cabecera = function ( $numero, $marca, $titulo, $id ) { ?>
  <p class="flex items-center gap-3.5 text-eyebrow text-amarillo">
    <?php get_template_part( 'components/trail-mark', null, array( 'tipo' => $marca ) ); ?>
    <span><?php echo esc_html( $numero ); ?></span>
  </p>
  <h2 id="<?php echo esc_attr( $id ); ?>" class="mt-6 text-display-section text-piedra lg:mt-10"><?php echo esc_html( $titulo ); ?></h2>
<?php };

// Tarjeta de nombre + explicación (Qué llevar, El día antes)
$tarjeta = function ( $cosa, $fondo ) { ?>
  <div class="flex flex-col gap-2 rounded-2xl p-6 lg:gap-2.5 <?php echo esc_attr( $fondo ); ?>">
    <h3 class="text-display-title text-piedra"><?php echo esc_html( $cosa['nombre'] ); ?></h3>
    <p class="text-body text-muted"><?php echo esc_html( $cosa['texto'] ); ?></p>
  </div>
<?php };
?>

<section class="contenedor pt-31 pb-12 md:pt-46 lg:pb-20">
  <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Consejos', 'paso' ); ?></p>
  <h1 class="mt-3 text-display-hero text-piedra lg:mt-5"><?php esc_html_e( 'Antes de salir', 'paso' ); ?></h1>
  <p class="mt-3 text-body text-muted md:max-w-160 lg:mt-5 lg:max-w-none"><?php esc_html_e( 'Lo básico para que la ruta sea cómoda y segura para todo el grupo. Si es tu primera vez, empieza por aquí.', 'paso' ); ?></p>
</section>

<!-- 01 · QUÉ LLEVAR -->
<section class="border-y border-line bg-petroleum-raised pt-12 pb-14 lg:pt-20 lg:pb-24" aria-labelledby="que-llevar">
  <div class="contenedor">
    <?php $cabecera( '01', 'pr', __( 'Qué llevar', 'paso' ), 'que-llevar' ); ?>
    <ul class="mt-6 grid gap-3 md:grid-cols-2 md:gap-6 lg:mt-10 lg:grid-cols-3 lg:items-start">
      <?php foreach ( paso_llevar_siempre() as $cosa ) : ?>
        <li><?php $tarjeta( $cosa, 'bg-petroleum' ); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- 02 · NIVELES DE DIFICULTAD + MIDE -->
<section class="pt-12 pb-14 lg:pt-20 lg:pb-24" aria-labelledby="niveles">
  <div class="contenedor">
    <?php $cabecera( '02', 'gr', __( 'Niveles de dificultad', 'paso' ), 'niveles' ); ?>
    <ul class="mt-6 grid md:grid-cols-3 md:gap-8 lg:mt-10">
      <?php foreach ( paso_niveles() as $nivel ) : ?>
        <li class="flex flex-col gap-2.5 border-t border-line pt-6 pb-6 md:pb-0">
          <h3 class="text-data-value text-amarillo"><?php echo esc_html( $nivel['nombre'] ); ?></h3>
          <p class="text-body text-piedra"><?php echo esc_html( $nivel['texto'] ); ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="mt-0 text-caption text-muted md:mt-6 lg:mt-10"><?php esc_html_e( 'La dificultad de cada ruta aparece en su ficha, junto a la distancia y el desnivel.', 'paso' ); ?></p>

    <!-- MIDE · escala de 1 a 5 sin valor (aquí se explica; el valor de cada ruta va en su ficha): solo el 1 en amarillo, para que se lea hacia dónde crece -->
    <!-- La guía oficial se abre en una pestaña nueva (es otra web) -->
    <div id="mide" class="mt-16 scroll-mt-28 lg:mt-28">
      <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Valoración MIDE', 'paso' ); ?></p>
      <h3 class="mt-3 text-display-subsection text-piedra"><?php esc_html_e( 'Qué es la valoración MIDE', 'paso' ); ?></h3>
      <p class="mt-3 text-body text-muted"><?php esc_html_e( 'El MIDE (Método de Información de Excursiones) es el sistema que usan las federaciones de montaña para describir una ruta. Puntúa cuatro aspectos del 1 al 5: cuanto más alto, más exigente.', 'paso' ); ?></p>

      <ul class="mt-5 grid gap-3 md:grid-cols-2 md:gap-6 lg:mt-8 lg:grid-cols-4">
        <?php foreach ( paso_mide_criterios() as $criterio ) : ?>
          <li class="flex flex-col gap-4 rounded-2xl bg-petroleum-soft p-6">
            <div class="flex flex-col gap-2.5">
              <h4 class="text-eyebrow text-piedra"><?php echo esc_html( $criterio['nombre'] ); ?></h4>
              <span class="flex flex-col gap-1" aria-hidden="true">
                <span class="flex gap-1"><?php for ( $i = 1; $i <= 5; $i++ ) : ?><span class="h-2 flex-1 rounded-xs <?php echo 1 === $i ? 'bg-amarillo' : 'bg-line'; ?>"></span><?php endfor; ?></span>
                <span class="flex justify-between text-caption text-muted"><span>1</span><span>5</span></span>
              </span>
              <p class="text-caption text-muted"><?php echo esc_html( $criterio['mide'] ); ?></p>
            </div>
            <p class="text-body text-piedra"><?php echo esc_html( $criterio['texto'] ); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="mt-5 flex flex-col items-start gap-2 lg:mt-8 lg:flex-row lg:items-center lg:gap-6">
        <p class="text-caption text-muted"><?php esc_html_e( 'Cada ruta indica su MIDE en la ficha, junto a la distancia y el desnivel.', 'paso' ); ?></p>
        <?php get_template_part( 'components/enlace-flecha', null, array( 'texto' => __( 'Guía oficial del MIDE', 'paso' ), 'url' => PASO_MIDE_GUIA, 'nueva' => true ) ); ?>
      </div>
    </div>
  </div>
</section>

<!-- 03 · NORMAS DEL GRUPO -->
<section class="border-y border-line bg-petroleum-raised pt-12 pb-14 lg:pt-20 lg:pb-24" aria-labelledby="normas">
  <div class="contenedor">
    <?php $cabecera( '03', 'sl', __( 'Normas del grupo', 'paso' ), 'normas' ); ?>
    <ol class="mt-6 lg:mt-10">
      <?php foreach ( paso_normas() as $i => $norma ) : ?>
        <li class="flex gap-6 border-t border-line py-5">
          <span class="w-4.5 shrink-0 pt-0.5 text-eyebrow text-amarillo" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
          <p class="text-body text-piedra"><?php echo esc_html( $norma ); ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- 04 · EL DÍA ANTES -->
<section class="pt-12 pb-16 lg:pt-20 lg:pb-30" aria-labelledby="dia-antes">
  <div class="contenedor">
    <?php $cabecera( '04', 'pr', __( 'El día antes', 'paso' ), 'dia-antes' ); ?>
    <ul class="mt-6 grid gap-3 md:grid-cols-2 md:gap-6 lg:mt-10 lg:grid-cols-3 lg:items-start">
      <?php foreach ( paso_dia_antes() as $cosa ) : ?>
        <li><?php $tarjeta( $cosa, 'bg-petroleum-soft' ); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php get_template_part( 'sections/home-proxima-ruta' ); ?>

<?php get_footer(); ?>
