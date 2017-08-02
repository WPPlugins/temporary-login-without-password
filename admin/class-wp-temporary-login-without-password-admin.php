<?php

class Wp_Temporary_Login_Without_Password_Admin {

        private $plugin_name;

        private $version;

        public function __construct( $plugin_name, $version ) {

                $this->plugin_name = $plugin_name;
                $this->version = $version;

        }

        public function enqueue_styles() {
                wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-temporary-login-without-password-admin.css', array(), $this->version, 'all' );
                
                wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        }

        public function enqueue_scripts() {
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-temporary-login-without-password-admin.js', array( 'jquery' ), $this->version, false );
                wp_enqueue_script( 'clipboardjs', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), $this->version, false );

                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-datepicker' );
                
                $data = array(
                    'admin_ajax_url' => admin_url( 'admin-ajax.php', 'relative' )
                );
                
                wp_localize_script($this->plugin_name, 'data', $data);

        }

        public function admin_menu() {
                $current_user_id = get_current_user_id();
                if ( !Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $current_user_id ) ) {
                        add_users_page( __( 'Temporary Logins', 'wp-temporary-login-without-password' ), __( 'Temporary Logins', 'wp-temporary-login-without-password' ), apply_filters( 'tempadmin_user_cap', 'manage_options' ), 'wp-temporary-login-without-password', array( __class__, 'admin_settings' ) );
                }

        }

        public static function admin_settings() {
                $_template_file = WTLWP_PLUGIN_DIR . '/templates/admin_settings.php';
                $wtlwp_generated_url = !empty( $_REQUEST[ 'wtlwp_generated_url' ] ) ? $_REQUEST[ 'wtlwp_generated_url' ] : '';
                $user_email = !empty( $_REQUEST[ 'user_email' ] ) ? $_REQUEST[ 'user_email' ] : '';
                include $_template_file;

        }

        public function create_user() {

                if ( empty( $_POST[ 'wtlwp_data' ] ) || empty( $_POST[ 'wtlwp-nonce' ] ) ) {
                        return;
                }

                $data = $_POST[ 'wtlwp_data' ];
                $email = $data[ 'user_email' ];
                $error = false;

                $redirect_link = '';
                if ( false == Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp() ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'unathorised_access',
                        );
                        $error = true;
                } elseif ( !wp_verify_nonce( $_POST[ 'wtlwp-nonce' ], 'wtlwp_generate_login_url' ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'nonce_failed',
                        );
                        $error = true;
                } elseif ( empty( $data[ 'user_email' ] ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'empty_email',
                        );
                        $error = true;
                } elseif ( !is_email( $email ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'not_valid_email',
                        );
                        $error = true;
                } elseif ( !empty( $data[ 'user_email' ] ) && email_exists( $data[ 'user_email' ] ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'email_is_in_use',
                        );
                        $error = true;
                }

                if ( !$error ) {
                        $user = Wp_Temporary_Login_Without_Password_Common::create_new_user( $data );
                        if ( !empty( $user[ 'error' ] ) ) {
                                $result = array(
                                    'status' => 'error',
                                    'message' => 'user_creation_failed',
                                );
                        } else {
                                $result = array(
                                    'status' => 'success',
                                    'message' => 'user_created',
                                );
                                $redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
                                $redirect_link = add_query_arg( 'wtlwp_generated_url', Wp_Temporary_Login_Without_Password_Common::get_login_url( $user ), $redirect_link );
                                $redirect_link = add_query_arg( 'user_email', $email, $redirect_link );
                        }
                }

                if ( empty( $redirect_link ) ) {
                        $redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
                }

                wp_redirect( $redirect_link, 302 );
                exit();

        }

        public static function delete_user() {

                if ( (false === Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp()) || empty( $_REQUEST[ 'wtlwp_action' ] ) || ($_REQUEST[ 'wtlwp_action' ] != 'delete') || empty( $_REQUEST[ 'user_id' ] ) || (absint( $_REQUEST[ 'user_id' ] ) == 0) ) {
                        return;
                }

                $user_id = absint( $_REQUEST[ 'user_id' ] );
                $nonce = $_REQUEST[ 'manage-temporary-login' ];
                $redirect_url = '';
                $error = false;
                if ( !wp_verify_nonce( $nonce, 'manage-temporary-login_' . $user_id ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'nonce_failed',
                        );
                        $error = true;
                } elseif ( !Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, false ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'is_not_temporary_login',
                        );
                        $error = true;
                }

                if ( !$error ) {
                        $delete_user = wp_delete_user( absint( $user_id ), get_current_user_id() );
                        if ( !is_wp_error( $delete_user ) ) {
                                $result = array(
                                    'status' => 'success',
                                    'message' => 'user_deleted',
                                );
                        } else {
                                $result = array(
                                    'status' => 'error',
                                    'message' => 'default_error_message',
                                );
                        }
                }

                $redirect_url = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
                wp_redirect( $redirect_url, 302 );
                exit();

        }

        public static function manage_temporary_login() {

                if ( (false === Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp()) || empty( $_REQUEST[ 'wtlwp_action' ] ) || ($_REQUEST[ 'wtlwp_action' ] != 'disable' && $_REQUEST[ 'wtlwp_action' ] != 'enable') || empty( $_REQUEST[ 'user_id' ] ) || (absint( $_REQUEST[ 'user_id' ] ) == 0) ) {
                        return;
                }

                $error = false;
                $user_id = absint( $_REQUEST[ 'user_id' ] );
                $action = $_REQUEST[ 'wtlwp_action' ];
                $nonce = $_REQUEST[ 'manage-temporary-login' ];

                $is_valid_temporary_user = Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, false );

                if ( !$is_valid_temporary_user ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'is_not_temporary_login',
                        );
                        $error = true;
                } elseif ( !wp_verify_nonce( $nonce, 'manage-temporary-login_' . $user_id ) ) {
                        $result = array(
                            'status' => 'error',
                            'message' => 'nonce_failed',
                        );
                        $error = true;
                }

                if ( !$error ) {
                        if ( $action == 'disable' ) {
                                $disable_login = Wp_Temporary_Login_Without_Password_Common::manage_login( absint( $user_id ), 'disable' );
                                if ( $disable_login ) {
                                        $result = array(
                                            'status' => 'success',
                                            'message' => 'login_disabled',
                                        );
                                } else {
                                        $result = array(
                                            'status' => 'error',
                                            'message' => 'default_error_message',
                                        );
                                        $error = true;
                                }
                        } elseif ( $action == 'enable' ) {
                                $enable_login = Wp_Temporary_Login_Without_Password_Common::manage_login( absint( $user_id ), 'enable' );

                                if ( $enable_login ) {
                                        $result = array(
                                            'status' => 'success',
                                            'message' => 'login_enabled',
                                        );
                                } else {
                                        $result = array(
                                            'status' => 'error',
                                            'message' => 'default_error_message',
                                        );
                                        $error = true;
                                }
                        } else {
                                $result = array(
                                    'status' => 'error',
                                    'message' => 'invalid_action',
                                );
                                $error = true;
                        }// End if().
                }// End if().

                $redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
                wp_redirect( $redirect_link, 302 );
                exit();

        }

        public function display_admin_notices() {

                if ( empty( $_REQUEST[ 'page' ] ) || (empty( $_REQUEST[ 'page' ] ) && $_REQUEST[ 'page' ] !== 'wp-temporary-login-without-password') || !isset( $_REQUEST[ 'wtlwp_message' ] ) || (!isset( $_REQUEST[ 'wtlwp_error' ] ) && !isset( $_REQUEST[ 'wtlwp_success' ] )) ) {
                        return;
                }

                $class = $message = '';
                $error = !empty( $_REQUEST[ 'wtlwp_error' ] ) ? true : false;
                $success = !empty( $_REQUEST[ 'wtlwp_success' ] ) ? true : false;
                if ( $error ) {
                        $message_type = !empty( $_REQUEST[ 'wtlwp_message' ] ) ? $_REQUEST[ 'wtlwp_message' ] : 'default_error_message';
                        switch ( $message_type ) {
                                case 'user_creation_failed':
                                        $message = __( 'User creation failed', 'wp-temporary-login-without-password' );
                                        break;

                                case 'unathorised_access':
                                        $message = __( 'You do not have permission to create a temporary login', 'wp-temporary-login-without-password' );
                                        break;

                                case 'email_is_in_use':
                                        $message = __( 'Email already is in use', 'wp-temporary-login-without-password' );
                                        break;

                                case 'empty_email':
                                        $message = __( 'Please enter valid email address. Email field should not be empty', 'wp-temporary-login-without-password' );
                                        break;

                                case 'not_valid_email':
                                        $message = __( 'Please enter valid email address', 'wp-temporary-login-without-password' );
                                        break;

                                case 'is_not_temporary_login':
                                        $message = __( 'User you are trying to delete is not temporary', 'wp-temporary-login-without-password' );
                                        break;

                                case 'nonce_failed':
                                        $message = __( 'Nonce failed', 'wp-temporary-login-without-password' );
                                        break;

                                case 'invalid_action':
                                        $message = __( 'Invalid action', 'wp-temporary-login-without-password' );
                                        break;

                                case 'default_error_message':
                                default:
                                        $message = __( 'Unknown error occured', 'wp-temporary-login-without-password' );
                                        break;
                        }// End switch().
                        $class = 'error';
                } elseif ( $success ) {
                        $message_type = !empty( $_REQUEST[ 'wtlwp_message' ] ) ? $_REQUEST[ 'wtlwp_message' ] : 'default_success_message';
                        switch ( $message_type ) {
                                case 'user_created':
                                        $message = __( 'Login created successfully!', 'wp-temporary-login-without-password' );
                                        break;

                                case 'user_deleted':
                                        $message = __( 'Login deleted successfully!', 'wp-temporary-login-without-password' );
                                        break;

                                case 'login_disabled':
                                        $message = __( 'Login disabled successfully!', 'wp-temporary-login-without-password' );
                                        break;

                                case 'login_enabled':
                                        $message = __( 'Login enabled successfully!', 'wp-temporary-login-without-password' );
                                        break;

                                default:
                                        $message = __( 'Success!', 'wp-temporary-login-without-password' );
                                        break;
                        }

                        $class = 'updated';
                }// End if().

                $class .= ' notice notice-succe is-dismissible';

                if ( !empty( $message ) ) {
                        $notice = '';
                        $notice .= '<div id="notice" class="' . $class . '">';
                        $notice .= '<p>' . esc_attr( $message ) . '</p>';
                        $notice .= '</div>';

                        echo $notice;
                }

                return;

        }

        /**
         *
         * Disable welcome notification for temporary user.
         */
        public function disable_welcome_notification( $blog_id, $user_id, $password, $title, $meta ) {

                if ( !empty( $user_id ) ) {
                        $check_expiry = false;
                        if ( Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, $check_expiry ) ) {
                                return false;
                        }
                }

                return true;

        }

        /**
         * Change the admin footer text on temporary login admin pages.
         *
         * @since  1.5
         * @param  string $footer_text
         * @return string
         */
        public function admin_footer_text( $footer_text ) {

                $current_screen = get_current_screen();

                if ( isset( $current_screen->id ) && $current_screen->id == 'users_page_wp-temporary-login-without-password' ) {

                        // Change the footer text
                        if ( !get_option( 'tlwp_admin_footer_text_rated' ) ) {
                                /* translators: %s: five stars */
                                $footer_text = sprintf( __( 'If you like <strong>Temporary Login Without Password</strong> please leave us a %s rating. A huge thanks in advance!', 'wp-temporary-login-without-password' ), '<a href="https://wordpress.org/support/plugin/temporary-login-without-password/reviews?rate=5#new-post" target="_blank" class="tlwp-rating-link" data-rated="' . esc_attr__( 'Thank You :)', 'wp-temporary-login-without-password' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>' );
                        } else {
                                $footer_text = sprintf(__( 'Thank you for using %s.', 'wp-temporary-login-without-password' ), '<a href="https://wordpress.org/plugins/temporary-login-without-password/" target="_blank">Temporary Login Without Password</a>');
                        }
                }

                return $footer_text;

        }

        /**
         * Triggered when clicking the rating footer.
         */
        public static function tlwp_rated() {
                update_option( 'tlwp_admin_footer_text_rated', 1 );
                wp_die();

        }

}
