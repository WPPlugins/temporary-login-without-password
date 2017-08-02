<?php ?>
<div class="wrap wtlwp-settings-wrap">
	<h2> <?php echo __( 'Temporary Logins', 'wp-temporary-login-without-password' ); ?> <span class="page-title-action" id="add-new-wtlwp-form-button"><?php _e( 'Create New', 'wp-temporary-login-without-password' ); ?></span> </h2>
	<div class="wtlwp-settings">
		<!-- Add New Form Start -->
		<div class="wrap new-wtlwp-form" id="new-wtlwp-form">
			<?php load_template( WTLWP_PLUGIN_DIR . '/templates/new_login.php' ); ?>
		</div>
		
		<?php if ( ! empty( $wtlwp_generated_url ) ) { ?>
		
		<div class="wrap generated-wtlwp-login-link" id="generated-wtlwp-login-link">
		   <p><?php _e( "Here's a temporary login link", 'wp-temporary-login-without-password' ); ?></p>
		   <input id="wtlwp-click-to-copy-btn" type="text" class="wtlwp-wide-input" value="<?php echo $wtlwp_generated_url; ?>">
		   <button class="wtlwp-click-to-copy-btn" data-clipboard-action="copy" data-clipboard-target="#wtlwp-click-to-copy-btn"><?php echo __( 'Click To Copy', 'wp-temporary-login-without-password' ); ?></button>
		   <span id="copied-text-message-wtlwp-click-to-copy-btn"></span>
		   <p><?php _e( 'User can directly login to wordpress admin panel without username and password by opening this link.', 'wp-temporary-login-without-password' );
			if ( ! empty( $user_email ) ) {
				echo __( sprintf( " <a href='mailto:%s'>Email</a> copied login link to user.", $user_email ), 'wp-temporary-login-without-password' );
			}
			?>
		   </p>
		   
		</div>
		<?php } ?>
		<!-- Add New Form End -->

		<!-- List All Generated Logins Start -->
		<div class="wrap list-wtlwp-logins" id="list-wtlwp-logins">
			<?php load_template( WTLWP_PLUGIN_DIR . '/templates/list_temporary_logins.php' ); ?>
		</div>
		<!-- List All Generated Logins End -->
	</div>
</div>
