<?php
class wpdevart_bc_Library {
	
	public static function get_value($key, $default_value=""){
		if (isset($_GET[$key])) {
		  $value = esc_html($_GET[$key]);
		}
		elseif (isset($_POST[$key])) {
		  $value = esc_html($_POST[$key]);
		}
		else {
		  $value = '';
		}
		if (!$value) {
		  $value = $default_value;
		}
		return $value;
	}
	 
    public static function wpdevart_callback_info($args,$value) {
	  ?>
	  <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-info" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<?php echo $args['description']; ?>
			  </div>
		  </div>
	  </div>
	  <?php
	}
	
	public static function wpdevart_callback_color($args,$value) {
	  ?>
	    <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-color" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<input type="text" id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" value="<?php if(!is_array($value)) echo $value; ?>" <?php echo ((isset($args['disabled']) && $args['disabled']==true))? "disabled='disabled'" : ""; ?> class="color <?php echo (isset($args["pro"]) && $args["pro"] === true)? "pro-field" : ""; ?>" <?php echo (isset($args["pro"]) && $args["pro"] === true)? "readonly" : ""; ?>>
			  </div>
		  </div>
	  </div>
	  <script  type="text/javascript">
		 jQuery(document).ready(function() {
		   jQuery('.color').wpColorPicker();
		 });
      </script>
	  <?php
	}
    public static function wpdevart_callback_select($args,$value) {
	  ?>
	  <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-checkbox" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<?php if(isset($args['currency']) && $args['currency'] === true) { ?>
					<select id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" <?php echo (isset($args['onchange'])? 'onchange="'.$args['onchange'].'"' : '' ); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>>
					<?php
					foreach ($args['valid_options'] as $valid_option) { ?>
						<option value='<?php echo $valid_option['code']; ?>' <?php echo selected($value,$valid_option['code']); ?>><?php echo $valid_option['name']. ' - ' .$valid_option['simbol']; ?></option>
					<?php  } ?>
					</select>
				<?php } else { ?>
					<select id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" <?php echo (isset($args['onchange'])? 'onchange="'.$args['onchange'].'"' : '' ); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>>
					<?php
					foreach ($args['valid_options'] as $key => $valid_option) { ?>
						<option value='<?php echo $key; ?>' <?php echo selected($value,$key); ?>><?php echo $valid_option; ?></option>
					<?php  } ?>
					</select>	
				<?php } ?>
			  </div>
		  </div>
	  </div>
	  <?php
	}


    public static function wpdevart_callback_checkbox($args,$value) {
	  ?>
	   <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-checkbox" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<?php
				if (isset($args['valid_options']) && $args['valid_options']!='') {
					foreach ($args['valid_options'] as $key => $valid_option) { ?>
						<input type='checkbox' id='checkbox_<?php echo $key; ?>' value='<?php echo $key; ?>' name='<?php echo $args['id'].'[]'; ?>' <?php echo checked(in_array($key,$value)); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>><label for='checkbox_<?php echo $key; ?>'><?php echo $valid_option; ?></label>
					<?php }  
				 } else {  ?>
					<input type='checkbox' id='<?php echo $args['id']; ?>' name='<?php echo $args['id']; ?>' <?php echo checked($value,'on'); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>>
				<?php } ?>
			  </div>
		  </div>
	  </div>
	  <?php
	}

    public static function wpdevart_callback_radio($args,$value) {
	  ?>
	<div class="wpdevart-item-container div-for-clear">
	    <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<?php
				if (isset($args['valid_options']) && $args['valid_options']!='') {
					foreach ($args['valid_options'] as $key => $valid_option) { ?>
						<input type='radio' id='radio_<?php echo $key; ?>' value='<?php echo $key; ?>' name='<?php echo $args['id']; ?>' <?php checked($value,$key); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>><label for='radio_<?php echo $key; ?>'><?php echo $valid_option; ?></label>
					<?php }  
				}  ?>
			  </div>
		</div>
	</div>
	  <?php
	}


    public static function wpdevart_callback_text($args,$value) {
	  ?>
	  <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title" id="label_<?php echo $args['id']; ?>" >
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio div-for-clear" id="wpdevart_wrap_<?php echo $args['id']; ?>">
				<input type="text" id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" value="<?php if(!is_array($value) && $value != "0") echo htmlspecialchars(stripslashes($value)); ?>" <?php echo ((isset($args['readonly']) && $args['readonly']==true))? "readonly" : ""; ?> <?php echo (isset($args['width']) && $args['width'])? 'style="width:'.$args['width'].'px"': ""; ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field' readonly" : ""; ?>>
			  </div>
		  </div>
	  </div>
	  <?php
	}


    public static function wpdevart_callback_textarea($args,$value) {
	  ?>
	   <div class="wpdevart-item-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio" id="wpdevart_wrap_<?php echo $args['id']; ?>">
			    <?php if(isset($args['wp_editor']) && $args['wp_editor'] && user_can_richedit()) {
					wp_editor(((!is_array($value))? $value : ""), $args['id'], array('teeny' => FALSE, 'textarea_name' => $args['id'], 'media_buttons' => FALSE, 'textarea_rows' => 5));
				} else { ?>
					<textarea id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field' readonly" : ""; ?>><?php if(!is_array($value) && $value != 0) echo $value; ?></textarea>
				<?php } ?>
			  </div>
		  </div>
	  </div>
	  <?php
	}


    public static function wpdevart_callback_checkbox_enable($args,$value) {
	  ?>
	  <div class="wpdevart-item-container div-for-clear">
	    <div class="wpdevart-fild-item-container"> 
	      <div class="section-title">
		     <span class="wpdevart-title"><?php echo $args['title']; ?></span>
			 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
			 <?php if(isset($args['description']) && $args['description'] != "") { ?>
				<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
			 <?php } ?>
		  </div>
		  <div class="wpdevart-item-elem-container element-checkbox-enable" id="wpdevart_wrap_<?php echo $args['id']; ?>">
			  <input type="checkbox" class="checkbox <?php echo (isset($args["pro"]) && $args["pro"] === true)? 'pro-field' : ""; ?>" name="<?php echo $args['id']; ?>" id="<?php echo $args['id'] ?>" <?php checked($value,'on'); ?>>
		  </div>
		</div>
	  </div>
	  <script>
	  jQuery(document).ready(function () {

		var wpdevart_element_<?php echo $args["id"]; ?> = {
		  id : "<?php echo $args["id"]; ?>",
		  enable : [
			<?php
			foreach ($args['enable'] as $enable) :
			echo "'". $enable ."', ";
			endforeach; 
			?>        
			]
		};
		wpdevart_elements.checkbox_enable(wpdevart_element_<?php echo $args["id"]; ?>);
		jQuery('#<?php echo $args["id"]; ?>').on( "click", function() {
		  wpdevart_elements.checkbox_enable(wpdevart_element_<?php echo $args["id"]; ?>);
		});

	  });
	  </script>
	  <?php
	}

    public static function wpdevart_callback_radio_enable($args,$value) {
	  ?>
	  <div class="wpdevart-item-container div-for-clear">
	    <div class="wpdevart-fild-item-container">
	      <div class="section-title">
		     <span class="wpdevart-title"><?php echo $args['title']; ?></span>
			 <?php echo (isset($args["pro"]) && $args["pro"] === true)? "<span class='pro_feature'>(pro)</span>" : ""; ?>
			 <?php if(isset($args['description']) && $args['description'] != "") { ?>
				<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
			 <?php } ?>
		  </div>
		  <div class="wpdevart-item-elem-container element-radio-enable" id="wpdevart_wrap_<?php echo $args['id']; ?>">
			<?php
			if (isset($args['valid_options']) && $args['valid_options']!='') {
				foreach ($args['valid_options'] as $key => $valid_option) { ?>
					<input type='radio' id='radio_<?php echo $key; ?>' value='<?php echo $key; ?>' name='<?php echo $args['id']; ?>' <?php echo checked($value,$key); ?> <?php echo (isset($args["pro"]) && $args["pro"] === true)? "class='pro-field'" : ""; ?>><label for='radio_<?php echo $key; ?>'><?php echo $valid_option; ?></label>
				<?php }  
			}
			?>
		  </div>
		</div>
	  </div>
	  <script>
	  jQuery(document).ready(function () {

		var wpdevart_element_<?php echo $args["id"]; ?> = {
		  id : "<?php echo $args["id"]; ?>",
		  enable : [
			<?php
			foreach ($args['enable'] as $key => $value) {
                echo "{key: '" . $key ."', val: [" ; 
				if(is_array($value)) {
					foreach ($value as $item){
						echo "'".$item."',";
					} 
				} else {
					echo "'".$value."',";
				}
				echo "]},";
            }  
			?>        
			]
		};
		
		wpdevart_elements.radio_enable(wpdevart_element_<?php echo $args["id"]; ?>);
		jQuery('input[type=radio][name="<?php echo $args['id']; ?>"]').on( "change", function() {
		  wpdevart_elements.radio_enable(wpdevart_element_<?php echo $args["id"]; ?>);
		});

	  });
	  </script>
	  <?php
	}

    public static function wpdevart_callback_hidden($args,$value) {
	  ?>
	  <input type="hidden" name="<?php echo $args["id"]; ?>" id="<?php echo $args["id"]; ?>" value="<?php echo $args["default"]; ?>">
	  <?php
	}
	
	
	
	/*
	*FORM
	*/
     
	 
    public static function wpdevart_form_select($args,$value) {
	  ?>
	  <div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="section-title-txt"><?php echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-checkbox" id="wpdevart_wrap_<?php echo $args['name']; ?>">
				<select id="<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>" disabled="disabled">
				<?php foreach ($args['valid_options'] as $key => $valid_option) { ?>
					<option value='<?php echo $key; ?>' <?php echo selected($value,$key); ?>><?php echo $valid_option; ?></option>
				<?php  } ?>
				</select>
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		  </div>
		  <div class="form-fild-options">
			<input type="hidden" name='<?php echo $args['name']; ?>[type]' value="<?php echo $args['type']; ?>">
			<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>" class="form_label">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type='checkbox' id='required_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[required]' <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Multiple select",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type='checkbox' id='form_multi_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[multi]' <?php echo checked(isset($value['multi']) && $value['multi'] == 'on'); ?> >
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Options",'booking-calendar'); ?> 	<span class="wpdevart-info-container">?<span class="wpdevart-info">Enter dropdown options. One option per line.</span></span>
				</div>
				
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<textarea id='form_opt_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[options]'><?php if(isset($value['options'])) echo $value['options']; ?></textarea>
				</div>
			</div>
		  </div>
	  </div>
	  <?php
	}


    public static function wpdevart_form_checkbox($args,$value) {
	  ?>
	   <div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="section-title-txt"><?php echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-checkbox" id="wpdevart_wrap_<?php echo $args['name']; ?>">
				<?php
				if (isset($args['options']) && $args['options']!='') {
					foreach ($args['options'] as $key => $valid_option) { ?>
						<input type='checkbox' id='checkbox_<?php echo $key; ?>' value='<?php echo $key; ?>' name='<?php echo $args['name'].'[]'; ?>' disabled='disabled'><label for='checkbox_<?php echo $key; ?>'><?php echo $valid_option; ?></label>
					<?php }  
				 } else {  ?>
					<input type='checkbox' id='<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>'  disabled='disabled'>
				<?php } ?>
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		  </div>
			<div class="form-fild-options">
				<input type="hidden" name='<?php echo $args['name']; ?>[type]' value="<?php echo $args['type']; ?>">
				<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			    <div class="wpdevart-item-container div-for-clear">
					<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
					<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
						<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>" class="form_label">
					</div>
				</div>
			    <div class="wpdevart-item-container div-for-clear">
					<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
					<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
						<input type='checkbox' id='required_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[required]' <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
					</div>
				</div>
		    </div>
	  </div>
	  <?php
	}

    public static function wpdevart_form_radio($args,$value) {
	  ?>
	<div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	    <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="section-title-txt"><?php echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio" id="wpdevart_wrap_<?php echo $args['name']; ?>">
				<?php
				if (isset($args['options']) && $args['options']!='') {
					foreach ($args['options'] as $key => $valid_option) { ?>
						<input type='radio' id='radio_<?php echo $key; ?>' value='<?php echo $key; ?>' name='<?php echo $args['name']; ?>' disabled='disabled'><label for='radio_<?php echo $key; ?>'><?php echo $valid_option; ?></label>
					<?php }  
				} ?>
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		</div>
		<div class="form-fild-options">
			<input type="hidden" name='<?php echo $args['name']; ?>[type]' value="<?php echo $args['type']; ?>">
			<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>" class="form_label">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type='checkbox' id='required_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[required]' <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
				</div>
			</div>
		</div>
	</div>
	  <?php
	}


    public static function wpdevart_form_text($args,$value) {
	  ?>
	  <div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title" id="label_<?php echo $args['name']; ?>" >
				 <span class="section-title-txt"><?php echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
				<input type="text" id="<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>"  disabled='disabled'>
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		  </div>
			<div class="form-fild-options">
				<input type="hidden" name='<?php echo $args['name']; ?>[type]' value="<?php echo $args['type']; ?>">
				<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			    <div class="wpdevart-item-container div-for-clear">
					<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
					<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
						<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>"  class="form_label">
					</div>
				</div>
			    <div class="wpdevart-item-container div-for-clear">
					<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
					<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
						<input type='checkbox' id='required_<?php echo $args['name']; ?>' name="<?php echo $args['name']; ?>[required]" <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
					</div>
				</div>
			    <div class="wpdevart-item-container div-for-clear">
					<div class="section-title"> <?php echo __("Is Email",'booking-calendar'); ?> </div>
					<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
						<input type='checkbox' id='form_isemail_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[isemail]' <?php echo checked(isset($value['isemail']) && $value['isemail'] == 'on'); ?> >
					</div>
				</div>
		    </div>
	  </div>
	  <?php
	}


    public static function wpdevart_form_textarea($args,$value) {
	  ?>
	   <div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="section-title-txt"><?php echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio" id="wpdevart_wrap_<?php echo $args['name']; ?>">
				<textarea id="<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>" disabled='disabled'></textarea>
				
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		  </div>
		  <div class="form-fild-options">
			<input type="hidden" name='<?php echo $args['name']; ?>[type]' value="<?php echo $args['type']; ?>">
			<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>" class="form_label">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type='checkbox' id='required_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[required]' <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
				</div>
			</div>
		  </div>
	  </div>
	  <?php
	}
	
    public static function wpdevart_extras_field($args,$value="") {	
       if(isset($value["items"]) && is_array($value["items"])) {
		   $last_element = end($value["items"]);
		   $max_id = str_replace('field_item', '', $last_element['name']);
	   } else {
		   $max_id = 3;
	   }	 
	   
	  ?>
	   <div class="wpdevart-item-container wpdevart-item-parent-container div-for-clear">
	      <div class="wpdevart-fild-item-container">
			  <div class="section-title">
				 <span class="section-title-txt"><?php if(isset($args['label'])) echo $args['label']; ?></span><span class="wpdevart-required"><?php if(isset($value['required']) && $value['required'] == 'on') echo "*"; ?></span>
			  </div>
			  <div class="wpdevart-item-elem-container element-radio" id="wpdevart_wrap_<?php if(isset($args['name'])) echo $args['name']; ?>">
				<div class="delete-form-fild">
				</div>
				<div class="open-form-fild-options">
				</div>
			  </div>
		  </div>
		  <div class="form-fild-options">
			<input type="hidden" name='<?php echo $args['name']; ?>[name]' value="<?php echo $args['name']; ?>">
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Label",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type="text" id="label_<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>[label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>" class="form_label">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title"> <?php echo __("Required",'booking-calendar'); ?> </div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					<input type='checkbox' id='required_<?php echo $args['name']; ?>' name='<?php echo $args['name']; ?>[required]' <?php echo checked(isset($value['required']) && $value['required'] == 'on'); ?> class="form_req">
				</div>
			</div>
			<div class="wpdevart-item-container div-for-clear">
				<div class="section-title div-for-clear"><span class="extra-items"> <?php echo __("Items",'booking-calendar'); ?></span>
					<div class="add_extra_field_item" data-max="<?php echo $max_id; ?>" data-field="<?php echo $args['name']; ?>"></div>
				</div>
				<div class="wpdevart-item-elem-container div-for-clear" id="wpdevart_wrap_<?php echo $args['name']; ?>">
					
					<div class="wpdevart-extra-items-container">
						<ul class="extra-items-labels div-for-clear">
							<li>Label</li>
							<li>Operation</li>
							<li>Price type</li>
							<li>Price/Percent</li>
						</ul>
						<?php if(isset($args["items"]) && count($args["items"])) { ?>
							<ul class="wpdevart-extra-item-container div-for-clear">
							<?php foreach($args["items"] as $key=>$item) {
								if(isset($value['items'][''.$key.''])) {
									$val = $value['items'][''.$key.''];
								} else {
									$val = 0;
								}
								echo self::wpdevart_extras_field_item($args['name'],$item,$val);
							 } ?>
							</ul> 
						<?php } ?>
						<ul class="pro-feature extra-items-labels div-for-clear">
							<li></li>
							<li><span class="pro_feature">(pro)</span></li>
							<li><span class="pro_feature">(pro)</span></li>
							<li><span class="pro_feature"></span></li>
						</ul>
					</div>
				</div>
			</div>
		  </div>
	  </div>
	  <?php
	}
	
		
    public static function wpdevart_extras_field_item($name,$args,$value=0) {
	  ?>
		<li> 
			<div class="wpdevart-extra-item  div-for-clear">
				<input type="hidden" name="<?php echo $name; ?>[items][<?php echo $args["name"]; ?>][name]" value="<?php echo (isset($args["name"]))? $args["name"] : ""; ?>">
				<input type="text" name="<?php echo $name; ?>[items][<?php echo $args["name"]; ?>][label]" value="<?php if(isset($value['label'])) echo $value['label']; ?>">
				<select name="<?php echo $name; ?>[items][<?php echo $args["name"]; ?>][operation]" class="pro-field">
					<option value="+" <?php selected(isset($value["operation"]) && $value["operation"]=="+"); ?>>+</option>
					<option value="-" <?php selected(isset($value["operation"]) && $value["operation"]=="-"); ?>>-</option>
				</select>
				<select name="<?php echo $name; ?>[items][<?php echo $args["name"]; ?>][price_type]" class="pro-field">
					<option value="price" <?php selected(isset($value["price_type"]) && $value["price_type"]=="price"); ?>>Price</option>
					<option value="percent" <?php selected(isset($value["price_type"]) && $value["price_type"]=="percent"); ?>>Percent</option>
				</select>
				<input type="text" name="<?php echo $name; ?>[items][<?php echo $args["name"]; ?>][price_percent]" value="<?php if(isset($value['price_percent'])) echo $value['price_percent']; ?>">
				<div class="delete-extra-fild"></div>
			</div>
		</li>
	  <?php
	}
	
	public static function items_nav($wpdevart_page,$items_count,$form_id){ ?>   
        <script type="text/javascript">
			function get_page(x,y) {
				var items_county=<?php if($items_count){ if($items_count%20){ echo ($items_count-$items_count%20)/20+1;} else echo ($items_count-$items_count%20)/20;} else echo 1;?>;
				switch(y) {
					case 1:
						if(x >= items_county) {
							jQuery(".wpdevart_page").val(items_county);
						} else {
							jQuery(".wpdevart_page").val(x+1);
						}
					    break;
					case 2:
						jQuery(".wpdevart_page").val(items_county);
						break;
					case -1:
						if(x == 1) {
							jQuery(".wpdevart_page").val(1);
						} else {
							jQuery(".wpdevart_page").val(x-1);
						}
					    break;
					case -2:
					    jQuery(".wpdevart_page").val(1);
					    break;
					default:
					    jQuery(".wpdevart_page").val(1);
				}	
				jQuery("#<?php echo $form_id; ?>").submit();	
			 }
		</script>
		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="displaying-num"><?php echo $items_count; ?> items</span>
				<?php 
				if($items_count > 20) {
					if($wpdevart_page==1) {
						$first = "first-page disabled";
						$prev = "prev-page disabled";
						$next = "next-page";
						$last = "last-page"; 
					}
					if($wpdevart_page>=(1+($items_count-$items_count%20)/20) ) {
						$first = "first-page ";
						$prev = "prev-page";
						$next = "next-page disabled";
						$last = "last-page disabled"; 
					} ?>     
					<span class="pagination-links">
						<a class="<?php echo $first; ?>" href="javascript:get_page(<?php echo $wpdevart_page; ?>,-2);">«</a>
						<a class="<?php echo $prev; ?>" href="javascript:get_page(<?php echo $wpdevart_page; ?>,-1);">‹</a>
						<span class="paging-input">
						<span class="total-pages"><?php echo $wpdevart_page; ?></span>
						of <span class="total-pages">
						<?php echo ($items_count-$items_count%20)/20+1; ?>
						</span>
						</span>
						<a class="<?php echo $next; ?>" href="javascript:get_page(<?php echo $wpdevart_page; ?>,1);">›</a>
						<a class="<?php echo $last; ?>" href="javascript:get_page(<?php echo $wpdevart_page; ?>,2);">»</a>
					</span>
				<?php } ?>
			</div>
	    </div >
		<input type="hidden" class="wpdevart_page" name="wpdevart_page" value="<?php echo (isset($_POST['wpdevart_page']))? esc_js($_POST['wpdevart_page']): 1; ?>"  />
		<?php
	
	}

 
 
}
?>