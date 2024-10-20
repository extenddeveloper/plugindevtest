<?php

/**
 * Fired during plugin activation
 *
 * @link       https://themefic.com
 * @since      1.0.0
 *
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/includes
 * @author     Themefic <career@themefic.com>
 */
class Tf_service_booking_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		ob_start();

		$query = new WP_Query(array(
			'post_type'   => 'page',
			'title'       => 'TF Service Result',
			'posts_per_page' => 1,
		));

		// Check if the page already exists by its title
		if (!$query->have_posts()) {
			$page_args = array(
				'post_title'    => 'TF Service Result',
				'post_content'  => '[tf_service_result]',
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_author'   => get_current_user_id(),
			);

			// Insert the page into the database
			$page_id = wp_insert_post($page_args);

			// Set the custom page template
			if ($page_id) {
				update_post_meta($page_id, '_wp_page_template',  'tf-service-result.php');
			}
		}

		// Clear the output buffer
		ob_end_clean();
	}
}
