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

			//include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/models/options.php';
			//$this->options = $SdsOptions;
			
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
        	add_action( 'wp_ajax_get_workout_bodyarea', array( $this, 'get_workout_bodyarea_callback') );        	
        	add_action( 'wp_ajax_get_bodyarea_workouts', array( $this, 'get_bodyarea_workouts_callback') );        	
        	add_action( 'wp_ajax_get_workout_set_form', array( $this, 'get_workout_set_form_callback') );
        	
        	//add_action( 'wp_ajax_get_workout_sheets', array( $this, 'get_workout_sheets_callback') );
        	//add_action( 'wp_ajax_nopriv_my_action', array( $this, 'get_workout_sheet_names_callback') );
		}
    	
    	/*
		*
		* Enqueue js files ready to be called by ajax
		*
		*/
	    public function enqueue_ajax() {
	        
	        //Get workout sheets
	        wp_enqueue_script('get_workout_sheet_names', plugins_url('js/subscriber/workout-plans.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_sheet_names', 'get_workout_sheet_names_file', array(
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
	
	    }
		
		
		/*
		*
		* 	Purpose: Initialises the plugin
		*
		*/
		public function init()
		{
		
			//Only load ajax scripts when logged in
			if($this->is_authorised() == true){
				add_action('wp_enqueue_scripts', array($this,'enqueue_ajax'));
			}
			
			add_action('admin_menu', array($this,'admin_menu'));
			add_action('subscriber_menu', array($this,'subscriber_menu'));

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
			add_filter( 'login_redirect', 'sds_login_redirect', 10, 3);
			
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
				$userid = 9;
				$subscriber_activity = $this->users->get_subscriber_activity($userid);
				
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
		* 	Purpose: Diplay workout plans - video, table and form
		*
		*/
		function workout_plans (){
		
			if($this->is_authorised() == true){
				
				//Get user details
				$user_id = wp_get_current_user()->ID; 
				$name = wp_get_current_user()->display_name;
				
				//Get from database
				$workouts = $this->workoutplans->byUserID($user_id);
				$workout_sheets = $this->get_workoutSheet();
				//$this->workoutplans->byWorkoutSheet();
				
				//Page data
				$page_title = "Workout Plans";
				
				//$this->register_js('workout_plans');
				//$this->register_css('admin');
				include_once dirname ( __FILE__ ).'/views/subscriber/workout-plans.php';
				include_once dirname ( __FILE__ ).'/css/admin-style.css';
				
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
					
					echo '<pre>workoutplan:' . print_r($_GET['workoutplan']) . "<pre>";
					echo '<pre>setid: ' . print_r($_GET['setid']) . "<pre>";
					echo '<pre>setno: ' . print_r($_GET['setno']) . "<pre>";					
					echo '<pre>POST: ' . print_r($p) . "<pre>";
		
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
						print_r($set_data);
						
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
        * Get the workout body area for link to body area workouts
        *
        */
		/*
		function get_workoutBodyArea (){
	
			$workout_bodyareas = $this->workoutplans->byWorkoutBodyArea($sheet_id);
			//return $workout_sheets;
			
			//array to string
			return json_encode($workout_bodyareas);
			die();
		}
		*/
		
		
		/*
        *
        * Get the workout sheet name and info for links - ajax call
        *
        */
		function get_workout_sheet_names_callback() {
        	//echo wp_die('<pre>' . print_r($_REQUEST) . "<pre>");
        	
			check_ajax_referer( '_wpnonce', 'security');
			$workout_sheets = $this->workoutplans->byWorkoutSheet();			
			echo json_encode($workout_sheets);        	
        	wp_die();
        }
        
        /*
        *
        * Get the workout sheet name and info for links - ajax call
        *
        */
		function get_workout_bodyarea_callback() {
        	
        	check_ajax_referer( '_wpnonce', 'security');
        	//echo ('<pre>' . print_r($_REQUEST) . "<pre>");
        	//echo ('<pre>' . print_r($_POST) . "<pre>");
			
			//workoutsheetno
			$sheet_id = $_REQUEST['workoutsheetno'];
			$workout_bodyareas = $this->workoutplans->byWorkoutBodyArea($sheet_id);
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
			$bodyarea_name = $_REQUEST['bodyareaname'];
			$sheet_id = $_REQUEST['workoutsheetno'];
			$workout_bodyarea_name = $this->workoutplans->get_byBodyAreaName($bodyarea_name, $sheet_id);
			echo json_encode($workout_bodyarea_name);        	
        	wp_die();
        }
        
        /*
        *
        * Database call for ajax action linked to workout plan
        *
        */
        function get_workout_sheets_callback($workout_sheet_id) {
        	//echo wp_die('<pre>' . print_r($_REQUEST) . "<pre>");
			check_ajax_referer( '_wpnonce', 'security');
			
			$workout_sheet_id = 1;
			$workout_sheets = $this->workoutplans->get_Workoutsheets($workout_sheet_id);			
			echo json_encode($workout_sheets);        	
        	wp_die();
        }
        
        
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
				
				//$formdata = '_args_workoutset_id: '.$args[0].' _args_set_id: '.$args[1];
				//echo $formdata;
				
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
				//$workout_set_entries = $this->workoutplans->get_WorkoutSetEntries($user_id, $_set_id, $_workoutset_id);
				
				//Create text fields - per number of sets
				$set_number = 3;
				$field_markup = '';
				for ($i = 0; $i < $set_number; $i++) {
					$field_markup .= "<input type='text' name='".$i."' placeholder='Enter weight' value='' />";		
				}
				
				$form_id = $this->sds_hash_make();
				
				//If no existing weight entries
				$form_markup = "<div id='".$form_id."'>";
				$form_markup .= $field_markup;
				$form_markup .= '<a href="#" onClick="saveWorkoutSetForm(\''.$form_id.'\');return false;">Save</a>';
				$form_markup .= "</div>";
				
				echo $form_markup;
				wp_die();
			
			}else {
				echo '<h4>Only subscribers can edit weights.</h4>';
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