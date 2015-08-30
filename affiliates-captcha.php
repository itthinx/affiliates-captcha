<?php
/**
 * affiliates-captcha.php
 *
 * Copyright (c) 2015 www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package affiliates-captcha
 * @since affiliates-captcha 1.0.0
 *
 * Plugin Name: Affiliates Captcha
 * Plugin URI: https://github.com/itthinx/affiliates-captcha
 * Description: Affiliates registration Captcha integration.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 */

class Affiliates_Captcha {

	/**
	 * Adds action and filter hooks.
	 */
	public static function init() {
		add_filter( 'affiliates_captcha_get', array( __CLASS__, 'affiliates_captcha_get' ), 10, 2 );
		add_filter( 'affiliates_captcha_validate', array( __CLASS__, 'affiliates_captcha_validate' ), 10, 2 );
	}

	/**
	 * Renders the captcha field.
	 * 
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	public static function affiliates_captcha_get( $field, $value ) {

		global $affiliates_captcha_error;

		if ( !isset( $affiliates_captcha_error ) ) {
			$affiliates_captcha_error = null;
		}

		$field = '';
		if( function_exists( 'cptch_display_captcha_custom' ) ) {
			$field_error = '';
			if ( !empty( $affiliates_captcha_error ) ) {
				$field_error = '<div class="error">';
				$field_error .= __( 'Please solve the captcha to proof that you are human.', 'affiliates-captcha' );
				$field_error .= '</div>';
			}

			$field .= '<div class="field">';
			$field .= '<label>';
			$field .= "<input type='hidden' name='cntctfrm_contact_action' value='true' />";
			$field .= cptch_display_captcha_custom();
			$field .= '</label>';
			$field .= '</div>';
			$field .= apply_filters( 'affiliates_captcha_field_error', $field_error );
		}
		return $field;
	}

	/**
	 * Validates the captcha.
	 * 
	 * @param boolean $result
	 * @param string $field_value
	 * @return boolean
	 */
	public static function affiliates_captcha_validate( $result, $field_value ) {

		global $affiliates_captcha_error;

		$result = true;
		if ( function_exists( 'cptch_check_custom_form' ) ) {
			$result = ( cptch_check_custom_form() === true );
		}
		if ( !$result ) {
			$affiliates_captcha_error = true;
		}
		return $result;
	}

}
add_action( 'init', array( 'Affiliates_Captcha', 'init' ) );
