<?php

class SdsStatistics {

	//function __construct($db, $leads, $companies, $users){
	function __construct($db, $companies, $users){

		$this->db 			= $db;
		//$this->leads 		= $leads;
		$this->companies 	= $companies;
		$this->users 		= $users;

	}

	/*
	 *
	 * 	Purpose: Gets the total number of approved companies
	 * 	Added in Version 1.1.0
	 *
	 */
	function totalCompanies() {

		return sizeof($this->companies->get('approved'));

	}

	/*
	 *
	 * 	Purpose: Gets the total number of approved leads
	 * 	Added in Version 1.1.0
	 *
	 */
	function totalLeads() {

		//return sizeof($this->leads->get('approved'));

	}

	/*
	 *
	 * 	Purpose: Gets the total revenue earnt
	 * 	Added in Version 1.1.0
	 *
	 */
	function totalRevenue() {

		$total_leads = 999;//$this->leads->totalPurchased();
		$options = get_option('sds');
		$price = $options['global']['sds_lead_price'];
		return $options['global']['sds_currency_accepted'] . ' ' . $revenue;

	}

}