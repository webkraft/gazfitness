<?php

class SdsUsers {

	function __construct($db)
	{

		$this->db = $db;
	}
	
	//$result['updated_at']= date('Y-m-d H:i:s');
	//$this->db->wpdb->show_errors();
	
	
	/*
	 *
	 * 	Purpose: Get users from wp_users
	 *
	 */
	function get_subscribers() 
	{
	
		$query =  $this->db->wpdb->get_results("
		SELECT * FROM wp_users 
		INNER JOIN wp_usermeta 
		ON (wp_users.ID = wp_usermeta.user_id) 
		WHERE 1=1
		AND ( (wp_usermeta.meta_key = 'wp_capabilities' AND CAST(wp_usermeta.meta_value AS CHAR) LIKE '%\"subscriber\"%') ) 
		ORDER BY user_registered ASC;");
		return $query;
	}
	
	
	/*
	 *
	 * 	Purpose: Get the user activity
	 *
	 */	
	function get_subscriber_activity($user_id) 
	{
	
		$query =  $this->db->wpdb->get_results("
		select
	    wkr_entry.member_id,
	    wkr_entry.workout_sheet_id,
	    wkr_entry.weight,
	    sub.user_nicename,
	    wrk_sheet.sheet_name,
	    wrk_sheet.workout_url,
	    wrk_set.set_id,
	    wrk_set.set_name,
	    wkr_entry.complete_date
	    from
	    gbf_workout_entry as wkr_entry
	    join wp_users as sub on wkr_entry.member_id = sub.id
	    join gbf_workout_sheet as wrk_sheet on wrk_sheet.id = wkr_entry.workout_sheet_id
	    join gbf_workout_set as wrk_set on wrk_set.set_id = wkr_entry.set_id
	    where member_id = ".$user_id."");
		return $query;
	}
	

}
$SdsUsers = new SdsUsers($SdsDb);