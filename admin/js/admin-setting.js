jQuery(document).ready(function($){

	/* form data variable */
	var form_original_data = jQuery("form#plugin-data").serialize();
	/* form data variable*/

	/* color picker */
	if( jQuery(".color_field").length > 0 ){
		jQuery(".color_field").wpColorPicker();	
	}	
	/* color picker */

	/* select 2 */

	if( jQuery('.multi_select_field').length > 0 ){
		jQuery('.multi_select_field').each( function (){			
			jQuery(this).select2( { placeholder : jQuery(this).attr('data-placeholder') } );
		} );
	}

	/* select 2 */

	/* Pixel Validations */
	jQuery(document).on( 'change keypress keydown keyup', '.pixel_valid input, .pixel_valid select', function(){			
		pixel_change_valid( jQuery(this).closest('.pixel_valid') ); 		
	} );
	/* Pixel Validations */

	/* ------------------- start plugin data saving ------------------- */
	jQuery(document).on('submit', 'form#plugin-data', function( event ) {

		event.preventDefault();
		var $this = jQuery(this);
		var target_btn = jQuery(this).find('button[name="submit"]');
		var FormData = jQuery(this).serialize();
		var action   = jQuery(this).find('input[name="action"]').val();
		/* | ajax | */
		jQuery.ajax({
			type : "POST",
			url : wt_ajax.ajaxurl,
			dataType : "json",
			data : { 'action': action , 'formdata': FormData, ajax_nonce: wt_ajax.nonce },
			beforeSend : function(){
				jQuery(target_btn).find('.loader').removeClass('hidden');
				jQuery(target_btn).find('.text').addClass('hidden');
				jQuery($this).find('.alert').removeClass('success');
			},
			success: function(data) {
				jQuery(target_btn).find('.loader').addClass('hidden');
				jQuery(target_btn).find('.text').removeClass('hidden');
				jQuery($this).find('.alert').addClass('success').html('<i class="fa-solid fa-circle-check"></i> Data saved successfully!');
				jQuery($this).find('.alert').fadeIn();
				setTimeout(function() {
					jQuery($this).find('.alert').fadeOut();
				}, 3000)
			}
		});
		/* | ajax | */

	});
	/* ------------------- end plugin data saving ------------------- */


	/* ------------------- start plugin data reset ------------------- */
	jQuery(document).on('click', 'form#plugin-data button[name="reset"]', function( event ) {

		event.preventDefault();
		var parent = jQuery(this).parents('.wt-submit');
		var action   = jQuery(this).parents('form#plugin-data').find('input[name="reset_action"]').val();
		var target_btn = jQuery(this);

		/* | ajax | */
		jQuery.ajax({
			type : "POST",
			url : wt_ajax.ajaxurl,
			dataType : "json",
			data : { 'action': action, ajax_nonce: wt_ajax.nonce },
			beforeSend : function(){
				jQuery(target_btn).find('.loader').removeClass('hidden');
				jQuery(target_btn).find('.text').addClass('hidden');
				jQuery(parent).find('.alert').removeClass('success');
			},
			success: function(data) {
				jQuery(target_btn).find('.loader').addClass('hidden');
				jQuery(target_btn).find('.text').removeClass('hidden');
				jQuery(parent).find('.alert').addClass('success').html('<i class="fa-solid fa-circle-check"></i> Data reset successfully!');
				jQuery(parent).find('.alert').fadeIn();
				setTimeout(function() {
					jQuery(parent).find('.alert').fadeOut();
					location.reload();
				}, 3000)
			}
		});
		/* | ajax | */

	});
	/* ------------------- end plugin data reset ------------------- */


	/* tooltip */
	jQuery( document ).tooltip();
	/* tooltip */

	/* copy */
	jQuery(document).on('click', '.copy', function() {
		var target = jQuery(this).attr('data-target');
		copyToClipboard( target );
		var $this = jQuery(this);
		jQuery(this).tooltip({ items: ".copy", content: "Copied!",  position: {my: "left+15 center", at: "right center"}});
		jQuery(this).tooltip("open");
		setTimeout(function() {
			jQuery($this).tooltip("disable");
		}, 1000);
	});
	/* copy */

	/* hide show password */
	jQuery(document).on('click', '.show-password', function() {
		var target = jQuery(this).attr('data-target');
		jQuery(this).find('.fa-eye-slash').toggleClass('hidden');
		jQuery(this).find('.fa-eye').toggleClass('hidden');
		if( jQuery(this).find('.fa-eye').hasClass('hidden') ){
			jQuery(target).attr('type', 'text');
		}else{
			jQuery(target).attr('type', 'password');
		}
	});
	/* hide show password */

	/* sortable js */
	jQuery(document).on('click', '.sortable .ui-sortable-handle .edit-label', function(){
		jQuery(this).parent().find('.ui-sortable-name').toggleClass('hidden').focus();
		jQuery(this).parent().find('.label').toggleClass('hidden');
	});
	
	jQuery(document).on('change', '.sortable .ui-sortable-handle .ui-sortable-name', function(){
		var label = jQuery(this).val();
		jQuery(this).parent().find('.label').text(label);
		var obj = {};
		var slug_obj = [];
		var main_obj = {};
		var uiRef = ( jQuery(this).parents('.ui-sortable').hasClass('ui-sortable-connected') ) ? '.ui-sortable.ui-sortable-active li' : '.ui-sortable li';

		jQuery(this).parents('.sortable_wrapper').find(uiRef).each(function( index ) {
			var slug = jQuery(this).attr('id');
			slug_obj.push(slug);
		});

		jQuery(this).parents('.sortable_wrapper').find('.ui-sortable li').each(function( index ) {

			var slug = jQuery(this).attr('id');
			var label = jQuery(this).find('.ui-sortable-name').val();

			obj[slug] = label;
		});
		main_obj['slug'] = slug_obj;
		main_obj['values'] = obj;		
		main_obj = JSON.stringify(main_obj);		

		jQuery(this).parents('.sortable_wrapper').find('.sortable-value').val(main_obj);
	});

	jQuery(document).on('sortupdate', '.sortable_wrapper  .ui-sortable', function(){		
		var obj = {};
		var slug_obj = [];
		var main_obj = {};
		var uiRef = ( jQuery(this).hasClass('ui-sortable-connected') ) ? '.ui-sortable.ui-sortable-active li' : '.ui-sortable li';
		jQuery(this).parents('.sortable_wrapper').find(uiRef).each(function( index ) {
			var slug = jQuery(this).attr('id');
			slug_obj.push(slug);
		});
		jQuery(this).parents('.sortable_wrapper').find('.ui-sortable li').each(function( index ) {
			var slug = jQuery(this).attr('id');
			var label = jQuery(this).find('.ui-sortable-name').val();
			obj[slug] = label;
		});
		main_obj['slug'] = slug_obj;
		main_obj['values'] = obj;		
		main_obj = JSON.stringify(main_obj);		

		jQuery(this).parents('.sortable_wrapper').find('.sortable-value').val(main_obj);
	});

	jQuery(document).mouseup(function(e){
		var container = jQuery(".ui-sortable-handle .label.hidden").parents('.ui-sortable-handle');

		if (!container.is(e.target) && container.has(e.target).length === 0){
			container.find('.label.hidden').removeClass('hidden');
			container.find('.ui-sortable-name').addClass('hidden');
		}
	});
	/* sortable js */


	/* Custom JS */
	if( jQuery(".select_field.select2").length > 0 ){
		jQuery(".select_field.select2").select2();	
	}	

});

function copyToClipboard( element ) {
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	$temp.val(jQuery(element).val()).select();
	document.execCommand("copy");
	$temp.remove();
}

function pixel_change_valid( parent_this ){
	var input_value =  parent_this.find('input').val();
	var select_value = parent_this.find('select').val();
	if( select_value == '%' ){
		if( input_value > 100 ){
			parent_this.find('input').val(100);
		}
	}
	if( input_value < 0 ){
		parent_this.find('input').val(0);
	} 
}