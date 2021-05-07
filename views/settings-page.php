<div class="wrap">
	<h2>SDS WorkShop Settings</h2>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<h2 class="nav-tab-wrapper">
			<a href="?page=sds&tab=general" class="nav-tab">General</a>
			<a href="?page=sds&tab=paypal_options" class="nav-tab">PayPal Buttons</a>
			<!-- <a href="?page=lcp&tab=on_account_options" class="nav-tab">On Account Payments</a> -->
		</h2>
		<?php
			$active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';

			if( $active_tab == 'general' ) {
				settings_fields( 'sds_options_group' );
				do_settings_sections( 'sds_general_settings' );
			/*
			} else if ( $active_tab == 'stripe_options' ) {
				settings_fields( 'lcp_stripe_payments_group' );
				do_settings_sections( 'lcp_stripe_settings' );
			*/
			} else if ( $active_tab == 'paypal_options' ) {
				settings_fields( 'sds_paypal_payments_group' );
				do_settings_sections( 'sds_paypal_settings' );
			/*
			} else if ( $active_tab == 'on_account_options' ) {
				settings_fields( 'lcp_on_account_group' );
				do_settings_sections( 'lcp_on_account_settings' );
			*/
			}

			submit_button();
		?>
	</form>
</div>