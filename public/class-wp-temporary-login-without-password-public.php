<?php

class Wp_Temporary_Login_Without_Password_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public static function get_error_messages( $error_code ) {

		$error_messages = array(
			'token' => __( 'Token empty', 'wp-temporary-login-without-password' ),
			'unauth' => __( 'Authentication failed', 'wp-temporary-login-without-password' ),
		);

		if ( ! empty( $error_code ) ) {
			return (isset( $error_messages[ $error_code ] ) ? $error_messages[ $error_code ] : '');
		}

		return $error_messages;
	}

	public function init_wtlwp() {

		if ( ! is_user_logged_in() && ! empty( $_GET['wtlwp_token'] ) ) {

			$error_messages = array();

			$wtlwp_token = $_GET['wtlwp_token'];
			$users = Wp_Temporary_Login_Without_Password_Common::get_valid_user_based_on_wtlwp_token( $wtlwp_token );

			if ( empty( $users ) ) {
				wp_safe_redirect( home_url() );
			} else {
				$user = $users[0];

				$user_id = $user->ID;
				$user_login = $user->login;
				update_user_meta( $user_id, '_wtlwp_last_login', Wp_Temporary_Login_Without_Password_Common::get_current_gmt_timestamp() );
				wp_set_current_user( $user_id, $user_login );
				wp_set_auth_cookie( $user_id );

				$redirect_to = admin_url();
				$redirect_to_url = apply_filters( 'login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user );

				do_action( 'wp_login', $user_login, $user );

				// If empty redirect user to admin page.
				if ( ! empty( $redirect_to_url ) ) {
					$redirect_to = $redirect_to_url;
				}

				wp_safe_redirect( $redirect_to ); // Redirect to given url after successfull login
			}
			exit();
		}

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			if ( ! empty( $user_id ) && Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, false ) ) {
				if ( Wp_Temporary_Login_Without_Password_Common::is_login_expired( $user_id ) ) {
					wp_logout();
					wp_safe_redirect( home_url() );
					exit();
				} else {
					global $pagenow;
					$bloked_pages = Wp_Temporary_Login_Without_Password_Common::get_blocked_pages();
					$page = ! empty( $_GET['page'] ) ? $_GET['page'] : '';
					if ( ( ! empty( $page ) && in_array( $page, $bloked_pages )) || ( ! empty( $pagenow ) && in_array( $pagenow, $bloked_pages )) ) {
						wp_die( __( "You don't have permission to access this page", 'wp-temporary-login-without-password' ) );
					}
				}
			}
		}
	}

	/**
	 * Hooked to wp_authenticate_user filter to disable login for temporary user using username/email and password
	 *
	 * @param type $user
	 * @param type $password
	 * @return \WP_Error
	 */
	function disable_temporary_user_login( $user, $password ) {

		if ( $user instanceof WP_User ) {
			$check_expiry = false;
			$is_valid_temporary_login = Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user->ID, $check_expiry );
			if ( $is_valid_temporary_login ) {
				$user = new WP_Error( 'denied', __( "ERROR: User can't find." ) );
			}
		}

		return $user;
	}

	/**
	 * Hooked to allow_password_reset filter to disable reset password for temporary user
	 *
	 * @param boolean $allow
	 * @param type    $user_id
	 * @return boolean
	 */
	function disable_password_reset( $allow, $user_id ) {

		if ( is_int( $user_id ) ) {
			$check_expiry = false;
			$is_valid_temporary_login = Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, $check_expiry );
			if ( $is_valid_temporary_login ) {
				$allow = false;
			}
		}

		return $allow;
	}

}
