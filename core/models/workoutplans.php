<?php

class SdsWorkoutplans {

	function __construct($db)
	{

		$this->db = $db;
		//$this->companies = $companies;

	}
	
	/*
	 *
	 * 	Purpose: Gets workouts by user
	 *
	 */
	function byUserID($user_id) 
	{
		/*
		$results =  $this->db->wpdb->get_results(
		"SELECT * FROM " . $this->db->tables['companies'] .
		" JOIN " .$this->db->tables['users'] . " ON " . $this->db->tables['users'] . ".id = ".$this->db->tables['companies'].".user_id".
		" WHERE status='" . $status . "'".
		" AND ".$this->db->tables['users'].".id = '".$user_id."'");
		*/
	
		$query =  $this->db->wpdb->get_results("
		select distinct
	    wkr_entry.member_id,
	    wkr_entry.workout_sheet_id,
	    sub.user_nicename,
	    wrk_sheet.sheet_name,
	    wrk_sheet.workout_url
	    from
	    gbf_workout_entry as wkr_entry
	    join wp_users as sub on wkr_entry.member_id = sub.id
	    join gbf_workout_sheet as wrk_sheet on wrk_sheet.id = wkr_entry.workout_sheet_id
	    where member_id = '".$user_id."'");
		
		return $query;
		//return $this->db->wpdb->get_results( $query );
	}
	
	/*
	 *
	 * 	Purpose: Gets workouts by user
	 *
	 */
	function byWorkoutSheet() 
	{
	
		$query =  $this->db->wpdb->get_results("
		select
	    id,
    	sheet_name
    	from gbf_workout_sheet");
		return $query;
	}
	

}
$SdsWorkoutplans = new SdsWorkoutplans($SdsDb);