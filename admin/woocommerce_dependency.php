<?php
// Check if ACF Pro is active
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

function tf_service_booking_check_dependencies()
{
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
    }
}

function my_plugin_admin_notice()
{
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        echo '<div class="error"><p><strong>TF Service Booking</strong> requires <strong></strong> WooCommerce to be actived.</p></div>';
    }
}
add_action('admin_notices', 'my_plugin_admin_notice');
