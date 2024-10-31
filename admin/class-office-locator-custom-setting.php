<?php
/**
 * The navs & panels array for setting page.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/admin
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Custom_Settings {

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

     add_filter( "office_locator_settings_nav", array( $this, "add_office_locator_plugin_nav" ), 10, 1 );
     add_filter( "office_locator_settings_panel", array( $this, "add_office_locator_plugin_panel" ), 10, 1 );

     add_filter( "wt_enqueue_admin_styles", array( $this, "change_admin_enqueue_styles") );
     add_filter( "wt_enqueue_admin_scripts", array( $this, "change_wt_enqueue_admin_scripts"), 11, 1 );

   }

    /**
    * This function is return navs array.
    *
    * @since    1.0.0
    * @access   public
    */
    public function add_office_locator_plugin_nav( $navs ) {

      $navs = array(
        'general' => array(
          'title' => __( 'Settings', 'office-locator' ),
          'icon' => 'fa-cogs',
          'action' => true
        ),
        'map' => array(
          'title' => __( 'Map Settings', 'office-locator' ),
          'icon' => 'fa-solid fa-location-arrow',
          'action' => true
        ),
        'map_shortcode' => array(
          'title' => __( 'Map View Generator', 'office-locator' ),
          'icon' => 'fa-solid fa-code',
          'action' => false
        ),
        'permalink' => array(
          'title' => __( 'Permalink', 'office-locator' ),
          'icon' => 'fa-solid fa-link',
          'action' => true
        ),
        'import_export' => array(
          'title' => __( 'Import / Export', 'office-locator' ),
          'icon' => 'fa-solid fa-file-import',
          'action' => false
        ),
      );

      return $navs;
    }

    /**
     * This function is return panels array.
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_office_locator_plugin_panel( $panels ) {

      global $olc_map_markers, $olc_map_styles, $olc_map_layout;

      $outer_zoom_level = $inner_zoom_level = array();

      for( $z = 1; $z <= 15; $z++ ){
        $outer_zoom_level[ $z ] = $z;
      }

      for( $x = 8; $x <= 25; $x++ ){
        $inner_zoom_level[ $x ] = $x;
      }

      $ofcRadiusList = array( 10 => 10,25 => 25,50 => 50,100 => 100,200 => 200,500 => 500,1000 => 1000 );
      $ofcResultList = array( 10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100 );
      
      $google_default_positions = array(
        "TOP_CENTER"    => __( "TOP CENTER", 'office-locator' ),
        "TOP_LEFT"      => __( "TOP LEFT", 'office-locator' ),
        "TOP_RIGHT"     => __( "TOP RIGHT", 'office-locator' ),
        "LEFT_TOP"      => __( "LEFT TOP", 'office-locator' ),
        "RIGHT_TOP"     => __( "RIGHT TOP", 'office-locator' ),
        "LEFT_CENTER"   => __( "LEFT CENTER", 'office-locator' ),
        "RIGHT_CENTER"  => __( "RIGHT CENTER", 'office-locator' ),
        "LEFT_BOTTOM"   => __( "LEFT BOTTOM", 'office-locator' ),
        "RIGHT_BOTTOM"  => __( "RIGHT BOTTOM", 'office-locator' ),
        "BOTTOM_CENTER" => __( "BOTTOM CENTER", 'office-locator' ),
        "BOTTOM_LEFT"   => __( "BOTTOM LEFT", 'office-locator' ),
        "BOTTOM_RIGHT"  => __( "BOTTOM RIGHT", 'office-locator' ),
      );

      $map_language = array(
        ""      =>  __( "Select your language", 'office-locator' ),
        "en"    =>  __( "English", 'office-locator' ),
        "ar"    =>  __( "Arabic", 'office-locator' ),
        "eu"    =>  __( "Basque", 'office-locator' ),
        "bg"    =>  __( "Bulgarian", 'office-locator' ),
        "bn"    =>  __( "Bengali", 'office-locator' ),
        "ca"    =>  __( "Catalan", 'office-locator' ),
        "cs"    =>  __( "Czech", 'office-locator' ),
        "da"    =>  __( "Danish", 'office-locator' ),
        "de"    =>  __( "German", 'office-locator' ),
        "el"    =>  __( "Greek", 'office-locator' ),
        "en-AU" =>  __( "English (Australian)", 'office-locator' ),
        "en-GB" =>  __( "English (Great Britain)", 'office-locator' ),
        "es"    =>  __( "Spanish", 'office-locator' ),
        "fa"    =>  __( "Farsi", 'office-locator' ),
        "fi"    =>  __( "Finnish", 'office-locator' ),
        "fil"   =>  __( "Filipino", 'office-locator' ),
        "fr"    =>  __( "French", 'office-locator' ),
        "gl"    =>  __( "Galician", 'office-locator' ),
        "gu"    =>  __( "Gujarati", 'office-locator' ),
        "hi"    =>  __( "Hindi", 'office-locator' ),
        "hr"    =>  __( "Croatian", 'office-locator' ),
        "hu"    =>  __( "Hungarian", 'office-locator' ),
        "id"    =>  __( "Indonesian", 'office-locator' ),
        "it"    =>  __( "Italian", 'office-locator' ),
        "iw"    =>  __( "Hebrew", 'office-locator' ),
        "ja"    =>  __( "Japanese", 'office-locator' ),
        "kn"    =>  __( "Kannada", 'office-locator' ),
        "ko"    =>  __( "Korean", 'office-locator' ),
        "lt"    =>  __( "Lithuanian", 'office-locator' ),
        "lv"    =>  __( "Latvian", 'office-locator' ),
        "ml"    =>  __( "Malayalam", 'office-locator' ),
        "mr"    =>  __( "Marathi", 'office-locator' ),
        "nl"    =>  __( "Dutch", 'office-locator' ),
        "no"    =>  __( "Norwegian", 'office-locator' ),
        "nn"    =>  __( "Norwegian Nynorsk", 'office-locator' ),
        "pl"    =>  __( "Polish", 'office-locator' ),
        "pt"    =>  __( "Portuguese", 'office-locator' ),
        "pt-BR" =>  __( "Portuguese (Brazil)", 'office-locator' ),
        "pt-PT" =>  __( "Portuguese (Portugal)", 'office-locator' ),
        "ro"    =>  __( "Romanian", 'office-locator' ),
        "ru"    =>  __( "Russian", 'office-locator' ),
        "sk"    =>  __( "Slovak", 'office-locator' ),
        "sl"    =>  __( "Slovenian", 'office-locator' ),
        "sr"    =>  __( "Serbian", 'office-locator' ),
        "sv"    =>  __( "Swedish", 'office-locator' ),
        "tl"    =>  __( "Tagalog", 'office-locator' ),
        "ta"    =>  __( "Tamil", 'office-locator' ),
        "te"    =>  __( "Telugu", 'office-locator' ),
        "th"    =>  __( "Thai", 'office-locator' ),
        "tr"    =>  __( "Turkish", 'office-locator' ),
        "uk"    =>  __( "Ukrainian", 'office-locator' ),
        "vi"    =>  __( "Vietnamese", 'office-locator' ),
        "zh-CN" =>  __( "Chinese (Simplified)", 'office-locator' ),
        "zh-TW" =>  __( "Chinese (Traditional)", 'office-locator' ),
      );

      $map_region = array(
        ""   => __( "Select your region", "office-locator" ),
        "af" => __( "Afghanistan", "office-locator" ),
        "al" => __( "Albania", "office-locator" ),
        "dz" => __( "Algeria", "office-locator" ),
        "as" => __( "American Samoa", "office-locator" ),
        "ad" => __( "Andorra", "office-locator" ),
        "ao" => __( "Angola", "office-locator" ),
        "ai" => __( "Anguilla", "office-locator" ),
        "aq" => __( "Antarctica", "office-locator" ),
        "ag" => __( "Antigua and Barbuda", "office-locator" ),
        "ar" => __( "Argentina", "office-locator" ),
        "am" => __( "Armenia", "office-locator" ),
        "aw" => __( "Aruba", "office-locator" ),
        "ac" => __( "Ascension Island", "office-locator" ),
        "au" => __( "Australia", "office-locator" ),
        "at" => __( "Austria", "office-locator" ),
        "az" => __( "Azerbaijan", "office-locator" ),
        "bs" => __( "Bahamas", "office-locator" ),
        "bh" => __( "Bahrain", "office-locator" ),
        "bd" => __( "Bangladesh", "office-locator" ),
        "bb" => __( "Barbados", "office-locator" ),
        "by" => __( "Belarus", "office-locator" ),
        "be" => __( "Belgium", "office-locator" ),
        "bz" => __( "Belize", "office-locator" ),
        "bj" => __( "Benin", "office-locator" ),
        "bm" => __( "Bermuda", "office-locator" ),
        "bt" => __( "Bhutan", "office-locator" ),
        "bo" => __( "Bolivia", "office-locator" ),
        "ba" => __( "Bosnia and Herzegovina", "office-locator" ),
        "bw" => __( "Botswana", "office-locator" ),
        "bv" => __( "Bouvet Island", "office-locator" ),
        "br" => __( "Brazil", "office-locator" ),
        "io" => __( "British Indian Ocean Territory", "office-locator" ),
        "vg" => __( "British Virgin Islands", "office-locator" ),
        "bn" => __( "Brunei", "office-locator" ),
        "bg" => __( "Bulgaria", "office-locator" ),
        "bf" => __( "Burkina Faso", "office-locator" ),
        "bi" => __( "Burundi", "office-locator" ),
        "kh" => __( "Cambodia", "office-locator" ),
        "cm" => __( "Cameroon", "office-locator" ),
        "ca" => __( "Canada", "office-locator" ),
        "ic" => __( "Canary Islands", "office-locator" ),
        "cv" => __( "Cape Verde", "office-locator" ),
        "bq" => __( "Caribbean Netherlands", "office-locator" ),
        "ky" => __( "Cayman Islands", "office-locator" ),
        "cf" => __( "Central African Republic", "office-locator" ),
        "ea" => __( "Ceuta and Melilla", "office-locator" ),
        "td" => __( "Chad", "office-locator" ),
        "cl" => __( "Chile", "office-locator" ),
        "cn" => __( "China", "office-locator" ),
        "cx" => __( "Christmas Island", "office-locator" ),
        "cp" => __( "Clipperton Island", "office-locator" ),
        "cc" => __( "Cocos (Keeling), Islands", "office-locator" ),
        "co" => __( "Colombia", "office-locator" ),
        "km" => __( "Comoros", "office-locator" ),
        "cd" => __( "Congo (DRC),", "office-locator" ),
        "cg" => __( "Congo (Republic),", "office-locator" ),
        "ck" => __( "Cook Islands", "office-locator" ),
        "cr" => __( "Costa Rica", "office-locator" ),
        "hr" => __( "Croatia", "office-locator" ),
        "cu" => __( "Cuba", "office-locator" ),
        "cw" => __( "Curaçao", "office-locator" ),
        "cy" => __( "Cyprus", "office-locator" ),
        "cz" => __( "Czech Republic", "office-locator" ),
        "ci" => __( "Côte d'Ivoire", "office-locator" ),
        "dk" => __( "Denmark", "office-locator" ),
        "dj" => __( "Djibouti", "office-locator" ),
        "cd" => __( "Democratic Republic of the Congo", "office-locator" ),
        "dm" => __( "Dominica", "office-locator" ),
        "do" => __( "Dominican Republic", "office-locator" ),
        "ec" => __( "Ecuador", "office-locator" ),
        "eg" => __( "Egypt", "office-locator" ),
        "sv" => __( "El Salvador", "office-locator" ),
        "gq" => __( "Equatorial Guinea", "office-locator" ),
        "er" => __( "Eritrea", "office-locator" ),
        "ee" => __( "Estonia", "office-locator" ),
        "et" => __( "Ethiopia", "office-locator" ),
        "fk" => __( "Falkland Islands(Islas Malvinas),", "office-locator" ),
        "fo" => __( "Faroe Islands", "office-locator" ),
        "fj" => __( "Fiji", "office-locator" ),
        "fi" => __( "Finland", "office-locator" ),
        "fr" => __( "France", "office-locator" ),
        "gf" => __( "French Guiana", "office-locator" ),
        "pf" => __( "French Polynesia", "office-locator" ),
        "tf" => __( "French Southern Territories", "office-locator" ),
        "ga" => __( "Gabon", "office-locator" ),
        "gm" => __( "Gambia", "office-locator" ),
        "ge" => __( "Georgia", "office-locator" ),
        "de" => __( "Germany", "office-locator" ),
        "gh" => __( "Ghana", "office-locator" ),
        "gi" => __( "Gibraltar", "office-locator" ),
        "gr" => __( "Greece", "office-locator" ),
        "gl" => __( "Greenland", "office-locator" ),
        "gd" => __( "Grenada", "office-locator" ),
        "gu" => __( "Guam", "office-locator" ),
        "gp" => __( "Guadeloupe", "office-locator" ),
        "gt" => __( "Guatemala", "office-locator" ),
        "gg" => __( "Guernsey", "office-locator" ),
        "gn" => __( "Guinea", "office-locator" ),
        "gw" => __( "Guinea-Bissau", "office-locator" ),
        "gy" => __( "Guyana", "office-locator" ),
        "ht" => __( "Haiti", "office-locator" ),
        "hm" => __( "Heard and McDonald Islands", "office-locator" ),
        "hn" => __( "Honduras", "office-locator" ),
        "hk" => __( "Hong Kong", "office-locator" ),
        "hu" => __( "Hungary", "office-locator" ),
        "is" => __( "Iceland", "office-locator" ),
        "in" => __( "India", "office-locator" ),
        "id" => __( "Indonesia", "office-locator" ),
        "ir" => __( "Iran", "office-locator" ),
        "iq" => __( "Iraq", "office-locator" ),
        "ie" => __( "Ireland", "office-locator" ),
        "im" => __( "Isle of Man", "office-locator" ),
        "il" => __( "Israel", "office-locator" ),
        "it" => __( "Italy", "office-locator" ),
        "jm" => __( "Jamaica", "office-locator" ),
        "jp" => __( "Japan", "office-locator" ),
        "je" => __( "Jersey", "office-locator" ),
        "jo" => __( "Jordan", "office-locator" ),
        "kz" => __( "Kazakhstan", "office-locator" ),
        "ke" => __( "Kenya", "office-locator" ),
        "ki" => __( "Kiribati", "office-locator" ),
        "xk" => __( "Kosovo", "office-locator" ),
        "kw" => __( "Kuwait", "office-locator" ),
        "kg" => __( "Kyrgyzstan", "office-locator" ),
        "la" => __( "Laos", "office-locator" ),
        "lv" => __( "Latvia", "office-locator" ),
        "lb" => __( "Lebanon", "office-locator" ),
        "ls" => __( "Lesotho", "office-locator" ),
        "lr" => __( "Liberia", "office-locator" ),
        "ly" => __( "Libya", "office-locator" ),
        "li" => __( "Liechtenstein", "office-locator" ),
        "lt" => __( "Lithuania", "office-locator" ),
        "lu" => __( "Luxembourg", "office-locator" ),
        "mo" => __( "Macau", "office-locator" ),
        "mk" => __( "Macedonia (FYROM),", "office-locator" ),
        "mg" => __( "Madagascar", "office-locator" ),
        "mw" => __( "Malawi", "office-locator" ),
        "my" => __( "Malaysia ", "office-locator" ),
        "mv" => __( "Maldives ", "office-locator" ),
        "ml" => __( "Mali", "office-locator" ),
        "mt" => __( "Malta", "office-locator" ),
        "mh" => __( "Marshall Islands", "office-locator" ),
        "mq" => __( "Martinique", "office-locator" ),
        "mr" => __( "Mauritania", "office-locator" ),
        "mu" => __( "Mauritius", "office-locator" ),
        "yt" => __( "Mayotte", "office-locator" ),
        "mx" => __( "Mexico", "office-locator" ),
        "fm" => __( "Micronesia", "office-locator" ),
        "md" => __( "Moldova", "office-locator" ),
        "mc" => __( "Monaco", "office-locator" ),
        "mn" => __( "Mongolia", "office-locator" ),
        "me" => __( "Montenegro", "office-locator" ),
        "ms" => __( "Montserrat", "office-locator" ),
        "ma" => __( "Morocco", "office-locator" ),
        "mz" => __( "Mozambique", "office-locator" ),
        "mm" => __( "Myanmar (Burma),", "office-locator" ),
        "na" => __( "Namibia", "office-locator" ),
        "nr" => __( "Nauru", "office-locator" ),
        "np" => __( "Nepal", "office-locator" ),
        "nl" => __( "Netherlands", "office-locator" ),
        "an" => __( "Netherlands Antilles", "office-locator" ),
        "nc" => __( "New Caledonia", "office-locator" ),
        "nz" => __( "New Zealand", "office-locator" ),
        "ni" => __( "Nicaragua", "office-locator" ),
        "ne" => __( "Niger", "office-locator" ),
        "ng" => __( "Nigeria", "office-locator" ),
        "nu" => __( "Niue", "office-locator" ),
        "nf" => __( "Norfolk Island", "office-locator" ),
        "kp" => __( "North Korea", "office-locator" ),
        "mp" => __( "Northern Mariana Islands", "office-locator" ),
        "no" => __( "Norway", "office-locator" ),
        "om" => __( "Oman", "office-locator" ),
        "pk" => __( "Pakistan", "office-locator" ),
        "pw" => __( "Palau", "office-locator" ),
        "ps" => __( "Palestine", "office-locator" ),
        "pa" => __( "Panama", "office-locator" ),
        "pg" => __( "Papua New Guinea", "office-locator" ),
        "py" => __( "Paraguay", "office-locator" ),
        "pe" => __( "Peru", "office-locator" ),
        "ph" => __( "Philippines", "office-locator" ),
        "pn" => __( "Pitcairn Islands", "office-locator" ),
        "pl" => __( "Poland", "office-locator" ),
        "pt" => __( "Portugal", "office-locator" ),
        "pr" => __( "Puerto Rico", "office-locator" ),
        "qa" => __( "Qatar", "office-locator" ),
        "re" => __( "Reunion", "office-locator" ),
        "ro" => __( "Romania", "office-locator" ),
        "ru" => __( "Russia", "office-locator" ),
        "rw" => __( "Rwanda", "office-locator" ),
        "sh" => __( "Saint Helena", "office-locator" ),
        "kn" => __( "Saint Kitts and Nevis", "office-locator" ),
        "vc" => __( "Saint Vincent and the Grenadines", "office-locator" ),
        "lc" => __( "Saint Lucia", "office-locator" ),
        "ws" => __( "Samoa", "office-locator" ),
        "sm" => __( "San Marino", "office-locator" ),
        "st" => __( "São Tomé and Príncipe", "office-locator" ),
        "sa" => __( "Saudi Arabia", "office-locator" ),
        "sn" => __( "Senegal", "office-locator" ),
        "rs" => __( "Serbia", "office-locator" ),
        "sc" => __( "Seychelles", "office-locator" ),
        "sl" => __( "Sierra Leone", "office-locator" ),
        "sg" => __( "Singapore", "office-locator" ),
        "sx" => __( "Sint Maarten", "office-locator" ),
        "sk" => __( "Slovakia", "office-locator" ),
        "si" => __( "Slovenia", "office-locator" ),
        "sb" => __( "Solomon Islands", "office-locator" ),
        "so" => __( "Somalia", "office-locator" ),
        "za" => __( "South Africa", "office-locator" ),
        "gs" => __( "South Georgia and South Sandwich Islands", "office-locator" ),
        "kr" => __( "South Korea", "office-locator" ),
        "ss" => __( "South Sudan", "office-locator" ),
        "es" => __( "Spain", "office-locator" ),
        "lk" => __( "Sri Lanka", "office-locator" ),
        "sd" => __( "Sudan", "office-locator" ),
        "sz" => __( "Swaziland", "office-locator" ),
        "se" => __( "Sweden", "office-locator" ),
        "ch" => __( "Switzerland", "office-locator" ),
        "sy" => __( "Syria", "office-locator" ),
        "st" => __( "São Tomé &amp; Príncipe", "office-locator" ),
        "tw" => __( "Taiwan", "office-locator" ),
        "tj" => __( "Tajikistan", "office-locator" ),
        "tz" => __( "Tanzania", "office-locator" ),
        "th" => __( "Thailand", "office-locator" ),
        "tl" => __( "Timor-Leste", "office-locator" ),
        "tk" => __( "Tokelau", "office-locator" ),
        "tg" => __( "Togo", "office-locator" ),
        "to" => __( "Tonga", "office-locator" ),
        "tt" => __( "Trinidad and Tobago", "office-locator" ),
        "ta" => __( "Tristan da Cunha", "office-locator" ),
        "tn" => __( "Tunisia", "office-locator" ),
        "tr" => __( "Turkey", "office-locator" ),
        "tm" => __( "Turkmenistan", "office-locator" ),
        "tc" => __( "Turks and Caicos Islands", "office-locator" ),
        "tv" => __( "Tuvalu", "office-locator" ),
        "ug" => __( "Uganda", "office-locator" ),
        "ua" => __( "Ukraine", "office-locator" ),
        "ae" => __( "United Arab Emirates", "office-locator" ),
        "gb" => __( "United Kingdom", "office-locator" ),
        "us" => __( "United States", "office-locator" ),
        "uy" => __( "Uruguay", "office-locator" ),
        "uz" => __( "Uzbekistan", "office-locator" ),
        "vu" => __( "Vanuatu", "office-locator" ),
        "va" => __( "Vatican City", "office-locator" ),
        "ve" => __( "Venezuela", "office-locator" ),
        "vn" => __( "Vietnam", "office-locator" ),
        "wf" => __( "Wallis Futuna", "office-locator" ),
        "eh" => __( "Western Sahara", "office-locator" ),
        "ye" => __( "Yemen", "office-locator" ),
        "zm" => __( "Zambia", "office-locator" ),
        "zw" => __( "Zimbabwe", "office-locator" ),
        "ax" => __( "Åland Islands", "office-locator" ),
      );

$panels['general'] = array(
  'section' => array(
    array(
      'title' => __( 'Map API', 'office-locator' ),
      'icon' => 'fa-solid fa-location-dot',
      'desc' => '',
      'fields' => array(
        array(
          'name' => 'map_api_key',
          'type' => 'text',
          'title' => __( 'API Key', 'office-locator' ),
          'desc' => '',
          'field_desc' => sprintf( __( 'How to obtain google maps api key? <a href="%s" target="_blank">click here</a>.', 'office-locator' ), 
            'https://developers.google.com/maps/documentation/javascript/get-api-key'
          ),
          'required' => true
        ),
        array(
          'type' => 'select',
          'title' => __( 'Map Language', 'office-locator' ),
          'field_desc' =>  __( 'If no map language is chosen, the browsers default language is utilised.', 'office-locator' ),
          'name' => 'map_language',
          'options' => $map_language,
          'default' => 'en'
        ),
        array(
          'type' => 'select',
          'title' => __( 'Map Region', 'office-locator' ),
          'field_desc' => sprintf( __( 'This will skew the <a href="%s" target="_blank">geocoding</a> results in favour of the chosen region.<br>If no region is chosen, the bias is set to the United States.', 'office-locator' ), 
            'https://developers.google.com/maps/documentation/javascript/geocoding#Geocoding'
          ),
          'name' => 'map_region',
          'options' => $map_region,
          'default' => 'us'
        ),
      )
    )
  )
);

$panels['map'] = array(
  'section' => array(
    array(
      'title' => __( 'Clarifications', 'office-locator' ),
      'icon' => 'fa-solid fa-map',
      'desc' => '',
      'fields' => array(
        array(
          'name' => 'olc_start_point',
          'type' => 'google_autocomplete',
          'required' => true,
          'title' => __( 'Start point', 'office-locator'  )
        ),
        array(
          'type' => 'select',
          'title' => __('Outer Zoom Level', 'office-locator' ),
          'name' => 'outer_zoom_level',
          'options' => $outer_zoom_level,
          'default' => 4
        ),
        array(
          'type' => 'select',
          'title' => __( 'Inner Zoom Level', 'office-locator' ),
          'name' => 'inner_zoom_level',
          'options' => $inner_zoom_level,
          'default' => 11
        ),
        array(
          'type' => 'switch',
          'name' => 'street_view_control',
          'title' => __( 'Enable the street view controls', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),

        array(
          'type' => 'select',
          'title' => __( 'Street View Control Position', 'office-locator' ),
          'name' => 'street_view_ctlr_pos',
          'options' => $google_default_positions,
          'default' => 'RIGHT_BOTTOM'
        ),

        array(
          'type' => 'switch',
          'name' => 'map_type_control',
          'title' => __( 'Enable map type control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),

        array(
          'type' => 'select',
          'title' => __( 'Map Type Control Position', 'office-locator' ),
          'name' => 'map_type_ctlr_pos',
          'options' => $google_default_positions,
          'default' => 'TOP_LEFT'
        ),

        array(
          'type' => 'switch',
          'name' => 'full_screen_control',
          'title' => __( 'Enable full Screen control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),

        array(
          'type' => 'select',
          'title' => __( 'Full Screen Control Position', 'office-locator' ),
          'name' => 'full_screen_ctlr_pos',
          'options' => $google_default_positions,
          'default' => 'RIGHT_TOP'
        ),

        array(
          'type' => 'switch',
          'name' => 'zoom_control',
          'title' => __( 'Enable Zoom control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),
        array(
          'type' => 'switch',
          'name' => 'wheel_zooming',
          'title' => __( 'Enable scroll wheel zooming', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),

        array(
          'type' => 'select',
          'title' => __( 'Zoom Control Position', 'office-locator' ),
          'name' => 'zoom_position',
          'options' => $google_default_positions,
          'default' => 'RIGHT_BOTTOM'
        ),

        array(
          'name' => 'map_width',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Map Width', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'map_width'
          ),
          'parameters' => array(   
            // Available only Text, Number and Select             
            array(
              'type' => 'number',
              'name' => 'width',
              'title' => __( 'Width', 'office-locator' ),
              'desc' => '',
              'default' => 100,
              'placeholder' => __( 100, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
                '%' => __( '%', 'office-locator' )
              ),
              'default' => '%'
            )
          )
        ),
        array(
          'name' => 'map_height',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Map Height', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'map_height',
          ),
          'parameters' => array(
            // Available only Text, Number and Select
            array(
              'type' => 'number',
              'name' => 'height',
              'title' => __( 'Height', 'office-locator' ),
              'desc' => '',
              'default' => 100,
              'placeholder' => __( 100, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
                '%' => __( '%', 'office-locator' ),
              ),
              'default' => '%'
            )
          )
        ), 
        array(
          'name' => 'start_location_marker_width',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Start Location Marker Width', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'start_location_marker_width',
          ),
          'parameters' => array(
            // Available only Text, Number and Select
            array(
              'type' => 'number',
              'name' => 'width',
              'title' => __( 'Width', 'office-locator' ),
              'desc' => '',
              'default' => 25,
              'placeholder' => __( 25, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
              ),
              'default' => 'PX'
            )
          )
        ), 
        array(
          'name' => 'start_location_marker_height',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Start Location Marker Height', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'start_location_marker_height',
          ),
          'parameters' => array(
            // Available only Text, Number and Select
            array(
              'type' => 'number',
              'name' => 'height',
              'title' => __( 'Height', 'office-locator' ),
              'desc' => '',
              'default' => 35,
              'placeholder' => __( 35, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
              ),
              'default' => 'PX'
            )
          )
        ),  

        array(
          'name' => 'store_location_marker_width',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Store Location Marker Width', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'store_location_marker_width',
          ),
          'parameters' => array(
            // Available only Text, Number and Select
            array(
              'type' => 'number',
              'name' => 'width',
              'title' => __( 'Width', 'office-locator' ),
              'desc' => '',
              'default' => 25,
              'placeholder' => __( 25, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
              ),
              'default' => 'PX'
            )
          )
        ), 
        array(
          'name' => 'store_location_marker_height',
          'type' => 'multiple_parameter_inputs',
          'class' => 'parameter_wrapper pixel_valid',
          'title' => __( 'Store Location Marker Height', 'office-locator' ),
          'attributes' => array(
            'shortcode_attr' => 'store_location_marker_height',
          ),
          'parameters' => array(
            // Available only Text, Number and Select
            array(
              'type' => 'number',
              'name' => 'height',
              'title' => __( 'Height', 'office-locator' ),
              'desc' => '',
              'default' => 35,
              'placeholder' => __( 35, 'office-locator' ),
              'min' => 1
            ),
            array(
              'type' => 'select',
              'title' => __( 'Select', 'office-locator' ),
              'name' => 'value',
              'options' => array(
                'px' => __( 'PX', 'office-locator' ),
              ),
              'default' => 'PX'
            )
          )
        ),
        array(
          'type' => 'select',
          'title' => __( 'Map Type', 'office-locator' ),
          'name' => 'map_view_type',
          'options' => array(
            'roadmap' => __( 'Roadmap', 'office-locator' ),
            'hybrid' => __( 'Hybrid', 'office-locator' ),
            'satellite' => __( 'Satellite', 'office-locator' ),
            'terrain' => __( 'Terrain', 'office-locator' )
          ),
          'default' => 'roadmap'
        ),
        array(
          'type' => 'select',
          'title' => __( 'Map Office Radius', 'office-locator' ),
          'name' => 'map_office_radius',
          'options' =>  $ofcRadiusList,
          'default' => '10'
        ), 
        array(
          'type' => 'select',
          'title' => __( 'Map Office Results', 'office-locator' ),
          'name' => 'map_office_results',
          'options' => $ofcResultList,
          'default' => '10'
        ),
        array(
          'name' => 'map_office_unit',
          'type' => 'radio',
          'title' => __( 'Distance Unit', 'office-locator' ),
          'options' => array(
            'km',
            'mi',
          ),
          'default' => 'km',
        ),
        array(
          'type' => 'switch',
          'name' => 'enable_store_filter',
          'title' => __( 'Enable Stores Filter Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),
        array(
          'type' => 'switch',
          'name' => 'enable_store_office',
          'title' => __( 'Enable Stores Offices Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),
        array(
          'type' => 'switch',
          'name' => 'enable_start_location_marker_control',
          'title' => __( 'Enable Start Location Marker Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),
        array(
          'type' => 'switch',
          'name' => 'enable_store_location_marker_control',
          'title' => __( 'Enable Store Location Marker Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ), 
        array(
          'type' => 'switch',
          'name' => 'enable_start_location_marker_pop_up_control',
          'title' => __( 'Enable Start Location Marker Pop-Up Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),
        array(
          'type' => 'switch',
          'name' => 'enable_store_location_marker_pop_up_control',
          'title' => __( 'Enable Store Location Marker Pop-Up Control', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
          'default' => 'unable'
        ),   
      ),
),
array(
  'title' => __( 'Styles', 'office-locator' ),
  'icon' => 'fa-solid fa-location-dot',
  'desc' => '',
  'fields' => array(
    array(
      'type' => 'select',
      'title' => __( 'Map Container Size', 'office-locator' ),
      'name' => 'map_container_size',
      'options' => array(
        'container' => __( 'Container', 'office-locator' ),
        'full-width' => __( 'Full Width', 'office-locator' )
      ),
      'default' => 'container'
    ),
    array(
      'name' => 'map_container_max_width',
      'type' => 'multiple_parameter_inputs',
      'class' => 'parameter_wrapper pixel_valid',
      'title' => __( 'Map Container Max Width', 'office-locator' ),
      'attributes' => array(
        'shortcode_attr' => 'map_container_max_width',
      ),
      'parameters' => array(
        // Available only Text, Number and Select
        array(
          'type' => 'number',
          'name' => 'width',
          'title' => __( 'Width', 'office-locator' ),
          'desc' => '',
          'default' => 1650,
          'placeholder' => __( 1650, 'office-locator' ),
          'min' => 1
        ),
        array(
          'type' => 'select',
          'title' => __( 'Select', 'office-locator' ),
          'name' => 'value',
          'options' => array(
            'px' => __( 'PX', 'office-locator' ),
            '%' => __( '%', 'office-locator' )
          ),
          'default' => 'PX'
        )
      )
    ),
    array(
      'name' => 'map_background_color',
      'type' => 'color',
      'title' => __( 'Map Background Color', 'office-locator' ),
      'field_desc' => __( 'Map Background Color Colours.', 'office-locator' ),
      'required' => false,
      'default' => '#f9f9f9'
    ), 
    array(
      'name' => 'map_style',
      'type' => 'appearance',
      'title' => __( 'Map Styles', 'office-locator' ),
      'class' => 'input-field',
      'attributes' => array(
        'shortcode_attr' => 'map_style'
      ),
      'desc' => '',
      'field_desc' => '',
      'default' => 'standard',
      'options' => $olc_map_styles,
      'OR' => array(
        array(
          'name' => 'custom_style',
          'type' => 'textarea',
          'title' => __( 'Custom Style', 'office-locator' ),
          'desc' => '',
          'field_desc' => ''
        )
      )
    ),
    array(
      'name' => 'start_location_marker',
      'type' => 'appearance',
      'class' => 'input-field',
      'title' => __( 'Start Location Marker', 'office-locator' ),
      'desc' => '',
      'field_desc' => '',
      'default' => 'store-blue',
      'attributes' => array(
        'shortcode_attr' => 'start_location_marker'
      ),
      'options' => $olc_map_markers,
      'OR' => array(
        array(
          'name' => 'custom_start_location_marker',
          'type' => 'file',
          'title' => __( 'Custom Start Location Marker', 'office-locator' ),
          'desc' => '',
          'field_desc' => ''
        )
      )
    ),
    array(
      'name' => 'store_marker',
      'type' => 'appearance',
      'class' => 'input-field',
      'title' => __( 'Store Location Marker', 'office-locator' ),
      'desc' => '',
      'field_desc' => '',
      'default' => 'store-green',
      'attributes' => array(
        'shortcode_attr' => 'store_marker'
      ),
      'options' => $olc_map_markers,
      'OR' => array(
        array(
          'name' => 'custom_store_marker',
          'type' => 'file',
          'title' => __( 'Custom Store Location Marker', 'office-locator' ),
          'desc' => '',
          'field_desc' => ''
        )
      )
    ), 
    array(
      'name' => 'map_layout',
      'type' => 'appearance',
      'class' => 'input-field',
      'title' => __( 'Map Layout', 'office-locator' ),
      'desc' => '',
      'field_desc' => '',
      'default' => 'layout-1',
      'attributes' => array(
        'shortcode_attr' => 'map_layout'
      ),
      'options' => $olc_map_layout,
    ),
  )
)
)
);

$panels['permalink'] = array(
  'section' => array(
    array(
      'title' => __( 'Permalink', 'office-locator' ),
      'icon' => 'fa-solid fa-link',
      'fields' => array(
       array(
        'type' => 'switch',
        'name' => 'permalink_switcher',
        'title' => __( 'Enable Permalink', 'office-locator' ),
        'default' => 'disable'
      ),
       array(
        'type' => 'switch',
        'name' => 'open_office_new_tab',
        'title' => __( 'Open Office New Tab', 'office-locator' ),
        'default' => 'disable'
      ),
       array(
        'name' => 'store_slug',
        'type' => 'text',
        'title' => __( 'Store Slug', 'office-locator' ),
        'desc' => __( '', 'office-locator' ),                            
        'field_desc' => __( 'The <b>permalink slugs</b> must be unique on your site', 'office-locator' ),
        'disabled' => false,
        'readonly' => false,
        'required' => false,
        'icon' => 'fa-solid fa-clone'                            
      ),
     )
    )
  )
);

$panels['map_shortcode'] = $panels['map'];
unset($panels['map_shortcode']['section'][1]['fields'][0]['OR']);

$args = array(
  'post_type' => 'offices',
  'post_status' => 'publish',
  'posts_per_page' => -1,
);

$office_ids = array();
$fields = array();

$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
  while ( $the_query->have_posts() ) { 
    $the_query->the_post();
    $office_ids[get_the_ID()] = get_the_title();
  }
} 
wp_reset_postdata();

if( isset( $office_ids ) && !empty( $office_ids ) ){
  $fields = array(
    'title' => __( 'Offices', 'office-locator' ),
    'icon' => 'fa-solid fa-store',
    'desc' => '',
    'fields' => array(
      array(
        'type' => 'multi-select',
        'title' => __( 'Offices', 'office-locator' ),
        'name' => 'offices',
        'options' => $office_ids,
        'class' => '',
        'attributes' => array(
          'shortcode_attr' => 'offices',
        ),
      )
    )
  );
  array_unshift( $panels['map_shortcode']['section'], $fields );
}

$shortcode_fields = array(
  'title' => __( 'Shortcode', 'office-locator' ),
  'icon' => 'fa-solid fa-code',
  'desc' => '',
  'fields' => array(
    array(
      'name' => 'office_locator_shortcode',
      'type' => 'text',
      'title' => __( 'Shortcode', 'office-locator' ),
      'desc' => '',
      'field_desc' => '',
      'default' => '[office_locator]',
      'readonly' => true,
      'icon' => 'fa-solid fa-clone',
    )
  )
);

array_unshift( $panels['map_shortcode']['section'], $shortcode_fields );

$panels['import_export'] = array(
  'section' => array(
    array(
      'title' => __( 'Import / Export', 'office-locator' ),
      'icon' => 'fa-solid fa-file-import',
      'desc' => '',
      'fields' => array(
        array(
          'name' => 'map_office_import',
          'type' => 'import_export',
          'class' => 'input-field',
          'title' => __( 'Import Office Address', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
        ),
        array(
          'name' => 'map_office_export',
          'type' => 'export_import',
          'class' => 'input-field',
          'title' => __( 'Export Office Address', 'office-locator' ),
          'desc' => '',
          'field_desc' => '',
        ),
      )
    )
  )
);

return $panels;

}

    /**
    * This function is for add custom extra scripts after default scripts.
    *
    * @since    1.0.0
    * @access   public
    */
    public function change_wt_enqueue_admin_scripts( $enqueue_scripts ) {
      $current_screen = get_current_screen();
      global $post;      
      if ( strpos( $current_screen->base, $this->plugin_name ) == true || isset( $post->post_type ) && $post->post_type == 'offices'  ) {
        $option_name = str_replace( '-', '_', $this->plugin_name ) .'_general';
        $general_data = get_option( $option_name );
        $key = ( isset( $general_data['map_api_key'] ) && !empty( $general_data['map_api_key'] ) ) ? '&key='.$general_data['map_api_key'] : '';
        $map_language = ( isset( $general_data['map_language'] ) && !empty( $general_data['map_language'] ) ) ? '&language='.$general_data['map_language'] : '';
        $map_region = ( isset( $general_data['map_region'] ) && !empty( $general_data['map_region'] ) ) ? '&region='.$general_data['map_region'] : '';
        if( $key ){                  
          $enqueue_scripts[$this->plugin_name] = array(
            'localize_script' => 'wt_ajax',
            'direct' => false,
            'path' => plugin_dir_url( __FILE__ ) . 'js/admin.js',
            'localize_array' => array(
              'ajaxurl' => admin_url( 'admin-ajax.php' ),
              'nonce' => wp_create_nonce('wt_form_save'),
            )
          );
          $enqueue_scripts["olc-google-map"] = array(
            'localize_script' => array(),
            'direct' => false,
            'path' => 'https://maps.googleapis.com/maps/api/js?callback=ofcMpInitialize'.$key.''.$map_language.''.$map_region.'&libraries=places&sensor=false'
          );
        }

      }
      return $enqueue_scripts;

    }

    /**
     * This function is for change default styles.
     *
     * @since    1.0.0
     * @access   public
     */
    public function change_admin_enqueue_styles( $enqueue_styles ) {
      global $post;      
      if( isset( $post->post_type ) && $post->post_type == 'offices' ){       
        $enqueue_styles['wt-admin-setting'] = array(
          'direct' => false,
          'path' => plugin_dir_url( __FILE__ ) . 'css/admin.css'
        );
        $enqueue_styles['wt-font-awesome'] = array(
          'direct' => false,
          'path' => plugin_dir_url( __FILE__ ) . 'css/all.min.css'
        );
      }
      return $enqueue_styles;
    }

  }