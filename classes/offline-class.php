<?php

/*
 * This class does all the offline implementation
 */

Class Offline_CRM{
	
	/*
	 * contain the hooks
	 */
	static function init(){
		//creating table to store information if the crm is down
		register_activation_hook(CRMGRAVITYFILE, array(get_class(), 'create_offline_table'));
	}
	
	/*
	 * tracing table
	 */
	static function create_offline_table(){
		$table = self::get_offline_table();
		$sql = "CREATE TABLE IF NOT EXISTS $table(
			`id` bigint unsigned NOT NULL AUTO_INCREMENT,
			`form_id` bigint unsigned NOT NULL,
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
	
	
	
}