<?php

/*
 * This class does all the offline implementation
 * gets form meta data and lead entries of the failed submission to CRM
 * uses some functions of form submission class to create xml, put xml to the remote server.
 * remove an action that inserts lead id and it's status
 * add an action to updates the lead id and it's status
 */

Class Offline_CRM{
	
	/*
	 * contain the hooks
	 */
	static function init(){
		//binding the cron job
		add_action('update_failed_deal_once_daily', array(get_class(), 'process_schudle'));
				
		//creating table to store information if the crm is down
		register_activation_hook(CRMGRAVITYFILE, array(get_class(), 'create_offline_table'));
		
		//making a cron job
		register_activation_hook(CRMGRAVITYFILE, array(get_class(), 'run_a_schudle'));
		register_deactivation_hook(CRMGRAVITYFILE, array(get_class(), 'stop_a_schudle'));
		
		//cron hooks
		do_action('update_failed_deal_once_daily');
	}
	
	/*
	 * stops the scheduling jog
	 */
	static function stop_a_schudle(){
		wp_clear_scheduled_hook('update_failed_deal_once_daily');
	}
	
	
	
	/*
	 * main function to process the schulde
	 */
	static function process_schudle(){
		return self::process_offline_leads();
	}
	
	
	
	/*
	 * makes a cron functionalitly
	 *  */
	static function run_a_schudle(){
		wp_schedule_event( current_time(time()), 'daily', 'update_failed_deal_once_daily');
	}




	/*
	 * tracing table
	 */
	static function create_offline_table(){
		$table = self::get_offline_table();
		$sql = "CREATE TABLE IF NOT EXISTS $table(
			`id` bigint unsigned NOT NULL AUTO_INCREMENT,			
			`lead_id` bigint unsigned NOT NULL,	
			`crm_status` tinyint DEFAULT 1,
			PRIMARY KEY(id),
			UNIQUE(lead_id)	 
		)";
		
		if(!function_exists('dbDelta')) :
			include ABSPATH . 'wp-admin/includes/upgrade.php';
		endif;
		dbDelta($sql);
	}
	
	/*
	 * return the tracing table
	 */
	static function get_offline_table(){
		global $wpdb;
		return $wpdb->prefix . 'rg_crm_offline'; 
	}
	
	
	/*
	 * Finds a failed leads
	 */
	static function get_failed_leads(){
		$table = self::get_offline_table();
		global $wpdb;
		return $wpdb->get_col("SELECT `lead_id` FROM $table WHERE crm_status = 2");
	}
	
	
	/*
	 * processing the offline data
	 * removes action xml_pushed_to_crm 
	 * add new action to update the database
	 */
	static function process_offline_leads(){
		$offline_leads = self::get_failed_leads();
		if(empty($offline_leads)) return;
		
		//remove and create new actions
		remove_action('xml_pushed_to_crm', array('Form_submission_To_CRM', 'tracing_crm_data'));
		//new action
		add_action('xml_pushed_to_crm', array(get_class(), 'update_tracing_data'));
		
		foreach($offline_leads as $lead){
			$entry = RGFormsModel::get_lead($lead);
			$form = RGFormsModel::get_form_meta($entry['form_id']);
			Form_submission_To_CRM :: push($entry, $form);
		}
	}
	
	
	/*
	 * updates tracing tabel data while a offline xml data is pushed to the CRM
	 */
	static function update_tracing_data($lead_id, $status){
		$table = self::get_offline_table();
		global $wpdb;
		$wpdb->update($table, array('crm_status'=>(int)$status), array('lead_id'=>(int)$lead_id), array('%d'), array('%d'));
		
	}
	
	
	
}