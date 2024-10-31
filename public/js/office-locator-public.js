(function($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 /* This form use to return all office locations */
	jQuery(document).on('submit', '.olc-storelocator-filter', function(e) {
		e.preventDefault();
		var olc_form = jQuery(this);
		var olc_section = jQuery(this).closest('.olc-layout');
		var olcSectionID = olc_section.attr( 'data-id' );
		var location_address = olc_form.find('.olc-location-search-input').val();
		olc_form.find('.olc_office_ids').val( ofcMapObj[olcSectionID].offices );		
		olc_form.find('.olc-error').remove();
		location_address = location_address.trim();
		if(location_address == '') {
			olc_form.find('.olc-location-search-input').after('<p class="olc-error">Please ' + olc_form.find('.olc-location-search-input').attr('placeholder') + '</p>');
		} else {
			olc_form.find('.olc-submit-btn .olc-loader').addClass('active');
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: ofcAjax.ajaxurl,
				data: olc_form.serialize(),
				success: function( response ) {
					ofcMapObj[olcSectionID].olc_map.data.setMap(null);
					for(let i = 0; i < ofcMapObj[olcSectionID].load_olc_markers.length; i++) {
						ofcMapObj[olcSectionID].load_olc_markers[i].setMap(null);
					}
					ofcMapObj[olcSectionID].directionsRenderer.setMap(null);
					olc_section.find('.olc-address-list,.olc-direction-view,.olc-direction-top').remove();					

					olcLoadOffices( response, olc_section, 1, olcSectionID );
					olc_form.find('.olc-submit-btn .olc-loader').removeClass('active');
				},
				error: function( response ) {
					ofcMapObj[olcSectionID].olc_map.data.setMap(null);
					for(let i = 0; i < ofcMapObj[olcSectionID].load_olc_markers.length; i++) {
						ofcMapObj[olcSectionID].load_olc_markers[i].setMap(null);
					}
					ofcMapObj[olcSectionID].directionsRenderer.setMap(null);
					olc_section.find('.olc-address-list,.olc-direction-view,.olc-direction-top').remove();
					olc_form.find('.olc-submit-btn .olc-loader').removeClass('active');
				}
			});
		}

	});

	 /* This click use to render direction on google map */
	jQuery(document).on('click', '.ofc-directions', function(e) {
		var location_html = '';
		var store_title = '';
		var office_id = jQuery(this).attr('data-office-id');		
		var olc_section = jQuery(this).closest('.olc-layout');		
		var olcSectionID = olc_section.attr( 'data-id' );
		var location_search = olc_section.find('.olc-location-search-input').val();
		location_search = location_search.trim();

		if( location_search == '' ) {
			olcRenderDirectionsPlace( office_id, olc_section, olcSectionID, location_html, store_title, false );
		} else {
			olcRenderDirectionsPlace( office_id, olc_section, olcSectionID, location_html, store_title, true );
		}

		if( ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control != '' && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control == 'yes' ){
			setTimeout(function () {
				ofcMapObj[olcSectionID].ofcInfowindow.close();
			}, 500);
		}
		jQuery(this).closest('.office-locater-slider').find('.office-locater-arrow').hide();		
	});

	 /* This click use to render direction on google map and open google map infowindow */
	jQuery(document).on('click', '.office-locater-box', function(e) {
		var office_id = jQuery(this).attr('data-office-id');	
		var olc_section = jQuery(this).closest('.olc-layout');		
		var olcSectionID = olc_section.attr( 'data-id' );
		var location_html = '';
		var latitude = '';
		var langitude = '';
		var store_title = '';

		jQuery(ofcMapObj[olcSectionID].load_olc_stores).each(function(index,value){
			if( value.office_id == office_id ){				
				location_html = value.location_html;
				latitude = value.office_latitude;
				langitude = value.office_longitude;
				store_title = value.office_title;
			}
		});

		var markerOptions = {
			position: new google.maps.LatLng( latitude, langitude ),
			title: store_title,
			map: ofcMapObj[olcSectionID].olc_map
		}

		var $store_location_marker_width = 25;
		var $store_location_marker_height = 35;
		if( ofcMapObj[olcSectionID].store_location_marker_width && ofcMapObj[olcSectionID].store_location_marker_width != '' ){
			$store_location_marker_width = ofcMapObj[olcSectionID].store_location_marker_width;		
			if( $store_location_marker_width.toString().indexOf('px') !== -1){
				$store_location_marker_width = $store_location_marker_width.replace("px", "");
			}		
		}

		if( ofcMapObj[olcSectionID].store_location_marker_height && ofcMapObj[olcSectionID].store_location_marker_height != '' ){
			$store_location_marker_height = ofcMapObj[olcSectionID].store_location_marker_height;				
			if($store_location_marker_height.toString().indexOf('px') !== -1){
				$store_location_marker_height = $store_location_marker_height.replace("px", "");
			}		
		}

		if( ofcMapObj[olcSectionID].store_marker !== undefined ) {
			if( ofcMapObj[olcSectionID].store_marker ) {
				const olc_marker_icon = {
					url: ofcMapObj[olcSectionID].store_marker,
					scaledSize: new google.maps.Size( parseInt( $store_location_marker_width ) , parseInt( $store_location_marker_height ) )
				};
				markerOptions.icon = olc_marker_icon;
			}
		}

		if( ofcMapObj[olcSectionID].enable_store_location_marker_control && ofcMapObj[olcSectionID].enable_store_location_marker_control != '' && ofcMapObj[olcSectionID].enable_store_location_marker_control == 'yes' ){
			ofcMapObj[olcSectionID].ofcMarker = new google.maps.Marker(markerOptions);
			ofcMapObj[olcSectionID].load_olc_markers.push( ofcMapObj[olcSectionID].ofcMarker );
		}

		if( ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control != '' && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control == 'yes' ){
			location_html = location_html.replace( 'office-locater-box', 'olc-store' );
			ofcMapObj[olcSectionID].ofcInfowindow.setContent(location_html);
			ofcMapObj[olcSectionID].ofcInfowindow.open(ofcMapObj[olcSectionID].olc_map, ofcMapObj[olcSectionID].ofcMarker);
			setTimeout(function(){
				if( jQuery('.gm-style-iw-c').length > 0 ){
					jQuery('.gm-style-iw-c').addClass('olc-info-box-item');
				}				
			},100);

		}		
	});

	 /* This click use to back to the office item list */
	jQuery(document).on('click', '.olc-prev', function(e) {
		jQuery(this).closest('.office-locater-slider').find('.office-locater-arrow').show();		
		var olc_section = jQuery(this).closest('.olc-layout');		
		var olcSectionID = olc_section.attr( 'data-id' );
		olc_section.find('.olc-direction-view,.olc-direction-top').remove();
		olcHideDirections( olc_section, olcSectionID );
	});

})(jQuery);

	/* This function use to initialize office map layout. */
function ofcMpInitialize() {	
	if( jQuery('.olc-layout').length > 0 ){
		jQuery('.olc-layout').each(function(){
			var olc_section = jQuery(this);
			var olcSectionID = jQuery(this).attr( 'data-id' );				
			var olcMapExtra = {
				'directionsService' : new google.maps.DirectionsService(),
				'directionsRenderer' : new google.maps.DirectionsRenderer(),
				'geocoder' : new google.maps.Geocoder(),
				'ofcInfowindow' : new google.maps.InfoWindow(),
				'ofcMarker' : '',
				'olcPanelDiv' : olc_section.find('.office-panel'),
				'olcDefaultLatLong' : {} ,
				'olcPlace' : '',
				'olc_map_id' : '',
				'OlcMapStyle' : [],
				'officeLatLongCenter' : '',
				'ofc_lat' : '',
				'ofc_long' : '',
				'olcLocationInput' : olc_section.find('.olc-location-search-input')[0],
				'olcAutocomplete' : new google.maps.places.Autocomplete( olc_section.find('.olc-location-search-input')[0] ),
			};
			ofcMapObj[olcSectionID].olcDefaultZoom = ofcMapObj[olcSectionID].olc_zoom_map;

			ofcMapObj[olcSectionID] = jQuery.extend({}, ofcMapObj[olcSectionID], olcMapExtra );
			if( ofcMapObj[olcSectionID].olc_lat_long.length > 0 ){
				olc_load_offices( ofcMapObj[olcSectionID].olc_lat_long[0], ofcMapObj[olcSectionID].olc_lat_long[1], olc_section, olcSectionID );
			}else{
				if( navigator.geolocation ) {
					navigator.geolocation.getCurrentPosition(
						function(position) {				
							olc_load_offices( position.coords.latitude, position.coords.longitude, olc_section, olcSectionID );
						},
						function() {
							olc_load_offices( 37.09024, -95.712891, olc_section, olcSectionID );
						}
						);
				}else{
					olc_load_offices( 37.09024, -95.712891, olc_section, olcSectionID );
				}
			}		
			ofcMapObj[olcSectionID].olcAutocomplete.addListener('place_changed', function() {
				var olcPlace = ofcMapObj[olcSectionID].olcAutocomplete.getPlace();
				olc_section.find(".olc_latitude").val( olcPlace.geometry['location'].lat() );
				olc_section.find(".olc_longitude").val( olcPlace.geometry['location'].lng() );
			});
		});
	}
}

	/* This function use to load offices */
function olc_load_offices( olc_lat, olc_long, olc_section, olcSectionID ) {

	olc_section.find(".olc_latitude").val( olc_lat );
	olc_section.find(".olc_longitude").val( olc_long );
	olc_section.find('.olc_office_ids').val( ofcMapObj[olcSectionID].offices );

	ofcMapObj[olcSectionID].olc_map_id = google.maps.MapTypeId.ROADMAP;
	if(ofcMapObj[olcSectionID].map_view_type !== undefined) {
		ofcMapObj[olcSectionID].olc_map_id = ofcMapObj[olcSectionID].map_view_type;
	}

	if(ofcMapObj[olcSectionID].map_style) {		
		ofcMapObj[olcSectionID].OlcMapStyle = ofcMapObj[olcSectionID].map_style;
		if(typeof ofcMapObj[olcSectionID].OlcMapStyle == 'object') {			
			ofcMapObj[olcSectionID].OlcMapStyle = ofcMapObj[olcSectionID].OlcMapStyle;
		}else{
			ofcMapObj[olcSectionID].OlcMapStyle = jQuery.parseJSON( ofcMapObj[olcSectionID].OlcMapStyle );
		}
	}

	ofcMapObj[olcSectionID].olcDefaultLatLong = new google.maps.LatLng(olc_lat, olc_long);

	var olc_map_obj = {
		center: ofcMapObj[olcSectionID].olcDefaultLatLong,
		zoom: parseInt(ofcMapObj[olcSectionID].olc_zoom_map),
		mapTypeId: ofcMapObj[olcSectionID].olc_map_id,
		mapTypeControl: false,
		zoomControl : false,
		streetViewControl : false,		
		scrollwheel: false,		
		fullscreenControl : false
	};

	if( ofcMapObj[olcSectionID].zoom_control == 'yes'  ){
		olc_map_obj.zoomControl = true;
		olc_map_obj.zoomControlOptions = {
			position: google.maps.ControlPosition[ofcMapObj[olcSectionID].zoom_position],
		};

	}
	if( ofcMapObj[olcSectionID].street_view_control == 'yes'  ){
		olc_map_obj.streetViewControl = true;
		olc_map_obj.streetViewControlOptions = {
			position: google.maps.ControlPosition[ofcMapObj[olcSectionID].street_view_ctlr_pos],
		};
	}
	if( ofcMapObj[olcSectionID].full_screen_control == 'yes'  ){
		olc_map_obj.fullscreenControl = true;
		olc_map_obj.fullscreenControlOptions = {
			position: google.maps.ControlPosition[ofcMapObj[olcSectionID].full_screen_ctlr_pos]
		}
	}
	if( ofcMapObj[olcSectionID].map_type_control == 'yes'  ){
		olc_map_obj.mapTypeControl = true;
		olc_map_obj.mapTypeControlOptions = {
			position: google.maps.ControlPosition[ofcMapObj[olcSectionID].map_type_ctlr_pos],
		};
	}
	if( ofcMapObj[olcSectionID].wheel_zooming == 'yes'  ){
		olc_map_obj.scrollwheel = true;
	}

	ofcMapObj[olcSectionID].olc_map = new google.maps.Map(olc_section.find('.olc-google-map')[0], olc_map_obj );

	ofcMapObj[olcSectionID].olc_map.data.setMap(null);
	ofcMapObj[olcSectionID].olc_map.setOptions({
		styles: ofcMapObj[olcSectionID].OlcMapStyle
	});	
	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : ofcAjax.ajaxurl,
		data: olc_section.find('.olc-storelocator-filter').serialize(),
		success: function( response ) {
			olcLoadOffices( response, olc_section, 0, olcSectionID );
		}
	})  
}

	/* This function use to render places on google  map*/
function olcRenderDirectionsPlace ( office_id, olc_section, olcSectionID, location_html, store_title, render_flag ) {
	ofcMapObj[olcSectionID].olcDirectionTo = {};
	ofcMapObj[olcSectionID].olcDirectionFrom = olc_section.find('.olc-location-search-input').val();
	ofcMapObj[olcSectionID].olcDirectionFrom = ofcMapObj[olcSectionID].olcDirectionFrom.trim();
	if( ofcMapObj[olcSectionID].load_olc_stores !== undefined ) {
		jQuery( ofcMapObj[olcSectionID].load_olc_stores ).each(function(s_key, store_d) {
			if(store_d.office_id == office_id) {				
				ofcMapObj[olcSectionID].olcDirectionTo = store_d.position;			
			}
		});
	}
	if( render_flag ) {
		if( ofcMapObj[olcSectionID].olcDirectionFrom && ofcMapObj[olcSectionID].olcDirectionTo ) {
			olcRenderDirection( ofcMapObj[olcSectionID].olcDirectionFrom, ofcMapObj[olcSectionID].olcDirectionTo, olc_section, olcSectionID );
		}
	} else {
		if(ofcMapObj[olcSectionID].olcDirectionFrom == '') {

			if( ofcMapObj[olcSectionID].olc_lat_long.length > 0 ){			
				ofcMapObj[olcSectionID].olcDirectionFrom = new google.maps.LatLng( ofcMapObj[olcSectionID].olc_lat_long[0], ofcMapObj[olcSectionID].olc_lat_long[1] );
				olcRenderDirection( ofcMapObj[olcSectionID].olcDirectionFrom, ofcMapObj[olcSectionID].olcDirectionTo, olc_section, olcSectionID );
			}else{
				if(navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(
						function(position) {
							ofcMapObj[olcSectionID].olcDirectionFrom = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
							olcRenderDirection( ofcMapObj[olcSectionID].olcDirectionFrom, ofcMapObj[olcSectionID].olcDirectionTo, olc_section, olcSectionID );
						},
						function() {}
						);
				}
			}
		}
	}
}

	/* This function use to show render direction table */
function olcRenderDirection( olcDirectionFrom, olcDirectionTo, olc_section, olcSectionID ) {
	if( olcDirectionFrom && olcDirectionTo ) {
		olc_section.find(".olc-direction-top,.olc-direction-view").empty();		
		var olcUnit = 'IMPERIAL';
		if( ofcMapObj[olcSectionID].map_office_unit !== undefined && ofcMapObj[olcSectionID].map_office_unit == 'km' ){
			olcUnit = 'METRIC';
		}
		ofcMapObj[olcSectionID].directionsService.route({
			origin: olcDirectionFrom,
			destination: olcDirectionTo,
			travelMode: google.maps.DirectionsTravelMode.DRIVING,
			unitSystem: google.maps.UnitSystem[ olcUnit ] 
		}, function(c, d) {
			if(d == google.maps.DirectionsStatus.OK) {
				ofcMapObj[olcSectionID].directionsRenderer.setMap( ofcMapObj[olcSectionID].olc_map );
				ofcMapObj[olcSectionID].directionsRenderer.setDirections(c);
				var response = c;
				var olc_direction_list = '',
				olc_direction_header = '',
				olc_copyright_footer = '';
				if(c.routes.length > 0) {
					var olc_direction = c.routes[0];
					for(var x = 0; x < olc_direction.legs.length; x++) {
						var y_legs = olc_direction.legs[x];
						for(var y = 0, y_cnt = y_legs.steps.length; y < y_cnt; y++) {
							var step = y_legs.steps[y];
							var sr_no = y + 1;
							olc_direction_list += "<tr>\
							<td class='olc-index'>" + sr_no + "</td>\
							<td class='olc-desc'>" + step.instructions + "</td>\
							<td class='olc-distance'>" + step.distance.text + "</td>\
							</tr>";
						}
					}
					olc_direction_list = '<table>' + olc_direction_list + '</table>';
					olc_direction_header = "<div class='olc-direction-top'>\
					<a class='olc-prev' href='javascript:;'>"+ofcMapObj[olcSectionID].back_btn_text+"</a>\
					<ul><li><span class='olc-total-distance'>" + olc_direction.legs[0].distance.text + "</span> - <span class='olc-total-time'>" + olc_direction.legs[0].duration.text + "</span></li>\
					</div>";
					olc_copyright_footer = "<div class='olc-copyright-text'>" + response.routes[0].copyrights + "</div>";
					olc_section.find(".olc-address-list").fadeOut();
					olc_section.find(".olc-address-list").after(olc_direction_header+'<div class="olc-direction-view">' + olc_direction_list + olc_copyright_footer + '</div>');
					olc_section.find('.office-panel').animate({
						scrollTop: 0
					}, 100);

				}
			}
		});
	}
}

	/* This function use to hide render direction table */

function olcHideDirections( olc_section, olcSectionID ) {
	olc_section.find(".olc-address-list").fadeIn();
	ofcMapObj[olcSectionID].directionsRenderer.setMap( null );	
	ofcMapObj[olcSectionID].olc_map.setCenter( ofcMapObj[olcSectionID].olcDefaultLatLong );
	ofcMapObj[olcSectionID].olc_map.setZoom( parseInt(ofcMapObj[olcSectionID].olcDefaultZoom) );
}

	/* This function use to reload office */
function olcLoadOffices( response, olc_section, ajax = 0, olcSectionID ) {
	ofcMapObj[olcSectionID].load_olc_stores = [];
	ofcMapObj[olcSectionID].ofcMarker = [];
	if( ofcMapObj[olcSectionID].load_olc_markers.length > 0 ){
		for (let i = 0; i < ofcMapObj[olcSectionID].load_olc_markers.length; i++) {
			ofcMapObj[olcSectionID].load_olc_markers[i].setMap(null);
		}	
	}
	var apply_slider_flag = 0;
	if( response.length > 0 ) {

		var address_html = '';
		jQuery( response ).each(function(office_key, store_data) {

			address_html += store_data.office_store_html;
			store_data.location_html = store_data.office_store_html;

			if( store_data.office_latitude == '' && store_data.office_longitude == '' ){
				ofcMapObj[olcSectionID].geocoder.geocode( { 'address': store_data.office_address }, function(results, status) {
					if (status == 'OK') {
						store_data.office_latitude = results[0].geometry['location'].lat();
						store_data.office_longitude = results[0].geometry['location'].lng();
					} 
				});
			}

			var markerOptions = {
				position: new google.maps.LatLng( store_data.office_latitude, store_data.office_longitude ),
				title: store_data.office_title,
				map: ofcMapObj[olcSectionID].olc_map
			}

			var $store_location_marker_width = 25;
			var $store_location_marker_height = 35;
			if( ofcMapObj[olcSectionID].store_location_marker_width && ofcMapObj[olcSectionID].store_location_marker_width != '' ){
				$store_location_marker_width = ofcMapObj[olcSectionID].store_location_marker_width;		
				if( $store_location_marker_width.toString().indexOf('px') !== -1){
					$store_location_marker_width = $store_location_marker_width.replace("px", "");
				}		
			}

			if( ofcMapObj[olcSectionID].store_location_marker_height && ofcMapObj[olcSectionID].store_location_marker_height != '' ){
				$store_location_marker_height = ofcMapObj[olcSectionID].store_location_marker_height;				
				if($store_location_marker_height.toString().indexOf('px') !== -1){
					$store_location_marker_height = $store_location_marker_height.replace("px", "");
				}		
			}

			if( ofcMapObj[olcSectionID].store_marker !== undefined ) {
				if( ofcMapObj[olcSectionID].store_marker ) {
					const olc_marker_icon = {
						url: ofcMapObj[olcSectionID].store_marker,
						scaledSize: new google.maps.Size( parseInt( $store_location_marker_width ) , parseInt( $store_location_marker_height ) )
					};
					markerOptions.icon = olc_marker_icon;
				}
			}

			if( ofcMapObj[olcSectionID].enable_store_location_marker_control && ofcMapObj[olcSectionID].enable_store_location_marker_control != '' && ofcMapObj[olcSectionID].enable_store_location_marker_control == 'yes' ){
				ofcMapObj[olcSectionID].ofcMarker = new google.maps.Marker(markerOptions);
				ofcMapObj[olcSectionID].load_olc_markers.push( ofcMapObj[olcSectionID].ofcMarker );
			}

			if( ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control != '' && ofcMapObj[olcSectionID].enable_store_location_marker_pop_up_control == 'yes' ){
				google.maps.event.addListener( ofcMapObj[olcSectionID].ofcMarker, 'click', (function(ofcMarker, office_key, location_html) {
					return function() {
						store_data.location_html = store_data.location_html.replace( 'office-locater-box', 'olc-store' );
						ofcMapObj[olcSectionID].ofcInfowindow.setContent(store_data.location_html);
						ofcMapObj[olcSectionID].ofcInfowindow.open(ofcMapObj[olcSectionID].olc_map, ofcMarker);
						setTimeout(function(){
							if( jQuery('.gm-style-iw-c').length > 0 ){
								jQuery('.gm-style-iw-c').addClass('olc-info-box-item');
							}				
						},100);
					}
				})( ofcMapObj[olcSectionID].ofcMarker, office_key));
				
			}
			store_data.position = new google.maps.LatLng(store_data.office_latitude, store_data.office_longitude);
			ofcMapObj[olcSectionID].load_olc_stores.push(store_data);

		});

		var slider_class = '';
		if( olc_section.hasClass('layout-3') || olc_section.hasClass('layout-4') || olc_section.hasClass('layout-7') || olc_section.hasClass('layout-8') ){
			slider_class = 'swiper-wrapper';
			olc_section.find(".office-panel").attr('style',"height:auto !important");
			apply_slider_flag = 1;
		}else{
			olc_section.find(".office-panel").attr('style',"");
		}
		olc_section.find(".office-panel").removeClass('no-office-found');		
		olc_section.find(".office-panel").prepend('<div class="'+slider_class+' olc-address-list">' + address_html + '</div>');		

	} else {
		olc_section.find(".office-panel").addClass('no-office-found');
		olc_section.find(".office-panel").attr('style',"height:auto !important");
		olc_section.find(".office-panel").html('<div class="olc-address-list no-address-found"><p>'+ofcMapObj[olcSectionID].no_result_msg+'</p></div>');		
	}

	if( ajax == 1 ) {
		ofcMapObj[olcSectionID].ofc_lat = olc_section.find('.olc_latitude').val();
		ofcMapObj[olcSectionID].ofc_long = olc_section.find('.olc_longitude').val();			
		ofcMapObj[olcSectionID].officeLatLongCenter = new google.maps.LatLng( ofcMapObj[olcSectionID].ofc_lat, ofcMapObj[olcSectionID].ofc_long );
		ofcMapObj[olcSectionID].olcDefaultLatLong = ofcMapObj[olcSectionID].officeLatLongCenter;
		ofcMapObj[olcSectionID].olc_map.setCenter( ofcMapObj[olcSectionID].olcDefaultLatLong );
		ofcMapObj[olcSectionID].olcDefaultZoom = parseInt(ofcMapObj[olcSectionID].olc_inner_map);
		ofcMapObj[olcSectionID].olc_map.setZoom( ofcMapObj[olcSectionID].olcDefaultZoom );	
	}

	if( apply_slider_flag ){
		setTimeout(function(){			
			var swiper = new Swiper(olc_section.find('.office-locator-swiper')[0], {
				slidesPerView: 1,
				spaceBetween: 15,
				navigation: {
					nextEl: olc_section.find(".office-locater-slider .swiper-button-next")[0],
					prevEl: olc_section.find(".office-locater-slider .swiper-button-prev")[0],
				},
				breakpoints: {
					640: {
						slidesPerView: 2,
						spaceBetween: 15,
					},
					768: {
						slidesPerView: 2,
						spaceBetween: 15,
					},
					1024: {
						slidesPerView: 3,
						spaceBetween: 15,
					},
				},
			}); 
		},200);
	}

	if( ofcMapObj[olcSectionID].start_location_marker !== undefined ) {
		if( ofcMapObj[olcSectionID].startMarker !== undefined ){
			ofcMapObj[olcSectionID].startMarker.setMap(null);	
		}		
		var startmarkerOptions = {
			position: new google.maps.LatLng( olc_section.find('.olc_latitude').val(), olc_section.find('.olc_longitude').val() ),
			map: ofcMapObj[olcSectionID].olc_map,
			title : 'Your Start Office Location'
		}

		var $start_location_marker_width = 25;
		var $start_location_marker_height = 35;
		if( ofcMapObj[olcSectionID].start_location_marker_width && ofcMapObj[olcSectionID].start_location_marker_width != '' ){
			$start_location_marker_width = ofcMapObj[olcSectionID].start_location_marker_width;
			if( $start_location_marker_width.toString().indexOf('px') !== -1){
				$start_location_marker_width = $start_location_marker_width.replace("px", "");
			}
		}

		if( ofcMapObj[olcSectionID].start_location_marker_height && ofcMapObj[olcSectionID].start_location_marker_height != '' ){
			$start_location_marker_height = ofcMapObj[olcSectionID].start_location_marker_height;
			if( $start_location_marker_height.toString().indexOf('px') !== -1){
				$start_location_marker_height = $start_location_marker_height.replace("px", "");
			}
		}

		if( ofcMapObj[olcSectionID].start_location_marker ) {
			const olc_start_marker_icon = {
				url: ofcMapObj[olcSectionID].start_location_marker,
				scaledSize: new google.maps.Size( parseInt( $start_location_marker_width  ), parseInt( $start_location_marker_height ) )
			};
			startmarkerOptions.icon = olc_start_marker_icon;
		}		

		if( ofcMapObj[olcSectionID].enable_start_location_marker_control && ofcMapObj[olcSectionID].enable_start_location_marker_control != '' && ofcMapObj[olcSectionID].enable_start_location_marker_control == 'yes' ){
			ofcMapObj[olcSectionID].startMarker = new google.maps.Marker(startmarkerOptions);
		}

		if( ofcMapObj[olcSectionID].enable_start_location_marker_pop_up_control && ofcMapObj[olcSectionID].enable_start_location_marker_pop_up_control != '' && ofcMapObj[olcSectionID].enable_start_location_marker_pop_up_control == 'yes' ){

			google.maps.event.addListener( ofcMapObj[olcSectionID].startMarker, 'click', (function(startMarker) {
				return function() {
					ofcMapObj[olcSectionID].ofcInfowindow.setContent( ofcMapObj[olcSectionID].start_location_marker_msg );
					ofcMapObj[olcSectionID].ofcInfowindow.open(ofcMapObj[olcSectionID].olc_map, startMarker);
				}
			})( ofcMapObj[olcSectionID].startMarker));
		}
	}

}