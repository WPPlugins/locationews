<?php
/**
 * API calls
 *
 * @link       http://www.locationews.com
 * @since      1.0.0
 *
 * @package    Locationews
 * @subpackage Locationews/includes
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews_API {

    /**
	 * API url
	 */
	private $url = '';

    /**
     * Unique authorization token
     */
    private $token;

    /**
     * Plugin name
     */
    private $plugin_name;

	/**
	 * Locationews_API constructor.
	 *
	 * @param $url
	 * @param $token
	 * @param $plugin_name
	 */
	public function __construct( $url, $token, $plugin_name ) {
	    $this->url = $url;
        $this->token = $token;
        $this->plugin_name = $plugin_name;
	}

	/**
	 * Add news
	 *
	 * @param array $args
	 *
	 * @return mixed|string|void
	 */
	public function add( $args = array() ) {
		$return = array();
	    // Format and validate data
	    $data = $this->prepare_data( $args );
		// If we have valid data
        if ( $data ) {
        	// New post so no id
			unset( $data['Id'] );
			// Call API
            $return = $this->call('POST', rtrim( $this->url, '/') . '/news', $data ) ;
            if ( isset( $return['content']['test'] ) ) {
                $return['content']['test'] = 'add_test';
            }
        }

        return json_encode( array(
            'action'    => 'add',
            'return'    => $return,
            'data'      => $data
        ) );

    }

    /**
     * Update news
     *
     * @param array $args
     * @return mixed|string|void
     */
    public function update( $args = array() ) {
        $return = array();

	    // Format and validate data
        $data = $this->prepare_data( $args );
		// If we have valid data
        if ( $data ) {
        	// Call API
            $return = $this->call('POST', rtrim( $this->url, '/') . '/news/' . $data['Id'],  $data );
            if ( isset( $return['content']['test'] ) ) {
                $return['content']['test'] = 'update_test';
            }
        }
		// Return
        return json_encode( array(
            'action'    => 'update',
            'return'    => $return,
            'data'      => $data
        ) );

    }

	/**
	 * Delete news
	 *
	 * @param array $args
	 * @param bool $status_change
	 *
	 * @return mixed|string|void
	 */
	public function delete( $args = array(), $status_change = false ) {
        $return = array();

        if ( $status_change == true ) {
            $data = $args;
        } else {
            $data = $this->prepare_data( $args );
        }
        if ( $data ) {
            $return = $this->call('DELETE', rtrim( $this->url, '/') . '/news/' . $data['Id'],  $data );
            if ( isset( $return['content']['test'] ) ) {
                $return['content']['test'] = 'delete_test';
            }
        }
		// Return
        return json_encode( array(
            'action'    => 'delete',
            'return'    => $return,
            'data'      => $data
        ) );

    }

	/**
	 * Get categories
	 *
	 * @param string $lan
	 *
	 * @return mixed|string|void
	 */
	public function get_categories( $lan = 'en') {
    	$return = $this->call('GET', rtrim( $this->url, '/') . '/categories?lan=' . $lan );

    	return json_encode( array(
    		'action'    => 'update_categories',
		    'return'    => $return
	    ) );
    }

    /**
     * Prepare user data for API
     *
     * @param array $args
     * @return array
     */
    private function prepare_data( $args = array() ) {

	    $data = array(
        	'Id'            => isset( $args[ $this->plugin_name . '_Id'] ) ? filter_var( $args[ $this->plugin_name . '_Id'], FILTER_SANITIZE_STRING ) : false,
	        'title'         => filter_var( $args['post_title'], FILTER_SANITIZE_STRING ),
	        'created'       => date("Y-m-d H:i:s"),
	        'text'          => $args['content'],
	        'url'           => filter_var( $args['url'], FILTER_SANITIZE_URL ),
            'image'         => filter_var( $args['image'], FILTER_SANITIZE_URL ),
			'showMore'      => false != filter_var( $args[ $this->plugin_name . '_showmore'], FILTER_VALIDATE_INT ) ? 1 : 0,
			'ads'           => false != filter_var( $args[ $this->plugin_name . '_showmore'], FILTER_VALIDATE_INT ) ? 1 : 0,
	        'publicationId' => filter_var( $args[ $this->plugin_name . '_publicationId'], FILTER_SANITIZE_NUMBER_INT ),
			'categoryId'    => false != filter_var( $args[ $this->plugin_name . '_category'], FILTER_SANITIZE_NUMBER_INT ) ? filter_var( $args[ $this->plugin_name . '_category'], FILTER_SANITIZE_NUMBER_INT ) : 1,
	    );

	    $latlong = filter_var( $args[ $this->plugin_name . '_coordinates'], FILTER_SANITIZE_STRING );

	    if ( strpos( $latlong, ',' ) !== false ) {
		    list( $data['latitude'], $data['longitude'] ) = explode( ',', $latlong );
	    } else {
	    	$data['latitude'] = '';
	    	$data['longitude'] = '';
	    }

	    return $data;
    }

    /**
     * Connect and send requests to API
     *
     * @param string $method
     * @param string $url
     * @param array $args
     * @return array
     */
    private function call( $method = 'POST', $url = '', $args = array() ) {

        // Test use
        // Return success
        if ( $this->token == 'plugintest') {
            return array(
                'success'   => '1',
                'url'       => $url,
                'content'   => array('test' => 1,'id' => rand(1,1000) )
            );
        }

        $ch = curl_init();
        $headers = array(
	        'Content-Type: multipart/form-data;',
	        'Authorization: Bearer ' . trim( $this->token )
        );

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        if ( $method == 'DELETE' ) {
	        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        } elseif ( $method == 'GET') {
        } else {
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $args );
        }

        $curl_response = curl_exec( $ch );
        curl_close( $ch );

        if ( $curl_response === false ) {
            return array(
                'error' => '1',
                'msg'   => 'locationews curl connection error.'
            );
        } else {
            $arr = json_decode( $curl_response, true );
            if ( isset( $arr['response']['status'] ) && $arr['response']['status'] == 'ERROR' ) {
                return array(
                    'error' => '1',
                    'msg'   => 'locationews curl response error: ' . $arr['response']['errormessage'] . '.'
                );
            } else {
                if ( isset( $arr['errors'] ) ) {
                    return array(
                        'error' => '1',
                        'msg'   => $arr['errors'],
	                    'url'   => $url
                    );
                } else {
                    return array(
                        'success'   => '1',
                        'url'       => $url,
                        'content'   => $arr
                    );
                }
            }
        }
    }
}
