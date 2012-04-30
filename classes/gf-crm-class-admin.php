<?php

/*
 * this class handles all the things to the admin panel
 */

if(class_exists('GravityFormCustomCRM')) return;

class GravityFormCustomCRM{
	
	//tolltips
	public static $gftooltips_default = array(
		'client_fname' => array('Client First Name', 'Client First Name'),
		'client_lname' => array('Client Last Name', 'Client Last Name'),
		'client_addr1' => array('Client Address Line 1', 'Client Address Line 1'),
		'client_addr2' => array('Client Address Line 2', 'Client Address Line 2'),
		'client_zip' => array('Client Zip', 'Client Zip'),
		'client_city' => array('Client City', 'Client City'),
		'client_town' => array('Client Town', 'Client town'),
		'client_telephone' => array('Client Telephone', 'Client Telephone'),
		'client_email' => array('Client Email', 'Client Email address'),
		'client_state' => array('Client State', 'Client State/Province/Region'),
		'flight_location' => array('Flight Location', 'Flight Location'),
		'flight_date' => array('Flight Date', 'Flight Date'),
		'flight_length' => array('Flight Length', 'Flight Length'),
		'flight_video_enable' => array('Video Enable', 'Flight Video Enable'),
		'person_who_fly_fname' => array('First Name(who fly)', 'Person who Fly'),
		'person_who_fly_lname' => array('Last Name(who fly)', 'Person who Fly'),
		'person_who_fly_body_size' => array('Body Size(who fly)', 'Body size in CM')
		
	);
	public static $gftooltips_hidden = array(
		'client_state_after_submit' => array('Client State After Submit', 'Client State After Submit'),
		'client_email_follow' => array('Email Follow', 'Trigger Follow Email')
	);
	
	
	
	/*
	 * contains necessary hooks
	 */
	static function init(){
		
		//adding new fileds in advanced setting section
		add_action('gform_advanced_settings', array(get_class(), 'gform_advanced_settings'));
		
		//add extra tool tips
		add_filter('gform_tooltips', array(get_class(), 'gform_tooltips'));
		
	}
	
	/*
	 *Add extra tooltips to show for this addon 
	 */
	static function gform_tooltips($gf_tooltips){
		
		$gf_tooltips["customcrm_enabled"] = "<h6>".__("Integrate form with CustomCRM")."</h6>".__("Tick this box to integrate this form with CustomCRM. When this form is submitted successfulling the data will be added to customCRM.");
		
		foreach(self::$gftooltips_default as $key=>$value){		
			$gf_tooltips['customcrm_'.$key] = '<h6>' . __($value[0]) . '</h6>' . __($value[1]);
		}
		
		foreach(self::$gftooltips_hidden as $key=>$value){		
			$gf_tooltips['customcrm_'.$key] = '<h6>' . __($value[0]) . '</h6>' . __($value[1]);
		}
		
		return $gf_tooltips;
	}
	
		
	
	/*
	 * adding new settings fields with the Form in admin panel
	 */
	static function gform_advanced_settings($position, $form_id = ''){
		if($position != 800) return;
		if(isset($form_id) != $_GET['id']) return;
		
		
		 echo '<li><input type="checkbox" onclick="ToggleCustomCRM();" id="gform_customcrm" /> ';
		 echo '<label for="gform_enable_customcrm" id="gform_enable_customcrm_label">';
		 _e("Enable CustomCRM integration ");
		 
		gform_tooltip("customcrm_enable");
		
		 echo '</label></li>';
		 echo '<li id="gform_customcrm_container" style="display:none">';
			self::gfcustomcrm_form_options($_GET['id']);
		echo '</li>';
		
		?>

		<script>
			function ToggleCustomCRM(isInit)
			{
				var speed = isInit ? "" : "slow";
				if(jQuery("#gform_customcrm").is(":checked")) 
					jQuery("#gform_customcrm_container").show(speed);		
				else
					jQuery("#gform_customcrm_container").hide(speed);
					form.customcrm_enabled = jQuery("#gform_customcrm").is(":checked");
			}
			
			function ChangeCustomCRMfield(field_name) 
			{
				//alert(jQuery("#"+field_name).val());
				eval('form.'+field_name+' = jQuery("#"+field_name).val();');
				//alert(form.customcrm_person_email);
			}
			jQuery("#gform_customcrm").attr("checked", form.customcrm_enabled ? true : false);
			ToggleCustomCRM(true);
			
		</script>
		
		<?php
	}
	
	/*
	 * Custom Form Fields
	 */
	static function gfcustomcrm_form_options($form_id){
		// load the form for the field merge tag generators
		 $form = RGFormsModel::get_form_meta($form_id);
		 include dirname(__FILE__) . '/includes/crm-form-options.php';
	}
	
	/*
	 * Selector fields
	 */
	public static function get_field_selector($form_id, $field_name, $selected_field = null) {
		$form_fields = self::get_form_fields($form_id);
		$str = '<select id="'.$field_name.'" size="1" onchange=\'ChangeCustomCRMfield("'.$field_name.'");\'>';
		$str .= '<option value="">Choose</option>'."\n";
		foreach($form_fields as $_field) 
		{
			$str .= '<option value="'.$_field[0].'"';
			if($selected_field && $_field[0] == $selected_field) $str .= ' selected';
			$str .= '>'.$_field[1].'</option>'."\n";
		}
		$str .= '</select>'."\n";
		$str .= '<script> jQuery("#'.$field_name.'").val( form.'.$field_name.'); </script>'."\n";
		return $str;
	}
	
	/*
	 * statif cuntions to return fields
	 */
	public static function get_form_fields($form_id){
		$form = RGFormsModel::get_form_meta($form_id);
		$fields = array();
		
		if(is_array($form["fields"])){
			foreach($form["fields"] as $field){
				if(is_array(rgar($field, "inputs"))){					
					
					foreach($field["inputs"] as $input)
						$fields[] =  array($input["id"], GFCommon::get_label($field, $input["id"]));
				}
				else if(!rgar($field,"displayOnly")){
					$fields[] =  array($field["id"], GFCommon::get_label($field));
				}
			}
		}
		return $fields;
    }

}