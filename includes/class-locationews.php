<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.locationews.com
 * @since      1.0.0
 *
 * @package    Locationews
 * @subpackage Locationews/includes
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Locationews constructor.
	 */
	public function __construct() {
		$this->plugin_name = 'locationews';
		$this->version = LOCATIONEWS_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locationews-loader.php';
		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locationews-i18n.php';
		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locationews-admin.php';
		// The class responsible for for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-locationews-public.php';
        // The class responsible for handling data between plugin and API.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locationews-api.php';
		// The class responsible for core functionalities.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locationews-core.php';
		// The class responsible for plugin activation
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locationews-activator.php';

		$this->loader = new Locationews_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 * Uses the Locationews_i18n class in order to set the domain and to register the hook with WordPress.
	 */
	private function set_locale() {
		$plugin_i18n = new Locationews_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Locationews_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'locationews_enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'locationews_enqueue_scripts', 999 );
		$this->loader->add_action('admin_menu', $plugin_admin, 'locationews_add_dashboard_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'locationews_register_settings');
        $this->loader->add_action('admin_init', $plugin_admin, 'locationews_register_sections');
        $this->loader->add_action('admin_init', $plugin_admin, 'locationews_register_fields');
        $this->loader->add_action('admin_init', $plugin_admin, 'locationews_add_metaboxes');
        $this->loader->add_action('admin_init', $plugin_admin, 'locationews_update_categories');
        $this->loader->add_action('save_post',  $plugin_admin, 'locationews_save_post');
        $this->loader->add_action('publish_future_post',  $plugin_admin, 'locationews_save_post');
        $this->loader->add_action('transition_post_status', $plugin_admin, 'locationews_unpublished', 10, 3 );
        $this->loader->add_action('before_delete_post', $plugin_admin, 'locationews_delete_post' );
        $this->loader->add_action('edit_form_top', $plugin_admin, 'locationews_adminNotices');

        if ( is_multisite()  ) {
        	$plugin_activator = new Locationews_Activator();
			$this->loader->add_action('admin_init', $plugin_activator, 'check_activated_multisite');
		}
    }

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 */
	private function define_public_hooks() {
		$plugin_public = new Locationews_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

    /**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
