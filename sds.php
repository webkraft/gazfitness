<?php
/*
Plugin Name: GazBFit Workouts
Plugin URI: 
Description: GazBFit Workouts
Author: Scruffy Dog Studio
Version: 1.0
Author URI: https://scruffydogstudio.com.au
License: GPL
Copyright: Phil Noon
Update log: 
27 May 2:22pm
*/

if (!class_exists("sds_gazbfit")) {

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
	    die;
	}

	class sds_gazbfit
	{

		public function __construct()
		{

			// Setup the plugins defaults when activated.
			$this->settings = array(
					'version'			=>	'1.0',
					'path'				=>	plugin_dir_path(__FILE__),
					'url'				=>	plugins_url() . '/gazbfit-workouts',
					'plugin_name' 		=> 'gazbfit-workouts', //folder name
					'update_version' 	=> 110
				);

			include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/db.php';
			$this->db = $SdsDb;
			
			include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/models/workoutplans.php';
			$this->workoutplans = $SdsWorkoutplans;
			
			include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/models/users.php';
			$this->users = $SdsUsers;
			
			// Register the install and uninstall hooks
			register_activation_hook(__FILE__, array($this->db,'install') );
                        
			// Actions
			add_action('init', array($this,'init'),1);
			add_action('init', array($this,'register_shortcode'), 20);
			
			/*
			*
			* Set names for ajax action
			*
			*/
			
        	add_action( 'wp_ajax_get_workout_sheet_names', array( $this, 'get_workout_sheet_names_callback') );
        	
        	//get_workout_user - sets the number of days buttons, diet plans
			add_action( 'wp_ajax_get_workout_user', array( $this, 'get_workout_user_callback') );
        	
        	add_action( 'wp_ajax_get_workout_bodyarea', array( $this, 'get_workout_bodyarea_callback') );
        	add_action( 'wp_ajax_get_bodyarea_workouts', array( $this, 'get_bodyarea_workouts_callback') );
        	
        	//Workout sets
        	add_action( 'wp_ajax_get_workout_set_form', array( $this, 'get_workout_set_form_callback') );
        	add_action( 'wp_ajax_save_workout_set_form', array( $this, 'save_workout_set_form_callback') );
        	//add_action( 'wp_ajax_get_workout_sheets', array( $this, 'get_workout_sheets_callback') );
        	//add_action( 'wp_ajax_nopriv_my_action', array( $this, 'get_workout_sheet_names_callback') );
        	
        	//Save personal details before showing workouts
			add_action( 'wp_ajax_save_personal_details_form', array( $this, 'save_personal_details_form_callback') );
		}
    	
    	/*
		*
		* Enqueue js files ready to be called by ajax
		*
		*/
	    public function enqueue_ajax() {
	        
	        //Get workout sheets - First row of workou links
	        wp_enqueue_script('get_workout_sheet_names', plugins_url('js/subscriber/workout-plans.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_sheet_names', 'get_workout_sheet_names_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        wp_enqueue_script('get_workout_user', plugins_url('js/subscriber/workout-plans.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_user', 'get_workout_user_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        //Get workouts for body area drop downs - depreciated
	        /*wp_enqueue_script('get_workout_sheets', plugins_url('js/subscriber/workout-sheets.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_sheets', 'get_workout_sheets_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        */
	        
	        // --- Get body areas and user weight entries from workouts
	        wp_enqueue_script('get_workout_bodyarea', plugins_url('js/subscriber/workout-bodyareas.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_bodyarea', 'get_workout_bodyarea_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        // --- Get workouts based on body area
	        wp_enqueue_script('get_bodyarea_workouts', plugins_url('js/subscriber/bodyarea-workouts.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_bodyarea_workouts', 'get_bodyarea_workouts_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        // --- Get and set workout set form
	        wp_enqueue_script('get_workout_set_form', plugins_url('js/subscriber/workout-set-form.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_set_form', 'get_workout_set_form_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        // --- Get and set workout set form
	        wp_enqueue_script('save_workout_set_form', plugins_url('js/subscriber/workout-save-set-form.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'save_workout_set_form', 'save_workout_set_form_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        
	        //Save personal details before showing workouts
	        wp_enqueue_script('save_personal_details_form', plugins_url('js/subscriber/personal-details.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'save_personal_details_form', 'save_personal_details_form_file', array(
	            'ajaxurl' => admin_url('admin-ajax.php'),
	            'nonce' => wp_create_nonce('_wpnonce')
	        ));
	        	
	    }
		
		
		/*
		*
		* 	Purpose: Initialises the plugin
		*
		*/
		public function init()
		{
			date_default_timezone_set('Australia/Perth');
			
			//Only load ajax scripts when logged in
			if($this->is_authorised() == true){
				add_action('wp_enqueue_scripts', array($this,'enqueue_ajax'));
			}
			
			add_action('admin_menu', array($this,'admin_menu'));
			add_action('subscriber_menu', array($this,'subscriber_menu'));			

			/*
			function sds_login_redirect($redirect_to, $request, $user)
			{
			
				if (is_object($user)){
					if( isset($user->id) ){					
						if (is_admin){
							return admin_url() . '?page=gbf_admin_dashboard';
							return admin_url() . '?page=edit_workouts';
						}										
					}
				}

			}
			*/
			//add_filter( 'login_redirect', 'sds_login_redirect', 10, 3);
			
		}
				

		/*
		 *
		 * 	Purpose: Registers the CSS, jS on demand per page
		 *
		*/
		
		function register_css($type)
		{
			if($type =='admin'){				
				wp_register_style('admin-style', $this->settings['url'] .'/css/admin-style.css' );
		        wp_enqueue_style('admin-styles');           
			}
		}
				
		function register_js($type)
		{
			if($type =='admin'){		
				wp_register_script('admin-jquery', $this->settings['url'] . '/js/admin-jquery.js' , '', '', true );
				wp_enqueue_script('admin-jquery' );
			}
			
			/*if($type =='workout_plans'){		
				wp_register_script('workout-plans-js', $this->settings['url'] . '/js/subscriber/workout-plans.js' , '', '', true );
				wp_enqueue_script('workout-plans-js');
			}*/
		}		

		
	/*
	*
	*
	-------- End of SETTINGS ----------------------------------------------------------------
	*
	*
	*/
		
		
		
	/*
	*
	*
	-------- ADMIN FUNCTIONS ----------------------------------------------------------------
	*
	*
	*/

		/*
		 *
		 * 	Purpose: Displays and processes the admin dashboard
		 *
		 */
		function admin_dashboard()
		{
		
			$page_title = "GazBFit Dashboard";
			
			
			if($this->is_admin() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
								
				//Page data				
				
				//Display subscribers
				$subscribers = $this->users->get_subscribers();				
				
				//Display subscriber activity
				//$userid = 9;
				$subscriber_activity = $this->users->get_subscriber_activity();
				
				//Get from database - select all workouts
				//$all_workouts = $this->workoutplans->get_Allworkouts();
				
				//echo '<pre>' . print_r($_REQUEST) . "<pre>";
				//echo '<pre>' . print_r($all_workouts) . "<pre>";
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				include_once dirname ( __FILE__ ).'/views/admin/dashboard-page.php';
				include_once dirname ( __FILE__ ).'/css/admin-style.css';
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		}
		
		/*
		 *
		 * 	Purpose: Displays and processes the subscriber admin dashboard
		 *
		 */
		function subscriber_dashboard()
		{
		
			$page_title = "GazBFit Subscriber Dashboard";
			// Include the admin page view
			$this->register_css('admin');
			include_once dirname ( __FILE__ ).'/views/subscriber/dashboard-page.php';

		}


		/*
		 *
		 * 	Purpose: Function for shortcodes
		 *
		 */
		function sds_signup_form_shortcode (){
			
			$this->register_css('admin');
			$this->register_js('admin');
			//$this->register_css('public');
			//include_once dirname ( __FILE__ ).'/views/admin/income-details-page.php';
			//include_once dirname ( __FILE__ ).'/views/public/user-signup-form.php';

		}
		
		
		/*
		* 	Purpose: Edit/Add workouts
		*/
		function edit_workouts (){
		
			if($this->is_admin() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
								
				//Page data
				$page_title = "Edit workouts";
				//Get from database - select all workouts
				$all_workouts = $this->workoutplans->get_Allworkouts();
				//echo '<pre>' . print_r($_REQUEST) . "<pre>";
				//echo '<pre>' . print_r($all_workouts) . "<pre>";
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				include_once dirname ( __FILE__ ).'/views/admin/edit-workouts.php';
				include_once dirname ( __FILE__ ).'/css/admin-style.css';				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		
		/*
		*
		* 	Purpose: Edit single workouts
		*
		*/
		function edit_single_workout (){
		
			if($this->is_admin() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
				
				//Barbell Hip Thrusts Lower body
				//Dumbbell Walking Lunges 
				
				/*
				Set name X1: Dumbbell Walking Lunges xxc 
				Body area X1: Lower body ewr
				
				Array ( [set_id] => 
				[set_name] => Dumbbell Walking Lunges xxc 
				[body_area] => Lower body ewr ) NULL
				*/
								
								
				//Page data
				$page_title = "Edit workout - Single";				
				
				//Process the form data
				$validated = null;
				//$form_data = array();
				//$form_data['ref'] = $_GET['ref'];	
				
				if (count($_POST)== 0) {
					//Get the row
					$single_workout = $this->workoutplans->get_Workout_by_id($_GET['set_id']);
					//echo '<pre>' . print_r($_GET['set_id']) . "<pre>";
					//echo '<pre>' . print_r($_REQUEST) . "<pre>";
					//echo '<pre>' . print_r($all_workouts) . "<pre>";
				};
				
				
				if (count($_POST)>0) {

					$p = $_POST;
					//print_r($p);
					$action = $_GET['action'];
	
					$validated = true;
	
					foreach ($p as $key => $value) {
						if( strlen($value) == 0 || $value == 'null' ){
							$validated = false;
							break;
						}
					}
					
					if (true == $validated && $action == 'save') {
	
						$data = array();
						//$data['set_id'] = $p['set_id'];
						$data['set_name'] = $p['set_name'];
						$data['body_area'] = $p['body_area'];
						
						print_r($data);
	
						if ( $this->workoutplans->update($data, array('set_id'=>$p['set_id'])) !== FALSE ) {
	
							$updated = TRUE;
							
						}else{
							$error_msg = $this->db->wpdb->show_errors();
						}
					}
				}
				
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				include_once dirname ( __FILE__ ).'/views/admin/form-edit.php';
				include_once dirname ( __FILE__ ).'/css/admin-style.css';				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		
		/*
		*
		* 	Purpose: Add single workouts
		*
		*/
		function add_single_workout (){
		
			if($this->is_admin() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
				
				//Barbell Hip Thrusts Lower body
				//Dumbbell Walking Lunges 
				
				/*
				Set name X1: Dumbbell Walking Lunges xxc 
				Body area X1: Lower body ewr
				
				Array ( [set_id] => 
				[set_name] => Dumbbell Walking Lunges xxc 
				[body_area] => Lower body ewr ) NULL
				*/
								
								
				//Page data
				$page_title = "Add workout";				
				
				//Process the form data
				$validated = null;
				//$form_data = array();
				//$form_data['ref'] = $_GET['ref'];				
				
				if (count($_POST)>0) {

					$p = $_POST;
					//print_r($p);
					$action = $_GET['action'];
	
					$validated = true;
	
					foreach ($p as $key => $value) {
						if( strlen($value) == 0 || $value == 'null' ){
							$validated = false;
							break;
						}
					}
					
					if (true == $validated && $action == 'save') {
	
						$data = array();
						//$data['set_id'] = $p['set_id'];
						$data['set_name'] = $p['set_name'];
						$data['body_area'] = $p['body_area'];
						$data['sets_number'] = $p['sets_number'];
						$data['workout_sheet_id'] = $p['workout_sheet_id'];
						$data['video_link'] = $p['video_link'];
						$data['reps'] = $p['reps'];
						$data['tempo'] = $p['tempo'];
						$data['video_link'] = $p['video_link'];
						$data['rest'] = $p['rest'];
						$data['notes'] = $p['notes'];
						//print_r($data);
	
						if ( $this->workoutplans->insert($data) !== FALSE ) {
	
							$updated = TRUE;
							
						}else{
							$updated = FALSE;
							$error_msg = $this->db->wpdb->show_errors();
						}
					}
				}
				
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				include_once dirname ( __FILE__ ).'/views/admin/form-add.php';
				include_once dirname ( __FILE__ ).'/css/admin-style.css';				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		
/*
*
*
-------- USER FUNCTIONS ----------------------------------------------------------------
*
*
*/	
		

		function gbf_testpage (){
		
			if($this->is_subscriber() == 'subscriber'){
				
				$page_title = "Test page";	
				$this->register_js('subscriber');
				include_once dirname ( __FILE__ ).'/views/subscriber/demo-page.php';
				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		
		/*
		*
		* 	Purpose: Save workout set
		*
		*/
		function save_personal_details_form_callback(){
		
			check_ajax_referer( '_wpnonce', 'security');
			if($this->is_subscriber() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$validated = null;				
				
				if (count($_POST)>0) {
		
					$p = $_POST;
					$validated = true;					
					
					if (true == $validated) {
						
						$data = array();
						$data['wp_user_id'] = $user_id;
						$data['age'] = $p['age'];
						$data['weight'] = $p['weight'];
						$data['height'] = $p['height'];
						$data['gender'] = $p['gender'];
						$data['workout_level'] = $p['workout_level'];
						$data['workout_sheets'] = $p['workout_sheets'];
						$data['workout_goal'] = $p['workout_goal'];
						$data['workout_days'] = $p['workout_days'];
						
						if ( $this->users->insert_workout_user($data) !== FALSE ) {
						
							$updated = TRUE;
							echo($updated);
							die();							
							
						}else{
							$error_msg = $this->db->wpdb->show_errors();
							echo($error_msg);
							die();
						}
					}
				}			
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}	
		
		
		/*
		*
		* 	Purpose: Diplay workout plans - video, table and form
		*
		*/
		function workout_plans (){
		
			if($this->is_authorised() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
				
				/*
				-
				- Check if the use has entered details
				- Load form if new user or show link to update workout days
				-
				*/
				
				//Get user personal details - workout days, weight, height..
				$personal_data = $this->users->get_personal_details($user_id);
				
				if($personal_data == null){
					$page_title = "Workout Preference";
					$settings_status = '0';
					include_once dirname ( __FILE__ ).'/views/subscriber/workout-preference.php';
					
				}else{
				
				/*
				-- Load conditional workout plans
				-- 4 day workout
				-- 5 day workout
				-- Level 1
				-- Level 2
				--
				-- Display meal plan Weight loss/Muscle gain
				*/
				
					//Get from database
					//$workouts = $this->workoutplans->byUserID($user_id);
					//$workout_sheets = $this->get_workoutSheet();
					//$this->workoutplans->byWorkoutSheet();
					
					$workout_level = $personal_data[0]->workout_level;
					$workout_goal = $personal_data[0]->workout_goal;
					$workout_days = $personal_data[0]->workout_days;
					$workout_sheets = $personal_data[0]->workout_sheets;
					$default_current_day = 1;
					$default_current_workout_sheet = 1;
					
					//workout what day has been completed
					$current_day = null;
					
					//Page data
					$page_title = "Workout Plans";			
					include_once dirname ( __FILE__ ).'/views/subscriber/workout-plans.php';
				
				}
				
				//$this->register_js('workout_plans');
				//include_once dirname ( __FILE__ ).'/views/subscriber/workout-plans.php';
				//include_once dirname ( __FILE__ ).'/js/subscriber/personal-details.js';
				
				//the style style
				//include_once dirname ( __FILE__ ).'/css/admin-style.css';
				//$this->register_css('admin');
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		
		/*
        *
        * Get the workout sheet name and info for links
        *
        */
		function get_workoutSheet (){
	
			$workout_sheets = $this->workoutplans->byWorkoutSheet();
			//return $workout_sheets;
			
			//array to string
			return json_encode($workout_sheets);
			die();
		}
		
		
		/*
		*
		* Short code example
		*
		*/
		function save_workout_set_from_shortcode() {
			
			if($this->is_subscriber() == true){
			
				include_once dirname ( __FILE__ ).'/views/subscriber/save-set-form.php';
			
			}else {
				echo '<h4>Only subscribers can edit weights.</h4>';
			}
			
		}
		
		
		
		/*
		*
		* 	Purpose: Save workout set
		*
		*/
		function save_workout_set (){
		
			if($this->is_subscriber() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				//$name = wp_get_current_user()->display_name;								
								
				//Page data
				//$page_title = "Edit workout - Single";				
				
				//Process the form data
				$validated = null;
				//$form_data = array();
				//$form_data['ref'] = $_GET['ref'];	
				
				if (count($_POST)== 0) {
					//Get the row
					//$single_workout = $this->workoutplans->get_Workout_by_id($_GET['set_id']);
					
					//$clean_fname = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
					//$usr_fname =  substr($clean_fname,0,5);
					//$_passie = $this->create_hash(); -- create an id for each row/weight entry
					
					echo '<pre>workoutplan:' . print_r($_GET['workoutplan']) . "<pre>";
					echo '<pre>setid: ' . print_r($_GET['setid']) . "<pre>";
					echo '<pre>setno: ' . print_r($_GET['setno']) . "<pre>";
					//echo '<pre>' . print_r($_REQUEST) . "<pre>";
					//echo '<pre>' . print_r($all_workouts) . "<pre>";
				};
				
				
				if (count($_POST)>0) {
		
					$p = $_POST;
					//print_r($p);
					//$action = $_GET['action'];
					
					/*
					echo '<pre>workoutplan:' . print_r($_GET['workoutplan']) . "<pre>";
					echo '<pre>setid: ' . print_r($_GET['setid']) . "<pre>";
					echo '<pre>setno: ' . print_r($_GET['setno']) . "<pre>";					
					echo '<pre>POST: ' . print_r($p) . "<pre>";
					*/
		
					$validated = true;
		
					/*foreach ($p as $key => $value) {
						if( strlen($value) == 0 || $value == 'null' ){
							$validated = false;
							break;
						}
					}*/
					
					if (true == $validated && $action == 'save') {
		
						$set_data = array();
						$set_data['user_id'] = $user_id;
						$set_data['set_id'] = $_GET['setid'];
						$set_data['workout_sheet_id'] = $_GET['workoutplan'];
						$set_data['weight'] = 999;
						//print_r($set_data);
						
						$new_workoutset = $this->workoutplans->insert_workout_set($set_data);
						
						//$data['set_id'] = $p['set_id'];
						//$data['set_name'] = $p['set_name'];
						//$data['body_area'] = $p['body_area'];
		
						/*
						if ( $this->workoutplans->update($data, array('set_id'=>$p['set_id'])) !== FALSE ) {
		
							$updated = TRUE;
							
						}else{
							$error_msg = $this->db->wpdb->show_errors();
						}
						*/
					}
				}				
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				//include_once dirname ( __FILE__ ).'/views/admin/form-edit.php';
				//include_once dirname ( __FILE__ ).'/css/admin-style.css';				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}		
		
		/*
        *
        * Get the workout sheet name and info for links - ajax call
        -- This displays the top level workout links - user selects a day, then select a group/body area
        *
        */
		function get_workout_sheet_names_callback() {        	
        	check_ajax_referer( '_wpnonce', 'security');
        	
        	//Which day
        	//Get sets by level and day
        	//include all the ids for button parameters
        	$workout_day = $_REQUEST['day'];
        	$workout_level = $_REQUEST['level'];        	
        	$workout_sheet_no = 1;
        	
        	//Top level links are the body areas
        	$workouts_top_level_links = $this->workoutplans->workoutTopLevelLinks_workoutAreas($workout_day,$workout_level);
        	
        	//$workouts_top_level_links = $this->workoutplans->workoutTopLevelLinks_workoutAreas($workout_day,$workout_level);
        	//print_r($workouts_top_level_links);			
			
			$workout_area_arr=array();
			$workout_area_arr_unq=array();
			foreach($workouts_top_level_links as $i => $i_value) {
			
			   	$workout_area_arr[] = $i_value->workout_area;
			   	//array_push($workout_area_arr, $i_value->workout_area);
			}
			$workout_area_arr_unq = array_unique($workout_area_arr);
			
			$new_workout_area = array();
			foreach($workout_area_arr_unq as $workout_area_arr_unq_2){
				array_push($new_workout_area, $workout_area_arr_unq_2);
			}
			
			// ---------------- >>>>> Get the number of items completed
			
			$workout_object = [];
			$unique_array = array();
			foreach($workouts_top_level_links as $i => $i_value) {
				
				foreach ($new_workout_area as $key => $value) {
					$output = '';
				   	if ($i_value->workout_area == $key) {
				   		//in_array($i_value->workout_area, $new_workout_area)
				   		//array_push($new_workout_area[''], $i_value->workout_set_ids);
				   		array_push($unique_array, $i_value->workout_set_ids);
				   		//print_r($unique_array);
						//$output .= implode(',', $unique_array);
						$workout_object[$i_value->workout_area] = array_slice($unique_array, -6);
						//$output; 
				   	}
			   	}
			}
			//print_r($workout_object);			
			$workouts = json_encode($workout_object);
			print_r($workouts);
			//{"Upper":["66","37","143","69","154"],"Lower":["31","0","15","48","155"],"Upper 2":["116"
			wp_die();
        }
        
        
        /*
        *
        * Gets saved starter info (age,weight,workout level, workout goal...) - ajax call
        *
        */
        function get_workout_user_callback() {        	
        	check_ajax_referer( '_wpnonce', 'security');
        	
        	$user_id = wp_get_current_user()->ID;
        	$get_user_settings = $this->workoutplans->get_user_settings_by_id($user_id);
        	//print_r($get_user_settings);
        	echo json_encode($get_user_settings);
        	wp_die();
        }
        
        
        
        /*
        *
        * Get the workout sheet name and info for links - ajax call
        *
        */
		function get_workout_bodyarea_callback() {
        	
        	check_ajax_referer( '_wpnonce', 'security');
			$sheet_id = $_REQUEST['workoutsheetno'];
			//echo ('$sheet_id: <pre>' . $sheet_id . "</pre>");
			$workout_bodyareas = $this->workoutplans->byWorkoutBodyArea($sheet_id);
			//print_r($workout_bodyareas);
			echo json_encode($workout_bodyareas);
        	wp_die();
        }
        
        /*
        *
        * ----> Get the workouts based on body area
        *
        */
		function get_bodyarea_workouts_callback() {
        	
        	check_ajax_referer( '_wpnonce', 'security');
        	//echo ('<pre>' . print_r($_REQUEST) . "<pre>");
        	//echo ('<pre>' . print_r($_POST) . "<pre>");
			
			//Body area name
			$user_id = wp_get_current_user()->ID;
			//$bodyarea_name = $_REQUEST['bodyareaname'];
			//$sheet_id = 5;//$_REQUEST['workoutsheetno'];
			//$workout_bodyarea_name = $this->workoutplans->get_byBodyAreaName($user_id, $bodyarea_name, $sheet_id);
			//echo json_encode($workout_bodyarea_name);
			
			$_bodyarea_ids = $_REQUEST['bodyarea_ids'];
			$bodyarea_ids = preg_replace('/\\\\/', '', $_bodyarea_ids);
			
			$workout_sets = $this->workoutplans->get_workoutSets_by_id($user_id, $bodyarea_ids);
			echo json_encode($workout_sets);     	
        	wp_die();
        }
        
        /*
        *
        * Database call for ajax action linked to workout plan
        *
        */
        /*
        function get_workout_sheets_callback($workout_sheet_id) {
        	//echo wp_die('<pre>' . print_r($_REQUEST) . "<pre>");
			check_ajax_referer( '_wpnonce', 'security');
			
			$workout_sheet_id = 1;
			$workout_sheets = $this->workoutplans->get_Workoutsheets($workout_sheet_id);			
			echo json_encode($workout_sheets);        	
        	wp_die();
        }
        */
        
        
        /*
        *
        * Build the workout sets form - add number of rows, add any values, add form header for saving
        *
        */
		function get_workout_set_form_callback($workoutset_args) {
			
			if($this->is_subscriber() == true){
				
				check_ajax_referer( '_wpnonce', 'security');
				
				//Get user details
				$user_id = wp_get_current_user()->ID;
				//Get ajax args
				
				//split by _
				$args = preg_split("/[_,-, ]+/", $_REQUEST['workoutset_args']);
				
				$_workoutset_id = $args[0];
				$_set_id = $args[1];
				$_set_count = $args[2];
				
				//$_workoutset_id = substr($_REQUEST['workoutset_args'],0,1);
				//$_set_id =  substr($_REQUEST['workoutset_args'],1,4);
				//$formdata = '_args_workoutset_id: '.$_args_workoutset_id.' _args_set_id: '.$_args_set_id;
				//echo $formdata;
				
				/*
				$selection =  "	<select name='sds_paypal_btn_mode' id='sds_paypal_btn_mode'>";

				foreach($selection_modes as $mode){
					$selected = ($mode == $setting)? "selected" : false;
					$selection .= "<option value='$mode' $selected>".ucfirst($mode)."</option>";
				}
	
				$selection .= "</select>";
				*/
				
				
				//Get already submitted weights
				$workout_set_entries = $this->workoutplans->get_WorkoutSetEntries($user_id, $_set_id, $_workoutset_id);
				//print_r($workout_set_entries);
				
				/*
				[entry_id] => 51
	            [user_id] => 9
	            [set_id] => 0
	            [workout_sheet_id] => 1
	            [weight] => [\"23\",\"23\",\"23\"]
	            [completed] => 
	            [complete_date] => 
	            */
	            	            
	            //echo 'A: '.$workout_set_entries[0]->weight;
	            //A: [\"23\",\"23\",\"23\"]
	            $weights = $workout_set_entries[0]->weight;
				$weights_json = preg_replace('/\\\"/',"\"", $weights);
				$weights_arr = json_decode($weights_json);
				$weights_size = count($weights_arr);
				
				//Create text fields per number of sets (some have 3 or 4)
				// Could default weight col with [\"\",\"\",\"\"]
				
				$field_markup = '';
				$form_id = '';
				//$form_id = //Change to entry_id //$this->sds_hash_make();
				
				//If no existing weights
				if ($weights_size == 0){
					
					$form_id = $this->sds_hash_make();
					for ($i = 0; $i < $_set_count; $i++) {
						//Empty fields
						$field_markup .= "<input type='text' name='".$form_id."' placeholder='Enter weight' value='' />";
					}				
				}
				
				//If have existing weights
				if ($weights_size >= 1){
				
					$form_id = $workout_set_entries[0]->entry_id;
					for ($i = 0; $i < $weights_size; $i++) {										
						 //enter weight data
						 $field_markup .= "<input type='text' name='".$i."' placeholder='Enter weight' value='".$weights_arr[$i]."' />";		
					}
				}
				
				//If no existing weight entries
				$form_markup = "<div id='".$form_id."'>";
				$form_markup .= '<div class="'.$form_id.'">';
				$form_markup .= $field_markup;
				$form_markup .= '</div>';
				$form_markup .= '<a href="#" style="max-width:100%;margin-bottom:10px;" onClick="saveWorkoutSetForm(\''.$form_id.'\');return false;" class="button enter-weights-btn"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Save</a>';
				$form_markup .= '<a href="#" class="btn-clear '.$form_id.'" onClick="hideWorkoutSetForm(\''.$form_id.'\');return false;" class="btn-clear"><i class="fa fa-chevron-circle-up" aria-hidden="true"></i> Hide</a>';
				$form_markup .= "</div>";
				
				echo $form_markup;
				wp_die();
			
			}else {
				echo '<h4>Only subscribers can edit weights.</h4>';
			}
			
			
		}
		
		
		/*
        *
        * Get the workout sheet name and info for links - ajax call
        *
        */
		function save_workout_set_form_callback() {
        	
			check_ajax_referer( '_wpnonce', 'security');
			
			$data = array();			
			$data['user_id'] = wp_get_current_user()->ID;
			//Get ajax args			
			$data['entry_id'] = $_REQUEST['entry_id'];
			$data['set_id'] = $_REQUEST['set_id'];
			$data['workout_sheet_id'] = $_REQUEST['workout_id'];
			$data['weight'] = $_REQUEST['weights'];
			$data['date_updated'] = date('Y-m-d H:i:s');			
			//print_r($data);
			
			//Check existing entries
			$workout_set_entries = $this->workoutplans->get_WorkoutSetEntries($data['user_id'], $data['set_id'], $data['workout_sheet_id']);
			$weights = $workout_set_entries[0]->weight;
			$weights_json = preg_replace('/\\\"/',"\"", $weights);
			$weights_arr = json_decode($weights_json);
			$weights_size = count($weights_arr);
			
			if ($weights_size > 1){
			
				//Update existing entries by entry_id
				$data['date_completed'] = date('Y-m-d H:i:s');
				if ($this->workoutplans->update_WorkoutSetEntries($data, array('entry_id'=>$data['entry_id'])) !== FALSE ) {
				
					echo('Updated');
					
				}else{
					$error_msg = $this->db->wpdb->show_errors();
					echo($error_msg);				
				}
				wp_die();
			}
			
			if ($weights_size == 0){		
				//Save data - saves new entries	
				//$workout_set_entries = $this->workoutplans->save_WorkoutSetEntries($data);
				if ($this->workoutplans->save_WorkoutSetEntries($data) !== FALSE ) {	
					echo('Saved');
					
				}else{
					$error_msg = $this->db->wpdb->show_errors();
					echo($error_msg);				
				}
				wp_die();
			}
			
        }

/*
*
*
------------------------------------------------------------------------
*
*
*/


		/*
		 *
		 * 	Purpose: Builds the admin and user menus
		 *
		 */
		function admin_menu()
		{
			
			if (is_admin() ) {			
			add_menu_page('GBF Dashboard','GBF Dashboard','administrator','gbf_admin_dashboard', array($this,'admin_dashboard'), 'dashicons-dashboard',30);
			
			add_submenu_page( 'gbf_admin_dashboard', 'Edit workouts', 'Edit workouts','administrator', 'edit_workouts', array($this,'edit_workouts'), 30);
			
			//Hidden pages
			add_submenu_page( null, 'Edit workout', 'Edit workout','administrator', 'edit_single_workout', array($this,'edit_single_workout'), 30);
			//Save workout set
			add_submenu_page( null, 'Save workout set', 'Save workout set','subscriber', 'save_workout_set', array($this,'save_workout_set'), 30);			
			//Add workout form
			add_submenu_page( null, 'Add workout', 'Add workout','administrator', 'add_single_workout', array($this,'add_single_workout'), 30);
			}
		}
		

		/*
		 *
		 * 	Purpose: Registers the shortcodes
		 *
		 */
		function register_shortcode() {
		
			//add_shortcode( 'sds_signup_form', array($this,'sds_signup_form_shortcode') );
			//add_shortcode( 'ajax_signup_form', array($this,'ajax_signup_form_shortcode') );
			
			/* --------------------------------------------------------
			-- Display user data and get their workout plan or links to the plan pages
			--------------------------------------------------------- */
			//add_shortcode( 'testpage', 'gbf_testpage' );
			add_shortcode( 'testpage', array($this,'gbf_testpage') );
			add_shortcode( 'sc_workoutplans', array($this,'workout_plans') );
			
			add_shortcode( 'sc_save_workout_set', array($this,'save_workout_set_from_shortcode') );
			
			
			/* --------------------------------------------------------
			-- Display user data and get their workout plan or links to the plan pages
			--------------------------------------------------------- */
			//add_shortcode( 'user_workout_plan', 'gbf_user_workout_plan' );
			
			/* ------------------------------------------------------------------------------------
			-- //[workout_sets workout_id="1"] // Load the set ID and get the user workout entries
			------------------------------------------------------------------------------------- */
			//add_shortcode( 'workout_sets', 'gbf_workouts' );
			
		}

		/*
		 *
		 * 	Purpose: Helper functions
		 *
		 */
		 
		function is_authorised() {
		
			$current_user = wp_get_current_user();
			if($current_user->roles[0] == 'subscriber' || $current_user->roles[0] == 'administrator'){
				return true;
			}		
		}
		 
		function is_subscriber() {
		
			$current_user = wp_get_current_user();
			if($current_user->roles[0] == 'subscriber'){
				return $current_user->roles[0];
			}		
		}
		
		
		function is_admin() {
		
			$current_user = wp_get_current_user();
			if($current_user->roles[0] == 'administrator'){
				return $current_user->roles[0];
			}		
		}
		
		function get_subcribelink() {
			return '<p><a href="'.site_url().'/my-account">Log in</a> or <a href="'.site_url().'/training/standard-online-coaching/">Subscribe today</a></p>';
			
		}
		 
		 
		function helperfunction_date($date, $new_format)
		{
			return date($new_format, strtotime($date));

		}
		
		function sds_hash_make()
		{
			return md5(uniqid("", true));
		}	

	}
	//initialize our plugin
	global $sds;
	// Create an instance of our class
	$sds = new sds_gazbfit();	
}