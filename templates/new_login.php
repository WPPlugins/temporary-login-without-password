<h2> <?php echo __( 'Create a new Temporary Login', 'wp-temporary-login-without-password' ); ?></h2>
<form method="post">
	<table class="form-table wtlwp-form">
		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row"> <label for="user_email"><?php echo __( 'Email*', 'wp-temporary-login-without-password' ); ?> </label></th>
			<td><input name="wtlwp_data[user_email]" type="text" id="user_email" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" class="wtlwp-form-input"/></td>
		</tr>

		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row"> <label for="user_first_name"><?php echo __( 'First Name', 'wp-temporary-login-without-password' ); ?> </label></th>
			<td><input name="wtlwp_data[user_first_name]" type="text" id="user_first_name" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" class="wtlwp-form-input"/></td>
		</tr>

		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row"> <label for="user_last_name"><?php echo __( 'Last Name', 'wp-temporary-login-without-password' ); ?> </label></th>
			<td><input name="wtlwp_data[user_last_name]" type="text" id="user_last_name" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" class="wtlwp-form-input"/></td>
		</tr>
		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row"><label for="adduser-role"><?php echo __( 'Role', 'wp-temporary-login-without-password' ); ?></label></th>
			<td><select name="wtlwp_data[role]" id="user-role">
					<?php wp_dropdown_roles( 'administrator' ); ?>
				</select>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row"><label for="adduser-role"><?php echo __( 'Expiry', 'wp-temporary-login-without-password' ); ?></label></th>
			<td>
                <span id="expiry-date-selection">
                        <select name="wtlwp_data[expiry]" id="user-expiry-time">
					<?php Wp_Temporary_Login_Without_Password_Common::get_expiry_duration_html(); ?>
				</select>
                </span>
                
                <span style="display:none;" id="custom-date-picker">
                    <input type="date" id="datepicker" name="wtlwp_data[custom_date]" value="" class="example-datepicker" />
                </span>
                
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row"><label for="adduser-role"></label></th>
			<td><p class="submit"><input type="submit" class="button button-primary wtlwp-form-submit-button" value="<?php _e( 'Submit', 'wp-temporary-login-without-password' ); ?>" class="button button-primary" id="generatetemporarylogin" name="generate_temporary_login"> <?php _e( 'or', 'wp-temporary-login-without-password' ); ?> <span class="cancel-new-login-form" id="cancel-new-login-form"><?php _e( 'Cancel', 'wp-temporary-login-without-password' ); ?></span></p>
			</td>
		</tr>
		<?php wp_nonce_field( 'wtlwp_generate_login_url', 'wtlwp-nonce', true, true ); ?>
	</table>
</form>