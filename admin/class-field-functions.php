<?php
/**
 * The field functions for setting page.
 *
 * @package    Office_Locator
 * @subpackage Office_Locator/admin
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Fields {

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

        add_action( 'admin_print_scripts', array( $this, 'admin_inline_js' ) );

      }


      /**
      * That function return input value.
      *
      * @since    1.0.0
      * @access   public
      * @param    string  $name    The Name key of option.
      * @param    string  $default The Default value of option.
      */

      public function get_value( $name, $default='' ){

        $tab = 'general';
        $value = $default;

        if( isset( $_GET['tab'] ) && !empty( $_GET['tab'] ) ){          
          $tab = sanitize_text_field( $_GET['tab'] );
        }

        $option_name = str_replace( '-', '_', $this->plugin_name ) .'_'.$tab;
        $option = get_option( $option_name );

        if( $option ){          
          if( isset( $option[$name] ) && !empty( $option[$name] ) ){
            $value = $option[$name];
          }
          if( !is_array( $value ) ){
            $value = str_replace( "\'", "'", $value );            
          }
          
        }

        if( $value ){
          return $value;  
        }else{
          return ''; 
        }

      }

      /**
      * That function sets attributes.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $attributes   All attributes ablue the field.
      */
      public function set_attributes( $attributes ){

        $attr_htm = '';

        if( $attributes ){
          foreach ( $attributes as $attr_name => $attr_value ) {
            if( $attr_value  ){
              $attr_htm .= esc_html( $attr_name ).'="'.esc_attr( $attr_value ).'" '; 
            }                     
          }
        }

        return $attr_htm;
      }

      /**
      * That function return text input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function text_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start text input */
        echo '<input type="text" class="text_field" ';

        /* input name */
        echo $this->get_field_value( $field ,'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field ,'id', 'name' );

        /* custom attribute */
        echo $this->get_field_value( $field ,'attributes', 'attributes' );

        /* input placeholder */
        echo $this->get_field_value( $field ,'placeholder', 'placeholder' );

        /* input default value */
        echo $this->get_field_value( $field , 'value' , $value );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* input readonly attribute */
        echo $this->get_field_attr( $field, 'readonly' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* end text input */
        echo '>';

        echo ( isset( $field['icon'] ) && ( $field['icon'] ) ? '<a href="javascript:;" class="copy field-trigger" data-target="'.esc_attr( '#'.$name ).'"> <i class="'.esc_attr( $field['icon'] ).'"></i></a>' : '' );

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return textarea field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function textarea_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        /* start textarea */
        echo '<textarea class="'.esc_attr( 'textarea_field '.$class ).'"';

        /* textarea name */
        echo $this->get_field_value( $field ,'name', 'name' );

        /* textarea id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* custom attribute */
        echo $this->get_field_value( $field , 'attributes', 'attributes' );

        /* textarea disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* textarea readonly attribute */
        echo $this->get_field_attr( $field, 'readonly' );

        /* textarea required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* textarea rows attribute */
        echo $this->get_field_value( $field , 'rows', 'rows' );

        /* textarea cols attribute */
        echo $this->get_field_value( $field , 'cols', 'cols' );

        /* complete textarea */
        echo '>';

        /* textarea content */
        echo esc_textarea( $value );

        /* end textarea */
        echo '</textarea>';

        /* textarea description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return hidden input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function hidden_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        /* start input hidden */
        echo '<input type="hidden" ';

        /* input name */
        echo $this->get_field_value( $field ,'name', 'name' );

        /* class name */
        echo $this->get_field_value( $field , 'class', 'class' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'value', $value );

        /* end input hidden */
        echo '>';
      }

      /**
      * That function return url input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function url_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        /* start input url */
        echo '<input type="url" class="'.esc_attr( 'url_field '.$class ).'"';

        /* input name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder' );

        /* input default value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* input readonly attribute */
        echo $this->get_field_attr( $field, 'readonly' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* input pattern attribute */
        echo $this->get_field_value( $field , 'pattern', 'pattern' );

        /* input size attribute */
        echo $this->get_field_value( $field , 'size', 'size' );

        /* end input url */
        echo '>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return password input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function password_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input password */
        echo '<input type="password" class="password_field"';

        /* input name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input minlength attribute */
        echo $this->get_field_value( $field , 'minlength', 'minlength' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder' );

        /* end input password */
        echo '>';

        echo '<a href="javascript:;" class="show-password field-trigger" data-target="'.esc_attr( '#'.$name ).'"><i class="fa-solid fa-eye"></i><i class="fa-solid fa-eye-slash hidden"></i></a>';

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return number input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function number_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input number */
        echo '<input type="number" class="number_field"';

        /* input name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input min attribute */
        echo $this->get_field_value( $field , 'min', 'min' );

        /* input max attribute */
        echo $this->get_field_value( $field , 'max', 'max' );

        /* custom attribute */
        echo $this->get_field_value( $field , 'attributes', 'attributes' );

        /* input default value */
        echo $this->get_field_value( $field , 'value', $value  );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* input readonly attribute */
        echo $this->get_field_attr( $field, 'readonly' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* input step attribute */
        echo $this->get_field_value( $field , 'step', 'step' );

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder' );

        /* end input number */
        echo '>';

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );
      }

      /**
      * That function return tel input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function tel_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input tel */
        echo '<input type="tel" class="tel_field"';

        /* input name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* input default value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled');

        /* input readonly attribute */
        echo $this->get_field_attr( $field, 'readonly');

        /* input pattern attribute */
        echo $this->get_field_value( $field , 'pattern', 'pattern' );

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder' );

        /* end input tel */
        echo '>';

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );
      }

      /**
      * That function return file media input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function file_field( $field ){
        $name = $field['name'];
        $file_name_id = isset($field['custom_id']) ? $field['custom_id'] : $name;
        $main_wrapper = $file_name_id.'-wrapper';
        $remove_btn = $file_name_id.'-remove';
        $remove_multiple_btn = $file_name_id.'-multiple-image';
        $upload_btn =  $file_name_id.'-upload-image';
        $multiple = ( isset( $field['multiple'] ) && ( !empty( $field['multiple'] ) ) ? $field['multiple'] : '' );
        $image_size = 'full';
        $remove_image = ( $multiple ) ? '<button type="button" class="'.esc_attr( $remove_multiple_btn ).'"><i class="fa fa-times" aria-hidden="true"></i></button>' : '' ;
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );
        $selected_image = '';
        $style = 'style="display:none;"';
        $value = $this->get_value( $name, $default );

        if( $value ){
          $attachment_ids = explode( ',', $value );
          $style = 'style=""';
          if( $attachment_ids ){
            foreach ( $attachment_ids as $attachment_id ) {
              $attachment_type = wp_check_filetype( wp_get_attachment_url( $attachment_id ) );              
              if( isset($attachment_type['type']) && strpos($attachment_type['type'], 'image') !== false ){
                $img = '<img src="'.esc_url( wp_get_attachment_url( $attachment_id ) ).'" width="200">';
              }else{
                $img = '<img style="object-fit: none;" src="'.esc_url( includes_url().'/images/media/document.png' ).'" width="200">';                
              }
              $selected_image .= '<li data-id="'.esc_attr( $attachment_id ).'">'.wp_kses_post( $img.$remove_image ).'</li>';
            }
          }          
        }

        echo '<div class="'.esc_attr( $main_wrapper.' input-media input-field '.$class ).'">';
        echo '<input type="hidden" value="'.esc_attr( $value ).'" class="'.esc_attr('attachment-id ' . $name ).'" name="' .esc_attr( $name ). '" id="'.esc_attr( $file_name_id ). '"/>';
        echo '<a href="javascript:;" class="'.esc_attr( $upload_btn.' field-trigger ').'">Add Media</a>';
        echo '<a href="javascript:;" class="'.esc_attr( $remove_btn.' remove-image' ).'" '.wp_kses_post( $style ).'><i class="fa-solid fa-trash-can"></i></a>';
        echo '</div>';
        echo '<div class="selected-image"><ul>'.wp_kses_post( $selected_image ).'</ul></div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );
        $media_script = '';
        ob_start();
        ?>        
        jQuery(document).ready(function($) {

          jQuery( 'body' ).on( 'click', '.<?php echo esc_attr( $upload_btn ); ?>', function( e ) {
            e.preventDefault();
            var button = jQuery(this),
            custom_uploader = wp.media({
              title: 'Insert image',
              button: { text: 'Use this image' },
              multiple: <?php echo ( $multiple ) ? 'true' : 'false' ; ?> 
            }).on('select', function() {
              var attachments = custom_uploader.state().get('selection'),
              i = 0;
              var url_arr = [];
              var id_arr = [];
              jQuery(button).parents('td').find('.selected-image ul').html('');
              attachments.each(function(attachment) {
                url_arr.push(attachment.attributes.url);
                id_arr.push(attachment.attributes.id);
                var data_id = '';
                <?php if( $multiple ){ ?>
                  data_id = 'data-id="'+attachment.attributes.id+'"';
                <?php } ?>

                var html = '';
                if( attachment.attributes.type == 'image'  ){
                  html = '<li '+data_id+'><img src="'+attachment.attributes.url+'" width="200"><?php echo wp_kses_post( $remove_image ); ?></li>';
                }else{
                  html = '<li '+data_id+'><img style="object-fit: none;" src="'+attachment.attributes.icon+'" width="100"><?php echo wp_kses_post( $remove_image ); ?></li>';
                }
                <?php if( $multiple ){ ?>
                  jQuery(button).parents('td').find('.selected-image ul').append(html);
                <?php }else{ ?>
                  jQuery(button).parents('td').find('.selected-image ul').html(html);
                <?php } ?>
                i++;
              });

              var id = id_arr.join(",");
              var url = url_arr.join(",");

              jQuery(button).siblings('.attachment-id').attr('value', id);

              jQuery(button).siblings('.<?php echo esc_attr( $remove_btn ); ?>').show();
            });

            custom_uploader.on('open',function() {
              var selection = custom_uploader.state().get('selection');
              var ids_value = jQuery(button).siblings('.attachment-id').val();
              if(ids_value.length > 0){
                var ids = ids_value.split(',');
                ids.forEach(function(id){
                  attachment = wp.media.attachment(id);
                  attachment.fetch();
                  selection.add(attachment ? [attachment] : []);
                });
              }
            });
            custom_uploader.open();
          });

          jQuery('body').on('click', '.<?php echo esc_attr( $remove_btn ); ?>', function() {
            jQuery(this).hide().prev().val('').prev().html('Add Media');
            jQuery(this).parents('td').find('.attachment-id').attr('value', '');
            jQuery(this).parents('td').find('.selected-image ul').html('');
            return false;
          });
          jQuery('body').on('click', '.<?php echo esc_attr( $remove_multiple_btn ); ?>', function() {
            var data_id = jQuery(this).parents('li').attr('data-id');
            var val = jQuery(this).parents('td').find('.attachment-id').val();

            var val_arr = val.split(',');
            var arr = [];
            jQuery.each(val_arr, function( index, value ) {
              if( value != data_id ){
                arr.push(value);
              }
            });

            val = arr.join(",");
            if( val.trim().length == 0 ){
              jQuery(this).parents('td').find('.remove-image').hide();
            }
            jQuery(this).parents('td').find('.attachment-id').attr('value', val);
            jQuery(this).parents('li').remove();
          });
        });
        <?php 
        $media_script = ob_get_clean();
        $this->admin_inline_js( $media_script );
      }


      /**
      * That function return email input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function email_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input email */
        echo '<input type="email" class="email_field"';

        /* input name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* input id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* input default value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* input readonly attribute */
        echo $this->get_field_attr( $field, 'readonly' );

        /* input pattern attribute */
        echo $this->get_field_value( $field , 'pattern', 'pattern' );

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder' );

        /* input size attribute */
        echo $this->get_field_value( $field , 'size', 'size' );

        /* end input file */
        echo '>';

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return submit input.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function submit_field( $field ){

        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        echo '<div class="'.esc_attr( 'submit'.$class ).'">';

        /* start input file */
        echo '<input type="submit" class="submit_field" ';

        /* input name */
        echo ( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'name="'.esc_attr( $field['name'] ).'"' : 'name="submitfield_name"' );

        /* input id */
        echo $this->get_field_value( $field ,'id', 'name' );

        /* input disabled attribute */
        echo $this->get_field_attr( $field, 'disabled' );

        /* input default value */
        echo $this->get_field_value( $field ,'value', $field['value'] );

        /* end input file */
        echo '>';

        echo '</div>';

        /* input description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return checkbox field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function checkbox_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        if( !isset( $value ) || empty( $value ) || $value == ''  ){
          $value = [];        
        }
        

        echo '<div class="'.esc_attr( 'field-checkbox'.$class ).'">';

        if( $field['options'] ){
          foreach( $field['options'] as $key => $options ){

            $checkbox_id = isset($field['custom_id']) ? $field['custom_id'] : $key;

            echo '<div class="field_wrapper">';
            /* start input checkbox */
            echo '<input type="checkbox" class="checkbox_field"';

            /* checkbox name */
            echo 'name="'.esc_attr( $name ).'[]"';

            /* checkbox id */
            echo ( ( isset( $key ) && !empty( $key ) ) ? 'id="'.esc_attr( $checkbox_id ).'"' : '' );

            /* checkbox value */
            echo 'value="'.esc_attr( $key ).'"';

            /* set the checked value */
            if( is_array( $value ) ){              
              checked( in_array( $key, $value ) , 1 );
            }
            /* end input checkbox */

            echo '>';

            /* checkbox label */
            echo '<label for="'.esc_attr( $key ).'">'. wp_kses_post( $options ) .'</label>';

            echo '</div>';

          }
        }

        echo '</div>';

        /* checkbox description */
        echo $this->get_field_attr( $field , 'field_desc' );
      }

      /**
      * That function return radio field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function radio_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        $radio_id = isset($field['custom_id']) ? $field['custom_id'] : $name;

        echo '<div class="'.esc_attr( 'field-radio' .$class ).'">';

        if( $field['options'] ){

          $i = 0;

          foreach( $field['options'] as $options ){

            echo '<div class="field_wrapper">';

            /* start input radio */
            echo '<input type="radio" class="radio_field"';

            /* input name */
            echo $this->get_field_value( $field ,'name', 'name' );

            /* set the checked value */
            checked( ( $value == $options ) , 1 );
            /* end input radio */

            /* input id */
            echo 'id="'.esc_attr( $radio_id.'-'.$i ).'"';

            /* input value */
            echo ( ( isset( $options ) && !empty( $options ) ) ? 'value="'.esc_attr( $options ).'"' : '' );

            /* end input radio */
            echo '>';

            /* radio label */
            echo '<label for="'.esc_attr( $name.'-'.$i ).'">'. wp_kses_post( $options ) .'</label>';

            echo '</div>';

            $i++;

          }
        }

        echo '</div>';

        /* radio description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }


      /**
      * That function return select field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function select_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $select_id = isset($field['custom_id']) ? $field['custom_id'] : $field['name'];

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start select */    
        echo '<select class="'.esc_attr( 'select_field '.$class ).'" '.( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'name="'.esc_attr( $field['name'] ).'"' : '').( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'id="'.esc_attr( $select_id ).'"' : '').( isset( $field['attributes'] ) && ( !empty( $field['attributes'] ) ) ? $this->set_attributes( $field['attributes'] ) : '' ).'>';

        if( $field['options'] ){
          foreach( $field['options'] as $key => $options ){            
            echo sprintf(
              '<option value="%1$s" %3$s>%2$s</option>',
              esc_attr( $key ),
              esc_html( $options ),
              selected( $value, esc_attr( $key ), false )
            );
          }
        }

        /* end select */
        echo '</select>';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return select field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function multi_select_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $placeholder = ( isset( $field['placeholder'] ) && ( !empty( $field['placeholder'] ) ) ? $field['placeholder'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $select_id = isset($field['custom_id']) ? $field['custom_id'] : $field['name'];

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start select */
        echo '<select multiple class="multi_select_field" data-placeholder="'.esc_attr( $placeholder ).'" '.( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'name="'.esc_attr( $field['name'] ).'[]"' : '').' '.( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'id="'.esc_attr( $select_id ).'"' : '').( isset( $field['attributes'] ) && ( !empty( $field['attributes'] ) ) ? $this->set_attributes( $field['attributes'] ) : '' ).'>';
        if( empty($value) ){
          $value = array();
        }        

        if( $field['options'] ){
          foreach( $field['options'] as $key => $options ){            
            echo sprintf(
              '<option value="%1$s" %3$s>%2$s</option>',
              esc_attr( $key ),
              esc_html( $options ),
              selected( (is_array( $value ) && !empty($value) && in_array( $key, $value )) ? $key : '', $key, false )
            );
          }
        }

        /* end select */
        echo '</select>';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }


      /**
      * That function return sortable field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field. 
      */
      public function sortable_field( $field ){

        $values_fields_arr = $val = $all_arr = $default_val_arr = $default_values_fields_arr = $default_arr = [];      
        $flag = 0;
        $labels = '';
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $all_fields_title = ( isset( $field['fields_title'] ) && !empty( $field['fields_title'] ) ) ? $field['fields_title'] : '';
        $default_fields_title = ( isset( $field['default_title'] ) && !empty( $field['default_title'] ) ) ? $field['default_title'] : '';
        $all_fields_id = ( isset( $field['id'] ) && !empty( $field['id'] ) ) ? $field['id'].'_all_fields' : '';
        $name = ( isset( $field['id'] ) && !empty( $field['id'] ) ) ? $field['id'] : '';
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );
        $default_fields_id = ( isset( $field['id'] ) && !empty( $field['id'] ) ) ? $field['id'].'_default_fields' : '';
        $sortable_type = ( isset( $field['sortable_type'] ) && !empty( $field['sortable_type'] ) ) ? $field['sortable_type'] : '';
        $connected_class = ( isset( $field['connected_class'] ) && !empty( $field['connected_class'] ) ) ? $field['connected_class'] : '';
        $option_value = $this->get_value( $name, $default );
        $IsLabel_overwrited = is_string($option_value) && is_array(json_decode($option_value, true));


        if( $IsLabel_overwrited ){
          $values = (array)json_decode( $option_value );
          $labels = (array)$values['values'];
        }         

        echo '<div class="'.esc_attr( 'sortable_wrapper '.$class ).'">';

      // --------------| Active Sortable |-------------- //

        if( isset( $field['sortable_list']['default'] ) && !empty( $field['sortable_list']['default'] ) ){

          $flag = 1;

          echo '<div class="sortable">';
          echo '<ul id="'.esc_attr( $default_fields_id ).'" class="'.esc_attr( 'ui-sortable-connected ui-sortable-active '.$connected_class ).'">';

          foreach ( $field['sortable_list']['default'] as $default_key => $default_value ) {
            $default_val_arr[] = $default_key;
          }

          $value = ( $option_value != '' ) ? (array)json_decode( $option_value ) : $default_val_arr;

          $fields_arr = ( $option_value != '' ) ? $value['slug'] : $value;       

          if( $fields_arr ){
            $fields = array_unique( array_filter( $fields_arr ) );

            foreach ( $fields as $field_key ) {
              $default_arr[] = $field_key;
              $values_fields_arr[$field_key] = ( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] );
              echo '<li class="ui-state-default" id="'.esc_attr( $field_key ).'">';
              echo '<input name="name" class="hidden ui-sortable-name" value="'.esc_attr( ( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] ) ).'"><span class="label">'.wp_kses_post( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] ).'</span>';
              echo '<a href="javascript:;" class="edit-label"><i class="fa-solid fa-pen-to-square"></i></a>';
              echo '</li>';
            }
          }

          echo '</ul>';
          echo '</div>';

        }

      // --------------| Active Sortable |-------------- //

      // --------------| Deactive Sortable |-------------- //

        if( isset( $field['sortable_list']['fields'] ) && !empty( $field['sortable_list']['fields'] ) ){

          echo '<div class="sortable">';
          echo '<ul id="'.esc_attr( $all_fields_id ).'" class="'.esc_attr( ( ( $flag ) ? 'ui-sortable-connected': '' ).' '.$connected_class ).'">';

          foreach ($field['sortable_list']['fields'] as $default_key => $default_value) {
            $all_val_arr[] = $default_key;
          }

          $value = ( $option_value != '' ) ? (array)json_decode( $option_value ) : $all_val_arr;      

          $fields_arr = ( $option_value != '' ) ? $value['slug'] : $value;     

          if( $sortable_type == 'connected_list' ){
            $fields_arr = ( $option_value != '' ) ? array_keys( (array)$value['values'] ) : $value;            
          }else{
            $field_unique = array_diff( $all_val_arr, $fields_arr );            
            $fields_arr = array_merge( $fields_arr, $field_unique );
          }

          $fields = $fields_arr;

          if( $fields ){
            $fields = array_unique( array_filter( $fields ) );
            foreach ( $fields as $field_key ) {
              if( !in_array( $field_key, $default_arr ) ){
                $all_arr[] = $field_key;
                echo '<li class="ui-state-default" id="'.esc_attr( $field_key ).'">';
                $default_values_fields_arr[$field_key] = ( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] );
                echo '<input name="name" class="hidden ui-sortable-name" value="'.esc_attr( ( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] ) ).'"><span class="label">'.wp_kses_post( ( $IsLabel_overwrited ) ? $labels[$field_key] : $field['sortable_list']['fields'][$field_key] ).'</span>';
                echo '<a href="javascript:;" class="edit-label"><i class="fa-solid fa-pen-to-square"></i></a>';
                echo '</li>';
              }
            }
          }

          echo '</ul>';
          echo '</div>';

        }

        if( $sortable_type == 'connected_list' ){

          $all_value_arr = array_merge( $values_fields_arr, $default_values_fields_arr );

          $val['slug'] = $default_arr;
          $val['values'] = $all_value_arr;
          $val = json_encode( $val );
          $target = '#'.$default_fields_id;
          $reference = '#'.$all_fields_id.', #'.$default_fields_id;

          $input_target = "input[name='".esc_attr( $name )."']";
          $on_update = 'var order = jQuery("'.esc_attr( $target ).' .ui-state-default").map( function() {return this.id;}).get();
          var input_value = jQuery("'.esc_attr( $input_target ).'").val();
          input_value = JSON.parse(input_value);
          var order_obj = { slug : order }
          jQuery.extend( input_value, order_obj );
          input_value = JSON.stringify(input_value);
          jQuery("'.esc_attr( $input_target ).'").val(input_value);';
          $sortable = '.sortable({connectWith: ".'.esc_attr( $connected_class ).'", update: function(event, ui) {'.$on_update.'}}).disableSelection();';
        }else{

          $val['slug'] = $all_arr;
          $val['values'] = $default_values_fields_arr;
          $val = json_encode( $val );
          $target = '#'.$all_fields_id;
          $reference = '#'.$all_fields_id;

          $input_target = "input[name='".esc_attr( $name )."']";
          $on_update = 'var order = jQuery("'.esc_attr( $target ).' .ui-state-default").map( function() {return this.id;}).get();
          var input_value = jQuery("'.esc_attr( $input_target ).'").val();
          input_value = JSON.parse(input_value);
          var order_obj = { slug : order }
          jQuery.extend(input_value , order_obj );
          input_value = JSON.stringify(input_value);
          jQuery("'.esc_attr( $input_target ).'").val(input_value);';
          $sortable = '.sortable({update: function(event, ui) {'.$on_update.'} });';
        }

        echo "<input type='hidden' class='sortable-value' name='".esc_attr( $name )."' value='".esc_attr( $val )."'>";

      // --------------| Deactive Sortable |-------------- //

        echo '</div>';

      // --------------| Script |-------------- //
        $script = 'jQuery( "'.esc_attr( $reference ).'" )'.$sortable;
      // --------------| Script |-------------- //        

        $this->admin_inline_js( $script );

      }

      /**
      * That function return color field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function color_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input color */
        echo '<input type="text" class="color_field"';

        /* field name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* field id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* field value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required' );

        /* end input color */
        echo '>';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

      }

      /**
      * That function return simple datepicker field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function datepicker_field( $field ){

        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );

        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';

        /* start input datepicker */
        echo '<input type="text" class="datepicker_field"';

        /* field name */
        echo $this->get_field_value( $field , 'name', 'name' );

        /* field id */
        echo $this->get_field_value( $field , 'id', 'name' );

        /* field value */
        echo $this->get_field_value( $field , 'value', $value );

        /* input required attribute */
        echo $this->get_field_attr( $field, 'required');

        /* input placeholder */
        echo $this->get_field_value( $field , 'placeholder', 'placeholder');

        /* end input datepicker */
        echo '>';

        echo '<label for="'.esc_attr( $field['name'] ).'" class="field-trigger"><i class="fa-regular fa-calendar"></i></label>';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

        /* Script */
        
        $script = 'var param = {};';

        $target = ( ( isset( $field['name'] ) && !empty($field['name'] ) ) ? '#'.$field['name'] : '.datepicker_field' );
        $date_format = ( ( isset( $field['date_format'] ) && !empty($field['date_format'] ) ) ? $field['date_format'] : 'dd-mm-yy' );
        $othermonths_date = ( ( isset( $field['othermonths_date'] ) && !empty( $field['othermonths_date'] ) ) ? $field['othermonths_date'] : false );
        $buttonbar = ( ( isset( $field['buttonbar'] ) && !empty( $field['buttonbar'] ) ) ? $field['buttonbar'] : false );
        $changemonth = ( ( isset( $field['changemonth'] ) && !empty( $field['changemonth'] ) ) ? $field['changemonth'] : false );
        $changeyear = ( ( isset( $field['changeyear'] ) && !empty( $field['changeyear'] ) ) ? $field['changeyear'] : false );

        $script .= 'param["dateFormat"] = "'.esc_attr( $date_format ).'";';
        $script .= 'param["showOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'param["selectOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'param["showButtonPanel"] = "'.esc_attr( $buttonbar ).'";';
        $script .= 'param["changeMonth"] = "'.esc_attr( $changemonth ).'";';
        $script .= 'param["changeYear"] = "'.esc_attr( $changeyear ).'";';       
        $script .= 'jQuery("'.esc_attr( $target ).'").datepicker(param);';


        $this->admin_inline_js( $script );

        /* Script */

      }

      /**
      * That function return From to datepicker field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function from_to_datepicker_field( $field ){

        $date_format = ( ( isset( $field['date_format'] ) && !empty($field['date_format'] ) ) ? $field['date_format'] : 'dd-mm-yy' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        echo '<div class="'.esc_attr( 'input-field '.$class.' from_to_datepicker' ).'">';

        $from_name = ( isset( $field['from']['name'] ) && ( !empty( $field['from']['name'] ) ) ? $field['from']['name'] : '' );
        $from_default = ( isset( $field['from']['default'] ) && ( !empty( $field['from']['default'] ) ) ? $field['from']['default'] : '' );
        $from_value = $this->get_value( $from_name, $from_default );

        echo '<label class="field-trigger" '.( ( isset( $field['from']['name'] ) && !empty( $field['from']['name'] ) ) ? 'for="'.esc_attr( $field['from']['name'] ).'"' : '' ).'>'.wp_kses_post( ( isset( $field['from']['title'] ) && !empty($field['from']['title'] ) ) ? $field['from']['title'] : 'From' ).'</label>';

        echo '<input type="text" value="'.esc_attr( $from_value ).'" '.( ( isset( $field['from']['name'] ) && !empty( $field['from']['name'] ) ) ? 'id="'.esc_attr( $field['from']['name'] ).'" name="'.esc_attr( $field['from']['name'] ).'"' : '' ).' >';

        $to_name = ( isset( $field['to']['name'] ) && ( !empty( $field['to']['name'] ) ) ? $field['to']['name'] : '' );
        $to_default = ( isset( $field['to']['default'] ) && ( !empty( $field['to']['default'] ) ) ? $field['to']['default'] : '' );
        $to_value = $this->get_value( $to_name, $to_default );

        echo '<label class="field-trigger" '.( ( isset( $field['to']['name'] ) && !empty( $field['to']['name'] ) ) ? 'for="'.esc_attr( $field['to']['name'] ).'"' : '' ).'>'.wp_kses_post( ( isset( $field['to']['title'] ) && !empty($field['to']['title'] ) ) ? $field['to']['title'] : 'To' ).'</label>';

        echo '<input type="text" value="'.esc_attr( $to_value ).'" '.( ( isset( $field['to']['name'] ) && !empty( $field['to']['name'] ) ) ? 'id="'.esc_attr( $field['to']['name'] ).'" name="'.esc_attr( $field['to']['name'] ).'"' : '' ).' >';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

        /* Script */


        $script = 'var fromparam = {};';
        $script .= 'var toparam = {};';

        $target = ( ( isset( $field['from']['name'] ) && !empty($field['from']['name'] ) ) ? '#'.$field['from']['name'] : '.datepicker_field' );
        $date_format = ( ( isset( $field['from']['date_format'] ) && !empty($field['from']['date_format'] ) ) ? $field['from']['date_format'] : 'dd-mm-yy' );
        $othermonths_date = ( ( isset( $field['from']['othermonths_date'] ) && !empty( $field['from']['othermonths_date'] ) ) ? $field['from']['othermonths_date'] : false );
        $buttonbar = ( ( isset( $field['from']['buttonbar'] ) && !empty( $field['from']['buttonbar'] ) ) ? $field['from']['buttonbar'] : false );
        $changemonth = ( ( isset( $field['from']['changemonth'] ) && !empty( $field['from']['changemonth'] ) ) ? $field['from']['changemonth'] : false );
        $changeyear = ( ( isset( $field['from']['changeyear'] ) && !empty( $field['from']['changeyear'] ) ) ? $field['from']['changeyear'] : false );

        $script .= 'fromparam["dateFormat"] = "'.esc_attr( $date_format ).'";';
        $script .= 'fromparam["showOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'fromparam["selectOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'fromparam["showButtonPanel"] = "'.esc_attr( $buttonbar ).'";';
        $script .= 'fromparam["changeMonth"] = "'.esc_attr( $changemonth ).'";';
        $script .= 'fromparam["changeYear"] = "'.esc_attr( $changeyear ).'";';

        $target = ( ( isset( $field['to']['name'] ) && !empty($field['to']['name'] ) ) ? '#'.$field['to']['name'] : '.datepicker_field' );
        $date_format = ( ( isset( $field['to']['date_format'] ) && !empty($field['to']['date_format'] ) ) ? $field['to']['date_format'] : 'dd-mm-yy' );
        $othermonths_date = ( ( isset( $field['to']['othermonths_date'] ) && !empty( $field['to']['othermonths_date'] ) ) ? $field['to']['othermonths_date'] : false );
        $buttonbar = ( ( isset( $field['to']['buttonbar'] ) && !empty( $field['to']['buttonbar'] ) ) ? $field['to']['buttonbar'] : false );
        $changemonth = ( ( isset( $field['to']['changemonth'] ) && !empty( $field['to']['changemonth'] ) ) ? $field['to']['changemonth'] : false );
        $changeyear = ( ( isset( $field['to']['changeyear'] ) && !empty( $field['to']['changeyear'] ) ) ? $field['to']['changeyear'] : false );

        $script .= 'toparam["dateFormat"] = "'.esc_attr( $date_format ).'";';
        $script .= 'toparam["showOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'toparam["selectOtherMonths"] = "'.esc_attr( $othermonths_date ).'";';
        $script .= 'toparam["showButtonPanel"] = "'.esc_attr( $buttonbar ).'";';
        $script .= 'toparam["changeMonth"] = "'.esc_attr( $changemonth ).'";';
        $script .= 'toparam["changeYear"] = "'.esc_attr( $changeyear ).'";';

        $script .= 'var dateFormat = "'.( ( isset( $field['date_format'] ) && !empty($field['date_format'] ) ) ? esc_attr( $field['date_format'] ) : 'dd-mm-yy' ).'",';

        $script .= 'from = jQuery( "#'.esc_attr( $field['from']['name'] ).'" ).datepicker(fromparam).on( "change", function() {to.datepicker( "option", "minDate", getDate( this ) );}),';

        $script .= 'to = jQuery( "#'.esc_attr( $field['to']['name'] ).'" ).datepicker(toparam).on( "change", function() {from.datepicker( "option", "maxDate", getDate( this ) );});';

        $script .= 'function getDate( element ) {var date;try {date = jQuery.datepicker.parseDate( dateFormat, element.value );} catch( error ) {date = null;}return date;}';

        $this->admin_inline_js( $script );
        /* Script */
      }

      /**
      * That function return switch field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function switch_field( $field ){


        $name = isset( $field['name'] ) && !empty( $field['name'] ) ? $field['name'] : '';
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : 0 );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );


        $switch_id = isset($field['custom_id']) ? $field['custom_id'] : $name;

        $value = $this->get_value( $name, $default );
        if( empty( $value ) ){
          if( $default == 'unable'  ){
            $value = 'yes';
          }
          if( $default == 'disable'  ){
            $value = 'no';
          }
        }
        
        /* start input switch */
        echo '<div class="'.esc_attr( 'switch-field '.$class ).'">';

        echo '<div class="field_wrapper">';
        echo '<input type="radio" id="'.esc_attr( $switch_id.'-unable' ).'" class="switcher" '.checked( $value, 'yes', false ).' '.checked( $value, 'unable', false ).' name="'.esc_attr( $name ).'" value="yes" >';
        echo '<label for="'.esc_attr( $name.'-unable' ).'">'. __( 'Enable', 'office-locator' ).'</label>';
        echo '</div>';

        echo '<div class="field_wrapper">';
        echo '<input type="radio" id="'.esc_attr( $switch_id.'-disable' ).'" class="switcher" '.checked( $value, 'no', false ).'  '.checked( $value, 'disable', false ).' name="'.esc_attr( $name ).'" value="no" >';
        echo '<label for="'.esc_attr( $name.'-disable' ).'">'. __( 'Disable', 'office-locator' ).'</label>';
        echo '</div>';

        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );
      }


      /**
      * That function return ranger field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function ranger_field( $field ){

        $wrapper_id = isset( $field['wrapper_id'] ) && !empty( $field['wrapper_id'] ) ? $field['wrapper_id'] : '';
        $handle_id = isset( $field['handle_id'] ) && !empty( $field['handle_id'] ) ? $field['handle_id'] : '';
        $name = isset( $field['name'] ) && !empty( $field['name'] ) ? $field['name'] : '';
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : 0 );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name, $default );
        echo '<div class="'.esc_attr( 'ranger_wrapper '.$class ).'">';
        echo '<div id="'.esc_attr( $wrapper_id ).'">';
        echo '<div id="'.esc_attr( $handle_id ).'" class="ui-slider-handle"></div>';
        echo '<input type="hidden" name="'.esc_attr( $name ).'" id="'.esc_attr( $name ).'">';
        echo '</div>';
        echo '</div>';

        echo $this->get_field_attr( $field , 'field_desc' );

        $script = 'var handle = jQuery( "#'.esc_attr( $handle_id ).'" );';
        $script .= 'var target = "#'.esc_attr( $name ).'";';
        $script .= 'jQuery( "#'.esc_attr( $wrapper_id ).'" ).slider({';
        $script .= ( $value ) ? 'value: '.esc_attr( $value ).',' : '';
        $script .= 'range: "min",
        create: function() {
          handle.text( jQuery( this ).slider( "value" ) );
          jQuery(target).val( jQuery( this ).slider( "value" ) );
          },
          slide: function( event, ui ) {     

            handle.text( ui.value );
            jQuery(target).val( ui.value );
          }
        });';  

        $this->admin_inline_js( $script );

      }

      /**
      * That function return between ranger field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function between_ranger_field( $field ){

        $title = ( isset( $field['ranger_title'] ) && !empty( $field['ranger_title'] ) ) ? $field['ranger_title'] : 'Ranger';
        $name = ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? $field['name'] : '';
        $handle_id = ( isset( $field['handle_id'] ) && !empty( $field['handle_id'] ) ) ? $field['handle_id'] : 'ranger';
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );
        $default = $field['default'];

        $min_id = ( isset( $name ) && ( !empty( $name ) ) ? $name.'-min' : '' );
        $max_id = ( isset( $name ) && ( !empty( $name ) ) ? $name.'-max' : '' );

        $value = $this->get_value( $name, $default );

        $default_min = ( isset( $default['min'] ) && ( !empty( $default['min'] ) ) ? $default['min'] : 0 );
        $default_max = ( isset( $default['max'] ) && ( !empty( $default['max'] ) ) ? $default['max'] : 0 );

        $min_val = ( isset( $value['min'] ) && ( !empty( $value['min'] ) ) ? $value['min'] : $default_min );
        $max_val = ( isset( $value['max'] ) && ( !empty( $value['max'] ) ) ? $value['max'] : $default_max );

        echo '<div class="'.esc_attr( 'ranger_wrapper '.$class ).'">';
        echo '<p>';
        echo '<div class="'.esc_attr( $name.'_label label' ).'"><span class="min">'.esc_html( $min_val ).'</span> - <span class="max">'.esc_html( $max_val ).'</span></div>';
        echo '<input type="hidden" name="'.esc_attr( $name.'[min]' ).'" id="'.esc_attr( $min_id ).'">';
        echo '<input type="hidden" name="'.esc_attr( $name.'[max]' ).'" id="'.esc_attr( $max_id ).'">';
        echo '</p>';

        echo '<div id="'.esc_attr( $handle_id ).'"></div>';
        echo '</div>';

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );

        $script = 'jQuery( "#'.esc_attr( $handle_id ).'" ).slider({range: true,';

        $script .= isset( $field['min'] ) && !empty( $field['min'] ) ? 'min: '.$field['min'].',' : '';
        $script .= isset( $field['max'] ) && !empty( $field['max'] ) ? 'max: '.$field['max'].',' : '';
        $script .= isset( $field['default'] ) && !empty( $field['default'] ) ? 'values: [ '. $min_val .', '. $max_val .' ],' : '';

        $script .= 'slide: function( event, ui ) {
          jQuery( ".'.esc_attr( $name ).'_label .min" ).html( ui.values[ 0 ] );
          jQuery( ".'.esc_attr( $name ).'_label .max" ).html( ui.values[ 1 ] );

          jQuery( "#'.esc_attr( $min_id ).'" ).val( ui.values[ 0 ] );
          jQuery( "#'.esc_attr( $max_id ).'" ).val( ui.values[ 1 ] );
        }
        });
        jQuery( ".'.esc_attr( $name ).'_label .min" ).html( jQuery( "#'.esc_attr( $handle_id ).'" ).slider( "values", 0 ) );
        jQuery( ".'.esc_attr( $name ).'_label .max" ).html( jQuery( "#'.esc_attr( $handle_id ).'" ).slider( "values", 1 ) );

        jQuery( "#'.esc_attr( $min_id ).'" ).val( jQuery( "#'.esc_attr( $handle_id ).'" ).slider( "values", 0 ) );
        jQuery( "#'.esc_attr( $max_id ).'" ).val( jQuery( "#'.esc_attr( $handle_id ).'" ).slider( "values", 1 ) );';

        $this->admin_inline_js( $script ); 

      }

      /**
      * That function return parameter select field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function parameter_select_field( $field ){
        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $default = ( isset( $field['default'] ) && ( !empty( $field['default'] ) ) ? $field['default'] : '' );
        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );

        $value = $this->get_value( $name );
        echo '<div class="'.esc_attr( 'input-field '.$class ).'">';
        /* start select */
        echo '<select  class="select_field" '.( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'name="'.esc_attr( $field['name'] ).'[]"' : '').' '.( ( isset( $field['name'] ) && !empty( $field['name'] ) ) ? 'id="'.esc_attr( $field['name'] ).'"' : '').'>';
        if( $field['options'] ){
          foreach( $field['options'] as $key => $options ){            
            echo sprintf(
              '<option value="%1$s" %3$s>%2$s</option>',
              esc_attr( $key ),
              esc_html( $options ),
              selected( isset($value[0]) ? $value[0] : '', esc_attr( $key ), false )
            );

          }
        }
        echo '</select>';
        /* end select */

        /* start parameter */
        if( $field['parameters'] ){
          foreach ($field['parameters'] as $parameters) {
            $name = $field['name'].'['.$parameters['name'].']';
            echo '<select  class="select_field parameter" '.( ( isset( $name ) && !empty( $name ) ) ? 'name="'.esc_attr( $name ).'"' : '').'>';
            echo '<option value="">'.esc_html( $parameters['title'] ).'</option>';
            if( $parameters['options'] ){
              foreach( $parameters['options'] as $key => $options ){                
                echo sprintf(
                  '<option value="%1$s" %3$s>%2$s</option>',
                  esc_attr( $key ),
                  esc_html( $options ),
                  selected( isset($value[$parameters['name']]) ? $value[$parameters['name']] : '', esc_attr( $key ), false )
                );
              }
            }
            echo '</select>';
          }
        }
        /* end parameter */

        echo '</div>';
        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );
      }
      
      /**
      * That function return multi parameter select field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function multiple_parameter_field( $field ){

        $class = ( isset( $field['class'] ) && ( !empty( $field['class'] ) ) ? $field['class'] : '' );
        $name = ( isset( $field['name'] ) && ( !empty( $field['name'] ) ) ? $field['name'] : '' );
        $value = $this->get_value( $name );

        if( $field['parameters'] ){
          $counter = 1;
          echo '<div class="'.esc_attr( 'input-field '.$class ).'">';
          foreach ( $field['parameters'] as $key => $parameters ) {

            // parameter name
            $parameter_name = isset($parameters['name']) ? $parameters['name'] : '';
            $parameter_default = isset($parameters['default']) ? $parameters['default'] : '';
            $parameters['name'] = $field['name'].'['.$parameters['name'].']';
            $parameter_value = isset($value[$parameter_name]) ? $value[$parameter_name] : '';      
            $parameters['default'] = ($parameter_value) ? $parameter_value : $parameter_default;
            // class
            $class = ( $counter > 1 ) ? 'parameter': '';

            // set custom attributes
            $parameters['attributes'] = $field['attributes'];

            echo '<div class="'.esc_attr( 'parameter-fields '.$class ).'">';
            switch ( $parameters['type'] ) {
              case "text":
              $this->text_field( $parameters );
              break;
              case "number":
              $this->number_field( $parameters );
              break;
              case "select":
              $this->select_field( $parameters );
              break;
            }
            echo '</div>';
            $counter++;
          }
          echo '</div>';
        }

        /* select description */
        echo $this->get_field_attr( $field , 'field_desc' );
      } 

      /**
      * That function return custom field.
      *
      * @since    1.0.0
      * @access   public
      * @param    array  $field   All options ablue the field.
      */
      public function custom_field( $field ){

        /* Please change function as per your plugin text domain because it will breakdown site. */
        if ( function_exists( 'wt_custom_field_group' ) ) {
          echo wt_custom_field_group( $field, $this->plugin_name, $this->version );
        }  

      }

      /**
      * That function return field attributes.
      * @since    1.0.0
      * @access   public
      * 
      * @param  array  $field  All options ablue the field.
      * @param  string $slug   Option name.
      * @param  string $value  Option key.    
      * @return string.
      */

      public function get_field_value( $field, $slug, $value ){ 
        $attr_value = '';
        if( $slug != 'value' ){
          $value = ( isset( $field[$value] ) && ( !empty( $field[$value] ) ) ? $field[$value] : '' );
          if( is_array( $value ) && !empty( $value ) ){
            $attr_value = $this->set_attributes( $value );            
          }else{
            if( !is_array( $value ) && $value  ){
              $attr_value = ' '.esc_html( $slug ).'="'.esc_attr( $value ).'"';
            }            
          }
        }else{
          $attr_value = ' '.esc_html( $slug ).'="'.esc_attr( $value ).'"';
        }        
        return $attr_value;
      }

      /**
      * That function return field attributes.
      *
      * @since    1.0.0
      * @access   public
      *
      * @param  array  $field         All options ablue the field.
      * @param  string $value         Option name.
      * @param  string $custom_value  Option value.    
      * @return string $value         equal field_desc then return html.
      * @return string.
      * 
      */

      public function get_field_attr( $field, $value, $custom_value = '' ){ 

        if( empty( $custom_value ) ){
          $custom_value = $value;
        }

        if( $value != 'field_desc' ){
          return ( isset( $field[ $value ] ) && ( $field[ $value ] ) ?  wp_kses_post( $custom_value ).' ' : '' );
        }else{
          return ( ( isset( $field[$value] ) && !empty($field[$value] ) ) ? '<span class="field_description">'.wp_kses_post( $field[$value] ).'</span>' : '' );
        }
      }

      /**
       * Register the Inline JavaScript for the admin area.
       *
       * @since    1.0.0
       * @access   public
       */
      public function admin_inline_js( $js ){

        $js = wp_check_invalid_utf8( $js );
        $js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $js );
        $js = str_replace( "\r", '', $js );

        $js = "<!-- Office Locator JavaScript -->\n\njQuery(function($) { $js });\n\n";

        $js = apply_filters( 'office_locator_queued_js', $js );
        wp_add_inline_script( $this->plugin_name, $js );

      }


    }
