<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themefic.com
 * @since      1.0.0
 *
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tf_service_booking
 * @subpackage Tf_service_booking/includes
 * @author     Themefic <career@themefic.com>
 */
class Tf_service_booking
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tf_service_booking_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('TF_SERVICE_BOOKING_VERSION')) {
			$this->version = TF_SERVICE_BOOKING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tf_service_booking';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		add_action('init', array($this, 'tf_custom_post_register'));
		add_shortcode('tf_service_result', array($this, 'render_tf_service_result'));
		new customFields();

		add_filter('woocommerce_data_stores', array($this, 'tf_services_woocommerce_data_stores'), 10, 1);
		add_filter('woocommerce_product_get_price', array($this, 'tf_service_product_get_price'), 10, 2);
		add_shortcode('tfservices_search_form', array($this, 'tfservices_search_form'), 10, 1);
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tf_service_booking_Loader. Orchestrates the hooks of the plugin.
	 * - Tf_service_booking_i18n. Defines internationalization functionality.
	 * - Tf_service_booking_Admin. Defines all hooks for the admin area.
	 * - Tf_service_booking_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tf_service_booking-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tf_service_booking-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tf_service_booking-admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/woocommerce_dependency.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/custom_fields.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tf-services-elementor-addon.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tf-services.php';
		new TFServices();
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tf_service_booking-public.php';

		$this->loader = new Tf_service_booking_Loader();

		add_action('plugins_loaded', array($this, 'tf_services_check_elementor'));
	}

	public function tf_services_check_elementor()
	{
		if (! defined('ELEMENTOR_VERSION')) {
			add_action('admin_notices', array($this, 'tf_elementor_not_active_notice'));
			return;
		}
	}

	public function tf_elementor_not_active_notice()
	{
		echo '<div class="notice notice-error"><p>' . esc_html__('TF Services requires Elementor to be installed and activated.', 'tf-services') . '</p></div>';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 * 
	 * @since    1.0.0
	 */

	public function tf_services_woocommerce_data_stores($stores)
	{

		require_once plugin_dir_path(dirname(__FILE__))  . '/includes/class-data-store-cpt.php';
		$stores['product'] = 'MY_Product_Data_Store_CPT';

		return $stores;
	}

	/**
	 *  Get price for tf services
	 * 
	 * @since    1.0.0
	 */
	public function tf_service_product_get_price($price, $product)
	{
		if (get_post_type($product->get_id()) === 'tfservices') {
			$regular_price = get_post_meta($product->get_id(), '_tf_service_regular_price', true);
			$sale_price = get_post_meta($product->get_id(), '_tf_service_sale_price', true);

			$price = !empty($sale_price) ? $sale_price : $regular_price;
		}
		return $price;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tf_service_booking_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Tf_service_booking_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Tf_service_booking_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Tf_service_booking_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Register the custom post type for service.
	 * 
	 * @since 1.0.0
	 */


	public function tf_custom_post_register()
	{
		$labels = array(
			'name'                  => _x('TF Services', 'Post type general name', 'tf_services'),
			'singular_name'         => _x('TF Service', 'Post type singular name', 'tf_services'),
			'menu_name'             => _x('TF Services', 'Admin Menu text', 'tf_services'),
			'name_admin_bar'        => _x('TF Services', 'Add New on Toolbar', 'tf_services'),
			'add_new'               => __('Add New', 'tf_services'),
			'add_new_item'          => __('Add New service', 'tf_services'),
			'new_item'              => __('New service', 'tf_services'),
			'edit_item'             => __('Edit service', 'tf_services'),
			'view_item'             => __('View service', 'tf_services'),
			'all_items'             => __('All services', 'tf_services'),
			'search_items'          => __('Search services', 'tf_services'),
			'parent_item_colon'     => __('Parent services:', 'tf_services'),
			'not_found'             => __('No services found.', 'tf_services'),
			'not_found_in_trash'    => __('No services found in Trash.', 'tf_services'),
		);
		$args = array(
			'labels'             => $labels,
			'description'        => '',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'tf_services'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array('title', 'editor', 'author', 'thumbnail'),
			'taxonomies'         => array(),
			'show_in_rest'       => true
		);

		register_post_type('TF Services', $args);
	}

	/**
	 * Render tf service booking shortcode.
	 * 
	 * @since 1.0.0
	 */

	// Shortcode function to display all services
	function render_tf_service_result($atts)
	{
		$args = array(
			'post_type'      => 'tfservices',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID',
			'post_status'    => 'publish',
		);

		$query = new WP_Query($args);

		ob_start();

		if ($query->have_posts()) {
			echo '<div class="tf-service-list">';
			while ($query->have_posts()) {
				$query->the_post();
		
				$regular_price = get_post_meta(get_the_ID(), '_tf_service_regular_price', true);
				$sale_price = get_post_meta(get_the_ID(), '_tf_service_sale_price', true);
				$product_id = get_the_ID(); // Use service ID as WooCommerce product ID
		
				?>
				<div class="tf-service">
					<div class="tf-service__thumbnail">
						<?php if (has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('medium'); ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="tf-service__details">
						<h2 class="tf-service__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="tf-service__excerpt">
							<?php echo wp_trim_words(get_the_excerpt(), 20, '...') ?>
						</div>
						<div class="tf-service__pricing">
							<?php if (!empty($regular_price)) : ?>
								<!-- Display price -->
								<?php if ($sale_price) : ?>
									<p><del><?php echo wc_price($regular_price); ?></del> <strong><?php echo wc_price($sale_price); ?></strong>
									</p>
								<?php else : ?>
									<p><?php echo wc_price($regular_price); ?></p>
								<?php endif; ?>
		
								<!-- Add to Cart Form -->
								<form class="tf-service__cart" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
									<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">
									<input type="number" name="quantity" value="1" min="1" class="tf-service__cart-qty input-text text" size="4" />
									<button type="submit" class="tf-service__cart-button button alt"><?php _e('Add to Cart', 'tf_services'); ?></button>
								</form>
							<?php else : ?>
								<!-- No price, show Read More -->
								<a href="<?php the_permalink(); ?>" class="tf-service__read-more"><?php _e('Read More', 'tf_services'); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo 'No services available.';
		}
		

		return ob_get_clean();
	}


	/**
	 * Render search form for TF Services.
	 * @since 1.0.0
	 */
	public function tfservices_search_form()
	{
		ob_start(); ?>
		<div id="tfservices__search-modal" class="tfservices__search-modal">
			<div class="tfservices__search-modal-content">
				<form id="tfservices__search-form">
					<input type="text" id="tfservices__search-input" placeholder="Search TF Services..." />
					<button type="button" class="tfservices__close-modal">×</button>
					<div id="tfservices__search-results"></div>
				</form>
			</div>
		</div>
		<button id="tfservices__open-search" class="tfservices__open-search">
			<input type="text" name="s" placeholder="Search TF Services...">
			<?php echo esc_html_e('Search', 'tf_services'); ?>
		</button>

		<?php
		return ob_get_clean();
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since  1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tf_service_booking_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
