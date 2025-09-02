<?php

if (is_file(__DIR__.'/vendor/autoload_packages.php')) {
    require_once __DIR__.'/vendor/autoload_packages.php';
}

function tailpress(): TailPress\Framework\Theme
{
    return TailPress\Framework\Theme::instance()
        ->assets(fn($manager) => $manager
            ->withCompiler(new TailPress\Framework\Assets\ViteCompiler, fn($compiler) => $compiler
                ->registerAsset('resources/css/app.css')
                ->registerAsset('resources/js/app.js')
                ->editorStyleFile('resources/css/editor-style.css')
                ->ssl(verify: false)
            )
            ->enqueueAssets()
        )
        ->features(fn($manager) => $manager->add(TailPress\Framework\Features\MenuOptions::class))
        ->menus(fn($manager) => $manager
            ->add('primary', __('Primary Menu', 'tailpress'))
            ->add('footer',  __('Footer Menu',  'tailpress'))
        )
        ->themeSupport(fn($manager) => $manager->add([
            'title-tag',
            'custom-logo',
            'post-thumbnails',
            'align-wide',
            'wp-block-styles',
            'responsive-embeds',
            'html5' => [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            ]
        ]));
}

tailpress();

// Deregister WordPress jQuery and register custom version
add_action( 'wp_enqueue_scripts', function() {
    if ( is_admin() ) return;

    wp_deregister_script( 'jquery' );
    wp_register_script(
        'jquery',
        get_template_directory_uri() . '/resources/js/jquery-3.7.1.min.js',
        [], '3.7.1', false
    );
    wp_enqueue_script( 'jquery' );
}, 1 );

/* ------------------------------------------------------------------
 *  HELPER FUNCTIONS
 * ------------------------------------------------------------------ */

require get_template_directory() . '/template-parts/helpers/button_ui.php';
require get_template_directory() . '/template-parts/helpers/page_snippets.php';
require get_template_directory() . '/template-parts/helpers/global_snippets.php';


/* ------------------------------------------------------------------
 *  CORS CONFIGURATION
 * ------------------------------------------------------------------ */

// Simple CORS setup - allow all origins
add_action('init', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    
    // Handle preflight OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }
});

/* ------------------------------------------------------------------
 *  WP ADMIN
 * ------------------------------------------------------------------ */

// Disable Gutenberg for all post types
add_filter( 'use_block_editor_for_post_type', '__return_false' );

// Completely disable the WordPress Customizer
add_action('admin_menu', function () {
    // Remove "Customize" from Appearance menu
    remove_submenu_page('themes.php', 'customize.php');
}, 999);

add_action('admin_bar_menu', function ($wp_admin_bar) {
    // Remove "Customize" from the admin bar
    $wp_admin_bar->remove_node('customize');
}, 999);

add_action('admin_head', function () {
    // Remove "Customize" button from active theme card in Appearance > Themes
    echo '<style>
        .theme-actions .button.customize { display: none !important; }
    </style>';
});

// Block direct access to the Customizer
add_action('load-customize.php', function () {
    wp_die(
        __('The Customizer has been disabled for this theme.', 'your-textdomain'),
        '',
        array('back_link' => true)
    );
});


/* ------------------------------------------------------------------
 *  FOOTER MENU DYNAMIC STYLING
 * ------------------------------------------------------------------ */

add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'footer') {
        $hover = isset($args->accent_hover) ? $args->accent_hover : '';
        $atts['class'] = trim('w-fit cursor-pointer hover:underline-offset-4 hover:underline decoration-1 ' . $hover);
        
        // Add target="_blank" and rel="noopener noreferrer" for external links
        if (!empty($item->url)) {
            $site_url = get_site_url();
            if (strpos($item->url, $site_url) === false && strpos($item->url, 'http') === 0) {
                $atts['target'] = '_blank';
                $atts['rel'] = 'noopener noreferrer';
            }
        }
    }
    return $atts;
}, 10, 3);

/**
 * Convert the first heading in HTML content to H1 and add top margin to first section
 * Preserves all other heading levels as-is
 */
function convert_first_heading_to_h1( $html ) {
    // Use DOMDocument for reliable HTML parsing
    $dom = new DOMDocument();
    
    // Suppress warnings for malformed HTML
    libxml_use_internal_errors( true );
    
    // Set encoding to UTF-8
    $dom->encoding = 'UTF-8';
    
    // Load HTML with proper encoding declaration
    $html_with_encoding = '<?xml version="1.0" encoding="UTF-8"?>' . 
                          '<div>' . $html . '</div>';
    
    $dom->loadHTML( $html_with_encoding, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    
    // Clear any libxml errors
    libxml_clear_errors();
    
    $xpath = new DOMXPath( $dom );
    
    // Find the first heading (h1, h2, h3, h4, h5, h6)
    $headings = $xpath->query( '//h1 | //h2 | //h3 | //h4 | //h5 | //h6' );
    
    if ( $headings->length > 0 ) {
        $first_heading = $headings->item( 0 );
        
        // Only convert if it's not already an H1
        if ( $first_heading->tagName !== 'h1' ) {
            // Create new H1 element
            $new_h1 = $dom->createElement( 'h1' );
            
            // Copy all attributes from the original heading
            foreach ( $first_heading->attributes as $attribute ) {
                $new_h1->setAttribute( $attribute->name, $attribute->value );
            }
            
            // Copy all child nodes (text content, spans, etc.)
            foreach ( $first_heading->childNodes as $child ) {
                $new_h1->appendChild( $child->cloneNode( true ) );
            }
            
            // Replace the original heading with the new H1
            $first_heading->parentNode->replaceChild( $new_h1, $first_heading );
        }
    }
    
    // Find the first section and add mt-24 to its container
    $sections = $xpath->query( '//section' );
    if ( $sections->length > 0 ) {
        $first_section = $sections->item( 0 );
        
        // Look for the first container div within this section
        $containers = $xpath->query( './/div[contains(@class, "container")]', $first_section );
        if ( $containers->length > 0 ) {
            $first_container = $containers->item( 0 );
            $current_classes = $first_container->getAttribute( 'class' );
            
            // Only add mt-24 if it's not already there
            if ( strpos( $current_classes, 'mt-24' ) === false ) {
                $new_classes = trim( $current_classes . ' mt-24' );
                $first_container->setAttribute( 'class', $new_classes );
            }
        }
    }
    
    // Extract the content from the wrapper div
    $wrapper = $dom->getElementsByTagName( 'div' )->item( 0 );
    $processed_html = '';
    
    foreach ( $wrapper->childNodes as $child ) {
        $processed_html .= $dom->saveHTML( $child );
    }
    
    return $processed_html;
}

/* ------------------------------------------------------------------
 *  DYNAMIC FAVICON SUPPORT
 * ------------------------------------------------------------------ */

// Add favicon support to theme
add_action('wp_head', function() {
    // Add theme support for favicon
    add_theme_support('custom-favicon');
}, 1);

// Make template directory URI available to JavaScript
add_action('wp_head', function() {
    ?>
    <script>
        window.getTemplateDirectoryUri = function() {
            return '<?php echo get_template_directory_uri(); ?>';
        };
    </script>
    <?php
}, 2);