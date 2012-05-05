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
		//add_action("gform_post_submission", array(get_class(), 'push'), 10, 2);
		add_action("gform_after_submission", array(get_class(), 'push'), 10, 2);
	}
	
	
	/*
	 * Receive the submitted form data
	 */
	static function push($entry, $form){
		
		/*
		var_dump($entry);
		echo '<hr/>';
		var_dump($form);
		exit;
		
		$lead_id = $entry['id'];
		$form_id = $entry['form_id'];
		*/
		if(!$form['customcrm_enabled']) return;		
		//include dirname(__FILE__) . '/includes/output-table.php';
		include dirname(__FILE__) . '/includes/lead.xml.php';
		
				
		exit;
	}
	
	
	/*
	 * xml data put to the remote CRM
	 * returns the response xml
	 */
	static function xml_put($xml){
		$url = GravityFormCustomCRM :: get_crm_url();
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));       
		$result = curl_exec($ch); 
		curl_close($ch);
		
		return self::result_parse($result);
	}
	
	/*
	 * parsing the reslultant xml
	 */
	static function result_parse($str){
		$xml = @simplexml_load_string($str);
		if(!$xml) return 2;
		return ($xml->result == 'OK') ? 1 : 2;
	}
}