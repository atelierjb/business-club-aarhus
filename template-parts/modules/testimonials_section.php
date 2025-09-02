<?php
/**
 * TESTIMONIALS ▸ Slug: testimonials_section
 * Data from ACF relationship (objects) + interactive slider (JS).
 */

$bg_class = get_sub_field('background_color') ?: 'bg-white';
$rels     = get_sub_field('testimonials'); // relationship -> objects
if ( empty($rels) || ! is_array($rels) ) { return; }

$count = count($rels);
$uid   = 'tst-' . ( function_exists('get_row_index') ? get_row_index() : wp_unique_id() );

// Pre-collect testimonial data for simpler templates
$items = [];
foreach ( $rels as $p ) {
	$id    = is_object($p) ? $p->ID : (int) $p;
	$quote = get_field('quote', $id);
	$afs   = get_field('afsender', $id) ?: [];
	$name  = $afs['name']     ?? '';
	$pos   = $afs['position'] ?? '';
	$comp  = $afs['company']  ?? '';
	$img   = $afs['image']    ?? null; // array per JSON

	$items[] = [
		'quote' => $quote,
		'name'  => $name,
		'pos'   => $pos,
		'comp'  => $comp,
		'img'   => $img,
		'id'    => $id,
	];
}
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr($bg_class); ?>">
	<div id="<?= esc_attr($uid); ?>" class="container js-testimonials" tabindex="0" aria-roledescription="carousel">
		<?php foreach ( $items as $i => $t ) : ?>
			<?php
			$is_active = $i === 0;
			$img_id    = is_array($t['img']) ? ($t['img']['ID'] ?? 0) : 0;
			$alt       = $t['name'] ?: get_the_title($t['id']);
			?>
			<div class="tst-slide <?= $is_active ? '' : 'hidden'; ?>" data-index="<?= esc_attr($i); ?>" role="group" aria-label="<?= ($i+1) . ' / ' . $count; ?>">
				<div class="grid grid-cols-2 lg:grid-cols-3 lg:items-start">
					<!-- LEFT COLUMN -->
					<div class="col-span-2 lg:col-span-1 flex flex-row lg:flex-col gap-6 pb-8 sm:pb-12 lg:pb-0 pr-0 lg:pr-12">
						<div class="flex flex-col font-bold order-2 lg:order-1">
							<?php if ( $t['comp'] ) : ?>
								<p class="text-xs mb-2 animateOnView"><?= esc_html($t['comp']); ?></p>
							<?php endif; ?>
							<?php if ( $t['name'] ) : ?>
								<p class="text-lg/[1.25] md:text-2xl/[1.25] animateOnView"><?= esc_html($t['name']); ?><?= $t['pos'] ? ',' : ''; ?></p>
							<?php endif; ?>
							<?php if ( $t['pos'] ) : ?>
								<p class="text-lg/[1.25] md:text-2xl/[1.25] font-normal animateOnView"><?= esc_html($t['pos']); ?></p>
							<?php endif; ?>
						</div>

						<div class="w-32 overflow-hidden order-1 lg:order-2 animateOnView">
							<?php if ( $img_id ) : ?>
								<?= wp_get_attachment_image( $img_id, 'medium', false, [
									'class' => 'aspect-[4/5] object-cover',
									'alt'   => esc_attr($alt)
								] ); ?>
							<?php else : ?>
								<img src="https://d22po4pjz3o32e.cloudfront.net/placeholder-image.svg" class="w-full aspect-[4/5] object-cover" alt="">
							<?php endif; ?>
						</div>

						<!-- DESKTOP NAV -->
						<div class="gap-2 hidden lg:flex order-3 js-nav animateOnView">
							<button class="cursor-pointer js-prev" aria-label="Previous">
								<svg width="15" height="20" viewBox="0 0 20 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.3128 0.869141H19.6289C17.8162 8.22229 10.3614 13.1762 4.90731 14.3638V14.6365C10.3614 15.824 17.8162 20.7779 19.6289 28.1312H14.3128C12.6655 22.6609 7.93852 18.0818 0.000165939 16.1359V12.8644C7.93852 10.9184 12.6655 6.33936 14.3128 0.869141Z" fill="black"/></svg>
							</button>
							<p class="text-3xl/[1.25] js-counter"><?= ($i+1) . '/' . $count; ?></p>
							<button class="cursor-pointer js-next" aria-label="Next">
								<svg width="15" height="20" viewBox="0 0 21 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.94505 0.869141H0.628906C2.44161 8.22229 9.89643 13.1762 15.3505 14.3638V14.6365C9.89643 15.824 2.44161 20.7779 0.628906 28.1312H5.94505C7.59236 22.6609 12.3193 18.0818 20.2576 16.1359V12.8644C12.3193 10.9184 7.59236 6.33936 5.94505 0.869141Z" fill="black"/></svg>
							</button>
						</div>
					</div>

					<!-- RIGHT COLUMN -->
					<div class="h-full border-l-0 border-t-1 lg:border-t-0 lg:border-l border-black pl-0 lg:pl-12 pt-8 sm:pt-12 lg:pt-0 col-span-2 animateOnView">
						<svg width="48" height="39" viewBox="0 0 48 39" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 24.2608V38.1911H15.7937V24.9994H6.96349C6.96349 15.973 11.6129 8.31918 21.857 7.42125V0.80957C7.6603 1.49168 0 11.8024 0 24.2608Z" fill="black"/><path d="M47.9998 7.42125V0.80957C33.8024 1.49168 26.1421 11.8024 26.1421 24.2608V38.1911H41.9357V24.9994H33.1056C33.1056 15.973 37.7557 8.31917 47.9998 7.42125Z" fill="black"/></svg>
						<?php if ( $t['quote'] ) : ?>
							<p class="text-xl/[1.4] lg:text-3xl/[1.4] text-balance mt-6"><?= wp_kses_post($t['quote']); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<hr class="mt-8 sm:mt-12 border-black">

				<!-- MOBILE NAV -->
				<div class="gap-2 flex lg:hidden mt-8 sm:mt-12 js-nav animateOnView">
					<button class="cursor-pointer js-prev" aria-label="Previous">
						<svg width="15" height="20" viewBox="0 0 20 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.3128 0.869141H19.6289C17.8162 8.22229 10.3614 13.1762 4.90731 14.3638V14.6365C10.3614 15.824 17.8162 20.7779 19.6289 28.1312H14.3128C12.6655 22.6609 7.93852 18.0818 0.000165939 16.1359V12.8644C7.93852 10.9184 12.6655 6.33936 14.3128 0.869141Z" fill="black"/></svg>
					</button>
					<p class="text-3xl/[1.25] js-counter"><?= ($i+1) . '/' . $count; ?></p>
					<button class="cursor-pointer js-next" aria-label="Next">
						<svg width="15" height="20" viewBox="0 0 21 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.94505 0.869141H0.628906C2.44161 8.22229 9.89643 13.1762 15.3505 14.3638V14.6365C9.89643 15.824 2.44161 20.7779 0.628906 28.1312H5.94505C7.59236 22.6609 12.3193 18.0818 20.2576 16.1359V12.8644C12.3193 10.9184 7.59236 6.33936 5.94505 0.869141Z" fill="black"/></svg>
					</button>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>