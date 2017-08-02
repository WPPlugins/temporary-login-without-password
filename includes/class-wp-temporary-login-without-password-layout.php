<?php

class Wp_Temporary_Login_Without_Password_Layout {

	public static function prepare_header_footer_row() {

		$row = '';

		$row .= '<th class="manage-column column-details" colspan="2">' . __( 'Users', 'wp-temporary-login-without-password' ) . '</th>';
		$row .= '<th class="manage-column column-email">' . __( 'Role', 'wp-temporary-login-without-password' ) . '</th>';
		$row .= '<th class="manage-column column-expired">' . __( 'Last Logged In', 'wp-temporary-login-without-password' ) . '</th>';
		$row .= '<th class="manage-column column-expired">' . __( 'Expiry', 'wp-temporary-login-without-password' ) . '</th>';
		$row .= '<th class="manage-column column-expired">' . __( 'Actions', 'wp-temporary-login-without-password' ) . '</th>';

		return $row;
	}

	public static function prepare_empty_user_row() {

		$row = '';

		$row .= '<tr class="tempadmin-single-user-row tempadmin-empty-users-row standard">';
		$row .= '<td colspan="6">';
		$row .= '<span class="description">' . __( 'You have not created any temporary logins yet.', 'wp-temporary-login-without-password' ) . '</span>';
		$row .= '</td>';
		$row .= '</tr>';

		return $row;
	}

	public static function prepare_single_user_row( $user = OBJECT, $class = 'standard' ) {
		global $wpdb;
		if ( is_numeric( $user ) && ! is_object( $user ) ) {
			$user = get_user_by( 'id', $user );
		}

		$create = get_user_meta( $user->ID, '_wtlwp_created', true );
		$expire = get_user_meta( $user->ID, '_wtlwp_expire', true );
		$token = get_user_meta( $user->ID, '_wtlwp_token', true );
		$last_login_time = get_user_meta( $user->ID, '_wtlwp_last_login', true );

		$last_login_str = __( 'Not yet logged in', 'wp-temporary-login-without-password' );
		if ( ! empty( $last_login_time ) ) {
			$last_login_str = Wp_Temporary_Login_Without_Password_Common::time_elapsed_string( $last_login_time, true );
		}

		$wtlwp_status = 'Active';
		if ( Wp_Temporary_Login_Without_Password_Common::is_login_expired( $user->ID ) ) {
			$wtlwp_status = 'Expired';
		}

		$capabilities = $user->{$wpdb->prefix . 'capabilities'};
		$wp_roles = new WP_Roles();
		$user_role = '';
		foreach ( $wp_roles->role_names as $role => $name ) {
			if ( array_key_exists( $role, $capabilities ) ) {
				$user_role = $name;
			}
		}

		$user_details = '<div><span>';
		if ( (esc_attr( $user->first_name )) ) {
			$user_details .= '<span>' . esc_attr( $user->first_name ) . '</span>';
		}

		if ( (esc_attr( $user->last_name )) ) {
			$user_details .= '<span> ' . esc_attr( $user->last_name ) . '</span>';
		}

		$user_details .= "  (<span class='wtlwp-user-login'>" . esc_attr( $user->user_login ) . ')</span><br />';

		if ( (esc_attr( $user->user_email )) ) {
			$user_details .= '<span><b>' . esc_attr( $user->user_email ) . '</b></span> <br />';
		}

		$user_details .= '</span></div>';

		$row = '';

		$row .= '<tr id="single-user-' . absint( $user->ID ) . '" class="tempadmin-single-user-row">';
		$row .= '<td class="email column-details" colspan="2">' . $user_details . '</td>';
		$row .= '<td class="wtlwp-token column-role">' . esc_attr( $user_role ) . '</td>';
		$row .= '<td class="wtlwp-token column-last-login">' . esc_attr( $last_login_str ) . '</td>';

		$row .= '<td class="expired column-expired wtlwp-status-' . strtolower( $wtlwp_status ) . '">';
		if ( ! empty( $expire ) ) {
			$row .= Wp_Temporary_Login_Without_Password_Common::time_elapsed_string( $expire );
		}
		$row .= '</td>';
		$row .= '<td class="wtlwp-token column-email">' . self::prepare_row_actions( $user, $wtlwp_status ) . '</td>';
		$row .= '</tr>';

		return $row;
	}

	public static function prepare_row_actions( $user, $wtlwp_status ) {

		$action_row = '<div class="actions">';

		$user_id = $user->ID;

		$delete_login_url = Wp_Temporary_Login_Without_Password_Common::get_manage_login_url( $user_id, 'delete' );
		$disable_login_url = Wp_Temporary_Login_Without_Password_Common::get_manage_login_url( $user_id, 'disable' );
		$enable_login_url = Wp_Temporary_Login_Without_Password_Common::get_manage_login_url( $user_id, 'enable' );

		if ( strtolower( $wtlwp_status ) == 'expired' ) {
			$action_row .= '<span class="enable"><a title="' . __( 'Reactivate for one day', 'wp-temporary-login-without-password' ) . '" href="' . $enable_login_url . '"><span class="dashicons dashicons-lock"></a></span></span>';
		} elseif ( strtolower( $wtlwp_status ) == 'active' ) {
			$action_row .= '<span class="disable"><a title="' . __( 'Disable', 'wp-temporary-login-without-password' ) . '" href="' . $disable_login_url . '"><span class="dashicons dashicons-unlock"></span></a></span>';
		}

		$action_row .= '<span class="edit"><a title="' . __( 'Delete', 'wp-temporary-login-without-password' ) . '" href="' . $delete_login_url . '"><span class="dashicons dashicons-no"></span></a></span>';
		$action_row .= '<span class="edit"><span id="text-' . $user->ID . '" class="dashicons dashicons-admin-links wtlwp-copy-to-clipboard" title="' . __( 'Copy login link', 'wp-temporary-login-without-password' ) . '" data-clipboard-text="' . Wp_Temporary_Login_Without_Password_Common::get_login_url( $user->ID ) . '"></span></span>';
		$action_row .= '<span id="copied-text-' . $user->ID . '" class="copied-text-message"></span>';
		$action_row .= '</div>';

		return $action_row;
	}

}
