<?php
/**
 * RUTA · GALERÍA · Figma: Section / Galería (mosaico de 6 fotos en escritorio, 4 hasta lg)
 * Escritorio: 2 cuadradas apiladas · 1 vertical alta · 1 horizontal ancha encima de 2 cuadradas.
 * Hasta lg: cuadrada + vertical alta / cuadrada / horizontal ancha (las fotos 5 y 6 solo se ven en el visor).
 * Etiqueta sobre la foto = su leyenda en Medios (p. ej. «Intento anterior · Nieve»).
 * Al pulsar, se abren a pantalla completa (<dialog>, assets/js/galeria.js); el visor recorre las 6.
 * $args['ruta']: WP_Post.
 */
$ruta  = $args['ruta'];
$fotos = array();
foreach ( range( 1, 6 ) as $i ) {
  $foto = get_field( 'ruta_galeria_' . $i, $ruta );
  if ( $foto ) $fotos[ $i ] = $foto;
}
if ( ! $fotos ) return;

// Sitio de cada posición en el mosaico (columnas y filas)
$sitios = array(
  1 => 'aspect-square lg:col-start-1 lg:row-start-1',
  2 => 'aspect-square lg:col-start-1 lg:row-start-2',
  3 => 'col-start-2 row-span-2 row-start-1 lg:row-span-2',
  4 => 'col-span-2 aspect-[342/178] lg:col-span-2 lg:col-start-3 lg:row-start-1',
  5 => 'hidden lg:block lg:col-start-3 lg:row-start-2',
  6 => 'hidden lg:block lg:col-start-4 lg:row-start-2',
);
// Ruta realizada: «Fotos del día · Así fue la ruta.» y, si el pie está vacío, «Fotos del grupo del 2 de mayo.»
$proxima = $args['proxima'];
$pie     = get_field( 'ruta_galeria_pie', $ruta );
$fecha   = get_field( 'ruta_fecha', $ruta, false );
if ( ! $pie && ! $proxima && $fecha ) $pie = sprintf( __( 'Fotos del grupo del %s.', 'paso' ), wp_date( 'j \d\e F', strtotime( $fecha ), wp_timezone() ) );
?>
<section class="bg-petroleum py-16 lg:py-30" aria-labelledby="galeria-titulo">
  <div class="contenedor">
    <p class="text-eyebrow text-amarillo"><?php echo esc_html( $proxima ? __( 'Galería', 'paso' ) : __( 'Fotos del día', 'paso' ) ); ?></p>
    <h2 id="galeria-titulo" class="mt-3 text-display-section-lg text-piedra lg:mt-5"><?php echo esc_html( $proxima ? __( 'Así es la ruta.', 'paso' ) : __( 'Así fue la ruta.', 'paso' ) ); ?></h2>

    <ul class="mt-8 grid grid-cols-2 gap-4 md:gap-7.5 lg:mt-12 lg:grid-cols-[300fr_300fr_310fr_310fr] lg:grid-rows-[19.375rem_19.375rem] lg:gap-5" data-galeria>
      <?php foreach ( $fotos as $i => $foto ) : $leyenda = wp_get_attachment_caption( $foto ); $alt = get_post_meta( $foto, '_wp_attachment_image_alt', true ); ?>
        <li class="relative <?php echo esc_attr( $sitios[ $i ] ); ?> lg:aspect-auto">
          <button type="button" class="group absolute inset-0 block overflow-hidden rounded-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amarillo"
            data-foto="<?php echo esc_url( wp_get_attachment_image_url( $foto, 'full' ) ); ?>" data-leyenda="<?php echo esc_attr( $leyenda ); ?>"
            aria-label="<?php echo esc_attr( $alt ? sprintf( __( 'Ver a pantalla completa: %s', 'paso' ), $alt ) : sprintf( __( 'Ver la foto %d a pantalla completa', 'paso' ), $i ) ); ?>">
            <?php echo wp_get_attachment_image( $foto, 'large', false, array(
              'class' => 'size-full object-cover transition-transform duration-500 ease-out motion-reduce:transition-none group-hover:scale-103',
              'sizes' => '(min-width: 1024px) 640px, 50vw',
            ) ); ?>
          </button>
          <?php if ( $leyenda ) : ?>
            <!-- En móvil la foto cuadrada mide ~163 px: etiqueta más pequeña (Label/Caption), que puede ocupar dos líneas; desde tablet, píldora Label/Eyebrow -->
            <p class="pointer-events-none absolute left-2 right-2 top-2 w-fit rounded-lg bg-petroleum/85 px-2 py-1 text-caption text-piedra md:left-4 md:right-4 md:top-4 md:rounded-full md:px-2.5 md:py-1.5 md:text-eyebrow"><?php echo esc_html( $leyenda ); ?></p>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <p class="mt-3 text-caption text-muted lg:mt-4"><?php echo esc_html( trim( $pie . ' ' . __( 'Pulsa una foto para verla a pantalla completa.', 'paso' ) ) ); ?></p>
  </div>

  <!-- VISOR a pantalla completa -->
  <dialog id="visor-galeria" class="m-0 size-full max-h-none max-w-none bg-petroleum/95 p-0 text-piedra backdrop:bg-petroleum/80" aria-label="<?php esc_attr_e( 'Fotos de la ruta', 'paso' ); ?>">
    <div class="flex size-full flex-col items-center justify-center gap-4 p-4 md:p-16">
      <img src="" alt="" class="max-h-[80svh] w-auto max-w-full rounded-xl object-contain" data-visor-imagen>
      <p class="min-h-5 text-caption text-piedra" data-visor-leyenda></p>
    </div>
    <button type="button" class="absolute right-4 top-4 flex size-11 items-center justify-center rounded-full bg-petroleum/85 text-display-title hover:text-amarillo focus-visible:outline-2 focus-visible:outline-amarillo" data-visor-cerrar aria-label="<?php esc_attr_e( 'Cerrar', 'paso' ); ?>"><span aria-hidden="true">×</span></button>
    <button type="button" class="absolute left-2 top-1/2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-petroleum/85 text-link hover:text-amarillo focus-visible:outline-2 focus-visible:outline-amarillo md:left-4" data-visor-anterior aria-label="<?php esc_attr_e( 'Foto anterior', 'paso' ); ?>"><span aria-hidden="true">←</span></button>
    <button type="button" class="absolute right-2 top-1/2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-petroleum/85 text-link hover:text-amarillo focus-visible:outline-2 focus-visible:outline-amarillo md:right-4" data-visor-siguiente aria-label="<?php esc_attr_e( 'Foto siguiente', 'paso' ); ?>"><span aria-hidden="true">→</span></button>
  </dialog>
</section>
