<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.locationews.com
 * @since      1.0.0
 *
 * @package    Locationews
 * @subpackage Locationews/includes
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews_i18n {
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'locationews',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
