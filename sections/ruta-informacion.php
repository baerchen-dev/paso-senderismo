<?php
/**
 * RUTA · INFORMACIÓN · Figma: Section / Datos clave + Section / MIDE + Section / Por qué esta ruta + Section / Detalle (pestañas)
 *   (en tablet y móvil, todo junto en Section / Información)
 * Fondo Petroleum Raised. En escritorio cada bloque es una banda separada por una línea; hasta lg van seguidos sin líneas.
 * Detalle: Recorrido / Qué llevar / Cómo llegar → pestañas desde lg, acordeón hasta entonces (assets/js/pestanas.js, CSS en main.css).
 * Ruta realizada (Figma: PASO / Ruta realizada): sin MIDE y solo con la pestaña Recorrido.
 * $args: 'ruta' (WP_Post), 'proxima' (bool).
 */
$ruta        = $args['ruta'];
$datos       = paso_ruta_datos( $ruta );
$titular     = get_field( 'ruta_titular', $ruta );
$descripcion = get_field( 'ruta_descripcion', $ruta );

// Paneles del detalle (los vacíos no salen)
$tramos = array();
foreach ( array( 1, 2, 3 ) as $i ) {
  $tramo = get_field( 'ruta_tramo_' . $i, $ruta );
  if ( ! empty( $tramo['texto'] ) ) $tramos[] = $tramo;
}
$llevar      = paso_lista( get_field( 'ruta_llevar', $ruta ) );
$aviso       = get_field( 'ruta_aviso', $ruta );
$coordenadas = preg_replace( '/\s+/', '', (string) get_field( 'ruta_coordenadas', $ruta ) );
$encuentro   = implode( ', ', array_filter( array( get_field( 'ruta_encuentro', $ruta ), get_field( 'ruta_localidad', $ruta ) ) ) );
$fecha       = paso_ruta_fecha( $ruta );
$llegada     = array_filter( array(
  __( 'Punto de encuentro', 'paso' ) => $encuentro . ( $encuentro && $fecha ? ' · ' . $fecha['hora'] : '' ),
  __( 'Desde Málaga', 'paso' )       => get_field( 'ruta_desde_malaga', $ruta ),
  __( 'Aparcamiento', 'paso' )       => get_field( 'ruta_aparcamiento', $ruta ),
  __( 'Coche compartido', 'paso' )   => __( 'Se organiza en el grupo de WhatsApp del club, como siempre.', 'paso' ),
) );

// Recorrido: perfil y datos secundarios salen del GPX
$gpx         = paso_ruta_gpx( $ruta );
$tipo        = get_field_object( 'ruta_tipo', $ruta );
$secundarios = $gpx ? array_filter( array(
  null !== $gpx['salida'] ? sprintf( __( 'Salida a %s m', 'paso' ), paso_cifra( $gpx['salida'] ) ) : '',
  sprintf( __( 'Desnivel de bajada %s m', 'paso' ), paso_cifra( $gpx['bajada'] ) ),
  $gpx['movimiento'] ? sprintf( __( 'Sin paradas: %s h', 'paso' ), paso_cifra( $gpx['movimiento'] ) ) : '',
) ) : array();

// Mapas fijos (inc/mapas.php) y etiquetas de salida y cima: «Salida · El Robledal · 1.159 m» · «La Maroma · 2.071 m»
$mapa            = paso_mapa_recorrido( $ruta );
$mapa_encuentro  = paso_mapa_encuentro( $ruta );
$vuelve_inicio   = in_array( $tipo['value'] ?? '', array( 'circular', 'ida-y-vuelta' ), true );   // acaba donde empieza: «Salida y llegada»
$etiqueta_salida = $gpx ? implode( ' · ', array_filter( array( $vuelve_inicio ? __( 'Salida y llegada', 'paso' ) : __( 'Salida', 'paso' ), get_field( 'ruta_salida', $ruta ), null !== $gpx['salida'] ? paso_cifra( $gpx['salida'] ) . ' m' : '' ) ) ) : '';
// En móvil, sin el nombre del lugar («Salida y llegada · 614 m»): entero se monta sobre el trazado (ya sale en la cabecera)
$etiqueta_salida_movil = $gpx ? implode( ' · ', array_filter( array( $vuelve_inicio ? __( 'Salida y llegada', 'paso' ) : __( 'Salida', 'paso' ), null !== $gpx['salida'] ? paso_cifra( $gpx['salida'] ) . ' m' : '' ) ) ) : '';
$etiqueta_cima   =$gpx ? ( get_field( 'ruta_cima', $ruta ) ?: __( 'Cima', 'paso' ) ) . ' · ' . paso_cifra( $gpx['altitud'] ) . ' m' : '';

$paneles = array();
if ( $tramos || $gpx ) $paneles['recorrido'] = __( 'Recorrido', 'paso' );
// Qué llevar y Cómo llegar solo en la próxima ruta: en una realizada interesa el recorrido
if ( $args['proxima'] ) {
  $paneles['que-llevar']  = __( 'Qué llevar', 'paso' );
  $paneles['como-llegar'] = __( 'Cómo llegar', 'paso' );
}
$primero = array_key_first( $paneles );
?>
<div class="border-b border-line bg-petroleum-raised">

  <?php if ( $datos ) : ?>
    <!-- DATOS CLAVE -->
    <section class="pt-10 lg:border-t lg:border-line lg:py-14" aria-label="<?php esc_attr_e( 'Datos de la ruta', 'paso' ); ?>">
      <div class="contenedor"><?php get_template_part( 'components/route-stats', null, array( 'datos' => $datos ) ); ?></div>
    </section>
  <?php endif; ?>

  <?php if ( $args['proxima'] ) : ?>
    <!-- MIDE (solo en la próxima ruta; la ficha de ruta realizada no lo lleva) -->
    <section class="pt-8 lg:mt-0 lg:border-t lg:border-line lg:pt-10 lg:pb-12">
      <div class="contenedor"><?php get_template_part( 'components/mide-panel', null, array( 'ruta' => $ruta ) ); ?></div>
    </section>
  <?php endif; ?>

  <?php if ( $titular || $descripcion ) : ?>
    <!-- POR QUÉ ESTA RUTA -->
    <section class="pt-12 lg:border-t lg:border-line lg:pt-16 lg:pb-30">
      <div class="contenedor">
        <?php if ( $titular ) : ?><h2 class="text-display-section text-piedra lg:max-w-250"><?php echo esc_html( $titular ); ?></h2><?php endif; ?>
        <?php if ( $descripcion ) : ?><p class="mt-5 text-body text-piedra lg:mt-8 lg:max-w-180"><?php echo esc_html( $descripcion ); ?></p><?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- DETALLE · pestañas (lg) / acordeón -->
  <section class="pt-10 pb-16 lg:pt-0 lg:pb-30" aria-label="<?php esc_attr_e( 'Detalle de la ruta', 'paso' ); ?>">
    <div class="contenedor" data-pestanas>

      <div class="hidden gap-12 border-b border-line lg:flex" role="tablist" aria-label="<?php esc_attr_e( 'Detalle de la ruta', 'paso' ); ?>">
        <?php foreach ( $paneles as $clave => $nombre ) : $activo = $clave === $primero; ?>
          <button type="button" role="tab" id="pestana-<?php echo esc_attr( $clave ); ?>" aria-controls="panel-<?php echo esc_attr( $clave ); ?>" aria-selected="<?php echo $activo ? 'true' : 'false'; ?>" tabindex="<?php echo $activo ? '0' : '-1'; ?>"
            class="-mb-px border-b-3 border-transparent py-3.5 text-link text-piedra/70 transition-colors duration-200 hover:text-piedra aria-selected:border-amarillo aria-selected:text-piedra focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amarillo">
            <?php echo esc_html( $nombre ); ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="border-b border-line lg:mt-12 lg:border-b-0">
        <?php foreach ( $paneles as $clave => $nombre ) : $abierto = $clave === $primero; ?>

          <!-- Fila del acordeón (hasta lg) -->
          <h3 class="border-t border-line lg:hidden">
            <button type="button" data-acordeon aria-expanded="<?php echo $abierto ? 'true' : 'false'; ?>" aria-controls="panel-<?php echo esc_attr( $clave ); ?>"
              class="group flex w-full items-center justify-between py-4.5 text-left text-link text-piedra focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amarillo">
              <?php echo esc_html( $nombre ); ?>
              <span class="text-display-title group-aria-expanded:text-amarillo" aria-hidden="true"><span class="group-aria-expanded:hidden">+</span><span class="hidden group-aria-expanded:inline">−</span></span>
            </button>
          </h3>

          <div id="panel-<?php echo esc_attr( $clave ); ?>" role="tabpanel" aria-labelledby="pestana-<?php echo esc_attr( $clave ); ?>" data-panel <?php echo $abierto ? 'data-activo data-abierto' : ''; ?> class="pb-6 lg:pb-0">

            <?php if ( 'recorrido' === $clave ) : ?>
              <!-- RECORRIDO · mapa (a la izquierda en escritorio) y columna con perfil de altitud, datos del GPX y tramos -->
              <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:gap-8">
              <?php if ( $mapa ) : ?>
                <div class="lg:flex-1"><?php get_template_part( 'components/mapa-recorrido', null, array( 'mapa' => $mapa, 'salida' => $etiqueta_salida, 'salida_movil' => $etiqueta_salida_movil, 'cima' => $etiqueta_cima ) ); ?></div>
              <?php endif; ?>
              <div class="flex flex-col gap-4 lg:ml-auto lg:w-117 lg:gap-6">
                <?php if ( $gpx ) : ?>
                  <div class="flex flex-col gap-4">
                    <?php get_template_part( 'components/perfil-altitud', null, array( 'gpx' => $gpx, 'tipo' => $tipo['choices'][ $tipo['value'] ] ?? '' ) ); ?>
                    <?php if ( $secundarios ) : ?><p class="text-caption text-muted"><?php echo esc_html( implode( ' · ', $secundarios ) ); ?></p><?php endif; ?>
                  </div>
                <?php endif; ?>
                <?php foreach ( $tramos as $tramo ) : ?>
                  <div class="flex flex-col gap-1 border-t border-line pt-4">
                    <?php if ( $tramo['km'] ) : ?><p class="text-eyebrow text-piedra"><?php echo esc_html( $tramo['km'] ); ?> km</p><?php endif; ?>
                    <p class="text-body text-piedra"><?php echo esc_html( $tramo['texto'] ); ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
              </div>

            <?php elseif ( 'que-llevar' === $clave ) : ?>
              <!-- QUÉ LLEVAR · «Siempre» (igual en todas) + «Para esta ruta» + aviso -->
              <div class="grid gap-6 lg:grid-cols-2 lg:items-start lg:gap-8">
                <?php get_template_part( 'components/lista-llevar', null, array(
                  'titulo' => __( 'Siempre', 'paso' ),
                  'intro'  => __( 'Lo básico en cualquier ruta, en cualquier época.', 'paso' ),
                  'lista'  => paso_llevar_siempre(),
                ) ); ?>
                <?php if ( $llevar ) get_template_part( 'components/lista-llevar', null, array(
                  'titulo' => __( 'Para esta ruta', 'paso' ),
                  'intro'  => get_field( 'ruta_llevar_intro', $ruta ),
                  'lista'  => $llevar,
                ) ); ?>
                <?php if ( $aviso ) : ?>
                  <div class="flex flex-col gap-2 rounded-xl border-l-3 border-amarillo bg-petroleum p-5 lg:col-span-2">
                    <p class="text-eyebrow text-amarillo"><?php esc_html_e( 'Aviso', 'paso' ); ?></p>
                    <p class="text-body text-piedra"><?php echo esc_html( $aviso ); ?></p>
                  </div>
                <?php endif; ?>
              </div>

            <?php else : ?>
              <!-- CÓMO LLEGAR · mapa del punto de encuentro (a la izquierda en escritorio) y datos de llegada -->
              <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:gap-8">
              <?php if ( $mapa_encuentro ) : ?>
                <div class="lg:flex-1"><?php get_template_part( 'components/mapa-encuentro', null, array( 'mapa' => $mapa_encuentro, 'lugar' => $encuentro ) ); ?></div>
              <?php endif; ?>
              <div class="lg:ml-auto lg:w-130">
                <dl>
                  <?php foreach ( $llegada as $nombre_dato => $texto ) : ?>
                    <div class="flex flex-col gap-1.5 border-t border-line py-4.5 first:border-t-0 first:pt-0 lg:first:border-t lg:first:pt-4.5">
                      <dt class="text-eyebrow text-muted"><?php echo esc_html( $nombre_dato ); ?></dt>
                      <dd class="text-body text-piedra"><?php echo esc_html( $texto ); ?></dd>
                    </div>
                  <?php endforeach; ?>
                </dl>
                <?php if ( $coordenadas ) : ?>
                  <?php get_template_part( 'components/boton', null, array(
                    'texto' => __( 'Abrir en Google Maps', 'paso' ),
                    'url'   => 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $coordenadas ),
                    'clase' => 'mt-6 w-full md:w-auto',
                  ) ); ?>
                  <p class="mt-3 text-caption text-muted"><?php esc_html_e( 'Se abre la ubicación exacta del parking en Google Maps (o en la app de mapas del móvil).', 'paso' ); ?></p>
                <?php endif; ?>
              </div>
              </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

</div>
