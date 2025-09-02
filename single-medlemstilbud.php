<?php
/**
 * Single template for CPT “Medlemstilbud”
 * Layout mirrors the two-column contact_section module.
 */

get_header();

while ( have_posts() ) : the_post();

	// ACF fields on the offer post
	$src  = get_field( 'source' );        // acts like “tag/company”
	$desc = get_field( 'description' );   // main body copy (WYSIWYG)
	$info = get_field( 'information' );   // link / document
	$c    = get_field( 'contact' );       // group with name, title, …

	// Optional featured image (used on the right, like contact image)
	$img_id = has_post_thumbnail() ? get_post_thumbnail_id() : 0;
?>
<section class="py-16 md:py-24 lg:py-28 bg-white">
	<div class="container mt-24">
		<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start gap-y-8 sm:gap-y-12">

			<?php /* ----------------------------------------------------
			     * IMAGE COLUMN (right on desktop)
			     * -------------------------------------------------- */ ?>
			<?php if ( $img_id ) : ?>
				<div class="order-1 lg:order-2">

        <div class=" lg:hidden flex items-center gap-4 mb-8 lg:mb-12">
        <a href="<?= esc_url( get_permalink( 108 ) ); ?>" class="w-fit [&_a]:!no-underline">
        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.373 8.75L9.06925 14.4462L8 15.5L0.5 8L8 0.5L9.06925 1.55375L3.373 7.25H15.5V8.75H3.373Z" fill="#1F1F1F"/>
        </svg>
        </a>
        <p class="font-bold [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-2 animateOnView">
              <a href="<?= esc_url( get_permalink( 108 ) ); ?>">
                  Tilbage til oversigten
              </a>
          </p>
      </div>

          <div class="overflow-hidden lg:ml-12 animateOnView">
            <?= wp_get_attachment_image(
              $img_id,
              'full',
              false,
              [
                'class' => 'w-full h-full aspect-[6/5] object-cover',
                'alt'   => esc_attr( get_the_title() ),
              ]
            ); ?>
          </div>

          <?php /* ---------------- CONTACT BLOCK ---------------- */ ?>
				  <?php if ( $c && ( $c['name'] || $c['email'] ) ) : ?>
					<div class="hidden lg:block mt-8 lg:mt-12 lg:ml-12 border-t border-black py-6">
						<h2 class="text-3xl/[1.25] mb-6 font-bold">
							Kontakt person
						</h2>
						<?php if ( $c['name'] )    : ?><p><?= esc_html( $c['name'] ); ?></p><?php endif; ?>
						<?php if ( $c['title'] )   : ?><p><?= esc_html( $c['title'] ); ?></p><?php endif; ?>
						<?php if ( $c['company'] ) : ?><p class="mb-4"><?= esc_html( $c['company'] ); ?></p><?php endif; ?>
						<?php if ( $c['phone'] )   : ?>
							<p>Telefon: <a href="tel:<?= esc_attr( preg_replace('/\D+/', '', $c['phone'] ) ); ?>"
							       class="underline underline-offset-4 decoration-1">
								<?= esc_html( $c['phone'] ); ?>
							</a></p>
						<?php endif; ?>
						<?php if ( $c['email'] )   : ?>
							<p>Email: <a href="mailto:<?= antispambot( $c['email'] ); ?>"
							       class="underline underline-offset-4 decoration-1">
								<?= antispambot( $c['email'] ); ?>
							</a></p>
						<?php endif; ?>
					
				  <?php endif; ?>

				  <?php /* ---------------- EXTRA INFO LINK/DOC --------- */ ?>
				  <?php if ( $info ) :
					if ( $info['typeof'] === 'link' && $info['link'] ) : ?>
						<a href="<?= esc_url( $info['link'] ); ?>"
						   target="_blank" rel="noopener noreferrer">
							  <button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] mt-8">
								Læs mere
							</button>
						</a>
					<?php elseif ( $info['typeof'] === 'document' && $info['dokument'] ) : ?>
						<a href="<?= esc_url( $info['dokument'] ); ?>"
						   target="_blank" rel="noopener noreferrer"
						   download>
                <button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] mt-8">
							Download invitation
						</button>
						</a>
					<?php endif;
				  endif; ?>
          </div>
				</div>
			<?php endif; ?>

			<?php /* ----------------------------------------------------
			     * TEXT COLUMN (left on desktop)
			     * -------------------------------------------------- */ ?>
			<div class="order-2 lg:order-1 border-t lg:border-t-0 lg:border-r border-black pt-8 sm:pt-12 lg:pt-0 lg:pr-12 h-full">

      <div class="hidden lg:flex items-center gap-4 mb-8 lg:mb-12">
        <a href="<?= esc_url( get_permalink( 108 ) ); ?>" class="w-fit [&_a]:!no-underline">
        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.373 8.75L9.06925 14.4462L8 15.5L0.5 8L8 0.5L9.06925 1.55375L3.373 7.25H15.5V8.75H3.373Z" fill="#1F1F1F"/>
        </svg>
        </a>
        <p class="font-bold [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-2 animateOnView">
              <a href="<?= esc_url( get_permalink( 108 ) ); ?>">
                  Tilbage til oversigten
              </a>
          </p>
      </div>

				<p class="text-sm mb-4 font-bold animateOnView">
					<?= $src ? esc_html( $src ) : '&nbsp;' /* placeholder keeps spacing */; ?>
				</p>

				<h1 class="mb-4 text-[2.8125rem]/[1.2] font-bold md:mb-6 md:text-5xl/[1.15] tracking-[-0.015em] animateOnView w-full lg:w-[80%]">
					<?= esc_html( get_the_title() ); ?>
				</h1>

				<hr class="mb-8 sm:mb-12 border-black hidden lg:block animateOnView">

				<?php if ( $desc ) : ?>
					<div class="text-base/[1.5] animateOnView prose max-w-none w-full lg:w-[80%]">
						<?= wp_kses_post( $desc ); ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<?php /* ----------------------------------------------------
	     * CONTACT BLOCK (mobile only - appears at end of page)
	     * -------------------------------------------------- */ ?>
	<?php if ( $c && ( $c['name'] || $c['email'] ) ) : ?>
		<div class="block lg:hidden">
			<div class="container">
        <div class="mt-8 sm:mt-12 border-t border-black py-6">
				<h2 class="text-3xl/[1.25] mb-6 font-bold">
					Kontakt person
				</h2>
				<?php if ( $c['name'] )    : ?><p><?= esc_html( $c['name'] ); ?></p><?php endif; ?>
				<?php if ( $c['title'] )   : ?><p><?= esc_html( $c['title'] ); ?></p><?php endif; ?>
				<?php if ( $c['company'] ) : ?><p class="mb-4"><?= esc_html( $c['company'] ); ?></p><?php endif; ?>
				<?php if ( $c['phone'] )   : ?>
					<p>Telefon: <a href="tel:<?= esc_attr( preg_replace('/\D+/', '', $c['phone'] ) ); ?>"
					       class="underline underline-offset-4 decoration-1">
						<?= esc_html( $c['phone'] ); ?>
					</a></p>
				<?php endif; ?>
				<?php if ( $c['email'] )   : ?>
					<p>Email: <a href="mailto:<?= antispambot( $c['email'] ); ?>"
					       class="underline underline-offset-4 decoration-1">
						<?= antispambot( $c['email'] ); ?>
					</a></p>
				<?php endif; ?>
        </div>
			
	<?php endif; ?>

	<?php /* ----------------------------------------------------
	     * EXTRA INFO LINK/DOC (mobile only)
	     * -------------------------------------------------- */ ?>
	<?php if ( $info ) :
		if ( $info['typeof'] === 'link' && $info['link'] ) : ?>
			<div class="block lg:hidden mt-5 lg:mt-8">
					<a href="<?= esc_url( $info['link'] ); ?>"
					   target="_blank" rel="noopener noreferrer">
						<button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] mt-8">
							Læs mere
						</button>
					</a>
			</div>
		<?php elseif ( $info['typeof'] === 'document' && $info['dokument'] ) : ?>
			<div class="block lg:hidden mt-5 lg:mt-8">

					<a href="<?= esc_url( $info['dokument'] ); ?>"
					   target="_blank" rel="noopener noreferrer"
					   download>
						<button class="inline-flex items-center justify-center h-[43px] md:h-fit rounded-full px-6 xs:px-7 py-auto md:py-2 text-base sm:text-sm font-semibold text-black border border-black hover:bg-black hover:text-white transition-transform duration-300 cursor-pointer tracking-[0.015em] mt-8">
							Download invitation
						</button>
					</a>
			</div>
		<?php endif;
	endif; ?>

</div>
</div>
<div class="container">
<hr class="mt-8 sm:mt-12 border-black animateOnView">
		</div>
</section>

<?php
endwhile;
get_footer();
