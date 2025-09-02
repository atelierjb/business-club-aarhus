<?php
/**
 * TEXT + IMAGE SECTION  ▸  Slug: text_image_section
 * Two‑column block with optional tag, heading, paragraph and CTA buttons.
 */

/* -------------------------------------------------------------------------
 * 1. ACF FIELDS
 * ---------------------------------------------------------------------- */
$bg        = get_sub_field( 'background_color' );
$pos       = get_sub_field( 'layout' ) ?: 'right';            // 'left' | 'right'
$image_id  = get_sub_field( 'image' );
$tag       = get_sub_field( 'tag' );
$heading   = get_sub_field( 'heading' );
$text      = get_sub_field( 'paragraph' );
$ctas      = get_sub_field( 'ctas' );                         // repeater (max 2)

/* Column‑specific Tailwind helpers ------------------------------------- */
$img_wrap  = $pos === 'left'
    ? 'overflow-hidden mb-8 sm:mb-12 lg:mb-0 mr-0 lg:mr-12 order-1 animateOnView'
    : 'order-1 lg:order-2 overflow-hidden ml-0 lg:ml-12 mb-8 sm:mb-12 lg:mb-0 animateOnView';

$content_wrap = $pos === 'left'
    ? 'h-full border-l-0 border-t-1 lg:border-t-0 lg:border-l border-black pl-0 lg:pl-12 pt-8 sm:pt-12 lg:pt-0 order-2 lg:order-1'
    : 'h-full w-full border-r-0 border-t-1 lg:border-t-0 lg:border-r border-black pr-0 lg:pr-12 pt-8 sm:pt-12 lg:pt-0 order-2 lg:order-1';

/* Bail if no content */
if ( ! $image_id && ! $tag && ! $heading && ! $text && ! $ctas ) {
	return;
}

/* ------------------------------------------------------------------
 * 2. MARKUP
 * ------------------------------------------------------------------ */
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start">

      <?php /* IMAGE COLUMN ------------------------------------------------ */ ?>
      <?php if ( $image_id ) : ?>
        <div class="<?= $img_wrap; ?>">
          <?= wp_get_attachment_image(
                $image_id,
                'full',
                false,
                [
                  'class' => 'h-full w-full aspect-[6/5] object-cover',
                  'alt'   => esc_attr( $heading ?: '' ),
                ]
          ); ?>
        </div>
      <?php endif; ?>

      <?php /* CONTENT COLUMN --------------------------------------------- */ ?>
      <?php if ( $tag || $heading || $text || $ctas ) : ?>
        <div class="<?= $content_wrap; ?>">
          <?php if ( $tag ) : ?>
            <span class="text-xs font-semibold uppercase tracking-wider text-brand text-trim animateOnView"><?= esc_html( $tag ); ?></span>
          <?php endif; ?>

          <?php if ( $heading ) : ?>
            <h2 class="text-[2.8125rem]/[1.2] font-bold mb-6 md:text-5xl/[1.15] tracking-[-0.015em] text-trim animateOnView w-full lg:w-[80%]">
              <?= esc_html( $heading ); ?>
            </h2>
          <?php endif; ?>

          <?php if ( $text ) : ?>
            <p class="text-base/[1.5] animateOnView w-full lg:w-[80%]">
              <?= wp_kses_post( $text ); ?>
            </p>
          <?php endif; ?>

          <?php if ( $ctas ) : ?>
                <div class="mt-6 flex flex-wrap gap-3 md:mt-8">
                    <?php foreach ( $ctas as $i => $row ) {
                        mytheme_render_button( $row['buttons'], $i );
                    } ?>  
                </div>
            <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
    
        <?php /* Optional divider: only if something rendered above ------------- */ ?>
        <?php if ( $image_id || $tag || $heading || $text || $ctas ) : ?>
            <hr class="mt-8 sm:mt-12 border-black animateOnView">
            <?php endif; ?>
    </div>
</section>