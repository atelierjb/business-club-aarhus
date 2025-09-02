<?php
/**
 * HERO SECTION  ▸  Slug: hero_section
 * Tailwind only – no extra CSS.
 */

/* -------------------------------------------------------------------------
 * 1. ACF FIELDS
 * ---------------------------------------------------------------------- */
$bg       = get_sub_field( 'background_color' );
$h1       = get_sub_field( 'heading_primary' );
$intro    = get_sub_field( 'subheading' );
$image_id = get_sub_field( 'image' );
$h2       = get_sub_field( 'heading_secondary' );
$body     = get_sub_field( 'paragraph' );
$ctas     = get_sub_field( 'ctas' );            // repeater (max 2)

/* Bail if no content */
if ( ! $h1 && ! $intro && ! $image_id && ! $h2 && ! $body && ! $ctas ) {
	return;
}

/* ------------------------------------------------------------------
 * 2. MARKUP
 * ------------------------------------------------------------------ */
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container">

		<?php if ( $h1 || $intro ) : ?>
			<div class="flex flex-col gap-4 sm:gap-6 <?= $image_id ? 'mb-8 sm:mb-12' : ''; ?> w-full lg:w-[80%]">
				<?php if ( $h1 ) : ?>
					<h1 class="text-6xl/[1.1] sm:text-7xl/[1.2] lg:text-8xl/[1] font-bold tracking-[-0.015em] text-trim hyphens-auto sm:hyphens-none">
						<?= esc_html( $h1 ); ?>
					</h1>
				<?php endif; ?>

				<?php if ( $intro ) : ?>
					<p class="my-4 sm:my-6 text-xl/[1.4] lg:text-3xl/[1.4] hyphens-auto">
						<?= nl2br( esc_html( $intro ) ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $image_id ) : ?>
			<div class="order-1 lg:order-2 overflow-hidden">
				<?= wp_get_attachment_image(
					$image_id,
					'full',
					false,
					[
						'class' => 'w-full aspect-[6/5] sm:aspect-[16/9] object-cover',
						'alt'   => esc_attr( $h1 ?: '' ),
					]
				); ?>
			</div>
		<?php endif; ?>

		<?php if ( $h2 || $body || $ctas ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start mt-8 sm:mt-12">

				<?php if ( $h2 ) : ?>
					<div class="h-full border-r-0 border-t-1 lg:border-t-0 lg:border-r border-black pr-0 lg:pr-12 pt-8 sm:pt-12 lg:pt-0">
						<h2 class="mb-4 text-[2.8125rem]/[1.2] font-bold md:mb-6 md:text-5xl/[1.15] tracking-[-0.015em] w-full lg:w-[80%] animateOnView">
							<?= esc_html( $h2 ); ?>
						</h2>
					</div>
				<?php endif; ?>

				<?php if ( $body || $ctas ) : ?>
					<div class="pl-0 lg:pl-12">
						<?php if ( $body ) : ?>
							<p class="text-base/[1.5] w-full lg:w-[80%] animateOnView">
								<?= wp_kses_post( $body ); ?>
							</p>
						<?php endif; ?>

						<?php if ( $ctas ) : ?>
                            <div class="mt-6 flex flex-wrap gap-3 md:mt-8">
                                <?php foreach ( $ctas as $i => $row ) {
                                    mytheme_render_button( $row['buttons'], $i );
                                } ?>
                            </div>
                        <?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<?php if ( $image_id || $h1 || $intro || $h2 || $body || $ctas ) : ?>
			<hr class="mt-8 sm:mt-12 border-black animateOnView">
		<?php endif; ?>

	</div>
</section>