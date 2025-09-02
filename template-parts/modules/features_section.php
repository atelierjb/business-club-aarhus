<?php
/**
 * FEATURES SECTION  ▸  Slug: features_section
 * Tailwind only – no extra CSS.
 */

/* -------------------------------------------------------------------------
 * 1. ACF FIELDS
 * ---------------------------------------------------------------------- */
$bg          = get_sub_field( 'background_color' );
$heading     = get_sub_field( 'heading' );
$intro       = get_sub_field( 'paragraph' );             // intro copy
$features    = get_sub_field( 'content' );              // repeater

/* Bail if no content */
if ( ! $features && ! $heading && ! $intro ) {
	return;
}

/* ------------------------------------------------------------------
 * 2. MARKUP
 * ------------------------------------------------------------------ */
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container">

		<?php /* ----------------------------------------------------
		     * TOP HEADER + INTRO
		     * -------------------------------------------------- */ ?>
		<?php if ( $heading || $intro ) : ?>
			<div class="grid grid-cols-1 gap-y-3 lg:gap-y-0 lg:grid-cols-2 lg:items-start mb-10 sm:mb-16">
				<?php if ( $heading ) : ?>
					<h2 class="text-[2.8125rem]/[1.2] font-bold mb-6 md:text-5xl/[1.15] lg:pr-12 tracking-[-0.015em] text-trim animateOnView w-full lg:w-[80%]">
						<?= esc_html( $heading ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $intro ) : ?>
					<p class="text-base lg:pl-12 text-trim animateOnView w-full lg:w-[80%]">
						<?= wp_kses_post( $intro ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>


		<?php /* ----------------------------------------------------
		     * FEATURE GRID
		     * -------------------------------------------------- */ ?>
		<?php if ( $features ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start">

				<?php foreach ( $features as $i => $row ) :

					$tag   = $row['tag']      ?? '';
					$h3    = $row['heading']  ?? '';
					$text  = $row['paragraph']?? '';
					$ctas  = $row['ctas']     ?? [];

					/* column + border classes alternate L/R */
					$is_left  = $i % 2 === 0;
					$wrapper  = $is_left
						? 'h-full w-full border-r-0 border-b lg:border-b-0 lg:border-r border-black pr-0 lg:pr-12 py-8 sm:py-12 lg:py-0'
						: 'h-full w-full border-b lg:border-b-0 border-black pl-0 lg:pl-12 py-8 sm:py-12 lg:py-0';
					?>

					<div class="<?= $wrapper; ?>">
						<?php if ( $tag ) : ?>
							<p class="text-xs mb-4 sm:mb-6 font-bold animateOnView w-full lg:w-[80%]"><?= esc_html( $tag ); ?></p>
						<?php endif; ?>

						<?php if ( $h3 ) : ?>
							<h3 class="text-[1.375rem]/[1.25] sm:text-3xl/[1.25] mb-6 font-bold animateOnView w-full lg:w-[80%]"><?= esc_html( $h3 ); ?></h3>
						<?php endif; ?>

						<?php if ( $text ) : ?>
							<p class="text-base/[1.5] animateOnView w-full lg:w-[80%]"><?= wp_kses_post( $text ); ?></p>
						<?php endif; ?>

						<?php if ( $ctas ) : ?>
							<div class="mt-6 flex flex-wrap gap-3 md:mt-8">
								<?php foreach ( $ctas as $k => $cta_row ) {
									mytheme_render_button( $cta_row['buttons'], $k );
								} ?>
							</div>
						<?php endif; ?>
					</div>

					<?php
					/* Divider after each desktop row (i.e. after every 2 items) */
					$next_is_row_start = ( ( $i + 1 ) % 2 === 0 );
					$not_last          = ( $i + 1 !== count( $features ) );
					if ( $next_is_row_start && $not_last ) : ?>
						<hr class="my-8 sm:my-12 border-black hidden lg:block col-span-2 animateOnView">
					<?php endif; ?>

				<?php endforeach; ?>

			</div>
		<?php endif; ?>


		<?php /* bottom divider only if content existed */ ?>
		<?php if ( $features ) : ?>
			<hr class="hidden lg:block mt-8 sm:mt-12 border-black animateOnView">
		<?php endif; ?>

	</div>
</section>