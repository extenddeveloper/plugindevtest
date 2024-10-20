<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themefic.com
 * @since      1.0.0
 *
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/public
 * @author     Themefic <career@themefic.com>
 */
class Tf_service_booking_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tf_service_booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tf_service_booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tf_service_booking-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tf_service_booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tf_service_booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tf_service_booking-public.js', array('jquery'), $this->version, false);

		// Localize to pass ajax_url to JS
		if (is_post_type_archive('tfservices')) {
			wp_localize_script($this->plugin_name, 'tf_service', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('tf-ajax-nonce'),
			));
		}

		wp_localize_script($this->plugin_name, 'tfservicesAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('tf-ajax-nonce'),
		));
	}
}
