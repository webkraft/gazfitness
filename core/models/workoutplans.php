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
	    wkr_entry.user_id,
	    wkr_entry.workout_sheet_id,
	    sub.user_nicename,
	    wrk_sheet.sheet_name,
	    wrk_sheet.workout_url
	    from
	    gbf_workout_entry as wkr_entry
	    join wp_users as sub on wkr_entry.user_id = sub.id
	    join gbf_workout_sheet as wrk_sheet on wrk_sheet.id = wkr_entry.workout_sheet_id
	    where user_id = '".$user_id."'");
		
		return $query;
		//return $this->db->wpdb->get_results( $query );
	}
	
	/*
	 *
	 * 	Purpose: Gets workouts sheets
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
	
	/*
	 *
	 * 	Purpose: Gets workout body areas
	 *
	 */
	function byWorkoutBodyArea($sheet_id)
	{
	
		$query =  $this->db->wpdb->get_results("
		SELECT DISTINCT
		gbf_workout_set.body_area
		from gbf_workout_set
		where gbf_workout_set.workout_sheet_id = '".$sheet_id."'");
		return $query;
	}
	
	
	/*
	 *
	 * 	Purpose: Gets workouts all data
	 *
	 */
	function get_Workoutsheets($workout_sheet_id) 
	{
	
		$query =  $this->db->wpdb->get_results("
		select 
        wrk_sheet.id,
        wrk_sheet.sheet_name,
        wrk_set.workout_sheet_id,
        wrk_set.body_area,
        wrk_set.set_name,
        wrk_set.video_link,
        wrk_set.set_id,
        wrk_set.sets_number,
        wrk_set.reps,
        wrk_set.tempo,
        wrk_set.rest,
        wrk_set.notes
        from 
        gbf_workout_sheet as wrk_sheet,
        gbf_workout_set as wrk_set
        where
        wrk_sheet.id = wrk_set.workout_sheet_id and wrk_sheet.id = ".$workout_sheet_id."");
		return $query;
	}
	
	
	/*
	 *
	 * 	------- Purpose: Get user weight entries by workout set 
	 *
	 */
	function get_UserWeightsByWorkoutSet($user_id, $set_id, $workout_sheet_id) 
	{
	
		$query =  $this->db->wpdb->get_results("
		SELECT
		workout_entry.user_id,
		workout_entry.set_id,
		workout_entry.workout_sheet_id,
		workout_entry.weight,
		workout_set.set_name,
		workout_set.body_area
		
		FROM 
		gbf_workout_entry as workout_entry,
		gbf_workout_set as workout_set
		
		where 
		workout_entry.user_id = ".$user_id."
		and workout_entry.set_id = ".$set_id."
		and workout_entry.workout_sheet_id = ".$workout_sheet_id."
		and workout_set.set_id = ".$set_id."");
		return $query;
	}
	
	
	
	
	/*
	 *
	 * 	Purpose: Gets all workouts
	 *
	 */
	function get_Allworkouts() 
	{
	
		//$query =  $this->db->wpdb->get_results("select * from gbf_workout_set");
		//return $query;
		//var_dump();
		//$this->db->wpdb->show_errors();
		//var_dump($wpdb->last_query);
		
		$sql = "select * from gbf_workout_set";
		return $this->db->wpdb->get_results( $sql );
		
	}
	
	/*
	 *
	 * 	Purpose: Gets workouts by user
	 *
	 */
	function get_Workout_by_id($workout_id) 
	{	
		$query =  $this->db->wpdb->get_results("
		select * from gbf_workout_set where set_id = '".$workout_id."'");
		
		return $query;
	}
	
	/*
	 *
	 * 	Purpose: Gets workouts by user
	 *
	 */
	function get_byBodyAreaName($bodyareaname, $sheet_id)
	{	
		$query =  $this->db->wpdb->get_results("
		SELECT * FROM gbf_workout_set where gbf_workout_set.body_area like '".$bodyareaname."' and gbf_workout_set.workout_sheet_id = ".$sheet_id."");
		
		return $query;
	}
	
	
	/*
 	 *
	 * 	Purpose: Updates a workout row
	 *
	 */
	function update($data, $where)
	{
		$this->db->wpdb->update($this->db->tables['gbf_workout_set'], $data, $where);
		
		$this->db->wpdb->show_errors();
		var_dump($wpdb->last_query);
	}
		
	
	
	/*
 	 *
	 * 	Purpose: Updates a workout set
	 *
	 */
	/*
	function update_workout_set($entry_id, $set_id, $sheet_id, $member_id, $weight)
	{
		//$weight - array		
		$this->db->wpdb->update($this->db->tables['gbf_workout_entry'], $data, $where);
		
		$this->db->wpdb->show_errors();
		var_dump($wpdb->last_query);
	}
	*/
	
	function update_workout_set($data, $entry_id)
	{
	
		$result['weight']	= $data['weight'];
		//$result['salutation']	= $data['salutation'];
		
		$this->db->wpdb->update($this->db->tables['gbf_workout_entry'], $result, array('entry_id'=>$entry_id));
		$this->db->wpdb->show_errors();
	}
	
	
	/*
 	 *
	 * 	Purpose: Insert a workout set
	 *
	 */
	function insert_workout_set($data)
	{
		//print_r($data);
		//$weight - array
		$this->db->wpdb->insert($this->db->tables['gbf_workout_entry'], $data);
		
		$this->db->wpdb->show_errors();
		var_dump($wpdb->last_query);
	}
	
	
	
	/*
 	 *
	 * 	Purpose: Save a workout sets - $data in array order of columns
	 *	
	 */
	function save_WorkoutSetEntries($data)
	{		
		$this->db->wpdb->insert($this->db->tables['gbf_workout_entry'], $data);
		
		//$this->db->wpdb->show_errors();
		//var_dump($wpdb->last_query);
	}	
	
	
	/*
 	 *
	 * 	Purpose: Update a workout sets
	 *	
	 */
	function update_WorkoutSetEntries($data, $where)
	{		
		
		$this->db->wpdb->update($this->db->tables['gbf_workout_entry'], $data, $where);
		
		$this->db->wpdb->show_errors();
		var_dump($wpdb->last_query);
	}
	
	
	/*
	 *
	 * 	Purpose: Gets workout set
	 *
	 */
	function get_WorkoutSetEntries($user_id, $_set_id, $_workoutset_id)
	{	
		$query =  $this->db->wpdb->get_results("
		SELECT * FROM gbf_workout_entry where gbf_workout_entry.user_id = ".$user_id." and gbf_workout_entry.set_id = ".$_set_id." and gbf_workout_entry.workout_sheet_id = ".$_workoutset_id."");
		
		return $query;
		
		$this->db->wpdb->show_errors();
		var_dump($wpdb->last_query);
	}
	

	/*
 	*
	 * 	Purpose: Insert row
	 *
	 */
	function insert($data)
	{
		$this->db->wpdb->insert($this->db->tables['gbf_workout_set'], $data);
	}
	
	/*
	 *
	 * 	Purpose: Deletes a workout row
	 *
	 */
	function delete($id)
	{
		//get the wp user id	
		//$results = $this->db->wpdb->get_results( "SELECT wp_user_id FROM ".$this->db->tables['companies']." WHERE id='".$id."'" );
			
		//$this->db->wpdb->query( " DELETE FROM wp_users WHERE ID = '".$results[0]->wp_user_id."'");
		$this->db->wpdb->query( " DELETE FROM ".$this->db->tables['gbf_workout_set']." WHERE set_id = '".$id."'");
		
		//$this->db->wpdb->show_errors();
		//var_dump($wpdb->last_query);
	}
	

}
$SdsWorkoutplans = new SdsWorkoutplans($SdsDb);