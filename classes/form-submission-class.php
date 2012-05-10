<?php

/*
 * This class handles the front end form submissin
 * creteas xml
 * put xml to the remote site
 * parse returned xml and fires an action
 * the action is used to update the tracing table
 */

class Form_submission_To_CRM{
	
	/*
	 * contains the hoook
	 */
	public static function init(){
		//if the form is submitted
		//add_action("gform_post_submission", array(get_class(), 'push'), 10, 2);
		add_action("gform_after_submission", array(get_class(), 'push'), 10, 2);
		add_action('xml_pushed_to_crm', array(get_class(), 'tracing_crm_data'), 10, 2);
	}
	
	/**
	 * tracomg crm data
	 */
	static function tracing_crm_data($lead_id, $status){
		$table = Offline_CRM::get_offline_table();
		global $wpdb;
		$wpdb->insert($table, array('lead_id'=>(int)$lead_id, 'crm_status'=>(int)$status), array('%d', '%d'));
	}






	/*
	 * Receive the submitted form data
	 */
	static function push($entry, $form){
		
		
		$lead_id = $entry['id'];
				
		if(!$form['customcrm_enabled']) return;		
		//include dirname(__FILE__) . '/includes/output-table.php';
		include dirname(__FILE__) . '/includes/lead.xml.php';
		
		$status = self::xml_put($xml);
		
		//action hooks fires with status from the crm
		do_action('xml_pushed_to_crm', $lead_id, $status);
	}
	
	
	/*
	 * xml data put to the remote CRM
	 * returns the response xml
	 * makes put request
	 */
	static function xml_put($xml){
		$url = GravityFormCustomCRM :: get_crm_url();
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		
		if(GravityFormCustomCRM :: ssl_enabled()){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));       
		$result = curl_exec($ch); 
		curl_close($ch);
		
		return self::parse_returned_xml($result);
	}
	
	/*
	 * parsing the reslultant xml
	 */
	static function parse_returned_xml($str=''){
		$xml = @simplexml_load_string($str);
		if(!$xml) return 2;
		return (strtolower($xml->result) == 'ok') ? 1 : 2;
	}
}