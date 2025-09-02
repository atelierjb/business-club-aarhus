<?php
/**
 * CLUB MANAGER / CODE SNIPPET  ▸  Slug: code_snippet
 * Outputs optional heading, raw code block, and optional CTAs.
 * BG colour comes from a select that already stores Tailwind classes.
 *
 * JSON fields used:
 * - background_color   (select → Tailwind class)
 * - heading            (text)
 * - heading_type       (radio: heading_primary | heading_secondary)
 * - code               (textarea, no formatting)
 * - ctas               (repeater → clone of UI—Button)
 */

$bg       = get_sub_field( 'background_color' );
$heading  = get_sub_field( 'heading' );
$type     = get_sub_field( 'heading_type' ) ?: 'heading_secondary';
$code     = get_sub_field( 'code' );
$ctas     = get_sub_field( 'ctas' ); // repeater

// Decide tag & classes based on heading_type
$is_primary = ( $type === 'heading_primary' );
$tag        = $is_primary ? 'h2' : 'h3';
$h_classes  = $is_primary
	? 'text-6xl/[1.1] sm:text-7xl/[1.2] lg:text-8xl/[1.2] font-bold tracking-[-0.015em] text-trim animateOnView mb-8 sm:mb-12'
	: 'text-[2.8125rem]/[1.2] font-bold md:text-5xl/[1.15] tracking-[-0.015em] text-trim animateOnView mb-8 sm:mb-12';

// If literally nothing is filled, skip rendering to keep DOM clean
if ( ! $heading && ! $code && ! $ctas ) {
	return;
}
?>
<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container grid grid-cols-1">

		<?php if ( $heading ) : ?>
			<<?= $tag; ?> class="<?= esc_attr( $h_classes ); ?>">
				<?= esc_html( $heading ); ?>
			</<?= $tag; ?>>
		<?php endif; ?>

		<?php if ( $code ) : ?>
			<div class="club-manager-snippet animateOnView" style="height: auto;">
				<?php echo $code; // always render on frontend ?>
			</div>
		<?php endif; ?>

        <hr class="mt-8 sm:mt-12 w-full border-black animateOnView">
		<?php if ( $ctas ) : ?>
			<div class="mt-8 sm:mt-12 flex flex-wrap gap-3 w-full">
				<?php foreach ( $ctas as $i => $row ) {
					if ( function_exists( 'mytheme_render_button' ) ) {
						mytheme_render_button( $row['buttons'], $i );
					}
				} ?>
			</div>
		<?php endif; ?>

	</div>
</section>