<?php
/**
 * The field functions for setting page.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/admin
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Extra_Fields {
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
    * @param      string    $version           The version of this plugin.
    */
    public function __construct( $plugin_name, $version ) {

      $this->plugin_name = $plugin_name;
      $this->version = $version;

    }

    /**
     * That function return appearance checkbox field.
     *
     * @since    1.0.0
     * @access   public
     */
    public function appearance_field( $field, $plugin_name, $version ){

      $structure = new Office_Locator_Fields( $plugin_name, $version );
      $structure_setting = new Office_Locator_Settings( $plugin_name, $version );

      $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
      $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
      $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

      $value = $structure->get_value( $name, $default );

      if( !isset( $value ) || empty( $value ) || $value == ''  ){
        $value = [];        
      }

      echo "<table width='100%'>";

      echo "<td>";

      echo '<div class="'.esc_attr( 'appearance-checkbox '.$class ).'">';

      if( $field['options'] ){

        foreach( $field['options'] as $key => $options ){

          echo '<div class="field_wrapper">';

          /* checkbox label */
          echo '<label for="'. esc_attr( $name.'-'.$key ) .'">';

          /* start input checkbox */
          echo '<input type="radio" class="radio_field"';

          /* checkbox name */
          echo 'name="'.esc_attr( $name ).'"';

          /* custom attribute */
          echo ( isset( $field['attributes'] ) && ( !empty( $field['attributes'] ) ) ? $structure->set_attributes($field['attributes']) : '' );

          /* checkbox id */
          echo ( ( isset( $key ) && !empty( $key ) ) ? 'id="'.esc_attr( $name.'-'.$key ).'"' : '' );

          /* checkbox value */
          echo 'value="'.esc_attr( $key ).'"';

          /* set the checked value */
          if( $value ){
            checked( $key, $value );  
          }
          /* end input checkbox */

          echo '>';

          echo '<img src="'.esc_url( $options['img'] ).'">';

          echo '<span class="title">'.wp_kses_post( $options['title'] ).'</span>';

          echo '</label>';

          echo '</div>';

        }
      }

      echo '</div>';

      /* checkbox description */
      echo ( ( isset( $field['field_desc'] ) && !empty($field['field_desc'] ) ) ? '<span class="field_description">'.wp_kses_post( $field['field_desc'] ).'</span>' : '' );

      echo "</td>";

      if( isset( $field['OR'] ) && !empty( $field['OR'] ) ){
        echo "<td>";

        $OR = $field['OR'];

        echo "<table width='100%'>";

        foreach( $OR as $or_field ){
          if( isset( $or_field['title'] ) ){
            $or_field['title'] = '<i>'.__( 'OR', 'office-locator' ).'</i> ~ '.( isset( $or_field['title'] ) && !empty( $or_field['title'] ) ? wp_kses_post( $or_field['title'] ) : '' );  
          }
          
          $structure_setting->plugin_get_field( $or_field );
        } 

        echo "</table>";

        echo "</td>";
      }

      echo "</table>";

    }


    /**
     * That function return google autocomplete field.
     *
     * @since    1.0.0
     * @access   public
     */
    public function google_autocomplete_field( $field, $plugin_name, $version ){

      $structure = new Office_Locator_Fields( $plugin_name, $version );

      $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
      $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
      $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

      $value = $structure->get_value( $name, $default );

      $address = isset($value['address']) ? $value['address'] : '';
      $latlong = isset($value['lat_long']) ? $value['lat_long'] : '';

      echo '<div class="'.esc_attr( 'input-field input-autocomplete-field '.$class ).'">';

      /* start text input */
      echo '<input type="text" class="text_field olc_autocomplete_field" ';

      /* input name */
      echo 'name="'.esc_attr( $name.'[address]' ).'"';

      /* input id */
      echo 'id="'.esc_attr( $name ).'"';

      echo 'shortcode_attr="text_field address"';

      /* input placeholder */
      echo ( isset( $field['placeholder'] ) && ( !empty( $field['placeholder'] ) ) ? 'placeholder="'.esc_attr( $field['placeholder'] ).'"' : '' );

      /* input default value */
      echo 'value="'.esc_attr( $address ).'"';

      /* input autocomplete */

      echo 'autocomplete="off"';

      /* input disabled attribute */
      echo ( isset( $field['disabled'] ) && ( $field['disabled'] ) ? ' disabled ' : '' );

      /* input readonly attribute */
      echo ( isset( $field['readonly'] ) && ( $field['readonly'] ) ? ' readonly ' : '' );

      /* input required attribute */
      echo ( isset( $field['required'] ) && ( $field['required'] ) ? ' required ' : '' );

      /* end text input */
      echo '>';

      /* set latitude and logitude */
      echo '<input type="hidden" name="'.esc_attr( $name.'[lat_long]' ).'" shortcode_attr="lat_long" class="text_field olc_lat_long" value="'.esc_attr( $latlong ).'" />';

      echo ( isset( $field['icon'] ) && ( $field['icon'] ) ? '<a href="javascript:;" class="copy field-trigger" data-target="#'.esc_attr( $name ).'"><i class="'.esc_attr( $field['icon'] ).'"></i></a>' : '' );

      echo '</div>';          

      /* input description */
      echo ( isset( $field['field_desc'] ) && ( !empty($field['field_desc'] ) ) ? '<span class="field_description">'.wp_kses_post( $field['field_desc'] ).'</span>' : '' );
    }

    /**
     * That function return import export field.
     *
     * @since    1.0.0
     * @access   public
     */
    public function import_export_field( $field, $plugin_name, $version ){

      $structure = new Office_Locator_Fields( $plugin_name, $version );
      $structure_setting = new Office_Locator_Settings( $plugin_name, $version );

      $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
      $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
      $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

      echo "<table class='office-locator-table' width='100%'>";
      echo '<thead>';
      echo '<tr><th colspan="5"><div class="office_locator_import_message"></div></th></tr>';
      echo '</thead>';
      echo '<tbody>';
      echo '<tr>';
      echo '<td class="office_locator_content">';
      echo '<div class="offcie_locator_csv_main">';
      echo ' <div class="office_locator_file_box">';
      echo '<input type="file" name="'.esc_attr( $name ).'" class="'.esc_attr( $class ).'" id="offcie_locator_file_upload" accept=".csv">';
      echo '<span><i class="fa fa-upload " aria-hidden="true"></i></span>';
      echo '</div>';
      echo '<div id="offcie_locator_upload_csv" class="offcie_locator_upload_btn  button button-primary">'.__( 'Import', 'office-locator' ).'<div class="office-locator-dual-ring"><span class="wc-loader-spin"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span></div></div>';
      echo '<div class="file-upload download-tooltip"><a href="'.esc_url( site_url( '/wp-content/plugins/office-locator/admin/images/office-sample.csv' ) ).'" download><i class="fa fa-download" aria-hidden="true"></i></a><span class="office-download-tooltiptext">'.__( 'Download Sample CSV', 'office-locator' ).'</span></div>';
      echo '</div>';
      echo "<div class='instruction'>".__( 'Only CSV files may be imported. Same as per sample CSV.', 'office-locator' )."</div>";
      echo '</td>';
      echo '</tr>';
      echo '</tbody>';
      echo "</table>";
    }

     /**
     * That function return import export field.
     *
     * @since    1.0.0
     * @access   public
     */
     public function export_import_field( $field, $plugin_name, $version ){

      $structure = new Office_Locator_Fields( $plugin_name, $version );
      $structure_setting = new Office_Locator_Settings( $plugin_name, $version );

      $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
      $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
      $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

      echo "<div class='office-locator-export' width='100%'>";
      echo '<div id="offcie_locator_export_csv" class="offcie_locator_export_btn  button"><a target="_blank" href="'.site_url( '/wp-admin/admin-ajax.php?action=export_post_address&export_data=export_data' ).'" ><i class="fa-solid fa-file-export"></i>'.__( 'Export', 'office-locator' ).'</a></div>';
      echo "</div>";
    }
  }