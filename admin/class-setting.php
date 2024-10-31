<?php
/**
 * The navs & panels array for setting page.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/admin
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Settings {

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
    * This function is return navs array.
    *
    * @since    1.0.0
    * @access   public
    */
    public function plugin_nav(){

      $navs = array();

      $navs = apply_filters( 'office_locator_settings_nav', $navs );

      return $navs;
    }

    /**
     * This function is return navs list.
     *
     * @since    1.0.0
     * @access   public
     */
    public function plugin_nav_list() {

      $navs = $this->plugin_nav();
      $i = 1;
      $nav_tab = isset($_GET['tab']) ? sanitize_text_field( $_GET['tab'] ) : '';
      if ( !empty( $navs ) ){

        echo '<ul class="wt-tabs">';

        foreach ( $navs as $key => $nav ) {
          $class = '';          
          if( !empty( $nav_tab ) ){
            if( $key == $nav_tab ){
              $class = 'active';
            }
          }else{
            if( $i == 1 ){
              $class = 'active';
            }
          } 

          echo '<li class="'.esc_attr( 'tab ' . $class ).'"><a href="' . esc_url( admin_url('admin.php?page=' . $this->plugin_name) ) . '&tab=' . esc_attr( $key ) . '">';
          echo (!empty( $nav['icon'] ) ) ? '<i class="'.esc_attr( 'fa ' . $nav['icon'] ).'" aria-hidden="true"></i> &nbsp;' : '';
          echo wp_kses_post( $nav['title'] ). '</a></li>';
          $i++;
        }
        echo '</ul>';
      }

    }

    /**
     * This function is return panels array.
     *
     * @since    1.0.0
     * @access   public
     */
    public function plugin_panel() {
      $panels = array();
      $panels = apply_filters( 'office_locator_settings_panel', $panels );     
      return $panels;
    }

    /**
     * This function is return panels list.
     *
     * @since    1.0.0
     * @access   public
     */
    public function plugin_panel_list() {   
      $panels = $this->plugin_panel();  
      $tab = (isset($_GET['tab'])) ? sanitize_text_field( $_GET['tab'] ) : 'general';   
      if ( isset($panels[$tab]) && $sections = $panels[$tab] ) {    
        foreach ( $sections['section'] as $key => $section ) {  
          echo '<div class="wt-section">';  
          echo '<div class="title">';   
          echo ( isset( $section['icon'] ) && !empty( $section['icon'] ) ) ? '<div class="icon"><i class="' .esc_attr( $section['icon'] ). '"></i></div>' : '' ;    
          echo '<div class="desc">';
          echo ( isset( $section['title'] ) && !empty( $section['title'] ) ) ? '<h2>' . wp_kses_post( $section['title'] ) . '</h2>' : '';
          echo ( isset( $section['desc'] ) && !empty( $section['desc'] ) ) ? '<p>'. wp_kses_post( $section['desc'] ) .'</p>' : '';
          echo '</div>';    
          echo '</div>';    
          echo '<div class="form-table-wrap">'; 
          echo '<table class="form-table">';    
          echo '<tbody>';   
          if( $section['fields'] ){ 
            foreach ( $section['fields'] as $field ) {  
              $this->plugin_get_field( $field );    
            }   
          } 
          echo '</tbody>';  
          echo '</table>';  
          echo '</div>';    
          echo '</div>';    
        }   
      } 
    }

    /**
     * This function is return field structure.
     *
     * @since    1.0.0
     * @access   public
     */
    public function plugin_get_field( $field ) {

      $structure = new Office_Locator_Fields( $this->plugin_name, $this->version );

      echo '<tr ' . ( ( $field['type'] == 'hidden' ) ? 'class="hidden"' : '') . '>';  
      echo '<th>';  
      echo ( isset( $field['title'] ) && !empty( $field['title'] ) ? wp_kses_post( $field['title'] ) : '' );  
      echo ( isset($field['desc']) && !empty( $field['desc'] ) ) ? '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>' : ''; 
      echo '</th>'; 
      echo '<td>';

      switch ( $field['type'] ) {
        case "text":
        $structure->text_field( $field );
        break;
        case "textarea":
        $structure->textarea_field($field);
        break;
        case "hidden":
        $structure->hidden_field( $field );
        break;
        case "url":
        $structure->url_field( $field );
        break;
        case "password":
        $structure->password_field( $field );
        break;
        case "number":
        $structure->number_field( $field );
        break;
        case "tel":
        $structure->tel_field( $field );
        break;
        case "file":
        $structure->file_field( $field );
        break;
        case "email":
        $structure->email_field( $field );
        break;
        case "submit":
        $structure->submit_field( $field );
        break;
        case "checkbox":
        $structure->checkbox_field( $field );
        break;
        case "radio":
        $structure->radio_field( $field );
        break;
        case "select":
        $structure->select_field( $field );
        break;
        case "multi-select":
        $structure->multi_select_field( $field );
        break;
        case "sortable":
        $structure->sortable_field( $field );
        break;
        case "color":
        $structure->color_field( $field );
        break;
        case "datepicker":
        $structure->datepicker_field( $field );
        break;
        case "from_to_datepicker":
        $structure->from_to_datepicker_field( $field );
        break;
        case "switch":
        $structure->switch_field( $field );
        break;
        case "ranger":
        $structure->ranger_field( $field );
        break;
        case "between_ranger":
        $structure->between_ranger_field( $field );
        break;
        case "parameter_select":
        $structure->parameter_select_field( $field );
        break;
        case "multiple_parameter_inputs":
        $structure->multiple_parameter_field( $field );
        break;
        default: 
        $structure->custom_field( $field );             
      }
      echo '</td>';
      echo '</tr>';
    }
  }