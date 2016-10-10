<?php
/*
  Plugin Name: DK PDF Generator
  Version: 1.3
  Plugin URI: http://wp.dinamiko.com/demos/dkpdf-generator
  Description: Create PDF documents with your selected posts, pages and custom post types.
  Author: Emili Castells
  Author URI: http://www.dinamiko.com
  Text Domain: dkpdfg
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'DKPDFG' ) ) {

	final class DKPDFG {

		private static $instance;

		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof DKPDFG ) ) {

				self::$instance = new DKPDFG;

				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'dkpdfg_load_textdomain' ) );				
				
				self::$instance->includes();	

			}

			return self::$instance;

		}

		public function dkpdfg_load_textdomain() {

			load_plugin_textdomain( 'dkpdfg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

		}

		private function setup_constants() {

			if ( ! defined( 'DKPDFG_VERSION' ) ) { define( 'DKPDFG_VERSION', '1.3' ); }
			if ( ! defined( 'DKPDFG_PLUGIN_DIR' ) ) { define( 'DKPDFG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); }
			if ( ! defined( 'DKPDFG_PLUGIN_URL' ) ) { define( 'DKPDFGPLUGIN_URL', plugin_dir_url( __FILE__ ) ); }
			if ( ! defined( 'DKPDFG_PLUGIN_FILE' ) ) { define( 'DKPDFG_PLUGIN_FILE', __FILE__ ); }			

		}

		private function includes() {
			
			if ( is_admin() ) {

				// Include the TGM_Plugin_Activation class
				require_once DKPDFG_PLUGIN_DIR . 'includes/class-tgm-plugin-activation.php';

				// settings
				require_once DKPDFG_PLUGIN_DIR . 'includes/class-dkpdfg-settings.php';
				$settings = new DKPDFG_Settings( $this );

				require_once DKPDFG_PLUGIN_DIR . 'includes/class-dkpdfg-admin-api.php';
				$this->admin = new DKPDFG_Admin_API();

			}

			// css / js
			require_once DKPDFG_PLUGIN_DIR . 'includes/dkpdfg-load-js-css.php';	

			// functions
			require_once DKPDFG_PLUGIN_DIR . 'includes/dkpdfg-functions.php';

			// templates 
			require_once DKPDFG_PLUGIN_DIR . 'includes/class-dkpdfg-template-loader.php';

		}

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'dkpdfg' ), DKPDFG_VERSION );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'dkpdfg' ), DKPDFG_VERSION );
		}

	}

}

/**
* Creates an instance of DK PDF Generator
*/
function DKPDFG() {

	return DKPDFG::instance();

}

DKPDFG();

/**
* Register required plugins
* since 1.3 
* http://tgmpluginactivation.com/download/
*/
add_action( 'tgmpa_register', 'dkpdfg_register_required_plugins' );
function dkpdfg_register_required_plugins() {

	$plugins = array(
		array(
			'name'        => 'DK PDF',
			'slug'      => 'dk-pdf',
			'required'  => true,
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'dkpdfg',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'dkpdfg' ),
			'menu_title'                      => __( 'Install Plugins', 'dkpdfg' ),
			'installing'                      => __( 'Installing Plugin: %s', 'dkpdfg' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'dkpdfg' ),
			'notice_can_install_required'     => _n_noop(
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop(
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop(
				'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop(
				'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop(
				'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
				'dkpdfg'
			), // %1$s = plugin name(s).
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'dkpdfg'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'dkpdfg'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'dkpdfg'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'dkpdfg' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'dkpdfg' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'dkpdfg' ),
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'dkpdfg' ),  // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'dkpdfg' ),  // %1$s = plugin name(s).
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'dkpdfg' ), // %s = dashboard link.
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'dkpdfg' ),

			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),
		*/
	);

	tgmpa( $plugins, $config );
}



