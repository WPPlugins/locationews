<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.locationews.com
 * @since      1.0.0
 *
 * @package    Locationews
 * @subpackage Locationews/admin
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews_Admin {
    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * The options of this plugin.
     */
    private $options;

	/**
     * The user options of this plugin.
     */
	private $user_options;

    /**
     * The front end options of this plugin.
     */
    private $front_options;

    /**
     * Meta field name
     */
    private $meta_name;

	/**
	 * Locationews_Admin constructor.
	 *
	 * @param $plugin_name
	 * @param $version
	 */
	public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->locationews_set_plugin_options();
    }

	/**
	 * Set plugin options
	 */
	private function locationews_set_plugin_options() {
        $this->options = get_option('locationews_options');
        $this->front_options = array();

        foreach ( array('defaultCategories', 'postTypes', 'location', 'gApiKey', 'gLanguage', 'gRegion', 'gZoom', 'gIcon', 'image', 'url', 'themeColor', 'lan') as $option ) {
            if ( isset( $this->options[ $option ] ) ) {
	              $this->front_options[ $option ] = $this->options[ $option ];
            }
        }

        $this->user_options = get_option('locationews_user');

        foreach ( array('defaultCategories', 'postTypes', 'location') as $option ) {
            if ( isset( $this->user_options[ $option ] ) ) {
	              $this->front_options[ $option ] = $this->user_options[ $option ];
            }
        }

        // Set post meta field name based on current api enviroment (possible values: locationews / locationews_dev)
        $this->meta_name = Locationews_Core::get_current_env( $this->options['apiUrl'] );

    }

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function locationews_enqueue_styles() {
		wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . '-css', plugin_dir_url( __FILE__ ) . 'css/locationews-wp-plugin.min.css', array(), $this->version, 'all');
	}

    /**
     * Register the JavaScript for the admin area.
     */
    public function locationews_enqueue_scripts( $hook ) {
	    wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery'), $this->version, false );
        wp_enqueue_script( 'bootstrap-switch-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap-switch.min.js', array('jquery'), $this->version, false );

	    $screen = get_current_screen();
	    $active_post_types = array_keys( $this->options['postTypes'] );

	    if ( isset( $screen->post_type )  && in_array( $screen->post_type, $active_post_types ) ) {

		    wp_enqueue_script( $this->plugin_name . '-metabox-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-metabox.min.js', array(
			    'jquery'
		    ), $this->version, true );

		    wp_enqueue_script( $this->plugin_name . '-google-map-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-google-map-init.min.js', array(
			    'jquery'
		    ), $this->version, true );

		    if ( false === $this->check_registered_api() ) {
			    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->front_options['gApiKey'] . '&language=' . $this->front_options['gLanguage'] . '&region=' . $this->front_options['gRegion'] . '&libraries=places', array( 'jquery' ), $this->version, true );
		    }
	    }

	}

    /**
     * Add dashboard menu item
     */
    public function locationews_add_dashboard_menu() {
        $locationews_options = add_menu_page(
            __('Locationews', $this->plugin_name ),
            __('Locationews', $this->plugin_name ),
            'manage_options',
            $this->plugin_name . '-settings',
            array( &$this, 'locationews_plugin_options_page'),
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAAYCAMAAAArvOYAAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACGVBMVEUAAADrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGSHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGiHrGSHrGSDrGB/rGiHrGB/rHyPtMTLuPzzrGiHrGSDvRkT3opn81c/95uLrGiHrGB/wVFL82dP////////82NPwVFLrGB/rGCDtNjX6zsjt8PDx8fH7zsjtNjXrFx70dnD8+PaKioylpKX39/f09PT09PX/////+/jzdXDrGiHrHiH3pZz8//94eHmZmZn39/iHh4lvb3Fzc3Ts7Oz3pZzrICL4q6H8//95eHqTkpTq6utiYWOTk5VYV1jQ0NH+///4q6HrGR71i4X8/fyCgoNPTk91dXdgYGK4ubpqaWuHiIrMzc33jYfrGB/vS0f85uHh4+TLy8zOz9DU1dbs7O3Y2drT1dbp08/wS0jrGiHrHSL0hX3+9vP/9/T1hn7rHSLrGSDsIyb0fXb82dP+9/T//Pn0fXbsIybrGSDrGSDrGyDuOjjyZV70dnHrGyDrGSDrGiHrGCDrFx7rFh7rGiGalQZVAAAAPXRSTlMAAAo5ZHEFQJ/f9vkMeegGfvZO7hC2ROt0+oiE/2f2MOAHmS/WTuABStwATOMBZfMIk/4m03f6LMoSewQexmgNFwAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfhAwYHDTd7W4pMAAAA40lEQVQY023OPSvFARTH8e/3uP7ykFIWbDeLiaKsdpm9FkIYvBezshsV5Wa4i3gBBqU8lOFn+N/rKs5wOn0659cRENUkISA47lDyFcRpXW2ll7xFZ3VtuHOXvOqc66Or27w476YqoPp5nU45qfoByZRWOuWE6secqlZc3PGnnrrvuXDJ3RH5dp5OOfNL2pz/5RJgu0226/7gwzE9yqOy7MlQ9vJAharTpmmapqmDKiK4omcA+0k/FKRfdQgcVvUDYwDPC3W1daz3AQogvSqqegGQtm+YGwLQASCWaWEgUGYw/ZVvt+hf4QWLPjYAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTctMDMtMDZUMDc6MTM6NTUtMDU6MDBD8t61AAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE3LTAzLTA2VDA3OjEzOjU1LTA1OjAwMq9mCQAAAABJRU5ErkJggg=='
        );

	    add_action( 'load-' . $locationews_options, [ $this, 'load_ln_options_js' ] );
    }

	public function load_ln_options_js(){
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_ln_options_js' ] );
	}

	public function enqueue_ln_options_js() {

		wp_enqueue_script(  $this->plugin_name . '-settings-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-settings.min.js');
		wp_localize_script( $this->plugin_name . '-settings-init-js', 'locationews_settings_init',
			array(
				'options' => $this->user_options,
			)
		);

		wp_enqueue_script(  $this->plugin_name . '-google-map-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-google-map-init.min.js', array('jquery'), $this->version, true );
		wp_localize_script( $this->plugin_name . '-google-map-init-js', 'locationews_map_init',
			array(
				'plugin_url'                => plugin_dir_url( __FILE__ ),
				'locationews_meta'          => array('latlng' => ''),
				'locationews_options'       => $this->front_options,
				'locationews_user'          => $this->user_options,
				'zoom'                      => $this->front_options['gZoom'],
				'icon'                      => plugin_dir_url( __FILE__ ) . 'img/' . $this->front_options['gIcon'],
				'map_search_placeholder'    => __('Search location', $this->plugin_name )
			)
		);

		if ( false === $this->check_registered_api() ) {
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->front_options['gApiKey'] . '&language=' . $this->front_options['gLanguage'] . '&region=' . $this->front_options['gRegion'] . '&libraries=places', array('jquery'), $this->version, true );
		}

	}

	public function check_registered_api() {
		global $wp_scripts;
		$registered = $wp_scripts->registered;
		$api_already_registered = false;
		foreach ($registered as $script) {
			// For each script, verify if its src contains 'api_url'
			if ( strpos( $script->src, '//maps.googleapis.com/maps/api/') !== false ) {
				//$api_already_registered = true;
				wp_dequeue_script( $script->handle );

			}
		}
		return $api_already_registered;
	}


    /**
     * Creates the options page
     */
    public function locationews_plugin_options_page() {
	    // check user capabilities
	    if ( ! current_user_can( 'manage_options' ) ) {
		    return;
	    }

	    include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-settings.php');
    }

    /**
     * Registers settings fields with WordPress
     */
    public function locationews_register_fields() {

	    add_settings_field(
		    'locationewsCategory',
		    esc_html__( 'Locationews Default Category', $this->plugin_name ),
		    array( &$this, 'locationews_field_select'),
		    $this->plugin_name,
		    $this->plugin_name . '-fields',
		    array(
			    'description' 	=> __('Set the default category for Locationews articles. This function does not affect on the WordPress categories.', $this->plugin_name ),
			    'id' 			=> 'locationewsCategory',
			    'value'         => '',
			    'fields' 		=> $this->locationews_categories()
		    )
	    );

    	add_settings_field(
            'defaultCategories',
            esc_html__( 'Categories', $this->plugin_name ),
            array( &$this, 'locationews_field_multicheckbox'),
            $this->plugin_name,
            $this->plugin_name . '-fields',
            array(
                'description' 	=> __('Select WordPress Categories whose news you want to post to Locationews.', $this->plugin_name ),
                'id' 			=> 'defaultCategories',
                'value'         => 'all',
                'fields' 		=> $this->locationews_wp_categories()
            )
        );

        add_settings_field(
            'postTypes',
            esc_html__( 'Post types', $this->plugin_name ),
            array( &$this, 'locationews_field_multicheckbox'),
            $this->plugin_name,
            $this->plugin_name . '-fields',
            array(
                'description' 	=> __('Choose which post types you want to allow use Locationews. The default option is normal post type.', $this->plugin_name ),
                'id' 			=> 'postTypes',
                'value' 		=> 'post',
                'fields'        => $this->locationews_post_types()
            )
        );

        add_settings_field(
            'location',
            esc_html__( 'Default location', $this->plugin_name ),
            array( &$this, 'locationews_field_google_map'),
            $this->plugin_name,
            $this->plugin_name . '-fields',
            array(
                'description'   => __("Select the default location (the default option is your publication's address, here you can choose another location).", $this->plugin_name ),
                'id'            => 'location',
                'value'         => $this->user_options['location']
            )
        );
    }

    /**
     * Registers settings sections with WordPress
     */
    public function locationews_register_sections() {
        add_settings_section(
            $this->plugin_name . '-fields',
            esc_html__( 'Settings', $this->plugin_name ),
            array( &$this, 'locationews_return_false'),
            $this->plugin_name
        );
    }

	/**
	 * Dummy return false function
	 *
	 * @return bool
	 */
	public function locationews_return_false() {
        return false;
    }

    public function return_infotext() {
		echo __('Locationews plugin publish your news to Locationews service. With these settings, you can specify the basic functions on the map selector which appear in the article edit view.', $this->plugin_name );
    }

    /**
     * Registers user settings for plugin
     */
    public function locationews_register_settings() {
        register_setting(
            'locationews_user',
            'locationews_user',
            array('Locationews_Core', 'locationews_validate_options')
        );
    }

	/**
	 * Registers metaboxes with WordPress
	 */
	public function locationews_add_metaboxes() {

		$this->options['jwt'] = trim( $this->options['jwt'] );

		if ( ! empty( $this->options['jwt'] ) ) {
		    if ( ! is_array( $this->user_options['postTypes'] ) ) {
	              $this->user_options['postTypes'] = array();
            }

            $metabox = add_meta_box(
                $this->plugin_name,
                esc_html__('Locationews', $this->plugin_name ),
                array( $this, 'locationews_metabox'),
                array_keys( $this->user_options['postTypes'] ),
                'normal',
                'default'
            );

			// Load the JS conditionally
			//add_action( 'load-' . $metabox, [ $this, 'load_ln_metabox_js' ] );
        }
    }

	// This function is only called when our plugin's page loads!
	public function load_ln_metabox_js(){
		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_ln_metabox_js' ] );
	}


	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @param $post
	 * @param $params
	 */
	public function locationews_metabox( $post, $params ) {

		if ( ! is_admin()  || ! isset( $this->user_options['postTypes'] ) || ! in_array( $post->post_type, array_keys( $this->user_options['postTypes'] ) ) || empty( $this->options['jwt'] ) ) {
	          return;
        }

		$display_metabox        = false;
		$display_metabox_always = false;
		$cats                   = array();

        if ( $post->ID ) {
            $locationews_meta = get_post_meta( $post->ID, $this->meta_name, true );
            $post_categories  = wp_get_post_categories( $post->ID );
        } else {
            $post_categories = array();
        }

        if ( ! isset( $locationews_meta['id'] ) ) {
            $locationews_meta['id'] = '';
        }

        if ( ! isset( $locationews_meta['on'] ) ) {
          $locationews_meta['on'] = '1';
        }

        if ( ! isset( $locationews_meta['ads'] ) ) {
            $locationews_meta['ads'] = '1';
        }

        if ( ! isset( $locationews_meta['showmore'] ) ) {
            $locationews_meta['showmore'] = '';
        }

        if ( ! isset( $locationews_meta['category'] ) ) {
            $locationews_meta['category'] = isset( $this->user_options['locationewsCategory'] ) ? $this->user_options['locationewsCategory'] : $this->options['locationewsCategory'];
        }

        if ( ! isset( $locationews_meta['latlng'] ) ) {
            $locationews_meta['latlng'] = ''; //isset( $this->user_options['location'] ) ? $this->user_options['location'] : $this->options['location'];
        }

        foreach ( $this->user_options['defaultCategories'] as $catname => $val ) {
            $cat = get_category_by_slug( $catname );

            if ( $cat ) {
                $cats[] = $cat->term_id;
                if ( in_array( $cat->term_id, $post_categories ) ) {
                    $display_metabox = true;
                }
            }

            if ( $catname == 'all' ) {
                $display_metabox = true;
            }
        }

        if ( ! isset( $locationews_meta['api'] ) ) {
            $locationews_meta['api'] = $this->options['apiUrl'];
        }

        if ( isset( $this->user_options['defaultCategories'] ) ) {
	        if ( in_array( 'all', array_keys( $this->user_options['defaultCategories'] ) ) ) {
		        $display_metabox_always = true;
	        }
        }

		$screen = get_current_screen();
		$active_post_types = array_keys( $this->options['postTypes'] );

		if ( isset( $screen->post_type )  && in_array( $screen->post_type, $active_post_types ) ) {

			wp_enqueue_script( $this->plugin_name . '-metabox-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-metabox.min.js', array(
				'jquery',
				'google-maps'
			), $this->version, true );
			wp_localize_script( $this->plugin_name . '-metabox-init-js', 'locationews_metabox_init',
				array(
					'post_type'              => $post->post_type,
					'display_metabox'        => $display_metabox,
					'display_metabox_always' => $display_metabox_always,
					'catids'                 => $cats
				)
			);

			wp_enqueue_script( $this->plugin_name . '-google-map-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-google-map-init.min.js', array(
				'jquery',
				'google-maps'
			), $this->version, true );
			wp_localize_script( $this->plugin_name . '-google-map-init-js', 'locationews_map_init',
				array(
					'plugin_url'             => plugin_dir_url( __FILE__ ),
					'locationews_meta'       => $locationews_meta,
					'locationews_options'    => $this->front_options,
					'locationews_user'       => $this->user_options,
					'zoom'                   => $this->front_options['gZoom'],
					'icon'                   => plugin_dir_url( __FILE__ ) . 'img/' . $this->front_options['gIcon'],
					'map_search_placeholder' => __( 'Search location', $this->plugin_name )
				)
			);

			if ( false === $this->check_registered_api() ) {
				wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->front_options['gApiKey'] . '&language=' . $this->front_options['gLanguage'] . '&region=' . $this->front_options['gRegion'] . '&libraries=places', array( 'jquery' ), $this->version, true );
			}
		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-metabox.php');
    }

    public function enqueue_ln_metabox_js() {

	    $screen = get_current_screen();
	    $active_post_types = array_keys( $this->options['postTypes'] );

	    if ( isset( $screen->post_type )  && in_array( $screen->post_type, $active_post_types ) ) {

		     wp_enqueue_script( $this->plugin_name . '-metabox-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-metabox.min.js', array(
			    'jquery',
			    'google-maps'
		    ), $this->version, true );

		    wp_enqueue_script( $this->plugin_name . '-google-map-init-js', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-google-map-init.min.js', array(
			    'jquery',
			    'google-maps'
		    ), $this->version, true );

		    if ( false === $this->check_registered_api() ) {
			    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->front_options['gApiKey'] . '&language=' . $this->front_options['gLanguage'] . '&region=' . $this->front_options['gRegion'] . '&libraries=places', array( 'jquery' ), $this->version, true );
		    }
	    }

    }

	/**
	 * Save post metas & send to Locationews API
	 *
	 * @param $post_id
	 * @param string $post
	 * @param string $update
	 *
	 * @return mixed
	 */
	function locationews_save_post( $post_id, $post = '', $update = '' ) {
        // If this is scheduled future post, do not check these cases
        if ( current_filter() != 'publish_future_post' ) {
            if ( ! isset( $_POST['locationews-meta-box-nonce'] ) || ! wp_verify_nonce( $_POST['locationews-meta-box-nonce'], 'save_locationews_meta' ) || ! current_user_can('edit_post', $post_id ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) ) {
	              return $post_id;
            }
        }
        $ret = false;
        $post = get_post( $post_id );
        if ( isset( $_POST['locationews-meta-box-nonce'] ) ) {
		        $locationews_meta = array(
			        'on'        => isset( $_POST[ $this->plugin_name ] ) ? 1 : 0,
			        'ads'       => isset( $_POST[ $this->plugin_name . '_ads'] ) ? 1 : 0,
			        'showmore'  => isset( $_POST[ $this->plugin_name . '_showmore'] ) ? 1 : 0,
			        'id'        => isset( $_POST[ $this->plugin_name . '_Id'] ) ? filter_input( INPUT_POST, $this->plugin_name . '_Id', FILTER_SANITIZE_STRING ) : false,
			        'latlng'    => isset( $_POST[ $this->plugin_name . '_coordinates'] ) ? filter_input( INPUT_POST, $this->plugin_name . '_coordinates', FILTER_SANITIZE_STRING ) : false,
			        'category'  => isset( $_POST[ $this->plugin_name . '_category'] ) ? filter_input( INPUT_POST, $this->plugin_name . '_category', FILTER_SANITIZE_STRING ) : 1,
			        'api'       => $this->options['apiUrl']
		        );
        }

		$image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		$url = parse_url( $image_url );
		if ( is_array( $url ) ) {
			if ( isset( $url['scheme'] ) && isset( $url['host'] ) && isset( $url['path'] ) ) {
				$image_path = explode( '/', $url['path'] );
				$image      = array_pop( $image_path );
				$image_url  = $url['scheme'] . '://' . $url['host'] . implode( '/', $image_path ) . '/' . urlencode( $image );
			}
		}

		// Set data
        $data = array(
            $this->plugin_name                    => $locationews_meta['on'],
            $this->plugin_name . '_ads'           => $locationews_meta['ads'],
            $this->plugin_name . '_showmore'      => $locationews_meta['showmore'],
            $this->plugin_name . '_Id'            => $locationews_meta['id'],
            $this->plugin_name . '_coordinates'   => $locationews_meta['latlng'],
            $this->plugin_name . '_category'      => $locationews_meta['category'],
            $this->plugin_name . '_api'           => $locationews_meta['api'],
            $this->plugin_name . '_publicationId' => $this->options['id'],
            'post_title'                          => apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ),
            'content'                             => Locationews_Core::prepare_content( apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ) ),
            'url'                                 => get_permalink( $post_id ),
            'image'                               => $image_url,
            'post_ID'                             => $post_id
        );

        // Is post published
        if ( 'publish' == $post->post_status ) {
            // Locationews enabled OR disabled and we have an Id
            if ( $data[ $this->plugin_name ] == 1 || $data[ $this->plugin_name . '_Id'] ) {
                // Init API
                $locatioapi = new Locationews_API( $this->options['apiUrl'], $this->options['jwt'], $this->plugin_name );
            }
            // Locationews is enabled
            if ( $data[ $this->plugin_name ] == 1 ) {
                // Has id, update
                if ( $data[ $this->plugin_name . '_Id'] ) {
                    // Update post
                    $ret = $locatioapi->update( $data );
                    // Success code for admin notice
                    $successcode = 2;
                } else { // Is new news
	                  // Add to Locationews
                    $ret = $locatioapi->add( $data );
                    // Get API response
                    $retarr = json_decode( $ret, true);

                    // Get Locationews Id
                    if ( isset( $retarr['return']['content']['id'] ) ) {
                      $data[ $this->plugin_name . '_Id'] = $retarr['return']['content']['id'];
                    }
                    // Success code for admin notice
                    $successcode = 1;

                }
            } elseif ( $data[ $this->plugin_name . '_Id'] ) {
	              // Has Locationews Id but Locationews disabled
                // Delete from Locationews
                $ret = $locatioapi->delete( $data );
                // Success code for admin notice
                $successcode = 3;
                // Set Locationews Id to false
                $data[ $this->plugin_name . '_Id'] = false;
            }
        }
        // If post is not published, set Locationews Id to false
        if ( 'publish' != $post->post_status ) {
            $data[ $this->plugin_name . '_Id'] = false;
            $data[ $this->plugin_name ] = false;
        }
        // Set Locationews post meta
        $locatio_post_meta =
             array(
                'on'       => $data[ $this->plugin_name ],
                'ads'      => $data[ $this->plugin_name . '_ads'],
                'showmore' => $data[ $this->plugin_name . '_showmore'],
                'latlng'   => $data[ $this->plugin_name . '_coordinates'],
                'category' => $data[ $this->plugin_name . '_category'],
                'id'       => $data[ $this->plugin_name . '_Id'],
                'api'      => $data[ $this->plugin_name . '_api']
             );

        // Save Locationews post meta data
        update_post_meta( $post_id, $this->meta_name, $locatio_post_meta );

        // If API call was succesfull
        if ( $ret ) {

            // Convert JSON response to array
            $ret = json_decode( $ret, true );

            // If response is a valid array
            if ( is_array( $ret ) ) {

                // Uups, We do have an error
	            if ( isset( $ret['return']['error'] ) && ! isset( $ret['return']['content']['test'] ) ) {
	                $this->locationews_error( $ret['return']['msg'] );
	            } else {

					if ( isset( $ret['return']['content']['test'] ) ) {
						switch( $ret['return']['content']['test'] ) {
							case 'add_test':
								$successcode = 101;
								break;
							case 'update_test':
								$successcode = 102;
								break;
							case 'delete_test':
								$successcode = 103;
								break;
						}
					}

  	                // No errors means we did it! Success!
                    // Show it as admin notice
                    add_filter('redirect_post_location', function ( $loc ) use ( $successcode ) {
                        remove_query_arg('locationews-err');
                        return add_query_arg('locationews-msg', $successcode, $loc );
                    });
                }
            }
        }
        return $post_id;
    }

	/**
	 * Don't publish unpublished posts, so remove
	 *
	 * @param $new_status
	 * @param $old_status
	 * @param $post
	 */
	public function locationews_unpublished( $new_status, $old_status, $post ) {
	    // Delete post from Locationews API if status is not publish
        if ( !in_array( $new_status, array('publish', 'auto-draft') ) ) {
            $this->locationews_delete_post( $post->ID );
        }
    }

	/**
	 * Remove post from Locationews
	 *
	 * @param $postID
	 */
	public function locationews_delete_post( $postID ) {
        if ( $postID ) {
            $locationews_meta = get_post_meta( $postID, $this->meta_name, true );

            if ( ! isset( $locationews_meta['api'] ) ) {
                $locationews_meta['api'] = $this->options['apiUrl'];
            }

            if (isset( $locationews_meta['id'] ) && isset( $locationews_meta['on'] ) && $locationews_meta['on'] == 1 ) {
                $locatioapi = new Locationews_API( $this->options['apiUrl'], $this->options['jwt'], $this->plugin_name );
                $ret = $locatioapi->delete( array('Id' => $locationews_meta['id'] ), true );

                $successcode = 3;
                $ret = json_decode( $ret, true );

                if ( is_array( $ret ) ) {
                    if ( isset( $ret['return']['error'] ) ) {
	                    $this->locationews_error( $ret['return']['msg'] );
                    } else {
                        // Update post meta
                        $locatio_post_meta =
                            array(
                                'on'           => '',
                                'ads'          => $locationews_meta['ads'],
                                'showmore'     => $locationews_meta['showmore'],
                                'latlng'       => $locationews_meta['latlng'],
                                'category'     => $locationews_meta['category'],
                                'id'           => '',
                                'api'          => $locationews_meta['api'],
                                'post_status'  => 'removed'
                            );
                        update_post_meta( $postID, $this->meta_name, $locatio_post_meta );

                        if ( isset( $ret['return']['content']['test'] ) ) {
                            switch( $ret['return']['content']['test'] ) {
                                case 'delete_test':
                                    $successcode = 103;
                                    break;
                            }
                        }

                        add_filter('redirect_post_location', function ( $loc ) use ( $successcode ) {
                            return add_query_arg('locationews-msg', $successcode, $loc );
                        });
                    }
                }
            }
        }
    }

	/**
	 * Get categories from API and update options if necessary
	 */
	public function locationews_update_categories() {
	    $locatioapi = new Locationews_API( $this->options['apiUrl'], $this->options['jwt'], $this->plugin_name );
	    $ret = $locatioapi->get_categories( $this->front_options['lan'] );
	    $ret = json_decode( $ret, true );

	    if ( is_array( $ret ) ) {
		    if ( isset( $ret['return']['error'] ) ) {
		    	$this->locationews_error( $ret['return']['msg'] );
		    } elseif ( isset( $ret['return']['content'] ) ) {
			    // Success
			    if ( $ret['return']['content'] != $this->options['categories'] ) {
			    	if ( is_array( $ret['return']['content'] ) ) {
			    		if ( isset( $ret['return']['content'][0]['id'] ) && isset( $ret['return']['content'][0]['name'] ) ) {
			    			$this->options['categories'] = $ret['return']['content'];
			    			update_option('locationews_options', $this->options );
					    }
				    }
			    }
		    }
	    }
    }

	/**
	 * Metabox admin notices
	 */
	public function locationews_adminNotices() {
        // Do we have an error?
        if ( isset( $_GET['locationews-err'] ) ) {
            // Get current screen aka page aka view
            $screen = get_current_screen();
            // Make sure we are in the proper post type
            if ( in_array( $screen->post_type, $this->options['postTypes'] ) ) {
                // Get error codes from current url
                $errorcodes = explode(',', $_GET['locationews-err'] );
                $msg = array();
                // Do we have an error array?
                if ( is_array( $errorcodes ) ) {
                    // Loop through error codes
                    foreach ( $errorcodes as $errorcode ) {
                    	$msg[] = Locationews_Core::get_error_messages( $errorcode );
                    }
                }
                if ( ! empty( $msg ) ) {
	                  $this->_showAdminNotice( $msg, 'error is-dismissible' );
                }
            }
        } elseif ( isset( $_GET['locationews-msg'] ) ) {
            // No errors, success!
            // Set correct message based on code
	        $msg = Locationews_Core::get_success_messages( $_GET['locationews-msg'] );

            if ( ! empty( $msg ) && ! is_array( $msg ) ) {
	              $this->_showAdminNotice( $msg, 'updated notice notice-success is-dismissible' );
            }
        }
    }

	/**
	 * Shows the admin notice for the metabox
	 *
	 * @param $message
	 * @param string $type
	 */
	private function _showAdminNotice( $message, $type = 'error' ) {
        // Display admin message
        echo '<div class="' . esc_attr( $type ) . ' below-h2">
        <p><strong>' . __('Locationews', $this->plugin_name ) . '</strong></p>';
        if ( is_array( $message ) ) {
            echo '<ul>';
            foreach ( $message as $msg ) {
            	if ( ! is_array( $msg ) ) {
		            echo '<li>' . $msg . '.</li>';
	            }
            }
            echo '</ul>';
        } else {
            echo '<p>' . $message . '.</p>';
        }
        echo '</div>';
    }

	/**
	 * Add error codes to query params and display error message
	 *
	 * @param $errors
	 */
	public function locationews_error( $errors = array() ) {
		$errorcodesarr = array();
		$errorcodes = false;
		if ( is_array( $errors ) ) {
            foreach ($errors as $key => $err) {
                $errorcodesarr[] = $err['code'];
            }
            $errorcodes = urlencode(implode(',', $errorcodesarr));
            add_filter('redirect_post_location', function ( $loc ) use ( $errorcodes ) {
                return add_query_arg('locationews-err', $errorcodes, $loc );
            });
		}
	}

	/**
	 * Get Locationews categories
	 *
	 * @return array
	 */
	public function locationews_categories() {
		$categories = array();
		if ( is_array( $this->options['categories'] ) ) {
            foreach ($this->options['categories'] as $category) {
                $categories[] = array(
                    'name' => __($category['name'], $this->plugin_name),
                    'value' => $category['id']
                );
            }
        }
		usort( $categories, function( $a, $b ) {
			return strcmp( $a['name'], $b['name'] );
		});

        return $categories;
    }

	/**
	 * Get WP categories
	 *
	 * @return array
	 */
	public function locationews_wp_categories() {

        $categories[] = array(
            'id'          => 'locationews_categories_all',
            'name'        => 'locationews_user[defaultCategories][all]',
            'description' => __('All', $this->plugin_name ),
            'value'       => 'all',
            'value_id'    => 0
        );

        $wp_categories = get_categories( array(
            'orderby'     => 'name',
            'order'       => 'ASC',
            'hide_empty'  => '0'
        ) );

        foreach ( $wp_categories as $category ) {
            $categories[] = array(
                'id'          => 'locationews_categories_' . $category->slug,
                'name'        => 'locationews_user[defaultCategories][' . $category->slug . ']',
                'description' => __( $category->name, $this->plugin_name ),
                'value'       => $category->slug,
                'value_id'    => $category->term_id
            );
        }

        return $categories;

    }

	/**
	 * Get WP post types
	 *
	 * @return array
	 */
	public function locationews_post_types() {
        $post_types = array();
        $i = 0;
        foreach ( get_post_types( array( 'public' => true, 'show_ui' => true ), 'names' ) as $post_type ) {
            if ( $post_type != 'attachment' ) {
                $post_types[ $i ] = array(
                    'id'          => 'post_types-' . $post_type,
                    'name'        => 'locationews_user[postTypes][' . $post_type . ']',
                    'description' => __( $post_type, $this->plugin_name ),
                    'value'       => $post_type
                );
                $i++;
            }
        }
        return $post_types;
    }

	/**
	 * Creates select field
	 *
	 * @param $args
	 */
	public function locationews_field_select( $args ) {
		$defaults = array(
			'name'  => $this->plugin_name . '_user[' . $args['id'] . ']',
			'value' => '',
		);
		//apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->user_options[ $atts['id'] ] ) ) {
			$atts['value'] = $this->user_options[ $atts['id'] ];
		}
		include( plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-field-select.php');
	}

	/**
	 * Creates multiple checkbox fields
	 *
	 * @param $args
	 */
	public function locationews_field_multicheckbox( $args ) {
        $atts = wp_parse_args( $args, array() );
        if ( ! empty( $this->user_options[ $atts['id'] ] ) ) {
	        $atts['value'] = $this->user_options[ $atts['id'] ];
        }
        include( plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-field-multicheckbox.php');
    }

	/**
	 * Creates a Google Map location picker field
	 *
	 * @param $args
	 */
	public function locationews_field_google_map( $args ) {
		$defaults = array(
            'class'       => 'gllpLatlonPicker',
            'description' => '',
            'name'        => $this->plugin_name . '_user[' . $args['id'] . ']',
		);
        $atts = wp_parse_args( $args, $defaults );
        if ( ! empty( $this->user_options[ $atts['id'] ] ) ) {
	        $atts['value'] = $this->user_options[ $atts['id'] ];
        }
        include( plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-field-google-map.php');
    }

}
