<?php
/**
 * Theme footer template.
 *
 * @package TailPress
 */
?>
        </main>

        <?php do_action('tailpress_content_end'); ?>
    </div>

    <?php do_action('tailpress_content_after'); ?>

<?php
// ── Footer theme derived from ACF "footer_color" (Page Options) ─────────
$post_id = function_exists('get_queried_object_id') ? get_queried_object_id() : 0;
$choice  = function_exists('get_field') ? get_field('footer_color', $post_id) : null;
// Fallback to JSON default if missing
$choice  = in_array($choice, ['green','blue','bordeaux'], true) ? $choice : 'green';

$map = [
    'green' => [
        'json'   => 'bcaa-yellow-lottie.json',
        'gif'    => 'bcaa-yellow.gif',
        'bg'     => 'bg-green',
        'accent' => 'yellow',
    ],
    'blue' => [
        'json'   => 'bcaa-violet-lottie.json',
        'gif'    => 'bcaa-violet.gif',
        'bg'     => 'bg-navy',
        'accent' => 'violet',
    ],
    'bordeaux' => [
        'json'   => 'bcaa-blue-lottie.json',
        'gif'    => 'bcaa-blue.gif',
        'bg'     => 'bg-bordeaux',
        'accent' => 'blue',
    ],
];

// Fetch once (Options Page stores in 'option')
$opt_phone    = function_exists('get_field') ? get_field('phone', 'option')    : '';
$opt_mail     = function_exists('get_field') ? get_field('mail', 'option')     : '';
$opt_linkedin = function_exists('get_field') ? get_field('linkedin', 'option') : '';

/** tiny helpers */
$tel_href = $opt_phone ? preg_replace('/[^\d+]/', '', $opt_phone) : '';
$mail_out = $opt_mail  ? antispambot($opt_mail) : '';


$cfg          = $map[$choice];
$bg_class     = $cfg['bg'];
$accent_text  = 'text-'   . $cfg['accent'];
$accent_border= 'border-' . $cfg['accent'];
$json_url     = get_template_directory_uri() . '/assets/images/' . $cfg['json'];
$gif_url      = get_template_directory_uri() . '/assets/images/' . $cfg['gif'];
?>

<footer class="py-16 md:py-24 lg:py-28 <?= esc_attr("$bg_class $accent_text"); ?>">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-3 lg:items-start">
            <div class="flex flex-row col-span-2 lg:col-span-1 lg:flex-col gap-6 pb-8 sm:pb-12 lg:pb-0 pr-0 lg:pr-12">
                <div class="overflow-hidden">
                    <a href="<?= esc_url( home_url() ); ?>" aria-label="Go back to homepage">
                        <div id="footer-lottie-container" class="w-full h-full aspect-square animateOnView">
                            <!-- Lottie animation will be loaded here -->
                        </div>
                        <img
                            id="footer-logo-fallback"
                            src="<?= esc_url( $gif_url ); ?>"
                            class="w-full h-full aspect-square animateOnView hidden"
                            alt="Business Club Aarhus logo"
                            style="display: none;"
                        />
                    </a>
                </div>
            </div>

            <div class="h-full col-span-2 lg:col-span-1 border-l-0 border-t-1 lg:border-t-0 lg:border-l <?= esc_attr($accent_border); ?> pl-0 lg:pl-12 pt-8 sm:pt-12 lg:pt-0">
                <?php if ( has_nav_menu('footer') ) : ?>
                    <?php
                    wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    // UL classes (grid on mobile, vertical on desktop)
                    'menu_class'     => 'list-none grid grid-cols-2 grid-flow-row-dense lg:flex lg:flex-col gap-2 text-xl/[1.25] font-bold pb-8 sm:pb-12 lg:pb-0 [&_a]:!no-underline',
                    'li_class'        => 'w-fit cursor-pointer hover:underline-offset-4 hover:underline decoration-1 animateOnView',
                    'fallback_cb'    => false,
                    // custom arg read by the filter below to add the right hover colour
                    'accent_hover'   => 'no-underline hover:text-' . $cfg['accent'], // e.g. hover:text-yellow|violet|blue
                    ]);
                    ?>
                <?php endif; ?>
            </div>


            <div class="h-full col-span-2 lg:col-span-1 border-l-0 border-t-1 lg:border-t-0 lg:border-l <?= esc_attr($accent_border); ?> pl-0 lg:pl-12 pt-8 sm:pt-12 lg:pt-0">
                <div class="flex flex-col gap-2 text-xl/[1.25] font-bold [&_a]:!no-underline">
                <?php if ( $opt_mail ) : ?>
                    <p class="font-bold [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                        <a href="mailto:<?= esc_attr($mail_out); ?>" class="hover:underline hover:underline-offset-4"><?= esc_html($mail_out); ?></a>
                    </p>
                <?php endif; ?>

                <?php if ( $opt_phone ) : ?>
                    <p class="font-bold [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                        <a href="tel:<?= esc_attr($tel_href); ?>" class="hover:underline hover:underline-offset-4"><?= esc_html($opt_phone); ?></a>
                    </p>
                <?php endif; ?>

                </div>
            </div>
        </div>

        <hr class="mt-8 sm:mt-12 <?= esc_attr($accent_border); ?> animateOnView">

        <div class="gap-2 lg:gap-4 flex justify-start mt-6 text-xs xs:text-sm font-semibold <?= esc_attr($accent_text); ?>">
            <p class="[&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                <a href="<?= esc_url( get_permalink( 168 ) ); ?>">
                    Terms & conditions
                </a>
            </p>
            <p class="[&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                <a href="<?= esc_url( get_permalink( 3 ) ); ?>">
                    Privacy policy
                </a>
            </p>
            <p class="[&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                <a href="<?= esc_url( get_permalink( 170 ) ); ?>">
                    Cookie policy
                </a>
            </p>
        </div>
    </div>
</footer>

</div>

<!-- Load Lottie library from CDN -->
<script src="
https://cdn.jsdelivr.net/npm/lottie-web@5.13.0/build/player/lottie.min.js
"></script>

<script>
    // Lottie animation loader for footer logo
    document.addEventListener('DOMContentLoaded', function() {
        const lottieContainer = document.getElementById('footer-lottie-container');
        const fallbackImage = document.getElementById('footer-logo-fallback');
        const jsonUrl = '<?= esc_js($json_url) ?>';
        
        // Improved Lottie initialization with better error handling
        function initLottie() {
            if (typeof lottie !== 'undefined' && lottieContainer) {
                try {
                    // Try to load Lottie animation
                    const animation = lottie.loadAnimation({
                        container: lottieContainer,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: jsonUrl
                    });
                    
                    
                } catch (error) {
                    console.warn('Lottie initialization error:', error);
                    showFallback();
                }
            } else {
                // Lottie library not available, show fallback immediately
                console.warn('Lottie library not available, showing GIF fallback');
                showFallback();
            }
        }
        
        function showFallback() {
            if (lottieContainer) {
                lottieContainer.style.display = 'none';
            }
            if (fallbackImage) {
                fallbackImage.style.display = 'block';
                fallbackImage.classList.remove('hidden');
            }
        }
        
        // Try to initialize immediately, or wait for Lottie to load
        if (typeof lottie !== 'undefined') {
            initLottie();
        } else {
            // Wait for CDN to load with better error handling
            let attempts = 0;
            const maxAttempts = 10;
            
            function tryInitLottie() {
                attempts++;
                if (typeof lottie !== 'undefined') {
                    initLottie();
                } else if (attempts < maxAttempts) {
                    setTimeout(tryInitLottie, 200);
                } else {
                    console.warn('Lottie library failed to load after multiple attempts');
                    showFallback();
                }
            }
            
            setTimeout(tryInitLottie, 100);
        }
    });
</script>

<?php wp_footer(); ?>
</body>
</html>