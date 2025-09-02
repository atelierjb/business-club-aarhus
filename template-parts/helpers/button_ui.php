<?php
/**
 * Render one ACF “Button” clone group.
 *
 * @param array $btn  The array returned by the Clone field.
 * @param int   $i    Index inside a repeater (used as fallback type).
 */
function mytheme_render_button( array $btn, int $i = 0 ): void {

	$label  = trim( $btn['label'] ?? '' );
	$link   = $btn['link'] ?? [];
	$url    = $link['url'] ?? '';
	$target = $link['target'] ?? '_self';

	// Type can be chosen in the UI; otherwise fall back to order.
	$type = $btn['type']
		?? ( $i === 0 ? 'primary' : 'secondary' );   // preserves old behaviour

	$classes = match ( $type ) {
		'secondary' => 'inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] animateOnView',
		default     => 'inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold bg-black text-white border border-black hover:bg-black/0 hover:text-black transition-transform duration-300 cursor-pointer tracking-[0.015em] animateOnView',
	};

	if ( $label && $url ) {
		echo '<a href="' . esc_url( $url ) . '" target="' . esc_attr( $target ) . '"><button class="' . esc_attr( $classes ) . '">'
		     . esc_html( $label )
		     . '</button>'
		     . '</a>';
	}
}