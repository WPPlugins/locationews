<?php
/**
 * Core functionalities
 *
 * @link       http://www.locationews.com
 * @since      1.1.0
 *
 * @package    Locationews
 * @subpackage Locationews/includes
 * @author     Antti Luokkanen <antti.luokkanen@gmail.com>
 */
class Locationews_Core {
	/**
	 * Returns an array of options names and fields types
	 *
	 * @return array
	 */
	public static function locationews_get_options_list() {
		return array(
			0 => array(
				'locationewsCategory',
				'text',
				__('Locationews Default Category')
			),
			1 => array(
				'defaultCategories',
				'text',
				__('Categories', 'locationews')
			),
			2 => array(
				'postTypes',
				'text',
				__('Post types', 'locationews')
			),
			3 => array(
				'location',
				'text',
				__('Default location', 'locationews')
			)
		);
	}

	/**
	 * Validate plugin options
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public static function locationews_validate_options( $input ) {
		$valid = array();
		$options = self::locationews_get_options_list();

		foreach ( $options as $option ) {
			$name = $option[0];
			$type = $option[1];

			if ( isset( $input[ $name ] ) ) {
				if ( is_array( $input[ $name ] ) ) {
					$valid[ $option[0] ] = $input[ $name ];
				} else {
					$valid[ $option[0] ] = self::locationews_sanitizer( $type, $input[ $name ] );
				}
			}
		}
		return $valid;
	}

	/**
	 * Sanitize user input
	 *
	 * @param $type
	 * @param $data
	 *
	 * @return null|string
	 */
	public static function locationews_sanitizer( $type, $data ) {
		if ( empty( $type )  || empty( $data ) ) {
			return null;
		}
		if ( $type == 'text') {
			if ( function_exists('sanitize_text_field') ) {
				return sanitize_text_field( $data );
			} else {
				return trim( filter_var( $data, FILTER_SANITIZE_STRING ) );
			}
		} else {
			return trim( strip_tags( $data ) );
		}
	}

	/**
	 * Return success messages
	 *
	 * @param bool $code
	 *
	 * @return array|mixed
	 */
	public static function get_success_messages( $code = false ) {
		$success = array(
			1   => __( 'Post successfully added', 'locationews'),
			2   => __( 'Post successfully updated', 'locationews'),
			3   => __( 'Post deleted from Locationews', 'locationews'),
			101 => __( 'Post successfully added. <strong>Note that this is just a testing enviroment. Your article was not really posted to Locationews API.</strong> Register your free account at <a href="https://locationews.com/en/" target="_blank">Locationews.com</a>', 'locationews'),
			102 => __( 'Post successfully updated. <strong>Note that this is just a testing enviroment. Your article was not really posted to Locationews API.</strong> Register your free account at <a href="https://locationews.com/en/" target="_blank">Locationews.com</a>', 'locationews'),
			103 => __( 'Post deleted from Locationews. <strong>Note that this is just a testing enviroment. Your article was not really posted to Locationews API.</strong> Register your free account at <a href="https://locationews.com/en/" target="_blank">Locationews.com</a>', 'locationews')
		);

		if ( false != $code  && isset( $success[ $code ] ) ) {
			return $success[ $code ];
		} else {
			return $success;
		}
	}

	/**
	 * Return error messages
	 *
	 * @param bool $code
	 *
	 * @return array|mixed
	 */
	public static function get_error_messages( $code = false ) {
		$errors = array(
			0  => __('General error', 'locationews'),
			1  => __('Invalid URL or request method', 'locationews'),
			2  => __('No Authorization header', 'locationews'),
			3  => __('JWT error', 'locationews'),
			4  => __('Missing required parameter', 'locationews'),
			5  => __('Forbidden parameter', 'locationews'),
			6  => __('Invalid value for parameter', 'locationews'),
			7  => __('Insufficient privileges to perform action', 'locationews'),
			8  => __('No news id', 'locationews'),
			9  => __('No user id', 'locationews'),
			10 => __('User not found', 'locationews'),
			11 => __('Error uploading image', 'locationews'),
			12 => __('Unable to delete file since the file does not exist', 'locationews'),
			13 => __('Image file not deleted since it does not belong to this news', 'locationews'),
			14 => __('Saving news data failed', 'locationews'),
			15 => __('No publication id', 'locationews'),
			16 => __('Publication not found', 'locationews'),
			17 => __('Error processing image', 'locationews'),
			18 => __('Error resizing image', 'locationews'),
			19 => __('Error saving resized image', 'locationews'),
			20 => __('Error saving image thumbnail', 'locationews'),
			21 => __('Error copying image, file not found', 'locationews')
		);

		if ( false != $code  && isset( $errors[ $code ] ) ) {
			return $errors[ $code ];
		} else {
			return $errors;
		}
	}

    /**
     * Get current enviroment based on API url
     *
     * @param string $api_url
     * @return string
     */
    public static function get_current_env( $api_url = '' ) {
        if ( strpos( $api_url, 'api_dev' ) !== false ) {
            return 'locationews_dev';
        } else {
            return 'locationews';
        }
    }

    /**
     * Prepare and validate html content
     *
     * @param string $input_content
     * @return string
     */
    public static function prepare_content( $input_content = '' ) {

    	// Do the magic later in the API
    	return $input_content;
		/*
	    $output_content = trim( preg_replace(
	        array(
                # Strip tags around content
                '/\<(.*)doctype(.*)\>/i',
                '/\<(.*)html(.*)\>/i',
                '/\<(.*)head(.*)\>/i',
                '/\<(.*)body(.*)\>/i',
                # Strip tags and content inside
                '/\<(.*)script(.*)\>(.*)<\/script>/i',
                '/<!--(.*)-->/Uis',
                '/\<(.*)audio(.*)\>(.*)<\/audio>/i',
                '/\<(.*)video(.*)\>(.*)<\/video>/i',
                '/\<(.*)button(.*)\>(.*)<\/button>/i',
                '/\<(.*)code(.*)\>(.*)<\/code>/i',
                '/\<(.*)form(.*)\>(.*)<\/form>/i',
                '/\<(.*)frame(.*)\>(.*)<\/frame>/i',
                '/\<(.*)frameset(.*)\>(.*)<\/frameset>/i',
                '/\<(.*)iframe(.*)\>(.*)<\/iframe>/i',
                '/\<(.*)select(.*)\>(.*)<\/select>/i',
                '/\<(.*)textarea(.*)\>(.*)<\/textarea>/i',
                '/\<(.*)noframes(.*)\>(.*)<\/noframes>/i',
                '/\<(.*)object(.*)\>(.*)<\/object>/i',
                '/\<(.*)canvas(.*)\>(.*)<\/canvas>/i',
                '/<input[^>]+\>/i'
            ), '', $input_content ) );

        return $output_content;
		*/
    }
}
