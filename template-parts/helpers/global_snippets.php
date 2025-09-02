<?php
/**
 * Global Scripts (ACF Options)
 */

// Only run on frontend, not in admin
if (!is_admin()) {
	// Print the Global Scripts (ACF Options) into <head> site-wide.
	add_action( 'wp_enqueue_scripts', function() {
		if ( ! function_exists('get_field') ) return;
		$code = trim( get_field('code','option') );
		if ( $code ) {
			wp_add_inline_script( 'jquery', $code );
		}
	}, 20 );
	
}