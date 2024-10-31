<form class="olc-storelocator-filter <?php echo esc_attr( $class ); ?>" method="post">
    <div class="olc-location-search">           
        <div class="olc-input">
            <label for="olc-location-search-input"><?php echo __( 'Office Locations', 'office-locator' ); ?></label> 
            <input class="olc-location-search-input"  name="location_search" placeholder="<?php echo __( 'Enter office locations', 'office-locator' ); ?>" autocomplete="off">
        </div>
        <div class="olc-results">
            <div class="olc-input">
                <label for="olc_radius"><?php echo __( 'Office Radius', 'office-locator' ); ?></label>   
                <select id="olc_radius" name="olc_radius" class="olc_radius">
                    <?php 
                    $map_office_unit = isset( $ofcMapData['map_office_unit'] ) ? trim( $ofcMapData['map_office_unit'] ) : 'km';
                    
                    if( $ofcRadiusList ){
                        foreach ( $ofcRadiusList as $ofcRadius ) {
                            ?>
                            <option value="<?php echo esc_attr( $ofcRadius ); ?>" <?php echo ( ( $ofcRadius == $olcMapAttr['map_office_radius'] ) ? 'selected' : '' ); ?>><?php echo esc_html( $ofcRadius.' '.$map_office_unit ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>               
            </div>  
            <div class="olc-input">
                <label for="olc_results"><?php echo __( 'Office Results', 'office-locator' ); ?></label>                 
                <select class="olc_results" id="olc_results" name="olc_results">
                    <?php 
                    if( $ofcResultList ){
                        foreach ( $ofcResultList as $ofcResult ) {
                            ?>
                            <option value="<?php echo esc_attr( $ofcResult ); ?>" <?php echo ( ( $ofcResult == $olcMapAttr['map_office_results'] ) ? 'selected' : '' ); ?>><?php echo esc_html( $ofcResult ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>   
            </div>
        </div>
        <div class="olc-input">
            <?php wp_nonce_field( 'find_olc_stores', 'olcType' ); ?>
            <input type="hidden" name="action" value="get_office_locator_stores">
            <input type="hidden" name="olc_latitude"  class="olc_latitude" value="">
            <input type="hidden" name="olc_longitude" class="olc_longitude" value="">
            <input type="hidden" name="olc_office_ids" class="olc_office_ids" value="">
            <input type="hidden" name="olc_layout" class="olc_layout" value="<?php echo esc_attr($olcMapAttr['map_layout']); ?>">
            <input type="hidden" name="olc_distance_unit" class="olc_distance_unit" value="<?php echo esc_attr( $map_office_unit ); ?>">
            <button type="submit" class="olc-btn olc-submit-btn"><?php  echo __( 'Find Stores', 'office-locator' ); ?><span class="olc-loader"><i class="fa-solid fa-spin fa-spinner"></i></span></button>
        </div>  
    </div>
</form>