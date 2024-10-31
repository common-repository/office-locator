<?php
/**
* @package    Office_Locator
* @subpackage Office_Locator/admin/includes
* @author     Webby Template <support@webbytemplate.com>
*/

class Manage_Office_Locator {

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
* @param      string    $plugin_name       The name of this plugin.
* @param      string    $version    		 The version of this plugin.
*/

public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action( 'init', array( $this, 'register_offices' ) );
    add_action( 'add_meta_boxes', array( $this, 'offices_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_offices_meta_boxes' ) );

    add_action( 'wp_ajax_import_post_address',  array( $this, 'import_post_address' ) );
    add_action( 'wp_ajax_nopriv_import_post_address',  array( $this, 'import_post_address' ) ); 

    add_action( 'wp_ajax_export_post_address',  array( $this, 'export_post_address' ) );

}

/**
* Register post_type of Offices.
*
* @since    1.0.0
* @access   public
*/

public function register_offices() {

/**
* Post Type: Offices.
*/

$labels = [
    'name' => __( 'Offices', 'office-locator' ),
    'singular_name' => __( 'Office', 'office-locator' ),
    'menu_name' => __( 'My Offices', 'office-locator' ),
    'all_items' => __( 'All Offices', 'office-locator' ),
    'add_new' => __( 'Add New', 'office-locator' ),
    'add_new_item' => __( 'Add New Office', 'office-locator' ),
    'edit_item' => __( 'Edit Office', 'office-locator' ),
    'new_item' => __( 'New Office', 'office-locator' ),
    'view_item' => __( 'View Office', 'office-locator' ),
    'view_items' => __( 'View Offices', 'office-locator' ),
    'search_items' => __( 'Search Offices', 'office-locator' ),
    'not_found' => __( 'No Offices Found', 'office-locator' ),
    'not_found_in_trash' => __( 'No Offices Found in Trash', 'office-locator' ),
    'parent' => __( 'Parent Office', 'office-locator' ),
    'featured_image' => __( 'Featured Image for this Office', 'office-locator' ),
    'set_featured_image' => __( 'Set featured image for this office', 'office-locator' ),
    'remove_featured_image' => __( 'Remove featured image for this office', 'office-locator' ),
    'use_featured_image' => __( 'Use as featured image for this office', 'office-locator' ),
    'archives' => __( 'Office Archives', 'office-locator' ),
    'insert_into_item' => __( 'Insert into Office', 'office-locator' ),
    'uploaded_to_this_item' => __( 'Uploaded to this Office', 'office-locator' ),
    'filter_items_list' => __( 'Filter offices list', 'office-locator' ),
    'items_list_navigation' => __( 'Offices list navigation', 'office-locator' ),
    'items_list' => __( 'Offices list', 'office-locator' ),
    'attributes' => __( 'Offices Attributes', 'office-locator' ),
    'name_admin_bar' => __( 'Office', 'office-locator' ),
    'item_published' => __( 'Office published', 'office-locator' ),
    'item_published_privately' => __( 'Office published privately', 'office-locator' ),
    'item_reverted_to_draft' => __( 'Office reverted to draft', 'office-locator' ),
    'item_scheduled' => __( 'Office scheduled', 'office-locator' ),
    'item_updated' => __( 'Office updated', 'office-locator' ),
    'parent_item_colon' => __( 'Parent Office', 'office-locator' ),
];

$args = [
    'label' => __( 'Offices', 'office-locator' ),
    'labels' => $labels,
    'description' => '',
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_rest' => true,
    'rest_base' => '',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'rest_namespace' => 'wp/v2',
    'has_archive' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'delete_with_user' => false,
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'hierarchical' => true,
    'can_export' => false,
    'rewrite' => [ 'slug' => 'offices', 'with_front' => true ],
    'query_var' => true,
    'supports' => [ 'title', 'revisions' ],
    'show_in_graphql' => false,
    'menu_icon' => 'dashicons-store'
];

/**
* Get Option: Permalink.
*/
$option_name        = str_replace( '-', '_', $this->plugin_name ) .'_permalink';
$general_data       = get_option( $option_name );
$permalink_switcher = isset( $general_data['permalink_switcher'] ) ? $general_data['permalink_switcher'] : '';
$store_slug         = isset( $general_data['store_slug'] ) ? trim($general_data['store_slug']) : '';
if( empty($store_slug) ){
    $store_slug = 'offices';
}
if( $permalink_switcher == 'yes' ){
    $args['public'] = true;
    $args['publicly_queryable'] = true;
    if( $store_slug ){
        $args['rewrite'] = array( 'slug' => $store_slug );         
        $olc_office_rewrite_old = get_option( 'olc_office_rewrite_old' );    
        if( $olc_office_rewrite_old != $store_slug ){                        
            update_option( 'olc_office_rewrite_old', $store_slug );          
            flush_rewrite_rules();              
        }     
    }    
}

register_post_type( 'offices', $args );

}

/**
* Adding meta boxes to offices.
*
* @since    1.0.0
* @access   public
*/

public function offices_meta_boxes() {

    add_meta_box( 'office_info', __( 'Office Information', 'office-locator' ), array( $this, 'office_info' ), 'offices', 'normal', 'default' );
    add_meta_box( 'office_address', __( 'Address', 'office-locator' ), array( $this, 'office_address' ), 'offices', 'normal', 'default' );
}

/**
* Office info meta box.
*
* @since    1.0.0
* @access   public
*/

public function office_info() {

    global $post;

    $office_name = get_post_meta( $post->ID, 'office_name', true );
    $office_phone = get_post_meta( $post->ID, 'office_phone', true );
    $office_fax = get_post_meta( $post->ID, 'office_fax', true );
    $office_email = get_post_meta( $post->ID, 'office_email', true );
    ?>
    <table style = 'width: 100%' class = 'office-locator-metabox'>
        <tr>
            <th><?php echo __( 'Name', 'office-locator' ); ?></th>
            <td><input type='text' name='office_name' value="<?php echo esc_attr( $office_name ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'Phone', 'office-locator' ); ?></th>
            <td><input type='text' name='office_phone' value="<?php echo esc_attr( $office_phone ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'Fax', 'office-locator' ); ?></th>
            <td><input type='text' name='office_fax' value="<?php echo esc_attr( $office_fax ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'E-Mail', 'office-locator' ); ?></th>
            <td><input type='email' name='office_email' value="<?php echo esc_attr( $office_email ); ?>"></td>
        </tr>
    </table>
    <?php
}

/**
* Office address meta box.
*
* @since    1.0.0
* @access   public
*/

public function office_address() {

    global $post;

    $office_address = get_post_meta( $post->ID, 'office_address', true );
    $office_city = get_post_meta( $post->ID, 'office_city', true );
    $office_state = get_post_meta( $post->ID, 'office_state', true );
    $office_country = get_post_meta( $post->ID, 'office_country', true );
    $office_postal_code = get_post_meta( $post->ID, 'office_postal_code', true );
    $office_longitude = get_post_meta( $post->ID, 'office_longitude', true );
    $office_latitude = get_post_meta( $post->ID, 'office_latitude', true );

    ?>
    <table style = 'width: 100%' class = 'office-locator-metabox'>
        <tr>
            <th><?php echo __( 'Address', 'office-locator' ); ?></th>
            <td><input type='text' name='office_address' id='office_address_fill' autocomplete="off" value="<?php echo esc_attr( $office_address ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'City', 'office-locator' ); ?></th>
            <td><input type='text' name='office_city' id="office_city" value="<?php echo esc_attr( $office_city ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'State', 'office-locator' ); ?></th>
            <td><input type='text' name='office_state' id="office_state" value="<?php echo esc_attr( $office_state ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'Country', 'office-locator' ); ?></th>
            <td><input type='text' name='office_country' id="office_country" value="<?php echo esc_attr( $office_country ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'Postal Code', 'office-locator' ); ?></th>
            <td><input type='text' name='office_postal_code' id="office_postal_code" value="<?php echo esc_attr( $office_postal_code ); ?>"></td>
        </tr>
        <tr>
            <th><?php echo __( 'Office Longitude', 'office-locator' ); ?></th>
            <td><input type='text' name='office_longitude' id="office_longitude" value="<?php echo esc_attr( $office_longitude ); ?>" readonly></td>
        </tr>
        <tr>
            <th><?php echo __( 'Office Latitude', 'office-locator' ); ?></th>
            <td><input type='text' name='office_latitude' id="office_latitude" value="<?php echo esc_attr( $office_latitude ); ?>" readonly></td>
        </tr>
    </table>
    <?php
}

/**
* Saving office meta boxes.
*
* @since    1.0.0
* @access   public
*/

public function save_offices_meta_boxes( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return;
    }

    if ( !current_user_can( 'edit_post', $post_id ) ){
        return;
    }

    $office_save_object = array(
        'office_name',
        'office_phone',
        'office_fax',
        'office_email',
        'office_address',
        'office_city',
        'office_state',
        'office_country',
        'office_postal_code',
        'office_longitude',
        'office_latitude'
    );

    $office_longitude = isset($_POST[ 'office_longitude' ]) ? sanitize_text_field( $_POST[ 'office_longitude' ] ) : '';
    $office_latitude = isset($_POST[ 'office_latitude' ]) ? sanitize_text_field( $_POST[ 'office_latitude' ] ) : '';
    $office_address = isset($_POST[ 'office_address' ]) ? sanitize_text_field( $_POST[ 'office_address' ] ) : '';
    $office_state = isset($_POST[ 'office_state' ]) ? sanitize_text_field( $_POST[ 'office_state' ] ) : '';
    $office_country = isset($_POST[ 'office_country' ]) ? sanitize_text_field( $_POST[ 'office_country' ] ) : '';

    foreach ( $office_save_object as  $olc_meta_key ) {
        if ( isset( $_POST[ $olc_meta_key ] ) ) {
            if( $olc_meta_key == 'office_email' ){
                update_post_meta( $post_id, $olc_meta_key, sanitize_email( $_POST[ $olc_meta_key ] ) );    
            } else {
                update_post_meta( $post_id, $olc_meta_key, sanitize_text_field( $_POST[ $olc_meta_key ] ) );               
            }
        }
    }        

    if(  empty( $office_longitude ) && empty( $office_latitude ) ){
        $address = $office_address. '+' . $office_state . ' + ' . $office_country;
        $office_geocode = $this->get_offices_geocode( $address );
        update_post_meta( $post_id, 'office_latitude', $office_geocode['lat'] );                           
        update_post_meta( $post_id, 'office_longitude', $office_geocode['lng'] );                           
    }

}

/**
* Saving office meta boxes.
*
* @since    1.0.0
* @access   public
*/

public function get_offices_geocode( $address ){
    $data = array(
        'lat' => '',
        'lng' => '',
    );
    $option_name = str_replace( '-', '_', $this->plugin_name ) .'_general';
    $general_data = get_option( $option_name );
    $key = ( isset( $general_data['map_api_key'] ) && !empty( $general_data['map_api_key'] ) ) ? '&key='.$general_data['map_api_key'] : '';
    $map_language = ( isset( $options['map_language'] ) && !empty( $options['map_language'] ) ) ? '&language='.$options['map_language'] : '';
    $map_region = ( isset( $options['map_region'] ) && !empty( $options['map_region'] ) ) ? '&region='.$options['map_region'] : '';
    // geocoding api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode( $address ) . "".$key."".$map_language."".$map_region;
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    $response = curl_exec($ch);
    curl_close($ch);
    if( $response ){
        $response_a = json_decode($response);
        if( $response_a ){
            $data['lat'] = isset($response_a->results[0]->geometry->location->lat) ? $response_a->results[0]->geometry->location->lat : '';
            $data['lng'] = isset($response_a->results[0]->geometry->location->lng) ? $response_a->results[0]->geometry->location->lng : '';     
        }
    }        
    return $data;
}

/**
* import office post data.
*
* @since    1.0.0
* @access   public
*/

public function import_post_address(){

    global $wpdb;

    $office_flag = $inset_count = $update_count = 0;
    $office_meta = $address_arr = $return_array = $insert_array = $update_array = array();
    $post_title = $office_name = $office_phone = $office_fax = $office_email = $office_address = $office_city = $office_state = $office_country = $office_postal_code = $office_geocode = '';  
    if( isset($_FILES['upload_csv_file']['type']) && $_FILES['upload_csv_file']['type'] == 'text/csv' ){
        if( $_FILES['upload_csv_file']['error'] == 0 ){
            $name = $_FILES['upload_csv_file']['name'];
            $ext = strtolower(end(explode('.', $_FILES['upload_csv_file']['name'])));
            $type = $_FILES['upload_csv_file']['type'];
            $tmpName = $_FILES['upload_csv_file']['tmp_name'];
            if( $ext === 'csv' ){
                if(($handle = fopen($tmpName, 'r')) !== FALSE) {
                    set_time_limit(0);
                    $row = 0;
                    while( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== FALSE ) {
                        if( $row >= 1 ){
                            $address_arr[] = $data;
                        }                   
                        $row++;
                    }
                    fclose($handle);
                }
            }

            if( $address_arr && !empty( $address_arr ) ) {
                foreach( $address_arr as $address_data ) {
                    $post_id = '';
                    $unique_id = isset( $address_data[0] ) ? $address_data[0] : ''; 
                    $post_title = isset( $address_data[1] ) ? $address_data[1] : ''; 
                    $office_name = isset( $address_data[2] ) ? $address_data[2] : ''; 
                    $office_phone = isset( $address_data[3] ) ? $address_data[3] : ''; 
                    $office_fax = isset( $address_data[4] ) ? $address_data[4] : ''; 
                    $office_email = isset( $address_data[5] ) ? $address_data[5] : ''; 
                    $office_address = isset( $address_data[6] ) ? $address_data[6] : ''; 
                    $office_city = isset( $address_data[7] ) ? $address_data[7] : ''; 
                    $office_state = isset( $address_data[8] ) ? $address_data[8] : ''; 
                    $office_country = isset( $address_data[9] ) ? $address_data[9] : ''; 
                    $office_postal_code = isset( $address_data[10] ) ? $address_data[10] : ''; 

                    if( $office_address && $office_state && $office_country ){
                        $address = $office_address. '+' . $office_state . ' + ' . $office_country;
                        $office_geocode = $this->get_offices_geocode( $address );
                        $office_latitude = isset(  $office_geocode['lat']  ) ?  $office_geocode['lat'] : '';
                        $office_longitude = isset( $office_geocode['lng'] ) ? $office_geocode['lng'] : '';
                    }

                    $office_meta = array( 'office_name' => $office_name, 'office_phone' => $office_phone, 'office_fax' => $office_fax ,'office_email' => $office_email,'office_address' => $office_address,'office_city' => $office_city, 'office_state' => $office_state,'office_country' => $office_country, 'office_postal_code' => $office_postal_code,'office_latitude' => $office_latitude,'office_longitude' => $office_longitude );
                    
                    $office_sql = "SELECT post_id from $wpdb->postmeta where meta_key ='unique_id' AND meta_value = '".$unique_id."'";
                    $office_sql = apply_filters( 'office_import_address_sql', $office_sql );

                    $results = $wpdb->get_results( $office_sql, ARRAY_A );

                    if ( $results && isset( $results[0]['post_id'] ) ) {
                        $post_id = $results[0]['post_id'];
                        $update_array[$post_id] = $post_id;
                    } else {

                        $create_post = array(
                            'post_title'    => $post_title,
                            'post_status'   => 'publish',
                            'post_type'     => 'offices',
                            'post_author'   => get_current_user_id(),
                        );

                        $post_id = wp_insert_post($create_post);
                        $office_meta['unique_id'] = $unique_id;
                        $insert_array[$post_id] = $post_id;
                    }

                    if( !empty( $post_id ) ){
                        if(  $office_meta ){
                            foreach(  $office_meta as $office_meta_key  => $office_meta_value ){
                                if( $office_meta_key && !empty( $office_meta_key ) && $office_meta_value && !empty( $office_meta_value ) ){
                                    update_post_meta( $post_id, $office_meta_key, $office_meta_value );                                
                                }
                            }
                        }
                        $office_flag = 1;
                    }
                } 
            }
        }
    }

    if(  $office_flag == 1 ){
        if( $insert_array && !empty( $insert_array ) ){
            $inset_count = count( $insert_array );
        }
        if( $update_array && !empty( $update_array ) ){
            $update_count = count( $update_array );
        }
        $return_array = array( 'sucess' => 'sucess','inset_count' => $inset_count, 'update_count' => $update_count );
    } else {
        $return_array['error'] = 'error'; 
    }

    wp_send_json( $return_array );
}

/**
* export office post data.
*
* @since    1.0.0
* @access   public
*/

public function export_post_address(){

    $return_array = $post_data_arr = array();
    $post_title = $office_name = $office_phone = $office_fax = $office_email = $office_address = $office_city = $office_state = $office_country = $office_postal_code = $office_geocode = '';  

    if( isset( $_GET['export_data'] ) && !empty( $_GET['export_data'] ) ){

        $args = array(  
            'post_type' => 'offices',
            'post_status' => 'publish',
            'posts_per_page'=> -1,
        );

        $loop = new WP_Query( $args ); 

        if(  $loop->have_posts() ){
            while ( $loop->have_posts() ) {
                $loop->the_post(); 
                if( get_the_ID() ){
                    $_name = get_the_title();
                    $unique_id = get_post_meta( get_the_ID(),'unique_id', true);
                    $office_name = get_post_meta( get_the_ID(),'office_name', true);
                    $office_phone = get_post_meta( get_the_ID(),'office_phone', true);
                    $office_fax = get_post_meta( get_the_ID(),'office_fax', true);
                    $office_email = get_post_meta( get_the_ID(),'office_email', true);
                    $office_address = get_post_meta( get_the_ID(),'office_address', true);
                    $office_city = get_post_meta( get_the_ID(),'office_city', true);
                    $office_state = get_post_meta( get_the_ID(),'office_state', true);
                    $office_country = get_post_meta( get_the_ID(),'office_country', true);
                    $office_postal_code = get_post_meta( get_the_ID(),'office_postal_code', true);
                    if( empty( $unique_id ) ){
                        $unique_id = get_the_ID().rand(0,100);
                    }
                    $post_data_arr[get_the_ID()] = array( 
                        'Unique ID' => $unique_id, 
                        'Name' => $_name, 
                        'Office Name' => $office_name, 
                        'Office Phone' => $office_phone, 
                        'Office Fax' => $office_fax ,
                        'Office E-mail' => $office_email,
                        'Office Address' => $office_address,
                        'Office City' => $office_city, 
                        'Office State' => $office_state,
                        'Office Country' => $office_country, 
                        'Office Postal_code' => $office_postal_code
                    );
                }
            } 
            wp_reset_postdata();
        } else {
            $return_array['error'] = 'error';
        }

        if( $post_data_arr ){
            $this->download_send_headers("office-" . date("Y-m-d") . ".csv");
            echo $this->array2csv($post_data_arr);
        } else {
            echo "No data Found!";
        }
        die();
    }
}

public function array2csv( array &$array ) {
    if( count( $array ) == 0 ) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach( $array as $row ) {
        fputcsv( $df, $row );
    }
    fclose( $df );
    return ob_get_clean();
}

public function download_send_headers( $filename ) {
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

}