<?php

class SdsWorkshop {

	function __construct($db){

		$this->db = $db;

	}

	/*
	 *
	 * 	Purpose: Gets all leads based on status
	 * 	Added in Version 1.0.5
	 *
	 */
	function get($status = null) 
	{

		if ( $status == null) {
			$query = "SELECT * FROM ".$this->db->tables['deductions'];
		} else {
			$query = "SELECT * FROM ".$this->db->tables['deductions'] ." WHERE status='".$status."'";
		}

		return $this->db->wpdb->get_results( $query );

	}
	
	/*
	 *
	 * 	Purpose: Gets income by the user id
	 * 	Added in Version 1.0.5
	 *
	 */
	function byUserID($user_id, $status) 
	{
		$results =  $this->db->wpdb->get_results(	"SELECT * FROM " . $this->db->tables['deductions'] .
													" JOIN " .$this->db->tables['users'] . " ON " . $this->db->tables['users'] . ".id = ".$this->db->tables['deductions'].".user_id".
													" WHERE status='" . $status . "'".
													" AND ".$this->db->tables['users'].".id = '".$user_id."'");

		return $results[0];
	}
	
	
	function byHash($user_id, $hash) 
		{
			$results =  $this->db->wpdb->get_results(	"SELECT * FROM " . $this->db->tables['deductions'] .
														" JOIN " .$this->db->tables['users'] . " ON " . $this->db->tables['users'] . ".id = ".$this->db->tables['deductions'].".user_id".
														" WHERE hash='" . $hash . "'".
														" AND ".$this->db->tables['users'].".id = '".$user_id."'");
	
			return $results[0];
		}


	function byHashAdmin($hash) 
	{
		//$results =  $this->db->wpdb->get_results(	"SELECT * FROM " . $this->db->tables['deductions'] . " WHERE hash='" . $hash . "'");
		//return $results[0];
		
		/*
		$sql = "SELECT * FROM " . $this->db->tables['deductions'].
		" JOIN " . $this->db->tables['users']  . " ON " .$this->db->tables['users']. ".id = " .$this->db->tables['deductions']. ".user_id".
		" WHERE ". $this->db->tables['deductions']." .hash= '" . $hash . "'";			
								
		return $this->db->wpdb->get_results[0] ( $sql );
		*/
		
		$results =  $this->db->wpdb->get_results(
		"SELECT * FROM " . $this->db->tables['deductions'].
		" JOIN " . $this->db->tables['users']  . " ON " .$this->db->tables['users']. ".id = " .$this->db->tables['deductions']. ".user_id".
		" WHERE ". $this->db->tables['deductions']." .hash= '" . $hash . "'");
		
		return $results[0];
		
		
	}


	/*
	 *
	 * 	Purpose: Gets a lead by the company id
	 * 	Added in Version 1.0.5
	 *
	 */
	function getByCompanyID($id)
	{

		if ( strlen($id) ==0 ) {
			return array();
		}

		$deductions_results = $this->db->wpdb->get_results(
		"SELECT * FROM " . $this->db->tables['deductions'] ." WHERE company_id = ".$id."");
		return $deductions_results;

	}

	/*
	 *
	 * 	Purpose: Gets a lead by its hash
	 * 	Added in Version 1.0.5
	 *
	 */
	function getByHash($hash)
	{
		$results = $this->db->wpdb->get_results( "	SELECT * FROM ".$this->db->tables['leads']. " WHERE hash='".$hash."'" );
		return $results[0];
	}

	/*
	 *
	 * 	Purpose: Stores a lead
	 * 	Added in Version 1.0.5
	 *
	 */
	function insert($data) {

		return $this->db->wpdb->insert($this->db->tables['deductions'], $data);
							
	}

	/*
	 *
	 * 	Purpose: Updates a lead
	 * 	Added in Version 1.1.0
	 *
	 */
	 
	function update($data, $hash){
	
		$this->db->wpdb->show_errors();
		
		$result['ded_veh_wk_purposes_q']=$data['ded_veh_wk_purposes_q'];
		$result['ded_veh_wk_make']=$data['ded_veh_wk_make'];
		$result['ded_veh_wk_model']=$data['ded_veh_wk_model'];
		$result['ded_veh_wk_engine_size']=$data['ded_veh_wk_engine_size'];
		$result['ded_veh_wk_opening_mileage']=$data['ded_veh_wk_opening_mileage'];
		$result['ded_veh_wk_closing_mileage']=$data['ded_veh_wk_closing_mileage'];
		$result['ded_veh_wk_purchase_price']=$data['ded_veh_wk_purchase_price'];
		$result['ded_veh_wk_purchased_date']=$data['ded_veh_wk_purchased_date'];
		$result['ded_veh_wk_kilo_driven']=$data['ded_veh_wk_kilo_driven'];
		$result['ded_veh_expense']=$data['ded_veh_expense'];
		$result['ded_veh_lease_interest']=$data['ded_veh_lease_interest'];
		$result['ded_veh_rego']=$data['ded_veh_rego'];
		$result['ded_veh_insurance']=$data['ded_veh_insurance'];
		$result['ded_veh_services']=$data['ded_veh_services'];
		$result['ded_veh_tyres_batteries']=$data['ded_veh_tyres_batteries'];
		$result['ded_veh_repairs_maint']=$data['ded_veh_repairs_maint'];
		$result['ded_veh_car_washes']=$data['ded_veh_car_washes'];		
		
		$result['ded_veh_parking_other_q']=$data['ded_veh_parking_other_q'];
		$result['ded_veh_parking_other_c']=$data['ded_veh_parking_other_c'];
		$result['ded_veh_taxi_fares_q']=$data['ded_veh_taxi_fares_q'];
		$result['ded_veh_taxi_fares_c']=$data['ded_veh_taxi_fares_c'];
		$result['ded_veh_travel_for_work_q']=$data['ded_veh_travel_for_work_q'];
		$result['ded_veh_travel_for_work_c']=$data['ded_veh_travel_for_work_c'];
		$result['ded_veh_ppe_q']=$data['ded_veh_ppe_q'];
		$result['ded_veh_ppe_c']=$data['ded_veh_ppe_c'];
		$result['ded_slf_ed_q']=$data['ded_slf_ed_q'];
		$result['ded_slf_ed_c']=$data['ded_slf_ed_c'];
		$result['ded_othr_wk_exp_q']=$data['ded_othr_wk_exp_q'];
		$result['ded_othr_wk_exp_c']=$data['ded_othr_wk_exp_c'];
		$result['ded_low_val_pool_prv_yr_q']=$data['ded_low_val_pool_prv_yr_q'];
		$result['ded_low_val_pool_prv_yr_c']=$data['ded_low_val_pool_prv_yr_c'];
		$result['ded_exp_ern_int_or_divd_q']=$data['ded_exp_ern_int_or_divd_q'];
		$result['ded_exp_ern_int_or_divd_c']=$data['ded_exp_ern_int_or_divd_c'];
		$result['ded_chrty_gfts_donat_q']=$data['ded_chrty_gfts_donat_q'];
		$result['ded_chrty_gfts_donat_c']=$data['ded_chrty_gfts_donat_c'];
		$result['ded_lst_yr_rt_tx_agt_q']=$data['ded_lst_yr_rt_tx_agt_q'];
		$result['ded_lst_yr_rt_tx_agt_c']=$data['ded_lst_yr_rt_tx_agt_c'];
		$result['ded_prsn_spr_cont_q']=$data['ded_prsn_spr_cont_q'];
		$result['ded_prsn_spr_cont_c']=$data['ded_prsn_spr_cont_c'];
		$result['ded_prsn_othr_ded_q']=$data['ded_prsn_othr_ded_q'];
		$result['ded_prsn_othr_ded_c']=$data['ded_prsn_othr_ded_c'];
		
		//$result['ded_misc_other']=$data['ded_misc_other'];
		//$result['status']=$data['status'];
		//$result['created_at']=$data['created_at'];
		//$result['updated_at']=$data['updated_at'];

		//return $this->db->wpdb->update($this->db->tables['deuctions'], $data, array('hash'=>$data['hash']));
		
		$result['updated_at']= date('Y-m-d H:i:s');		
		//unset($result['Update']);	
		$this->db->wpdb->update($this->db->tables['deductions'], $result, array('hash'=>$hash));

	}
	
	/*
		 *
		 * 	Purpose: Update income files 	
		 *
		 */
		function updateFile($files, $hash, $plugin_dir, $username ) {
			
			$result = array();
				
			$client_docs_user_dir = $plugin_dir . 'client-docs/' . $username;
			
			//Create upload directory for user		
			if ( !file_exists($client_docs_user_dir ) ) {				
				mkdir($client_docs_user_dir, 0777, true);				
			}
			//Create deductions folder
			if ( !file_exists( $client_docs_user_dir . '/deductions' ) ) {				
				mkdir($client_docs_user_dir . '/deductions', 0777, true);						
			}
			
			$upload_dir = $plugin_dir . 'client-docs/'. $username .'/deductions/';
			
			$filenames = array(			
			'ded_veh_parking_other_docs',
			'ded_veh_taxi_fares_docs',
			'ded_veh_travel_for_work_docs',
			'ded_veh_ppe_docs',
			'ded_slf_ed_docs',
			'ded_othr_wk_exp_docs',
			'ded_low_val_pool_prv_yr_docs',
			'ded_exp_ern_int_or_divd_docs',
			'ded_chrty_gfts_donat_docs',
			'ded_lst_yr_rt_tx_agt_docs',
			'ded_prsn_spr_cont_docs',
			'ded_prsn_othr_ded_docs'
			);			
			
			for( $i=0; $i < count($filenames); $i++ ) {	
						
				$file_key = $filenames[$i];
				
				$file_arr_name = $files[$file_key]['name'];
				$file_arr_temp_name = $files[$file_key]['tmp_name'];
				$file_count = count($files[$file_key]['name']);		
					
				//echo $file_arr_name[0] . '<br>';
				
				//checks if has file uploaded then move flle
				if( $file_arr_name[0] ) {
					
					//check if there is a saved files in the database, if yes add the name with comma separated
					if( $deductiondata->$filenames[$i] != '' ) {
						$result[$file_key] = $deductiondata->$file_key . ',';
					} else {
						$result[$file_key] = '';
					}				
					
					for($j=0; $j < $file_count; $j++ ) {
						
						$file = $upload_dir . basename( $file_arr_name[$j]);					
						move_uploaded_file($file_arr_temp_name[$j], $file );
						
						$result[$file_key] .= $file_arr_name[$j];
						
						if( $j+1 != $file_count ) {
							$result[$file_key] .= ",";
						}
						
					}				
					
				}
				
			}
			
		//update filename to the database
		if( $result ) {
			$this->db->wpdb->update($this->db->tables['deductions'], $result, array('hash'=>$hash));		
		}	
	}
		

	/*
	 *
	 * 	Purpose: Gets availability of lead
	 * 	Added in Version 1.0.5
	 *
	 */
	function available($purchased_leads)
	{

		return $this->db->wpdb->get_results(	" SELECT * FROM ".$this->db->tables['leads'].
												" WHERE id NOT IN (".$purchased_leads.")".
												" AND status='approved'" );

	}

	/*
	 *
	 * 	Purpose: Sets status to approved
	 * 	Added in Version 1.0.5
	 *
	 */
	function approve($hash) {
		return $this->updateStatus('approved', $hash);
	}

	/*
	 *
	 * 	Purpose: Sets status to rejected
	 * 	Added in Version 1.0.5
	 *
	 */
	function reject($hash) {
		return $this->updateStatus('rejected', $hash);
	}

	/*
	 *
	 * 	Purpose: Deletes a lead
	 * 	Added in Version 1.0.5
	 *
	 */
	function delete($hash){
		return $this->db->wpdb->query( " DELETE FROM ".$this->db->tables['leads']." WHERE hash = '".$hash."'");
	}

	/*
	 *
	 * 	Purpose: Changes status of lead
	 * 	Added in Version 1.0.5
	 *
	 */
	private function updateStatus($status, $hash)
	{
		return $this->db->wpdb->query( " UPDATE ".$this->db->tables['leads']." SET status = '".$status."' WHERE hash = '".$hash."'");
	}
	
	
	function getLastId() {
	
			return $this->db->wpdb->insert_id;
			//$lastid = $wpdb->insert_id;
	
		}

		

}

$SdsWorkshop = new SdsWorkshop($SdsDb);