<?php

/**
 * Define the internationalization functionality
 */
class Wp_Temporary_Login_Without_Password_i18n {

	static $text_domain = 'wp-temporary-login-without-password';

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			self::$text_domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}
