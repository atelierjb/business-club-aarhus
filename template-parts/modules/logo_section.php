<?php
/**
 * LOGO SECTION  ▸  Slug: logo_section
 */

/* -------------------------------------------------------------------------
 * 1. ACF FIELDS
 * ---------------------------------------------------------------------- */
$bg        = get_sub_field( 'background_color' );
$heading   = get_sub_field( 'heading' );
$logos     = get_sub_field( 'logos' );          // repeater
$ctas      = get_sub_field( 'ctas' );           // repeater (max 1)

/* Bail if no content */
if ( ! $logos && ! $heading ) {
	return;
}

/* ------------------------------------------------------------------
 * 2. MARKUP
 * ------------------------------------------------------------------ */
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container grid grid-cols-1 lg:grid-cols-2">

		<?php if ( $heading ) : ?>
			<h2 class="col-span-1 mb-8 sm:mb-12 text-[2.8125rem]/[1.2] font-bold md:mb-6 md:text-5xl/[1.15] tracking-[-0.015em] w-full lg:w-[80%] animateOnView">
				<?= esc_html( $heading ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $logos ) : ?>
			<div class="col-span-1 lg:col-span-2 grid grid-cols-3 lg:grid-cols-6 gap-x-6 gap-y-3 lg:gap-y-0">
				<?php foreach ( $logos as $index => $row ) :
					$img_id = $row['logo'] ?? null;
					if ( ! $img_id ) { continue; } 
					
					// Determine visibility classes
					$is_mobile_visible = $index < 12; // Show first 12 on mobile
					$mobile_class = $is_mobile_visible ? 'block' : 'hidden';
					$desktop_class = 'lg:block'; // Show all on desktop
					$visibility_class = $mobile_class . ' ' . $desktop_class;
					?>

					<div class="flex items-center justify-center <?= $visibility_class; ?>">
						<?= wp_get_attachment_image(
							$img_id,
							'full',
							false,
							[
								'class' => 'w-24 lg:w-32 aspect-square mix-blend-multiply object-contain animateOnView',
								'alt'   => esc_attr( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ),
							]
						); ?>
					</div>

					<?php
					// HR after every desktop row of 6 (except after last)
					$next_starts_row = ( ( $index + 1 ) % 6 === 0 );
					$not_last        = ( $index + 1 !== count( $logos ) );
					if ( $next_starts_row && $not_last ) : ?>
						<hr class="my-3 sm:my-6 border-black hidden lg:block col-span-6 animateOnView">
					<?php endif; ?>

				<?php endforeach; ?>
			</div>
		<?php endif; ?>
        
        <?php if ( $logos || $ctas ) : ?>
            <hr class="col-span-1 lg:col-span-2 mt-8 sm:mt-12 lg:mt-6 border-black animateOnView">
        <?php endif; ?>

		<?php if ( $ctas ) : ?>
			<div class="mt-8 sm:mt-12">
				<?php mytheme_render_button( $ctas[0]['buttons'], 0 ); ?>
			</div>
		<?php endif; ?>


	</div>
</section>