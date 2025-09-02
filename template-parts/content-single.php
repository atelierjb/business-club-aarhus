<?php
/**
 * Singular post/page template part for TailPress.
 *
 * Shows the ACF "components" page-builder when present;
 * otherwise falls back to classic header, featured image
 * and the_content() markup (with an ACF WYSIWYG override).
 *
 * Path: template-parts/content-single.php
 */

$show_default_header = true;
$has_builder = have_rows( 'components' );

if ( $has_builder ) {
    // Start output buffering to capture the rendered components
    ob_start();
    
    // Render the page builder components
    get_template_part( 'template-parts/page-builder' );
    
    // Get the buffered content
    $components_html = ob_get_clean();
    
    // Process the HTML to convert first heading to H1 and add top margin
    $components_html = convert_first_heading_to_h1( $components_html );
    
    // Output the processed HTML
    echo $components_html;
    
    // Don't show default header since we now have a processed H1
    $show_default_header = false;
}

if ( $show_default_header ) : ?>
    <header class="mx-auto flex max-w-5xl flex-col text-center">
        <h1 class="mt-6 text-5xl font-medium tracking-tight [text-wrap:balance] sm:text-6xl opacity-0">
            <?php the_title(); ?>
        </h1>

        <?php if ( ! is_page() ) : ?>
            <time datetime="<?php echo get_the_date( 'c' ); ?>" itemprop="datePublished" class="order-first text-sm text-zinc-950">
                <?php echo get_the_date(); ?>
            </time>
            <p class="mt-6 text-sm font-semibold text-zinc-950">
                <?php printf( esc_html__( 'by %s', 'tailpress' ), get_the_author() ); ?>
            </p>
        <?php endif; ?>
    </header>
<?php endif; ?>

<?php /* ------------------------------------------------------------ *
 *  FEATURED IMAGE — skip if hero_section already supplies one.
 * ------------------------------------------------------------ */ ?>
<?php if ( ! $has_builder && has_post_thumbnail() ) : ?>
    <div class="mt-10 sm:mt-20 mx-auto max-w-4xl rounded-4xl bg-light overflow-hidden">
        <?php the_post_thumbnail( 'large', [ 'class' => 'aspect-16/10 w-full object-cover' ] ); ?>
    </div>
<?php endif; ?>

<?php /* ------------------------------------------------------------ *
 *  MAIN CONTENT AREA
 * ------------------------------------------------------------ */ ?>
<?php if ( ! $has_builder ) : ?>
    <section class="py-16 md:py-24 lg:py-28">
        <div class="container">
            <?php
            // If this page has the ACF WYSIWYG ("Udvidet tekstfelt" → name: content), render it:
            $acf_wysiwyg = function_exists( 'get_field' ) ? get_field( 'content' ) : '';

            if ( ! empty( $acf_wysiwyg ) ) {
                // Run through 'the_content' filters so shortcodes, embeds, oEmbed, etc. work.
                echo apply_filters( 'the_content', $acf_wysiwyg );
            } else {
                the_content();
            }
            ?>
        </div>
    </section>
<?php endif; ?>