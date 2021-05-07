<?php
class SdsDb {

var $tables = array();
var $wpdb;


	public function __construct($settings)
	{
		$this->settings = $settings;
		$this->init();
	}

	private function init()
	{

		global $wpdb, $charset_collate;
		$this->wpdb = $wpdb;

		// Set the names of the database tables
		$this->tables = array(
                'gbf_test_1' => $this->wpdb->prefix . 'gbf_test');

	}

	/*
	 *
	 * 	Purpose: Install the plugin
	 * 	Added in Version 1.0.5
	 *
	 */
	public function install()
	{

		$options = get_option('sds_gfb');
		$plugin_update_version = $options['update_version'];

		if ( $plugin_update_version < $this->settings['update_version'] ) {

			// Upgrade to current version
			$update_function = '_update_'.$this->settings['update_version'];
			$this->$update_function();

		}
	
	}
	

	/*
	 *
	 * 	Purpose: Update to 1.1.0
	 * 	Added in Version 1.1.0
	 *
	 */
	
	/*
	private function _update_110() {

		// Build the db structure
		$this->_update_107();

		$sql = "CREATE TABLE ".$this->tables['orders']." (
				id int(11) NOT NULL AUTO_INCREMENT,
				lead_id INT,
				company_id INT,
				payment_provider VARCHAR (100) DEFAULT NULL,
				transaction_reference text,
				created_at DATETIME,
				updated_at DATETIME,
				UNIQUE KEY id (id)
			);";

		dbDelta( $sql );

	}
	*/

	/*
	 *
	 * 	Purpose: Update to 1.0.7
	 * 	Added in Version 1.0.7
	 *
	 */
	/*
	private function _update_110() {
		
		global $wpdb;
		
		// Build the initial db structure
		$this->_update_100();
		
		
		// Replace all of the individual option rows and replace with one serialized version
				$options = array('global' => array(
		                    'sds_admin_email_address'=>'', 
		                    'sds_lead_stock_level'=>'99999', 
		                    'sds_lead_price'=>'600', 
		                    'sds_currency_accepted'=>'', 
		                    'sds_payment_gateway'=>''),
		                    'paypal'=> array('sds_paypal_btn_mode'=>'', 
		                    'sds_paypal_email_address'=>''));
		
				$replacement_options = array();
		
				foreach ($options as $option_key => $option_value) {
		
					foreach ($option_value as $setting_key => $setting_value) {
						$replacement_options[$option_key][$setting_key] = get_option($setting_key);
						delete_option($setting_key);
					}
		
				}
		
				add_option('sds', $replacement_options, "", "", "yes");

		
		
		// Update the update version in options
		$options = get_option('sds');
		$options['update_version'] = '110';
		update_option('sds', $options, "", "", "yes");

	}
	*/

	/*
	 *
	 * 	Purpose: Initial db structure
	 * 	Added in Version 1.0.7
	 *
	 */
	private function _update_110()
	{       
           
		//delete the plugin row from wp_options     
		//$wpdb->query("DELETE FROM ".$this->tables['options']." WHERE ".$this->tables['options'].".option_name = 'sds_gfb'");       
      
       
       //$sql1 = "DROP TABLE IF EXISTS gbf_test_1";	
       //$wpdb->query( $sql1 );
       
       $table_name = $wpdb->prefix . 'gbf_test_1';
       $wpdb_collate = $wpdb->collate;              		

		// Add table for testing
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  	id int(11) unsigned NOT NULL AUTO_INCREMENT,
			  	com_test_hash varchar(100) DEFAULT NULL,
			  	com_test_name varchar(100) DEFAULT NULL,
			  	created_at datetime DEFAULT NULL,
			  	updated_at datetime DEFAULT NULL,
			  	PRIMARY KEY (id),
			  	UNIQUE KEY id (id))
			  	$charset_collate;";
			  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );	
				
				
		// Update the update version in options
		$options = get_option('sds_gfb');
		$options['update_version'] = '100';
		update_option('sds_gfb', $options, "", "", "yes");
	}

}
$SdsDb = new SdsDb($this->settings);