<?php

class Wp_Temporary_Login_Without_Password_Deactivator {

	public static function deactivate() {

		$temporary_logins = Wp_Temporary_Login_Without_Password_Common::get_temporary_logins();

		$temporary_logins_data = array();
		if ( count( $temporary_logins ) > 0 ) {
			foreach ( $temporary_logins as $user ) {
				if ( $user instanceof WP_User ) {
					$temporary_logins_data[ $user->ID ] = $user->roles[0];
					$user = wp_update_user( array(
						'ID' => $user->ID,
						'role' => '',
					) );  // Downgrade role to none. So, user won't be able to login
				}
			}
		}

		$add = 'yes';
		update_option( 'temporary_logins_data', $temporary_logins_data, $add );
	}

}
