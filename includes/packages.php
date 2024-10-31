<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Office_Locator
 * @subpackage Office_Locator/includes
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version = OFFICE_LOCATOR_VERSION;
		$this->plugin_name = OFFICE_LOCATOR_NAME;
		$this->load_dependencies();
		$this->set_locale();			

		add_filter( 'plugin_action_links_' . plugin_basename( OFFICE_LOCATOR_PLUGIN_FILE ), array( $this, 'plugin_setting_link' ) );
		add_action( 'init', array( $this, 'set_olc_global_variable' ) );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Office_Locator_i18n. Defines internationalization functionality.
	 * - Office_Locator_Admin. Defines all hooks for the admin area.
	 * - Office_Locator_Public. Defines all hooks for the public side of the site.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {		

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		/**
		 * The class responsible for defining array of plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-setting.php';

		/**
		 * The class responsible for defining all methods that used in the plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-field-functions.php';

		/**
		 * The class responsible for defining all custom setting offices that used in the plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-office-locator-custom-setting.php';

		/**
		 * The class responsible for defining all offices that used in the plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-offices-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';

		$plugin_admin = new Office_Locator_Admin( $this->get_plugin_name(), $this->get_version() );
		new Manage_Office_Locator( $this->get_plugin_name(), $this->get_version() );
		new Office_Locator_Custom_Settings( $this->get_plugin_name(), $this->get_version() );
		$plugin_public = new Office_Locator_Public( $this->get_plugin_name(), $this->get_version() );

	}
	/**
	* Set Custom Global Variables 
	*/
	public function set_olc_global_variable(){

		$GLOBALS['olc_map_markers'] = array(
			'store-blue' => array(
				'title' => __( 'Blue', 'office-locator' ),
				'img' => plugins_url( 'admin/images/user-marker/blue.png', dirname(__FILE__) )
			),
			'store-green' => array(
				'title' => __( 'Green', 'office-locator' ),
				'img' => plugins_url( 'admin/images/user-marker/green.png', dirname(__FILE__) )
			),
			'store-orange' => array(
				'title' => __( 'Orange', 'office-locator' ),
				'img' => plugins_url( 'admin/images/user-marker/orange.png', dirname(__FILE__) )
			),
			'store-purple' => array(
				'title' => __( 'Purple' , 'office-locator' ),       
				'img' => plugins_url( 'admin/images/user-marker/purple.png', dirname(__FILE__) )
			),
			'store-red' => array(
				'title' => __( 'Red' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/user-marker/red.png', dirname(__FILE__) )
			),
			'store-yellow' => array(
				'title' => __( 'Yellow' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/user-marker/yellow.png', dirname(__FILE__) )
			)
		);
		$GLOBALS['olc_map_styles'] = array(
			'standard' => array(
				'title' => __( 'Standard' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/staticmap.png', dirname(__FILE__) )
			),
			'silver' => array(
				'title' => __( 'Silver' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/silver.png', dirname(__FILE__) )
			),
			'retro' => array(
				'title' => __( 'Retro' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/retro.png', dirname(__FILE__) )
			),
			'dark' => array(
				'title' => __( 'Dark' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/dark.png', dirname(__FILE__) )
			),
			'night' => array(
				'title' => __( 'Night' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/night.png', dirname(__FILE__) )
			),
			'aubergine' => array(
				'title' => __( 'Aubergine' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/aubergine.png', dirname(__FILE__) )
			)
		);

		$GLOBALS['olc_map_layout'] = array(
			'layout-1' => array(
				'title' => __( 'Layout 1' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-1.png', dirname(__FILE__) )
			),
			'layout-2' => array(
				'title' => __( 'Layout 2' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-2.png', dirname(__FILE__) )
			),
			'layout-3' => array(
				'title' => __( 'Layout 3' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-3.png', dirname(__FILE__) )
			),
			'layout-4' => array(
				'title' => __( 'Layout 4' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-4.png', dirname(__FILE__) )
			),
			'layout-5' => array(
				'title' => __( 'Layout 5' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-5.png', dirname(__FILE__) )
			),
			'layout-6' => array(
				'title' => __( 'Layout 6' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-6.png', dirname(__FILE__) )
			),
			'layout-7' => array(
				'title' => __( 'Layout 7' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-7.png', dirname(__FILE__) )
			),
			'layout-8' => array(
				'title' => __( 'Layout 8' , 'office-locator' ),
				'img' => plugins_url( 'admin/images/map-styles/Layout-8.png', dirname(__FILE__) )
			)
		);

		global $olc_map_markers, $olc_map_styles;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Office_Locator_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Office_Locator_i18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 * @return array
	 */
	public function plugin_setting_link( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.$this->plugin_name ) . '">' . esc_html__( 'Settings', 'office-locator' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}