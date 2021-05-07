<?php

class SdsUsers {

	function __construct($db, $companies)
	{

		$this->db = $db;
		$this->companies = $companies;
		//$this->leads = $leads;

	}

	/*
	 *
	 * 	Purpose: Deletes a user
	 * 	Added in Version 1.0.5
	 *
	 */
	function destroy($user_id)
	{

		// Get the associated company id
		$company_results = $this->companies->byUserID($user_id, 'approved');
		$company = $company_results;

		// Step 1. Remove the company / leads intersect
		$this->db->wpdb->query( "DELETE FROM ".$this->db->tables['companies_leads']." WHERE company_id = '".$company->id."'");

		// step 2. delete the company
		$this->db->wpdb->query( " DELETE FROM ".$this->db->tables['companies']." WHERE id = '".$company->id."'");

		// step 3. delete the user
		$this->db->wpdb->query( " DELETE FROM ".$this->db->tables['users']." WHERE ID = '".$user_id."'");

	}

	/*
	 *
	 * 	Purpose: Updates a user
	 * 	Added in Version 1.0.5
	 *
	 */	
	
	function update($data, $user_id)	{

		$result['company_name'] 			= $data['company_name'];
		$result['salutation'] 			= $data['salutation'];
		$result['first_name'] 				= $data['first_name'];
		$result['last_name'] 				= $data['last_name'];
		$result['other_name'] 				= $data['other_name'];
		$result['address1'] 						= $data['address1'];
		$result['address2'] 						= $data['address2'];
		$result['city'] 							= $data['city'];
		$result['state'] 						= $data['state'];
		
		$result['post_code'] 					= $data['post_code'];
		$result['day_phone'] 					= $data['day_phone'];
		$result['mobile_phone'] 			= $data['mobile_phone'];
		$result['email_address'] 			= $data['email_address'];		
		$result['date_of_birth']			= $data['date_of_birth'];		
		
		$result['abn_no'] 						= $data['abn_no'];
			
		$result['updated_at']= date('Y-m-d H:i:s');			
		
		//unset($result['Update']);
		$this->db->wpdb->update($this->db->tables['companies'], $result, array('user_id'=>$user_id));
		$this->db->wpdb->show_errors();

	}
		
	/*
	 *
	 * 	Purpose: Update income files 	
	 *
	 */
	function updateFile($files, $user_id, $plugin_dir, $username ) {
		
		$result = array();
					
		$client_docs_user_dir = $plugin_dir . 'client-docs/' . $username;
		
		//Create upload directory for user		
		if ( !file_exists($client_docs_user_dir ) ) {				
			mkdir($client_docs_user_dir, 0777, true);				
		}
		//Create income folder
		if ( !file_exists( $client_docs_user_dir . '/personalprofile' ) ) {				
			mkdir($client_docs_user_dir . '/personalprofile', 0777, true);						
		}
		
		$upload_dir = $plugin_dir . 'client-docs/'. $username .'/personalprofile/';
		
		$filenames = array(
			'private_hlth_insurnce_docs'
		);			
		
		//'private_hlth_insurnce_docs_2'
		
		for( $i=0; $i < count($filenames); $i++ ) {	
			
			$file_key = $filenames[$i];
			
			$file_arr_name = $files[$file_key]['name'];
			$file_arr_temp_name = $files[$file_key]['tmp_name'];
			$file_count = count($files[$file_key]['name']);		
				
			//echo $file_arr_name[0] . '<br>';
			
			//checks if has file uploaded then move flle
			if( $file_arr_name[0] ) {
				
				//check if there is a saved files in the database, if yes add the name with comma separated
				if( $company->$filenames[$i] != '' ) {
					$result[$file_key] = $company->$file_key . ',';
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
			//echo $result;
			$this->db->wpdb->update($this->db->tables['companies'], $result, array('user_id'=>$user_id));		
			$this->db->wpdb->show_errors();
		}
	}

}
$SdsUsers = new SdsUsers($SdsDb, $SdsCompanies);