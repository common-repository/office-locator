<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/public
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_public_dependencies();
		$this->init_hooks();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'set_ofc_global_variables' ), 9 );
		add_filter( 'body_class',array( $this, 'add_office_locator_classes' ) );
		add_action( 'wp_head',array( $this, 'add_office_locator_variables' ) );
		add_filter( 'template_include', array( $this, 'offices_locator_set_template' ) );

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {			
		add_action( 'after_setup_theme', array( $this, 'include_office_locators_template_functions' ), 11 );
	}

	/**
	 * That function including the functions files.
	 *
	 * @since    1.0.0
	 */
	public function include_office_locators_template_functions() {	

		include 'includes/office-locator-ajax-functions.php';
		$Office_Locator_Ajax_Functons = new Office_Locator_Ajax_Functons( $this->plugin_name, $this->version );		

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wt-font-awesome', plugin_dir_url( __DIR__ ) . '/admin/css/all.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wt-swiper-style', plugin_dir_url( __FILE__ ) . 'css/swiper-bundle.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $olc_map_markers, $olc_map_styles;
		$tab = 'general';
		$option_name = str_replace('-', '_', $this->plugin_name) .'_'.$tab;
		$options = get_option($option_name);		
		$key = ( isset( $options['map_api_key'] ) && !empty( $options['map_api_key'] ) ) ? '&key='.$options['map_api_key'] : '';			
		$map_language = ( isset( $options['map_language'] ) && !empty( $options['map_language'] ) ) ? '&language='.$options['map_language'] : '';
		$map_region = ( isset( $options['map_region'] ) && !empty( $options['map_region'] ) ) ? '&region='.$options['map_region'] : '';
		wp_enqueue_script( 'wt-font-awesome', plugin_dir_url( __DIR__ ) . '/admin/js/all.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'wt-swiper-script', plugin_dir_url( __FILE__ ) . 'js/swiper-bundle.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/office-locator-public.js', array( 'jquery' ), $this->version, true );		
		wp_localize_script( $this->plugin_name, 'ofcAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )); 				
		wp_enqueue_script( 'olc-google-map', 'https://maps.googleapis.com/maps/api/js?callback=ofcMpInitialize'.$key.''.$map_language.''.$map_region.'&libraries=places&sensor=false', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Make Office Locator Javascript Varibles.
	 *
	 * @since    1.0.0
	 */

	public function add_office_locator_variables(){
		?>
		<script type="text/javascript">
			var ofcMapObj = <?php echo esc_js( '[]' ); ?>
		</script>
		<?php
	}


	/**
	 * Creat Office Locator Set template Parts.
	 *
	 * @since    1.0.0
	 */

	public function offices_locator_set_template( $offices_template ){
		
		if( is_singular( 'offices' ) && 'single-offices.php' != $offices_template ){
			$offices_template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/template-parts/single-offices.php';
		}

		$offices_template = apply_filters( 'single_office_template_override' , $offices_template );

		return $offices_template;
	}

	 /**
	 * Make Office Locator Global Varibles.
	 *
	 * @since    1.0.0
	 */

	 public function set_ofc_global_variables(){

	 	global $olc_map_markers, $olc_map_styles;
	 	$map_options = str_replace( '-', '_', $this->plugin_name ) .'_map';
	 	$ofcMapData = get_option( $map_options );			 		 	
	 	$ofcMapData['no_result_msg'] = __( "No offices found.", 'office-locator' );	 	
	 	$ofcMapData['start_location_marker_msg'] = __( 'Your Start Office Location', 'office-locator' );	 	
	 	$ofcMapData['back_btn_text'] = __( 'Back', 'office-locator' );	 	

	 	$general_options = str_replace( '-', '_', $this->plugin_name ) .'_general';
	 	$ofcGeneralData = get_option( $general_options );	

	 	$olc_start_point = isset( $ofcMapData['olc_start_point'] ) ? $ofcMapData['olc_start_point'] : array();					 	
	 	$olc_lat_long = isset($olc_start_point['lat_long']) ? $olc_start_point['lat_long'] : array();
	 	if( $olc_lat_long ){
	 		$olc_lat_long = explode( ',' , $olc_lat_long );
	 	}
	 	$ofcMapData['olc_lat_long'] = $olc_lat_long;

	 	$ofcRadiusList = array( 10, 25, 50, 100, 200, 500, 1000 );
	 	$ofcResultList = array( 10, 25, 50, 75, 100 );

	 	$ofcRadiusList = apply_filters( 'office_store_radius_list', $ofcRadiusList );
	 	$ofcResultList = apply_filters( 'office_store_result_list', $ofcResultList );

	 	$GLOBALS['ofcMapData'] = $ofcMapData;
	 	$GLOBALS['ofcGeneralData'] = $ofcGeneralData;
	 	$GLOBALS['ofcResultList'] = $ofcResultList;
	 	$GLOBALS['ofcRadiusList'] = $ofcRadiusList;
	 }

	  /**
	 * Return Value Office Locator Map Settings Varibles.
	 *
	 * @since    1.0.0
	 */

	  public function set_olc_map_setting( $olcMapAttr ){

	  	global $olc_map_markers, $olc_map_styles, $olc_map_layout;

	  	if( empty( $olcMapAttr ) ){
	  		$olcMapAttr = (object)array();
	  	}else{

	  		$marker_img = '';
	  		if( isset( $olcMapAttr['custom_store_marker']) && !empty( trim( $olcMapAttr['custom_store_marker'] ) )  ){
	  			$marker_img = wp_get_attachment_url( $olcMapAttr['custom_store_marker'], 'thumbnail' );				
	  		}	
	  		if( empty($marker_img) ){
	  			if( isset( $olcMapAttr['store_marker']) && !empty( trim( $olcMapAttr['store_marker'] ) )  ){
	  				$marker_img = isset($olc_map_markers[$olcMapAttr['store_marker']]['img']) ? $olc_map_markers[$olcMapAttr['store_marker']]['img'] : '';
	  			}				
	  		}	
	  		$olcMapAttr['store_marker'] = $marker_img;

	  		$start_marker_img = '';
	  		if( isset( $olcMapAttr['custom_start_location_marker']) && !empty( trim( $olcMapAttr['custom_start_location_marker'] ) )  ){
	  			$start_marker_img = wp_get_attachment_url( $olcMapAttr['custom_start_location_marker'], 'thumbnail' );				
	  		}	
	  		if( empty($start_marker_img) ){
	  			if( isset( $olcMapAttr['start_location_marker']) && !empty( trim( $olcMapAttr['start_location_marker'] ) )  ){
	  				$start_marker_img = isset($olc_map_markers[$olcMapAttr['start_location_marker']]['img']) ? $olc_map_markers[$olcMapAttr['start_location_marker']]['img'] : '';
	  			}				
	  		}	
	  		$olcMapAttr['start_location_marker'] = $start_marker_img;	

	  		$olc_map_style = '';
	  		if( isset( $olcMapAttr['custom_style'] ) && !empty( trim( $olcMapAttr['custom_style'] ) )  ){
	  			$olc_map_style_valid =  ( is_string( $olcMapAttr['custom_style'] ) && is_array( json_decode( $olcMapAttr['custom_style'], true ) ) ) ? true : false;
	  			if( $olc_map_style_valid == true ){
	  				$olc_map_style = $olcMapAttr['custom_style'];
	  			}				
	  		}

	  		if( empty( $olc_map_style ) ){		  					
	  			$map_style = isset($olcMapAttr['map_style']) ? $olcMapAttr['map_style'] : '';						
	  			if( $map_style ){	  							  			
	  				$map_style_url = plugin_dir_path( __FILE__ ).'map-style-json/'.$map_style.'.json';					
	  				$olc_map_style = file_get_contents( $map_style_url );				
	  				if( $olc_map_style ){
	  					$olc_map_style = json_decode( $olc_map_style );
	  				}
	  			}
	  		}

	  		$olcMapAttr['map_style'] = $olc_map_style;
	  	}
	  	return $olcMapAttr;
	  }

	  /**
	 * That function including the public dependencies.
	 *
	 * @since    1.0.0
	 */
	  public function load_public_dependencies() {

	  	add_shortcode( 'office_locator', array( $this, 'office_locators' ) );

	  }

	/**
	 * Displaying office locators.
	 *
	 * @since    1.0.0
	 */
	public function office_locators( $atts ) {

		ob_start();
		global $olc_map_markers, $olc_map_layout, $olc_map_styles, $ofcMapData, $ofcGeneralData;	

		if( isset($atts['lat_long']) && !empty( trim( $atts['lat_long'] ) ) ){
			$atts['olc_lat_long'] = explode( ',', trim( $atts['lat_long'] ) );
		}

		$default_attr = array(
			'offices' => '',
			'custom_store_marker' => isset( $ofcMapData['custom_store_marker'] ) ? $ofcMapData['custom_store_marker'] : '',
			'custom_start_location_marker' => isset( $ofcMapData['custom_start_location_marker'] ) ? $ofcMapData['custom_start_location_marker'] : '',
			'custom_style' => isset( $ofcMapData['custom_style'] ) ? $ofcMapData['custom_style'] : '',
			'map_width' => $ofcMapData['map_width']['width'].$ofcMapData['map_width']['value'],
			'map_height' => $ofcMapData['map_height']['height'].$ofcMapData['map_height']['value'],
			'map_view_type' => $ofcMapData['map_view_type'],
			'map_style' => $ofcMapData['map_style'],	
			'store_marker' => $ofcMapData['store_marker'],
			'start_location_marker' => $ofcMapData['start_location_marker'],
			'no_result_msg' => $ofcMapData['no_result_msg'],
			'start_location_marker_msg' => $ofcMapData['start_location_marker_msg'],	 	
			'back_btn_text' => $ofcMapData['back_btn_text'],	 	
			'load_olc_stores' => array(),
			'olc_zoom_map' => $ofcMapData['outer_zoom_level'], 
			'olc_inner_map' => $ofcMapData['inner_zoom_level'], 
			'street_view_control' => $ofcMapData['street_view_control'], 
			'map_type_control' => $ofcMapData['map_type_control'], 
			'wheel_zooming' => $ofcMapData['wheel_zooming'], 
			'full_screen_control' => $ofcMapData['full_screen_control'], 
			'zoom_control' => $ofcMapData['zoom_control'], 
			'zoom_position' => $ofcMapData['zoom_position'], 
			'full_screen_ctlr_pos' => $ofcMapData['full_screen_ctlr_pos'], 
			'street_view_ctlr_pos' => $ofcMapData['street_view_ctlr_pos'], 
			'olc_lat_long' => $ofcMapData['olc_lat_long'], 
			'map_type_ctlr_pos' => $ofcMapData['map_type_ctlr_pos'], 
			'map_layout' => isset( $ofcMapData['map_layout'] ) ? $ofcMapData['map_layout'] : 'layout-1', 
			'enable_store_filter' => isset( $ofcMapData['enable_store_filter'] ) ? $ofcMapData['enable_store_filter'] : 'yes', 
			'enable_store_office' => isset( $ofcMapData['enable_store_office'] ) ? $ofcMapData['enable_store_office'] : 'yes', 
			'enable_start_location_marker_control' => isset( $ofcMapData['enable_start_location_marker_control'] ) ? $ofcMapData['enable_start_location_marker_control'] : 'yes', 
			'enable_store_location_marker_control' => isset( $ofcMapData['enable_store_location_marker_control'] ) ? $ofcMapData['enable_store_location_marker_control'] : 'yes', 
			'enable_start_location_marker_pop_up_control' => isset( $ofcMapData['enable_start_location_marker_pop_up_control'] ) ? $ofcMapData['enable_start_location_marker_pop_up_control'] : 'yes', 
			'enable_store_location_marker_pop_up_control' => isset( $ofcMapData['enable_store_location_marker_pop_up_control'] ) ? $ofcMapData['enable_store_location_marker_pop_up_control'] : 'yes', 
			'start_location_marker_width' => isset( $ofcMapData['start_location_marker_width']['width'] ) ? $ofcMapData['start_location_marker_width']['width'] : '25', 
			'start_location_marker_height' => isset( $ofcMapData['start_location_marker_height']['height'] ) ? $ofcMapData['start_location_marker_height']['height'] : '35', 
			'store_location_marker_width' => isset( $ofcMapData['store_location_marker_width']['width'] ) ? $ofcMapData['store_location_marker_width']['width'] : '25', 
			'store_location_marker_height' => isset( $ofcMapData['store_location_marker_height']['height'] ) ? $ofcMapData['store_location_marker_height']['height'] : '35', 
			'map_container_max_width' => isset( $ofcMapData['map_container_max_width']['width'] ) ? $ofcMapData['map_container_max_width']['width'].$ofcMapData['map_container_max_width']['value'] : '1650px', 
			'map_container_size' => isset( $ofcMapData['map_container_size'] ) ? $ofcMapData['map_container_size'] : 'container', 
			'map_background_color' => isset( $ofcMapData['map_background_color'] ) ? $ofcMapData['map_background_color'] : '#f9f9f9', 
			'map_office_radius' => isset( $ofcMapData['map_office_radius'] ) ? $ofcMapData['map_office_radius'] : 10, 
			'map_office_results' => isset( $ofcMapData['map_office_results'] ) ? $ofcMapData['map_office_results'] : 10,
			'map_office_unit' => isset( $ofcMapData['map_office_unit'] ) ? $ofcMapData['map_office_unit'] : 'km',
			'load_olc_markers' => array(), 
			'olc_view' => '', 
			'olc_panel' => '', 
			'olc_map' => '' 
		);

if( isset( $atts['map_style'] ) && !empty( $atts['map_style'] ) ){
	$default_attr['custom_style'] = '';			
}

$olcMapAttr = shortcode_atts( $default_attr, $atts );

$olcMapAttr = $this->set_olc_map_setting( $olcMapAttr );

$layout = '';

if( !empty( $olcMapAttr['map_layout'] ) ){
	include 'template-parts/layout/office-locator-'.$olcMapAttr['map_layout'].'.php';
}

return ob_get_clean();
}

	/**
	 * Adding Office Locator Class.
	 *
	 * @since    1.0.0
	 */
	public function add_office_locator_classes() {
		$classes[] = 'store-locater';

		return $classes;
	}
}