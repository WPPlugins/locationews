<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.locationews.com
 * @since      1.1.5
 *
 * @package    Locationews
 * @subpackage Locationews/public
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews_Public {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Locationews Public constructor
	 *
	 * @param $plugin_name
	 * @param $version
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_scripts() {
		wp_enqueue_script(  $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/min.js', array( 'jquery' ), $this->version, true );
	}

}
