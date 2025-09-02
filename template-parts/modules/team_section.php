<?php
/**
 * TEAM SECTION  ▸  Slug: team_section
 * Tailwind only – no extra CSS.
 *
 * Fields in ACF JSON (layout “team_section”):
 *   heading  : text
 *   team_members : repeater
 *     └ image (ID)
 *     └ info  (group)
 *         ├ name
 *         ├ position
 *         └ company
 *   background_color : select (value = Tailwind class e.g. bg-blue)
 */

/* 1 · GET FIELDS ------------------------------------------------ */
$bg           = get_sub_field( 'background_color' );
$heading      = get_sub_field( 'heading' );
$members      = get_sub_field( 'team_members' );          // repeater

/* Bail early if nothing to show */
if ( ! $heading && ! $members ) {
	return;
}

/* ------------------------------------------------------------------
 * 2. MARKUP
 * ------------------------------------------------------------------ */
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
	<div class="container">

		<?php /* ----------------------------------------------------
		     * SECTION HEADING
		     * -------------------------------------------------- */ ?>
		<?php if ( $heading ) : ?>
			<h2 class="mb-10 sm:mb-16 lg:mb-20 text-[2.8125rem]/[1.2] font-bold md:text-5xl/[1.15] tracking-[-0.015em] w-full lg:w-[45%] animateOnView">
				<?= esc_html( $heading ); ?>
			</h2>
		<?php endif; ?>


		<?php /* ----------------------------------------------------
		     * TEAM GRID
		     *  - 2 cols mobile  • 4 cols desktop
		     * -------------------------------------------------- */ ?>
		<?php if ( $members ) : ?>
			<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-y-0 lg:gap-x-12">

				<?php foreach ( $members as $index => $row ) :

					$img_id  = $row['image'] ?? null;
					$info    = $row['info']  ?? [];
					$name    = $info['name']     ?? '';
					$pos     = $info['position'] ?? '';
					$company = $info['company']  ?? '';
					?>

					<div class="flex flex-col gap-3">

						<?php if ( $company ) : ?>
							<p class="text-xs font-bold animateOnView"><?= esc_html( $company ); ?></p>
						<?php endif; ?>

						<?php if ( $img_id ) : ?>
							<div class="overflow-hidden animateOnView">
								<?= wp_get_attachment_image(
									$img_id,
									'full',
									false,
									[
										'class' => 'w-full aspect-[4/5] object-cover',
										'alt'   => esc_attr( $name ?: '' ),
									]
								); ?>
							</div>
						<?php endif; ?>

						<div class="flex flex-col text-xs/[1.5] md:text-sm/[1.5]">
							<?php if ( $name ) : ?>
								<p class="font-bold animateOnView"><?= esc_html( $name ); ?></p>
							<?php endif; ?>
							<?php if ( $pos ) : ?>
								<p class="animateOnView"><?= esc_html( $pos ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<?php
					/* Horizontal divider after each desktop row of 4 */
					$next_row_starts = ( ( $index + 1 ) % 4 === 0 );
					$not_last        = ( $index + 1 !== count( $members ) );
					if ( $next_row_starts && $not_last ) : ?>
						<hr class="my-6 border-black hidden lg:block col-span-4 animateOnView">
					<?php endif; ?>

				<?php endforeach; ?>

			</div>
		<?php endif; ?>


		<?php /* Bottom divider only if members were output */ ?>
		<?php if ( $members ) : ?>
			<hr class="mt-8 sm:mt-12 border-black animateOnView">
		<?php endif; ?>

	</div>
</section>