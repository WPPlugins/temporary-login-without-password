<?php

class Wp_Temporary_Login_Without_Password {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {

		$this->plugin_name = 'wp-temporary-login-without-password';
		$this->version = '1.4.2';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	public function define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-temporary-login-without-password-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-temporary-login-without-password-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-temporary-login-without-password-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-temporary-login-without-password-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-temporary-login-without-password-common.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-temporary-login-without-password-layout.php';

		$this->loader = new Wp_Temporary_Login_Without_Password_Loader();
	}

	private function set_locale() {

		$plugin_i18n = new Wp_Temporary_Login_Without_Password_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {

		$plugin_admin = new Wp_Temporary_Login_Without_Password_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'create_user' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'delete_user' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'manage_temporary_login' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );

		$this->loader->add_action( 'wp_ajax_tlwp_rated', $plugin_admin, 'tlwp_rated' );

		$this->loader->add_filter( 'wpmu_welcome_notification', $plugin_admin, 'disable_welcome_notification', 10, 5 );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'admin_footer_text', 1 );
	}

	private function define_public_hooks() {

		$plugin_public = new Wp_Temporary_Login_Without_Password_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'init_wtlwp' );
		$this->loader->add_filter( 'wp_authenticate_user', $plugin_public, 'disable_temporary_user_login', 10, 2 );
		$this->loader->add_filter( 'allow_password_reset', $plugin_public, 'disable_password_reset', 10, 2 );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
