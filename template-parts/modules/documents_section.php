<?php
/**
 * DOCUMENTS SECTION ▸ Slug: documents_section
 * Tailwind‑only – no extra CSS.
 */

/* ──────────────────────────────────────────────────────────
 * 1. ACF FIELDS
 * ------------------------------------------------------ */
$bg        = get_sub_field( 'background_color' );
$heading   = get_sub_field( 'heading' );
$documents = get_sub_field( 'document' ); // repeater rows (min 2 / max 4)

/* Bail early if nothing to show */
if ( ! $heading && ! $documents ) {
	return;
}

/* ──────────────────────────────────────────────────────────
 * 2. MARKUP
 * ------------------------------------------------------ */
if ( $heading || $documents ) : ?>
<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="mb-10 sm:mb-16 lg:mb-20 text-[2.8125rem]/[1.2] font-bold md:text-5xl/[1.15] tracking-[-0.015em] w-full lg:w-[45%] animateOnView">
                <?= esc_html( $heading ); ?>
            </h2>
            <hr class="mb-8 sm:mb-12 border-black hidden lg:block animateOnView">
		<?php endif; ?>

		<?php if ( $documents ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start">
				<?php
				foreach ( $documents as $index => $row ) :
					$doc_heading = $row['heading']   ?? '';
					$doc_body    = $row['paragraph'] ?? '';
					$file_url    = $row['file']      ?? '';

					/* Border logic: left col gets right border, right col gets left. */
					$is_left = $index % 2 === 0;
					$box_classes = $is_left
						? 'border-black pr-0 lg:pr-12'
						: 'border-black lg:border-l pl-0 lg:pl-12';

					/* Insert horizontal rule after each pair on large screens. */
					$needs_hr = ( $index % 2 === 1 ) && ( $index + 1 < count( $documents ) );
				?>
				<div class="py-8 sm:py-12 lg:py-0 border-t lg:border-t-0 <?= $box_classes; ?>">
					<?php if ( $doc_heading ) : ?>
						<h3 class="mb-2 text-[1.375rem]/[1.25] sm:text-3xl/[1.25] font-bold animateOnView w-full lg:w-[80%]"><?= esc_html( $doc_heading ); ?></h3>
					<?php endif; ?>

					<?php if ( $doc_body ) : ?>
						<p class="text-base/[1.5] animateOnView w-full lg:w-[80%]"><?= wp_kses_post( $doc_body ); ?></p>
					<?php endif; ?>

					<?php if ( $file_url ) : ?>
						<a href="<?= esc_url( $file_url ); ?>" title="Download" download>
                            <button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] animateOnView mt-4 sm:mt-6">
                                Download
                            </button>
						</a>
					<?php endif; ?>
				</div>

				<?php if ( $needs_hr ) : ?>
					<hr class="my-8 sm:my-12 border-black hidden lg:block col-span-2 animateOnView">
				<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<hr class="mt-8 sm:mt-12 border-black animateOnView">
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>