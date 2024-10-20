<?php

/**
 * Template Name: TF Service Result
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php
        echo do_shortcode('[tfservices_search_form]');

        the_content();

?>
<?php
    endwhile;
endif;

get_footer();
