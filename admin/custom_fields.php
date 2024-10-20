<?php

class customFields
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'tf_service_add_meta_boxes'));
        add_action('save_post', array($this, 'tf_service_save_price_meta'));
    }

    /**
     * Add meta boxes for regular and sale price
     * @return void
     */
    public function tf_service_add_meta_boxes()
    {
        add_meta_box(
            'tf_service_price_meta',
            __('Service Pricing', 'tf_service_booking'),
            array($this, 'tf_service_price_meta_box_callback'),
            'tfservices',
            'side'
        );
    }

    /**
     * Callback function for meta box
     * @param WP_Post $post
     * @return void
     */
    public function tf_service_price_meta_box_callback($post)
    {
        wp_nonce_field('tf_service_save_price_meta', 'tf_service_price_meta_nonce');

        $regular_price = get_post_meta($post->ID, '_tf_service_regular_price', true);
        $sale_price = get_post_meta($post->ID, '_tf_service_sale_price', true);

?>
        <p>
            <label for="tf_service_regular_price"><?php _e('Regular Price', 'tf_service_booking'); ?></label>
            <input type="number" id="tf_service_regular_price" name="tf_service_regular_price"
                value="<?php echo esc_attr($regular_price); ?>" step="0.01">
        </p>
        <p>
            <label for="tf_service_sale_price"><?php _e('Sale Price', 'tf_service_booking'); ?></label>
            <input type="number" id="tf_service_sale_price" name="tf_service_sale_price"
                value="<?php echo esc_attr($sale_price); ?>" step="0.01">
        </p>
<?php
    }


    /**
     * Save custom fields
     * @param int $post_id
     * @return void
     */

    public function tf_service_save_price_meta($post_id)
    {
        if (!isset($_POST['tf_service_price_meta_nonce']) || !wp_verify_nonce($_POST['tf_service_price_meta_nonce'], 'tf_service_save_price_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['tf_service_regular_price'])) {
            update_post_meta($post_id, '_tf_service_regular_price', sanitize_text_field($_POST['tf_service_regular_price']));
        }

        if (isset($_POST['tf_service_sale_price'])) {
            update_post_meta($post_id, '_tf_service_sale_price', sanitize_text_field($_POST['tf_service_sale_price']));
        }
    }
}
