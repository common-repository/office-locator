jQuery(document).ready(function(){


	/* On Load Shortcode auto generate */
	setTimeout(function(){
		if( jQuery('#office_locator_shortcode').length > 0 ){
			generate_map_shortcode();

			if ( wp.media ) {
				wp.media.view.Modal.prototype.on( "close", function() {
					setTimeout(function(){
						generate_map_shortcode();
					},500);
				});
			}

		}
	},1000);

	/* Any input,select, textarea, switch change generate shortcode */
	jQuery(document).on("change", ".wt-section .input-field input,.wt-section .switch-field input, .wt-section .input-field select,.wt-section .textarea_field", function() {				
		generate_map_shortcode();
	});

	/* When custom marker image remove generate shortcode */
	jQuery(".wt-section .remove-image").click( function() {				
		setTimeout(function(){
			generate_map_shortcode();			
		},1000);
	});

	/*end scroll shortcode section js */

	if( jQuery('#office_locator_shortcode').length > 0 ){
		jQuery('#office_locator_shortcode').closest('.wt-section').addClass('wt-locator-scroll');
	}

	jQuery(document).scroll('window',function(){
		office_locator_scrollHeader();
	});

	jQuery(document).on("click",".office-locator-table .offcie_locator_upload_btn",function(e){

		e.preventDefault();
		var html = '';
		var fd = new FormData();
		var file = jQuery(this).parents('.form-table').find('#offcie_locator_file_upload');
		var individual_file = file[0].files[0];
		var import_message = jQuery('.office_locator_import_message');
		var import_loader = jQuery('.office-locator-dual-ring');
		var import_uploader = jQuery('#offcie_locator_file_upload');

		jQuery(this).find('.office-locator-dual-ring').addClass('active-import');
		if(  individual_file == '' || individual_file === undefined ){

			import_message.html('<span class="error">Please select file</span>');
			import_loader.removeClass('active-import');
			setTimeout(function(){
				import_message.html('');
			},10000);
		}else{
			fd.append("upload_csv_file", individual_file);  
			fd.append('action', 'import_post_address');
			jQuery.ajax({
				type: 'POST',
				url : wt_ajax.ajaxurl,
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){ 
					if( response && response.error ){
						import_message.html('<span class="error">Your File Formet Not Valid Please Upload Csv File Only.</span>');
						import_loader.removeClass('active-import');
						import_uploader.val('');
						setTimeout(function(){
							import_message.html('');
						},10000);
					}else{

						import_loader.removeClass('active-import');
						import_uploader.val('');

						if( response.inset_count && response.inset_count > 0  ){
							html += '<span class="success">Your Csv Successfully Insert '+ response.inset_count +' Office Address</span>';
						}

						if( response.update_count && response.update_count > 0 ){
							html += '<span class="success">Your Csv Successfully Update '+ response.update_count +' Office Address</span>';
						}

						if( html && html != '' ){
							import_message.html(html);
						}
						
						setTimeout(function(){
							import_message.html('');
						},10000);
					}
				}
			});
		}
	});

	/*end scroll shortcode section js */

});

function office_locator_scrollHeader() {
	if( jQuery('#office_locator_shortcode').length > 0 ){
		if ( jQuery("html, body").scrollTop() > 100 ||  jQuery("html, body").scrollTop() > 100 ) {
			jQuery(".wt-section.wt-locator-scroll").addClass("activated");
		} else {
			jQuery(".wt-section.wt-locator-scroll").removeClass("activated");
		}
	}	
}

/* Initialize google map auto complete address field functions */
var olcAddressInputs, olcAutocompleteInputs, olc_add_comp;
function  ofcMpInitialize() {
	olcAutocompleteInputs = document.getElementsByClassName("olc_autocomplete_field");	
	for ( var i = 0; i < olcAutocompleteInputs.length; i++ ) {

		var olc_autocomplete = new google.maps.places.Autocomplete( olcAutocompleteInputs[i] );
		olc_autocomplete.inputId = olcAutocompleteInputs[i].id;
		olc_autocomplete.inputField = olcAutocompleteInputs[i];

		google.maps.event.addListener( olc_autocomplete, 'place_changed', function () {			
			var olcPlace = this.getPlace();
			this.inputField.nextSibling.value = olcPlace.geometry['location'].lat()+','+olcPlace.geometry['location'].lng();
			generate_map_shortcode();
		});
	}

	olcAddressInputs = document.getElementById("office_address_fill");	
	if( olcAddressInputs !== undefined ){
		olc_add_comp = new google.maps.places.Autocomplete( olcAddressInputs );		
		google.maps.event.addListener( olc_add_comp, 'place_changed', fillolcAddress );
	}

}

/* Autofill address field functions */

function fillolcAddress() {  
	const olcAddPlace = olc_add_comp.getPlace();
	let address1 = "";
	let postcode = "";
	for (const component of olcAddPlace.address_components) {    
		const componentType = component.types[0];
		switch (componentType) {

		case "street_number": {
			address1 = `${component.long_name} ${address1}`;
			break;
		}

	case "route": {
		address1 += component.short_name;
		break;
	}

case "postal_code": {
	postcode = `${component.long_name}${postcode}`;
	break;
}

case "postal_code_suffix": {
	postcode = `${postcode}-${component.long_name}`;
	break;
}
case "locality":
	document.querySelector("#office_city").value = component.long_name;
	break;
case "administrative_area_level_1": {
	document.querySelector("#office_state").value = component.long_name;
	break;
}
case "country":
	document.querySelector("#office_country").value = component.long_name;
	break;
}
}
document.getElementById("office_latitude").value = olcAddPlace.geometry['location'].lat();
document.getElementById("office_longitude").value = olcAddPlace.geometry['location'].lng();
document.querySelector("#office_postal_code").value = postcode;

}

/* Map Shortcode Generator Function */
function generate_map_shortcode() {
	
	var map_shortcode = {};
	if( jQuery(".wt-section .input-field input:not(#office_locator_shortcode), .wt-section .input-field select, .wt-section .textarea_field ").length > 0 ){
		jQuery(".wt-section .input-field input:not(#office_locator_shortcode), .wt-section .input-field select, .wt-section .switch-field input, .wt-section .textarea_field ").each(function(){
			var shortcode_attr = jQuery(this).attr("shortcode_attr");
			var input_name = jQuery(this).attr("name");
			if( shortcode_attr !== undefined ){
				input_name = shortcode_attr;
			}
			var input_type = jQuery(this).attr("type");
			var tag_name = jQuery(this).prop("tagName");
			var input_val = '';
			if( "SELECT" == tag_name || "TEXTAREA" == tag_name ){
				input_val = jQuery(this).val();
			}else if("INPUT" == tag_name) {
				if("radio" == input_type || "checkbox" == input_type ) {
					if(!0 == jQuery(this).prop("checked")){
						input_val = jQuery(this).val();
					}
				}else{
					input_val = jQuery(this).val();
				}
			}
			if( input_val ){
				if( input_name in map_shortcode ){
					map_shortcode[input_name].push( input_val );
				}else{
					map_shortcode[input_name] = [ input_val ];
				}				
			}
		});
	}

	var generate_code = "";
	if( map_shortcode ){
		jQuery.each( map_shortcode, function( i_name , value ) {
			generate_code += i_name + "=";
			var str = "";
			jQuery.each( value, function( t, e ) {
				str += e;
			});
			generate_code += "'" + str + "'"; 
			generate_code += " ";
		})
	}
	generate_code = "[office_locator " + generate_code + " ]";
	jQuery('#office_locator_shortcode').val( generate_code );
}