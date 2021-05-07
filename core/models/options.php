<?php
class SdsOptions {

	function __construct($db)
	{

		$this->db = $db;

	}

	/*
	 *
	 * 	Purpose: Gets the plugins options
	 * 	Added in Version 1.0.5
	 *
	 */
	function get($section = null, $option = null)
	{

		$options = get_option('sds_gfb');

		if ( $option != null && $section != null ) {
			return $options[$section][$option];
		}

		return $options;

	}


}

$SdsOptions = new SdsOptions($SdsDb);