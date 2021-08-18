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
	 * 	Purpose: Insert a workout set
	 *
	 */
	function get_personal_details($user_id)
	{
		$query =  $this->db->wpdb->get_results("
		SELECT * FROM gbf_workout_user where wp_user_id = '".$user_id."'");
		return $query;
		
		//$this->db->wpdb->show_errors();
		//var_dump($wpdb->last_query);
	}
	
	
	/*
 	 *
	 * 	Purpose: Insert a workout set
	 *
	 */
	function insert_workout_user($data)
	{
		//print_r($data);
		$this->db->wpdb->insert($this->db->tables['gbf_workout_user'], $data);
		$this->db->wpdb->show_errors();
		//var_dump($wpdb->last_query);
	}
	
	function update_workout_user($data, $wp_user_id)
	{
		//$result['weight'] = $data['weight'];
		//$result['salutation']	= $data['salutation'];
		
		$this->db->wpdb->update($this->db->tables['gbf_workout_user'], $result, array('wp_user_id'=>$wp_user_id));
		$this->db->wpdb->show_errors();
	}
	
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
	function get_subscriber_activity() 
	{
	
		$query =  $this->db->wpdb->get_results("
		SELECT
		gbf_workout_set.set_id,
		gbf_workout_set.set_name,
		gbf_workout_set.workout_sheet_id as 'Wrk_sheet_Id',
		wrk_sheet.sheet_name,
		gbf_workout_entry.weight,
		gbf_workout_entry.user_id,
		sub.user_nicename as 'User_name',
		sub.display_name,
		gbf_workout_entry.date_updated,
		gbf_workout_entry.date_completed
		
		from gbf_workout_set
		join gbf_workout_entry ON gbf_workout_set.set_id = gbf_workout_entry.set_id
		join gbf_workout_sheet as wrk_sheet on wrk_sheet.id = gbf_workout_entry.workout_sheet_id
		left join wp_users as sub on gbf_workout_entry.user_id = sub.id");
		return $query;
	}
	

}
$SdsUsers = new SdsUsers($SdsDb);