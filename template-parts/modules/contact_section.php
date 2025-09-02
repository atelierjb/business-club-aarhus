<?php
/**
 * CONTACT SECTION  ▸  Slug: contact_section
 * Tailwind only – no extra CSS.
 */

/* 1 · ACF FIELDS ------------------------------------------------ */
$bg        = get_sub_field( 'background_color' );
$heading   = get_sub_field( 'heading' );
$body      = get_sub_field( 'paragraph' );
$phone     = get_sub_field( 'phone' );
$email     = get_sub_field( 'mail' );
$image_id  = get_sub_field( 'image' );

/* Bail if zero content */
if ( ! $heading && ! $body && ! $phone && ! $email && ! $image_id ) {
	return;
}
?>

<section class="py-16 md:py-24 lg:py-28 <?= esc_attr( $bg ); ?>">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start gap-y-8 sm:gap-y-12">

            <?php /* ----------------------------------------------------
                * IMAGE COLUMN (right on desktop)
                * -------------------------------------------------- */ ?>
            <?php if ( $image_id ) : ?>
                <div class="order-1 lg:order-2 overflow-hidden lg:ml-12 animateOnView">
                    <?= wp_get_attachment_image(
                        $image_id,
                        'full',
                        false,
                        [
                            'class' => 'h-full aspect-[6/5] object-cover',
                            'alt'   => esc_attr( $heading ?: '' ),
                        ]
                    ); ?>
                </div>
            <?php endif; ?>

            <?php /* ----------------------------------------------------
                * TEXT COLUMN (left on desktop)
                * -------------------------------------------------- */ ?>
            <div class="order-2 lg:order-1 border-t lg:border-t-0 lg:border-r border-black pt-8 sm:pt-12 lg:pt-0 lg:pr-12 h-full">

                <?php if ( $heading ) : ?>
                    <h2 class="mb-4 text-[2.8125rem]/[1.2] font-bold md:mb-6 md:text-5xl/[1.15] tracking-[-0.015em] animateOnView w-full lg:w-[80%]">
                        <?= esc_html( $heading ); ?>
                    </h2>
                <?php endif; ?>

                <?php if ( $body ) : ?>
                    <p class="text-base/[1.5] mb-6 animateOnView w-full lg:w-[80%]">
                        <?= wp_kses_post( $body ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $phone ) : ?>
                    <p class="font-bold text-base/[1.5] [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                        Tlf: <a href="tel:+<?= preg_replace( '/\D+/', '', $phone ); ?>" class="hover:underline hover:underline-offset-4 decoration-1"><?= esc_html( $phone ); ?></a>
                    </p>
                <?php endif; ?>

                <?php if ( $email ) : ?>
                    <p class="font-bold text-base/[1.5] [&_a]:!no-underline hover:underline hover:underline-offset-4 decoration-1 animateOnView">
                        <a href="mailto:<?= antispambot( $email ); ?>" class="hover:underline hover:underline-offset-4"><?= antispambot( $email ); ?></a>
                    </p>
                <?php endif; ?>

            </div>
        </div>
        <?php if ( $heading || $body || $phone || $email ) : ?>
            <hr class="mt-8 sm:mt-12 border-black animateOnView">
        <?php endif; ?>
    </div>

</section>
