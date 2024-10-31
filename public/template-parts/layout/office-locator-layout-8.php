<?php 
$map_container_size =  '100%';
if( $olcMapAttr && $olcMapAttr['map_container_size'] && !empty( $olcMapAttr['map_container_size'] ) && $olcMapAttr['map_container_size'] != 'full-width' ){
    $map_container_size = isset( $olcMapAttr['map_container_max_width']  ) ? $olcMapAttr['map_container_max_width'] : '100%';
}
$olc_section_id = "olc_".rand( 111111,999999 );
global $ofcRadiusList, $ofcResultList;
?>
<section class="office-locater-one olc-layout <?php echo esc_attr($olcMapAttr['map_layout']); ?>" data-id="<?php echo esc_attr( $olc_section_id ); ?>" id="<?php echo esc_attr( $olc_section_id ); ?>">
    <div class="olc-container">

        <script type="text/javascript">
            ofcMapObj['<?php echo esc_js( $olc_section_id ); ?>'] = <?php echo wp_json_encode( $olcMapAttr ); ?>;        
        </script>

        <style> 
            <?php 
            $olc_style_8 = "#".$olc_section_id." .olc-google-map{  width:".$olcMapAttr['map_width']." !important; height:".$olcMapAttr['map_height']." !important; } #".$olc_section_id." .office-panel{ height:".$olcMapAttr['map_height']." !important; } #".$olc_section_id.".olc-layout { max-width: ".$map_container_size." !important; background:".$olcMapAttr['map_background_color']."; } @media only screen and (max-width: 767px){ #".$olc_section_id." .olc-google-map, #".$olc_section_id." .office-panel {height: 500px  !important;width: 100%  !important; }} @media only screen and (max-width: 575px){ #".$olc_section_id." .office-panel,#".$olc_section_id." .olc-google-map {height: 400px  !important;}}";

            $olc_style_8 = apply_filters( 'olc_map_inline_style_8', $olc_style_8, $olc_section_id, $olcMapAttr );
            echo esc_html( $olc_style_8 );
            ?>
        </style>
        <?php

        if( isset($ofcGeneralData['map_api_key']) && !empty( $ofcGeneralData['map_api_key'] ) ){
            ?>
            <div class="olc-google-map"></div>
            <?php
        }else{
            ?>
            <div class="office-no-map"><p><?php echo __( 'Please Enter Google API First to Load Google Map', 'office-locator' );?></p></div>
            <?php
        }
        ?>
        <div class="office-locater-filter olc-col-1">
            <?php 
            $class = "hidden";
            if( isset( $olcMapAttr['enable_store_filter'] ) && !empty( $olcMapAttr['enable_store_filter'] ) && $olcMapAttr['enable_store_filter'] == 'yes' ){
                $class = "";
            } 
            include 'global/office-locator-filter.php';
            ?>
        </div> 
        <?php
        if( isset($ofcGeneralData['map_api_key']) && !empty( $ofcGeneralData['map_api_key'] ) ){
            if( isset( $olcMapAttr['enable_store_office'] ) && !empty( $olcMapAttr['enable_store_office'] ) && $olcMapAttr['enable_store_office'] == 'yes' ){ ?>  
                <div class="office-locater-slider">
                    <div class="swiper office-locator-swiper office-locater-one-left office-panel" id="office-panel">
                    </div>
                    <div class="office-locater-arrow">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>    
                    </div>
                </div>
            <?php }  
        }
        ?>
    </div>
</section>