<?php
/**
 * Template Name: Medlemstilbud Oversigt
 */

get_header();
the_post();

$selected  = get_field( 'medlemstilbud' );   // may be empty
$intro     = get_field( 'paragraph' );

// Fallback: latest 6 offers
if ( empty( $selected ) ) {
	$selected = get_posts( [
		'post_type'      => 'medlemstilbud',
		'posts_per_page' => 6,
		'post_status'    => 'publish',
	] );
}

// Normalise to array of WP_Post objects
$offers = array_map( fn( $p ) => is_object( $p ) ? $p : get_post( $p ), $selected );
?>

<section class="py-16 md:py-24 lg:py-28">
	<div class="container mt-24">

		<?php /* --------------------------------------------------------
		     * TOP HEADER + INTRO
		     * ----------------------------------------------------- */ ?>
			<div class="grid grid-cols-1 gap-y-3 lg:gap-y-0 lg:grid-cols-2 lg:items-start mb-10 sm:mb-16">
                <h1 class="text-[2.8125rem]/[1.2] font-bold mb-6 md:text-5xl/[1.15] lg:pr-12 tracking-[-0.015em] text-trim animateOnView w-full lg:w-[80%]">
                    <?= esc_html( get_the_title() ); ?>
                </h1>

				<?php if ( $intro ) : ?>
					<div class="text-base lg:pl-12 text-trim animateOnView prose max-w-none w-full lg:w-[80%]">
						<?= apply_filters( 'the_content', $intro ); ?>
					</div>
				<?php endif; ?>
			</div>


		<?php /* --------------------------------------------------------
		     * OFFER GRID  (styled like feature rows)
		     * ----------------------------------------------------- */ ?>
		<?php if ( $offers ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start">

				<?php foreach ( $offers as $i => $offer ) :

					$src     = get_field( 'source',      $offer->ID );
					$link    = get_permalink( $offer->ID );
					$is_left = $i % 2 === 0;
					$wrapper = $is_left
						? 'h-full w-full border-r-0 border-t lg:border-t-0 lg:border-r border-black pr-0 lg:pr-12 py-8 sm:py-12 lg:py-0 [&_a]:!no-underline'
						: 'h-full w-full border-t lg:border-t-0 border-black pl-0 lg:pl-12 py-8 sm:py-12 lg:py-0 [&_a]:!no-underline';
				?>
					<div class="<?= $wrapper; ?>">
						<?php if ( $src ) : ?>
							<p class="text-xs mb-6 font-bold animateOnView"><?= esc_html( $src ); ?></p>
						<?php endif; ?>

						<a href="<?= esc_url( $link ); ?>" class="no-underline">
								<h2 class="text-3xl/[1.25] mb-6 font-bold animateOnView w-full lg:w-[80%]">
								<?= esc_html( get_the_title( $offer ) ); ?>
							</h2>
						</a>

						<a href="<?= esc_url( $link ); ?>" class="">
							<button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] animateOnView">
								<?php _e( 'Læs mere', 'tailpress' ); ?>
							</button>
						</a>
					</div>

					<?php
					$next_is_row_start = ( ( $i + 1 ) % 2 === 0 );
					$not_last          = ( $i + 1 !== count( $offers ) );
					if ( $next_is_row_start && $not_last ) : ?>
						<hr class="my-8 sm:my-12 border-black hidden lg:block col-span-2 animateOnView">
					<?php endif; ?>

				<?php endforeach; ?>

			</div>

			<hr class="mt-8 sm:mt-12 border-black animateOnView">
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
