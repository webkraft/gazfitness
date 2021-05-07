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

			include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/models/options.php';
			$this->options = $SdsOptions;
			
			include_once plugin_dir_path( __DIR__ ) . $this->settings['plugin_name'] . '/core/models/workoutplans.php';
			$this->workoutplans = $SdsWorkoutplans;
			
			// Register the install and uninstall hooks
			register_activation_hook(__FILE__, array($this->db,'install') );
                        
			// Actions
			add_action('init', array($this,'init'),1);
			add_action('init', array($this,'register_shortcode'), 20);
			add_action('wp_enqueue_scripts', array($this,'enqueue_ajax'));
			
			/*
			*
			* Set names for ajax action
			*
			*/
        	add_action( 'wp_ajax_get_workout_sheet_names', array( $this, 'get_workout_sheet_names_callback') );
        	//add_action( 'wp_ajax_nopriv_my_action', array( $this, 'get_workout_sheet_names_callback') );
        	
		}
    	
    	/*
		*
		* Enqueue scripts
		*
		*/
	    public function enqueue_ajax() {
	        
	        wp_enqueue_script('get_workout_sheet_names', plugins_url('js/subscriber/workout-plans.js', __FILE__), array('jquery'), '1.0', true);
	        wp_localize_script( 'get_workout_sheet_names', 'get_workout_sheet_names_file', array(
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

			add_action('admin_menu', array($this,'admin_menu'));
			add_action('subscriber_menu', array($this,'subscriber_menu'));

			function sds_login_redirect($redirect_to, $request, $user)
			{
			
				if (is_object($user)){
					if( isset($user->id) ){					
						if (is_admin){
							return admin_url() . '?page=gbf_admin_dashboard';
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
			
			if($type =='workout_plans'){		
				wp_register_script('workout-plans-js', $this->settings['url'] . '/js/subscriber/workout-plans.js' , '', '', true );
				wp_enqueue_script('workout-plans-js');
			}
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
			// Include the admin page view
			$this->register_css('admin');
			include_once dirname ( __FILE__ ).'/views/admin/dashboard-page.php';

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
		 * 	Purpose: Initialises the plugin
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
				
				$this->register_js('workout_plans');
				include_once dirname ( __FILE__ ).'/views/subscriber/workout-plans.php';
				
				
			}else {
				$subcribe_links = $this->get_subcribelink();
				include_once dirname ( __FILE__ ).'/views/public/error-page.php';
			}
		
		}
		
		function get_workoutSheet (){
	
			$workout_sheets = $this->workoutplans->byWorkoutSheet();
			//return $workout_sheets;
			
			//array to string
			return json_encode($workout_sheets);
			die();
		}
		
		
		function get_workout_sheet_names_callback() {
        	//echo wp_die('<pre>' . print_r($_REQUEST) . "<pre>");
			check_ajax_referer( '_wpnonce', 'security');
			
			$workout_sheets = $this->workoutplans->byWorkoutSheet();			
			echo json_encode($workout_sheets);        	
        	wp_die();
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
			//add_menu_page('GBF Dashboard','GBF Workouts','administrator','gbf_user_dashboard', array($this,'subscriber_dashboard'), 'dashicons-dashboard',30);
			//add_menu_page('GBF Dashboard','GBF Workouts','subscriber','gbf_user_dashboard', array($this,'subscriber_dashboard'), 'dashicons-dashboard',30);
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

	}
	//initialize our plugin
	global $sds;
	// Create an instance of our class
	$sds = new sds_gazbfit();	
}