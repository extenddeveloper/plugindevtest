<?php

class TFServices
{
    public function __construct()
    {
        // Add hooks
        add_action('wp', [$this, 'remove_pagination_for_archive']);
        add_action('storefront_loop_after', [$this, 'load_more_button'], 15);
        add_action('wp_ajax_load_more_tfservices', [$this, 'load_more_posts']);
        add_action('wp_ajax_nopriv_load_more_tfservices', [$this, 'load_more_posts']);
        add_filter('theme_page_templates', [$this, 'add_plugin_page_template']);
        add_filter('page_template', [$this, 'get_plugin_template']);
        add_action('wp_ajax_tfservices_search', [$this, 'ajax_search']);
        add_action('wp_ajax_nopriv_tfservices_search', [$this, 'ajax_search']);
    }

    public function remove_pagination_for_archive()
    {
        if (is_post_type_archive('tfservices')) {
            remove_action('storefront_loop_after', 'storefront_paging_nav', 10);
        }
    }

    public function load_more_button()
    {
        if ($GLOBALS['wp_query']->max_num_pages > 1) {
            echo '<div class="tfservices-load-more-wrap">
                    <button id="load-more" data-page="1" data-max="' . $GLOBALS['wp_query']->max_num_pages . '">Load More</button>
                  </div>';
        }
    }

    public function load_more_posts()
    {
        $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $args = array(
            'post_type'      => 'tfservices',
            'posts_per_page' => get_option('posts_per_page', 5),
            'paged'          => $paged + 1,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('content', get_post_format());
            }
        } else {
            echo '<p>No more services found</p>';
        }

        wp_die();
    }

    public function add_plugin_page_template($post_templates)
    {
        $post_templates['tf-service-result.php'] = 'TF Service Result';
        return $post_templates;
    }

    public function get_plugin_template($template)
    {
        if (is_page_template('tf-service-result.php')) {
            $custom_template = WP_PLUGIN_DIR . '/plugindevtest/includes/page-templates/tf-service-result.php';
            if (file_exists($custom_template)) {
                $template = $custom_template;
            }
        }
        return $template;
    }

    public function ajax_search()
    {
        $search_term = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
        $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;

        $args = array(
            'post_type'      => 'tfservices',
            'posts_per_page' => 5,
            'paged'          => $paged,
            's'              => $search_term,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                echo '<div class="tfservice-item">
                        <img src="' . esc_url($image) . '" alt="' . get_the_title() . '" />
                        <a href="' . get_permalink() . '">' . get_the_title() . '</a>
                      </div>';
            }

            if ($query->max_num_pages > $paged) {
                echo '<a id="view-more" href="' . get_bloginfo('url') . '/?s=' . urlencode($search_term) . '&post_type=tfservices">View More</a>';
            }
        } else {
            echo 'No services found.';
        }

        wp_die();
    }
}
