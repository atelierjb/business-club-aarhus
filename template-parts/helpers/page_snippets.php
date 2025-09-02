<?php
// 1) First: deregister core jQuery, register your own
add_action( 'wp_enqueue_scripts', function() {
    if ( is_admin() ) {
        return;
    }

    wp_deregister_script( 'jquery' );
    wp_register_script(
        'jquery',
        get_template_directory_uri() . '/resources/js/jquery-3.7.1.min.js',
        [],          // no deps
        '3.7.1',     // bump as needed
        false        // load in <head>; change to true if you want it in footer
    );
    wp_enqueue_script( 'jquery' );
}, 1 );

// 2) Then: scan your ACF repeater, pull out every external <script> and inline <script>
add_action( 'wp_enqueue_scripts', function() {
    if ( ! is_singular() ) {
        return;
    }

    $post_id = get_queried_object_id();
    if ( ! have_rows( 'page_snippets', $post_id ) ) {
        return;
    }

    while ( have_rows( 'page_snippets', $post_id ) ) {
        the_row();

        $block = trim( get_sub_field( 'code' ) );
        $in_footer = get_sub_field( 'location' ) === 'footer';

        if ( ! $block ) {
            continue;
        }

        // 2a) Extract all external scripts
        preg_match_all(
            '/<script\b[^>]*\bsrc=["\']([^"\']+)["\'][^>]*><\/script>/i',
            $block,
            $src_matches
        );
        $external_srcs = $src_matches[1] ?? [];

        // 2b) Extract all inline scripts
        preg_match_all(
            '/<script\b[^>]*>([\s\S]*?)<\/script>/i',
            $block,
            $inline_matches
        );
        $inlines = $inline_matches[1] ?? [];

        // 2c) Enqueue each external file, in order, depending on jQuery
        $last_handle = null;
        foreach ( $external_srcs as $src ) {
            $h = 'page-sn-' . substr( md5( $src ), 0, 8 );

            if ( ! wp_script_is( $h, 'registered' ) ) {
                wp_register_script(
                    $h,
                    $src,
                    [ 'jquery' ],
                    null,
                    $in_footer
                );
            }
            wp_enqueue_script( $h );
            $last_handle = $h;
        }

        // 2d) Dump each inline block immediately after the last external (or after jQuery)
        foreach ( $inlines as $js ) {
            $js = trim( $js );
            if ( $js === '' ) {
                continue;
            }
            $target = $last_handle ?: 'jquery';
            wp_add_inline_script( $target, $js );
        }
    }
}, 20 );
