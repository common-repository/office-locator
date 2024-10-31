<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/admin
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name     The name of this plugin.
	 * @param    string    $version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_admin_dependencies();

		add_action( 'admin_menu', array( $this, 'register_plugin_menu' ) );
		add_action( 'after_setup_theme', array( $this, 'include_override_template_functions' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );				

		add_action( "wp_ajax_".$this->plugin_name."_datasave", array( $this, "plugin_datasave" ) );
		add_action( "wp_ajax_nopriv_".$this->plugin_name."_datasave", array( $this, "plugin_datasave" ) );

		add_action( "wp_ajax_".$this->plugin_name."_datareset", array( $this, "plugin_datareset" ) );
		add_action( "wp_ajax_nopriv_".$this->plugin_name."_datareset", array( $this, "plugin_datareset" ) );

	}	

	/**
	* That function add extra custom field in settings.
	*
	* @since    1.0.0
	* @access   public
	*/

	public function include_override_template_functions(){
		
		function wt_custom_field_group( $field, $plugin_name, $version ){

			$extra_field_cls = new Office_Locator_Extra_Fields( $plugin_name, $version );

			switch ( $field['type'] ) {
				
				case "appearance":
				$extra_field_cls->appearance_field( $field, $plugin_name, $version );
				break;

				case "google_autocomplete":
				$extra_field_cls->google_autocomplete_field( $field, $plugin_name, $version );
				break;	

				case "import_export":
				$extra_field_cls->import_export_field( $field, $plugin_name, $version );
				break;

				case "export_import":
				$extra_field_cls->export_import_field( $field, $plugin_name, $version );
				break;			
			}

		}
	}	

	/**
	* That function including the admin dependencies.
	*
	* @since    1.0.0
	* @access   public
	*/

	public function load_admin_dependencies(){
	 	
	 	/**
		 * The class responsible for defining Offices post_type & Other Funcionality
		 */
	 	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-extra-field-functions.php';

	 }

	/**
	* That function is create plugin setting menu.
	*
	* @since    1.0.0
	* @access   public
	*/

	public function register_plugin_menu() {

		global $GLOBALS;

		if ( !isset($GLOBALS['admin_page_hooks']['webby_template']) || empty($GLOBALS['admin_page_hooks']['webby_template'])) {	 		
			add_menu_page( __( 'WT Plugins', 'office-locator' ), __( 'WT Plugins', 'office-locator' ), 'webby_template_plugins', 'webby_template', array( $this, 'webby_template_plugins' ), plugins_url( '/images/logo.svg', __FILE__ ), 20 );
		}

		add_submenu_page( 'webby_template', __( 'Office Locator', 'office-locator' ), __( 'Office Locator', 'office-locator' ), 'administrator', 'office-locator', array( $this, 'plugin_setting_page' ) );
	}

	/**
	* That function are show the setting option on page.
	*
	* Callback function for add_submenu_page (function).
	*
	* @since    1.0.0
	* @access   public
	*/
	public function plugin_setting_page() {

		$settings = new Office_Locator_Settings( $this->plugin_name, $this->version );
		$plugin_nav_list = $settings->plugin_nav();
		$active_tab = isset( $_GET['tab'] ) && !empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
		$active_show = isset( $plugin_nav_list[ $active_tab ]['action'] ) ? $plugin_nav_list[ $active_tab ]['action'] : true;
		?>
		<div id="wt-panel-settings">
			<div class="nav-tab-wrapper">
				<?php $settings->plugin_nav_list(); ?>
			</div>
			<div class="panel-wrapper">
				<form method="post" id="plugin-data" enctype="multipart/form-data">
					<?php $settings->plugin_panel_list(); ?>
					<div class="wt-submit">
						<?php wp_nonce_field( $this->plugin_name, 'formType' ); ?>
						<input type="hidden" name="tab" value="<?php echo esc_attr( $active_tab ); ?>">
						<input type="hidden" name="action" value="<?php echo esc_attr( $this->plugin_name ); ?>_datasave">
						<input type="hidden" name="reset_action" value="<?php echo esc_attr( $this->plugin_name ); ?>_datareset">
						<span class="alert"></span>
						<?php 
						if( $active_show ){
							?>

							<div class="action-wrapper">
								<div class="documentation">
									<a href="javascript:;">
										<i class="fa-solid fa-file-invoice"></i>
									</a>
								</div>
								<div class="action-wrapper reset">
									<button type="reset" name="reset">
										<span class="textr">
											<i class="fa-solid fa-arrows-rotate"></i>
											<?php echo __( 'Reset', 'office-locator' ); ?>
										</span>
										<div class="loader loader-ellipsis hidden">
											<div></div><div></div><div></div><div></div>
										</div>
									</button>
								</div>
								<div class="submit">
									<button type="submit" name="submit">
										<span class="text">
											<i class="fa-solid fa-floppy-disk"></i>
											<?php echo __( 'Save', 'office-locator' ); ?>
										</span>
										<div class="loader loader-ellipsis hidden">
											<div></div><div></div><div></div><div></div>
										</div>
									</button>
								</div>
							</div>

							<?php
						}	
						?>	 					
					</div>
				</form>
			</div>
		</div>
		<?php
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {

		do_action( 'wt_enqueue_add_extra_styles_before' );	

		$enqueue_styles = array();		

		$current_screen = get_current_screen();

		if ( strpos( $current_screen->base, $this->plugin_name ) == true ) {

			$enqueue_styles['jquery-ui'] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/jquery-theme-ui.css'
			);

			$enqueue_styles['jquery-ui2'] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css'
			);

			$enqueue_styles['wt-select2'] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/select2.min.css'
			);

			$enqueue_styles['wp-color-picker'] = array(
				'direct' => true,
				'path' => ''
			);

			$enqueue_styles['jquery-ui-slider'] = array(
				'direct' => true,
				'path' => ''
			);
			$enqueue_styles['wt-font-awesome'] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/all.min.css'
			);
			$enqueue_styles['wt-admin-setting'] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/admin-setting.css'
			);

			$enqueue_styles[$this->plugin_name] = array(
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'css/admin.css'
			);

		}

		$enqueue_styles = apply_filters( 'wt_enqueue_admin_styles', $enqueue_styles );

		if( $enqueue_styles ){

			foreach( $enqueue_styles as $style_handle => $style_path ){

				if( isset( $style_path['direct' ] ) && !empty( $style_path['direct'] ) ){
					wp_enqueue_style( $style_handle );
				} else { 
					if( isset( $style_path['path'] ) && !empty( $style_path['path'] ) ){
						wp_enqueue_style( $style_handle, $style_path['path'], array(), $this->version, 'all' );
					}
				}

			}

		}

		do_action( 'wt_enqueue_add_extra_styles_after' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {

		$current_screen = get_current_screen();

		do_action( 'wt_enqueue_add_extra_scripts_before' );

		$enqueue_scripts = array();

		if ( strpos( $current_screen->base, $this->plugin_name ) == true ) {

			$enqueue_scripts['jquery-ui-datepicker'] = array(
				'localize_script' => '',
				'direct' => true,
				'path' => ''
			);

			$enqueue_scripts['jquery-ui-sortable'] = array(
				'localize_script' => '',
				'direct' => true,
				'path' => ''
			);

			$enqueue_scripts['jquery-ui-tooltip'] = array(
				'localize_script' => '',
				'direct' => true,
				'path' => ''
			);

			$enqueue_scripts['wp-color-picker'] = array(
				'localize_script' => '',
				'direct' => true,
				'path' => ''
			);

			$enqueue_scripts['jquery-ui-slider'] = array(
				'localize_script' => '',
				'direct' => true,
				'path' => ''
			);

			$enqueue_scripts['wt-font-awesome'] = array(
				'localize_script' => '',
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'js/all.min.js'
			);

			$enqueue_scripts['wt-select2'] = array(
				'localize_script' => '',
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'js/select2.min.js'
			);		

			$current_screen = get_current_screen();

			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			$enqueue_scripts['wt-admin-setting'] = array(
				'localize_script' => '',
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'js/admin-setting.js'
			);
			
			$enqueue_scripts[$this->plugin_name] = array(
				'localize_script' => 'wt_ajax',
				'direct' => false,
				'path' => plugin_dir_url( __FILE__ ) . 'js/admin.js',
				'localize_array' => array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce('wt_form_save'),
				)
			);
		}

		$enqueue_scripts = apply_filters( 'wt_enqueue_admin_scripts', $enqueue_scripts );

		
		if( $enqueue_scripts ){

			foreach( $enqueue_scripts as $script_handle => $script_path ){

				if( isset( $script_path['direct'] ) && !empty( $script_path['direct'] ) ){
					wp_enqueue_script( $script_handle );
				} else { 
					if( isset( $script_path['path'] ) && !empty( $script_path['path'] ) ){
						wp_enqueue_script( $script_handle, $script_path['path'], array(), $this->version, 'all' );
					}
					if( isset( $script_path['localize_script'] ) && !empty( $script_path['localize_script'] ) ){
						$localize_array = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
						if( isset($script_path['localize_array']) && !empty($script_path['localize_array']) ){
							$localize_array = $script_path['localize_array'];
						}
						wp_localize_script( $this->plugin_name, $script_path['localize_script'], $localize_array );
					}
				}

			}

		}

		do_action( 'wt_enqueue_add_extra_scripts_after' );
		
		
	}

	/**
	 * Saving the sanitize callback
	 *
	 * @since      	1.0.0
	 * @access   	public
	 * @param      	string    $plugin_name      The name of this plugin.
	 * @param      	string    $version    		The version of this plugin.
	 */
	public function plugin_sanitize_callback($value) {
		if ( is_array( $value ) ) {
        	// If the value is an array, recursively sanitize it
			$value = array_map( array( $this, 'plugin_sanitize_callback' ), $value );
		} else {
        	// Sanitize the value using sanitize_text_field()
			$value = sanitize_text_field( $value );
		}
		return $value;
	}

	/**
	 * Saving the plugin data
	 *
	 * @since      	1.0.0
	 * @access   	public
	 * @param      	string    $plugin_name      The name of this plugin.
	 * @param      	string    $version    		The version of this plugin.
	 */
	public function plugin_datasave() {
		$formdata_arr = $value_arr = array();		
		$success = false;
		check_ajax_referer( 'wt_form_save', 'ajax_nonce' );

		/*
		* In $_POST['formdata'] We have store data as array format
		* We also sanitize data using plugin_sanitize_callback function
		*/

		$formdata = isset($_POST['formdata']) ? $_POST['formdata'] : '';		
		parse_str( $formdata, $formdata_arr );		
		$formdata_arr = array_map( array( $this, 'plugin_sanitize_callback' ), $formdata_arr );
		if ( isset( $formdata_arr['formType'] ) && wp_verify_nonce( $formdata_arr['formType'], $this->plugin_name ) ) {
			$tab = $formdata_arr['tab'];			
			unset( $formdata_arr['_wp_http_referer'] );
			unset( $formdata_arr['tab'] );
			unset( $formdata_arr['action'] );
			unset( $formdata_arr['formType'] );
			unset( $formdata_arr['reset_action'] );
			$option_name = str_replace( '-', '_', $this->plugin_name ) .'_'.$tab;
			update_option( $option_name, $formdata_arr );
			$success = true;
		}
		wp_send_json( $success );
	}

	/**
	 * Ajax for Reset the plugin data 
	 *
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function plugin_datareset(){		
		check_ajax_referer( 'wt_form_save', 'ajax_nonce' );
		$this->reset_plugin_data( 1 );
	}

	/**
	 * Reset the plugin data
	 *
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    		The version of this plugin.
	 */	
	public function reset_plugin_data( $default = false ){
		$settings = new Office_Locator_Settings( $this->plugin_name, $this->version );
		$panel_slug = $settings->plugin_nav();
		$panel_data = $settings->plugin_panel();		
		if(  !empty( $panel_slug ) ){
			foreach ( $panel_slug as $slug_key => $slug_value ) {
				if( isset( $slug_value['action'] ) && !empty( $slug_value['action'] ) ){
					$plugin_opt_name = str_replace( '-', '_', $this->plugin_name ) .'_'.$slug_key;
					$plugin_opt_value = get_option( $plugin_opt_name );
					if( empty( $plugin_opt_value ) || $default == true ){						
						$default_panel_data = array();
						$panel_item = isset( $panel_data[$slug_key]['section'] ) ? $panel_data[$slug_key]['section'] : array();
						if( $panel_item ){

							foreach( $panel_item as $field_data ){
								$field_list = isset( $field_data['fields'] ) ? $field_data['fields'] : array();

								if( $field_list ){
									foreach ( $field_list as $field_item ) {
										if( isset($field_item['type']) && $field_item['type'] == 'multiple_parameter_inputs' ){
											if( isset( $field_item['parameters'] ) && !empty( $field_item['parameters'] ) ){
												foreach( $field_item['parameters'] as $parameters ){
													if( isset( $field_item['name'] ) ){
														$default_panel_data[ $field_item['name'] ][$parameters['name']] = isset( $parameters['default'] ) ? $parameters['default'] : '';			
													}
												}
											}										
										}else if( isset($field_item['type']) && $field_item['type'] == 'sortable' ){
											$sortable_arr = array(
												'slug' => isset($field_item['sortable_list']['default']) ? array_keys( $field_item['sortable_list']['default'] ) : array(),
												'values' => isset($field_item['sortable_list']['fields']) ? $field_item['sortable_list']['fields'] : array()
											);
											if( $field_item['sortable_type'] == 'simple' ){
												$sortable_arr['slug'] = isset($field_item['sortable_list']['fields']) ? array_keys( $field_item['sortable_list']['fields'] ) : array();
											}
											$default_panel_data[ $field_item['id'] ] = json_encode( $sortable_arr );
										}else if( isset($field_item['type']) && $field_item['type'] == 'switch' ){
											if( isset( $field_item['name'] ) ){

												if( isset($field_item['default']) && $field_item['default'] == 'unable' || isset($field_item['default']) && $field_item['default'] == 'yes' ){
													$default_panel_data[ $field_item['name'] ] = 'yes';
												}else{
													$default_panel_data[ $field_item['name'] ] = 'no';
												}										
											}									
										}else{
											if( isset( $field_item['name'] ) ){
												$default_panel_data[ $field_item['name'] ] = isset( $field_item['default'] ) ? $field_item['default'] : '';											
											}
										}

									}
								}
							}

						}			
						$option_name = str_replace( '-', '_', $this->plugin_name ) .'_'.$slug_key;
						update_option( $option_name, $default_panel_data );
					}
				}
			}
		}
	}

}