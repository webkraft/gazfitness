<?php

class SdsBroadcast {
	
	function __construct($options)
	{
		$this->options = $options;
	}

	/*
	 *
	 * 	Purpose: Broadcasts an email
	 * 	Added in Version 1.0.5
	 *
	 */
	function send($view, $data, $to, $subject)
	{

		// Render the html ready for sending
		$body = $this->_render($view, $data);

		// Get the admins email address
		$admin_email_address = $this->options->get('global', 'sds_admin_email_address');

		// Create the headers
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'From: '.$data['site_name'].' <'.$admin_email_address.'>';
		//$headers[] = 'BCC: '.$admin_email_address;

		wp_mail($to, $subject, $body, $headers);

	}

	/*
	 *
	 * 	Purpose: Parses the variables from the HTML template
	 * 	Added in Version 1.0.5
	 *
	 */
	private function _render($view, $data) {

		// Grab the email template
		$file_path = dirname ( __FILE__ ).'/../views/emails/'.$view.'.php';
		$html = file_get_contents($file_path);

		if ( $view == 'lead-approved' || $view == 'lead-rejected' ) {

			$html = str_replace('{{site_name}}', $data['site_name'], $html);
			$html = str_replace('{{submitter}}', $data['submitter'], $html);
		
		} else if ( $view == 'company-approved' || $view == 'company-rejected' ) {		
			
			$html = str_replace('{{submitter}}', $data['submitter'], $html);
			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);
			$html = str_replace('{{username}}', 	$data['username'], $html);
			$html = str_replace('{{submitter}}', $data['first_name'], $html);
			$html = str_replace('{{email}}', $data['email'], $html);
			$html = str_replace('{{admin_login}}', site_url(), $html);
			
		} else if ( $view == 'company-notification' ) {		
			
			$html = str_replace('{{first_name}}', $data['first_name'], $html);
			$html = str_replace('{{last_name}}', $data['last_name'], $html);
			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);
			$html = str_replace('{{username}}', 	$data['username'], $html);
			$html = str_replace('{{submitter}}', $data['first_name'], $html);
			$html = str_replace('{{email}}', $data['email'], $html);
			$html = str_replace('{{admin_login}}', site_url(), $html);
			$html = str_replace('{{created_at}}', 	$this->_convert_date_to_format($data['created_at'], 'l dS F @ H:i:s'), $html);
		
		} else if ( $view == 'lead-thanks' ) {

			$html = str_replace('{{submitter}}', 	$data['submitter'], $html);
			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);
			
			$subhtml='';
			
			foreach( $data['data'] as $row )
			{
				$subhtml.="<dl>
							<dt><strong>".$row['label']."</strong></dt>
							<dd>".$row['data']."</dd>
						</dl>";
			}
			
			$html = str_replace('{{body}}', 	$subhtml, $html);			
			$html = str_replace('{{created_at}}', 	$this->_convert_date_to_format($data['created_at'], 'l dS F @ H:i:s'), $html);

		} else if ( $view == 'company-thanks' ) {

			$html = str_replace('{{submitter}}', 	$data['submitter'], $html);
			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);
			$html = str_replace('{{company_name}}', $data['company_name'], $html);
			$html = str_replace('{{first_name}}', 	$data['first_name'], $html);
			$html = str_replace('{{lastname}}', 	$data['lastname'], $html);
			$html = str_replace('{{email}}', 		$data['email'], $html);
			$html = str_replace('{{username}}', 	$data['username'], $html);
			$html = str_replace('{{created_at}}', 	$this->_convert_date_to_format($data['created_at'], 'l dS F @ H:i:s'), $html);

		} else if ( $view == 'company-deleted') {

			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);

		} else if ( $view == 'lead-purchased') {

			$html = str_replace('{{submitter}}', 	$data['submitter'], $html);
			$html = str_replace('{{site_name}}', 		$data['site_name'], $html);
			$html = str_replace('{{lead_reference}}', 		$data['lead_reference'], $html);
			$html = str_replace('{{payment_provider}}', 		$data['payment_provider'], $html);
			$html = str_replace('{{created_at}}', 		$data['created_at'], $html);
			
			
			//$subhtml='';
			
			/*foreach( $data['data'] as $row )
			{
				$subhtml.="<dl>
							<dt><strong>".$row['label']."</strong></dt>
							<dd>".$row['data']."</dd>
						</dl>";
			}*/
			
			//$html = str_replace('{{body}}', 	$subhtml, $html);	
			//$html = str_replace('{{created_at}}', 		$this->_convert_date_to_format($data['created_at'], 'l dS F @ H:i:s'), $html);
		
		
		} else if ( $view == 'lead-purchased-notification') {
		
					$html = str_replace('{{submitter}}', 	$data['submitter'], $html);
					$html = str_replace('{{lead_reference}}', 		$data['lead_reference'], $html);
					$html = str_replace('{{payment_provider}}', 		$data['payment_provider'], $html);
					$html = str_replace('{{created_at}}', 		$data['created_at'], $html);
					
					//$subhtml='';
					
					/*foreach( $data['data'] as $row )
					{
						$subhtml.="<dl>
									<dt><strong>".$row['label']."</strong></dt>
									<dd>".$row['data']."</dd>
								</dl>";
					}*/
					
					//$html = str_replace('{{body}}', 	$subhtml, $html);	
					//$html = str_replace('{{created_at}}', 		$this->_convert_date_to_format($data['created_at'], 'l dS F @ H:i:s'), $html);
					

		} else if ( 'new-lead-available' ) {

			$html = str_replace('{{submitter}}', 	$data['submitter'], $html);
			$html = str_replace('{{site_name}}', 	$data['site_name'], $html);
			$html = str_replace('{{admin_url}}', 	$data['admin_url'], $html);
			$html = str_replace('{{first_name}}', 	$data['firstname'], $html);

		}

		return $html;

	}

	/*
	 *
	 * 	Purpose: Converts a date format
	 * 	Added in Version 1.0.5
	 *
	 */
	private function _convert_date_to_format($date, $new_format)
	{

		return date($new_format, strtotime($date));

	}

}