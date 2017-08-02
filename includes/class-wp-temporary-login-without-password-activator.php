<?php

class Wp_Temporary_Login_Without_Password_Activator {

	public static function activate() {

		$temporary_logins_data = get_option( 'temporary_logins_data', array() );

		if ( count( $temporary_logins_data ) > 0 ) {
			foreach ( $temporary_logins_data as $user_id => $user_role ) {
				wp_update_user( array(
					'ID' => $user_id,
					'role' => $user_role,
				) );
			}
		}

		$add = 'yes';

		// Empty set
		update_option( 'temporary_logins_data', array(), $add );
	}

}
