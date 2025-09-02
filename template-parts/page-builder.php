<?php
/**
 * Loops through ACF Flexible Content “components”
 * and pulls the matching section partial.
 */

if ( have_rows( 'components' ) ) :
    while ( have_rows( 'components' ) ) : the_row();

        get_template_part(
            'template-parts/modules/' . get_row_layout()
        );

    endwhile;
endif;