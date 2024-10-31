<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/public
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Ajax_Functons {

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

		add_action( "wp_ajax_get_office_locator_stores", array( $this, "get_office_locator_stores" ) );
		add_action( "wp_ajax_nopriv_get_office_locator_stores", array( $this, "get_office_locator_stores" ) );

	}	

	/**
	 * Get all office locator address
	 *
	 * @since    1.0.0
	 */
	public function get_office_locator_stores() {

		global $wpdb;
		$office_address = array();
		if ( isset( $_POST['olcType'] ) && wp_verify_nonce( $_POST['olcType'], 'find_olc_stores' ) ) {
			$olc_latitude = isset($_POST['olc_latitude']) ? sanitize_text_field( $_POST['olc_latitude'] ) : 0;
			$olc_longitude = isset($_POST['olc_longitude']) ? sanitize_text_field( $_POST['olc_longitude'] ) : 0;
			$olc_results = isset($_POST['olc_results']) ? sanitize_text_field( $_POST['olc_results'] ) : 10;
			$olc_radius = isset($_POST['olc_radius']) ? sanitize_text_field( $_POST['olc_radius'] ) : 10;
			$olc_office_ids = isset($_POST['olc_office_ids']) ? sanitize_text_field( $_POST['olc_office_ids'] ) : '';
			$olc_layout = isset($_POST['olc_layout']) ? sanitize_text_field( $_POST['olc_layout'] ) : '';
			$olc_distance_unit = isset($_POST['olc_distance_unit']) ? sanitize_text_field( $_POST['olc_distance_unit'] ) : 'km';
			$distance_unit = ( $olc_distance_unit == 'km' ) ? 6371 : 3959;
			$olc_where = '';
			if( $olc_office_ids ){
				$olc_where = " AND p.ID IN ( ". $olc_office_ids ." ) ";
			}

			$option_name        = str_replace( '-', '_', $this->plugin_name ) .'_permalink';
			$general_data       = get_option( $option_name );				

			$store_sql = "SELECT p.ID, ( ".$distance_unit." * acos( cos( radians(".$olc_latitude.") ) * cos( radians( olc_lat.meta_value ) ) * cos( radians( olc_lng.meta_value ) - radians(".$olc_longitude.") ) + sin( radians(".$olc_latitude.") ) * sin( radians( olc_lat.meta_value ) ) ) ) AS calculated_distance FROM ".$wpdb->posts." as 
			p
			INNER JOIN ".$wpdb->postmeta." AS olc_lat ON olc_lat.post_id = p.ID AND olc_lat.meta_key = 'office_latitude'
			INNER JOIN ".$wpdb->postmeta." AS olc_lng ON olc_lng.post_id = p.ID AND olc_lng.meta_key = 'office_longitude'
			WHERE p.post_type = 'offices'
			AND p.post_status = 'publish'
			".$olc_where."
			GROUP BY p.ID
			HAVING calculated_distance <= ".$olc_radius."		
			limit 0,".$olc_results;		
			$office_stores = $wpdb->get_results( $store_sql, ARRAY_A );
			if( $office_stores ){
				foreach ( $office_stores as $office_store_id ) {
					if( isset( $office_store_id['ID'] ) ){
						$address_item = $this->get_wt_office_addresses_json( $office_store_id['ID'] );                  
						array_push( $office_address, $address_item );
					}
				}
			}
			$office_address = $this->set_office_store_html( $office_address, $olc_layout, $general_data );
		}
		wp_send_json( $office_address );

	}

	/**
	 *  Return value function with all meta of office post
	 *
	 * @since    1.0.0
	 */

	public function get_wt_office_addresses_json( $office_id = 0 ){
		$address_item = array(); 
		$address_item['office_id'] = $office_id;            
		$address_item['office_title'] = get_the_title( $office_id );            
		$address_metas = array(
			'office_name','office_phone', 'office_fax', 'office_email', 'office_address', 'office_city', 'office_state', 'office_country', 'office_postal_code', 'office_latitude', 'office_longitude'
		);
		foreach ( $address_metas as $address_key ) {
			$address_item[$address_key] = get_post_meta( $office_id, $address_key, true ); 
		}    

		return $address_item;
	}

	/**
	 * Return all office address html
	 *
	 * @since    1.0.0
	 */
	public function set_office_store_html( $olcAddList, $olc_layout, $general_data ) {

		$permalink_switcher = isset( $general_data['permalink_switcher'] ) ? $general_data['permalink_switcher'] : 'no';			
		$open_office_new_tab = isset( $general_data['open_office_new_tab'] ) ? $general_data['open_office_new_tab'] : 'no';	
		
		if( $olcAddList ){
			$slider_class = '';
			if( $olc_layout == 'layout-3' || $olc_layout == 'layout-4' || $olc_layout == 'layout-7' || $olc_layout == 'layout-8' ){
				$slider_class = 'swiper-slide';
			}
			foreach ( $olcAddList as $item_key => $addressDetails ) {

				$office_title = '<h3 class="office-locater-one-title">' . $addressDetails['office_title'] . '</h3>';
				$target_href = '_self';
				if( $open_office_new_tab == 'yes' ){
					$target_href = '_blank';
				}

				if( $permalink_switcher == 'yes' ){
					$office_title = '<h3 class="office-locater-one-title"><a href="'.esc_url( get_the_permalink( $addressDetails['office_id'] ) ).'" target="'.esc_attr($target_href).'" >' . wp_kses_post( $addressDetails['office_title'] ) . '</a></h3>';
				}

				$olcStoreHtml = '<div class="'.$slider_class.' office-locater-one-box office-locater-box" id="office-locator-box-' . esc_attr( $addressDetails['office_id'] ) . '" data-office-id="' . esc_attr( $addressDetails['office_id'] ). '">'.wp_kses_post( $office_title );						

				if( $addressDetails['office_name'] ){
					$olcStoreHtml .= '<h4 class="office-locater-contact">' . wp_kses_post( $addressDetails['office_name'] ) . '</h4>';
				}
				$olcStoreInfoHtml = '';
				if( $addressDetails['office_address'] ){
					$olcStoreInfoHtml .= '<div class="address-one-list"><span><i class="fa-solid fa-location-dot"></i></span><p>' .wp_kses_post( $addressDetails['office_address']. ' ' .$addressDetails['office_postal_code'] ) . '</p></div>';	
				}
				if( $addressDetails['office_phone'] ){
					$olcStoreInfoHtml .= '<div class="address-one-list"><span><i class="fa-solid fa-phone"></i></span><p><a href="tel:' .esc_attr( $addressDetails['office_phone'] ). '">' . wp_kses_post( $addressDetails['office_phone'] ). '</a></p></div>';
				}
				if( $addressDetails['office_fax'] ){
					$olcStoreInfoHtml .= '<div class="address-one-list"><span><i class="fa-solid fa-fax"></i></span><p><a href="tel:' .esc_attr( $addressDetails['office_fax'] ). '">' .wp_kses_post( $addressDetails['office_fax'] ). '</a></p></div>';
				}
				if( $addressDetails['office_email'] ){
					$olcStoreInfoHtml .= '<div class="address-one-list"><span><i class="fa-solid fa-envelope"></i></span><p><a href="mailto:' .esc_attr( $addressDetails['office_email'] ). '">' .wp_kses_post( $addressDetails['office_email'] ). '</a></p></div>';
				}
				if( $olcStoreInfoHtml ){
					$olcStoreHtml .= '<div class="office-locater-one-address">'.wp_kses_post( $olcStoreInfoHtml ).'</div>';
				}
				$olcStoreHtml .= '<div class="address-one-btn"><a href="javascript:;" data-office-id="'.esc_attr( $addressDetails['office_id'] ).'" class="all-btn ofc-directions">'.__( 'Direction', 'office-locator' ).'</a></div></div>';
				$olcStoreHtml = apply_filters( 'office_store_item_html', $olcStoreHtml, $addressDetails );
				$olcAddList[$item_key]['office_store_html'] = $olcStoreHtml;
			}
		}
		return $olcAddList;

	}
	
}