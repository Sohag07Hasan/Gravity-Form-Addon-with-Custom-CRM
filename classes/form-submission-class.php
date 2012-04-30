<?php

/*
 * This class handles the front end form submissin
 */

class Form_submission_To_CRM{
	
	/*
	 * contains the hoook
	 */
	public static function init(){
		//if the form is submitted
		add_action("gform_post_submission", array(get_class(), 'push'), 10, 2);
	}
	
	
	/*
	 * Receive the submitted form data
	 */
	static function push($entry, $form){
				
		if(!$form['customcrm_enabled']) return;		
		include dirname(__FILE__) . '/includes/output-table.php';	
		
		exit;
	}
}