<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Handles misc functions of plugin
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_paypal_donation_lable' ) ) {
	
	function wppd_paypal_donation_lable( $plural = false ) {
		
		if( $plural ) { // for plural
			$label	= __( 'Donations', 'wppd' );
		} else { // for singular
			$label	= __('Donation', 'wppd');
		}
		
		return apply_filters( 'wppd_paypal_donation_lable', $label, $plural );
	}
}

/**
 * Handles donate buttons
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_donate_buttons' ) ) {
	
	function wppd_donate_buttons() {
		
		$donate_buttons = array(
			'small'			=> 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif',
			'large'			=> 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif',
			'cards'			=> 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif',
			'x-click-but2'	=> 'https://www.paypal.com/en_US/i/btn/x-click-but21.gif',
			'x-click-but04'	=> 'https://www.paypal.com/en_US/i/btn/x-click-but04.gif',
			'x-click-but11'	=> 'https://www.paypal.com/en_US/i/btn/x-click-but11.gif',
		);
		
		return apply_filters( 'wppd_donate_buttons', $donate_buttons );
	}
}

/**
 * Handles currency codes
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_currency_codes' ) ) {
	
	function wppd_currency_codes() {
		
		$currency_codes = array(
								'AUD'	=> __( 'Australian Dollars (A $)', 'wppd' ),
								'BRL'	=> __( 'Brazilian Real', 'wppd' ),
								'CAD'	=> __( 'Canadian Dollars (C $)', 'wppd' ),
								'CZK'	=> __( 'Czech Koruna', 'wppd' ),
								'DKK'	=> __( 'Danish Krone', 'wppd' ),
								'EUR'	=> __( 'Euros (&euro;)', 'wppd' ),
								'HKD'	=> __( 'Hong Kong Dollar ($)', 'wppd' ),
								'HUF'	=> __( 'Hungarian Forint', 'wppd' ),
								'ILS'	=> __( 'Israeli New Shekel', 'wppd' ),
								'JPY'	=> __( 'Yen (&yen;)', 'wppd' ),
								'MYR'	=> __( 'Malaysian Ringgit', 'wppd' ),
								'MXN'	=> __( 'Mexican Peso', 'wppd' ),
								'NOK'	=> __( 'Norwegian Krone', 'wppd' ),
								'NZD'	=> __( 'New Zealand Dollar ($)', 'wppd' ),
								'PHP'	=> __( 'Philippine Peso', 'wppd' ),
								'PLN'	=> __( 'Polish Zloty', 'wppd' ),
								'GBP'	=> __( 'Pounds Sterling (&pound;)', 'wppd' ),
								'RUB'	=> __( 'Russian Ruble', 'wppd' ),
								'SGD'	=> __( 'Singapore Dollar ($)', 'wppd' ),
								'SEK'	=> __( 'Swedish Krona', 'wppd' ),
								'CHF'	=> __( 'Swiss Franc', 'wppd' ),
								'TWD'	=> __( 'Taiwan New Dollar', 'wppd' ),
								'THB'	=> __( 'Thai Baht', 'wppd' ),
								'TRY'	=> __( 'Turkish Lira', 'wppd' ),
								'USD'	=> __( 'U.S. Dollars ($)', 'wppd' ),
							);
		
		return apply_filters( 'wppd_currency_codes', $currency_codes );
	}
}

/**
 * Handles currency codes
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_localized_buttons' ) ) {
	
	function wppd_localized_buttons() {
		
		$localized_buttons	= array(
								'en_AU'		=> __( 'Australia - Australian English', 'wppd' ),
								'de_DE/AT'	=> __( 'Austria - German', 'wppd' ),
								'nl_NL/BE'	=> __( 'Belgium - Dutch', 'wppd' ),
								'fr_XC'		=> __( 'Canada - French', 'wppd' ),
								'zh_XC'		=> __( 'China - Simplified Chinese', 'wppd' ),
								'fr_FR/FR'	=> __( 'France - French', 'wppd' ),
								'de_DE/DE'	=> __( 'Germany - German', 'wppd' ),
								'it_IT/IT'	=> __( 'Italy - Italian', 'wppd' ),
								'ja_JP/JP'	=> __( 'Japan - Japanese', 'wppd' ),
								'es_XC'		=> __( 'Mexico - Spanish', 'wppd' ),
								'nl_NL/NL'	=> __( 'Netherlands - Dutch', 'wppd' ),
								'pl_PL/PL'	=> __( 'Poland - Polish', 'wppd' ),
								'es_ES/ES'	=> __( 'Spain - Spanish', 'wppd' ),
								'de_DE/CH'	=> __( 'Switzerland - German', 'wppd' ),
								'fr_FR/CH'	=> __( 'Switzerland - French', 'wppd' ),
								'en_US'		=> __( 'United States - U.S. English', 'wppd' )
							);
		
		return apply_filters( 'wppd_localized_buttons', $localized_buttons );
	}
}

/**
 * Handles checkout langiages
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_checkout_languages' ) ) {
	
	function wppd_checkout_languages() {
		
		$checkout_languages = array(
									'AU'	=> __( 'Australia', 'wppd' ),
									'AT'	=> __( 'Austria', 'wppd' ),
									'BE'	=> __( 'Belgium', 'wppd' ),
									'BR'	=> __( 'Brazil', 'wppd' ),
									'CA'	=> __( 'Canada', 'wppd' ),
									'CN'	=> __( 'China', 'wppd' ),
									'FR'	=> __( 'France', 'wppd' ),
									'DE'	=> __( 'Germany', 'wppd' ),
									'IT'	=> __( 'Italy', 'wppd' ),
									'NL'	=> __( 'Netherlands', 'wppd' ),
									'PL'	=> __( 'Poland', 'wppd' ),
									'PR'	=> __( 'Portugal', 'wppd' ),
									'RU'	=> __( 'Russia', 'wppd' ),
									'ES'	=> __( 'Spain', 'wppd' ),
									'SE'	=> __( 'Sweden', 'wppd' ),
									'CH'	=> __( 'Switzerland', 'wppd' ),
									'GB'	=> __( 'United Kingdom', 'wppd' ),
									'US'	=> __( 'United States', 'wppd' ),
								);
		
		return apply_filters( 'wppd_checkout_languages', $localized_buttons );
	}
}


/**
 * Return PayPal Donation Button HTML
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
if( !function_exists( 'wppd_paypal_donation_html' ) ) {
	
	function wppd_paypal_donation_html( $post_id ) {
		
		global $wppd_model;
		
		$paypal_donation	= get_post( $post_id ); 
		
		if( !empty( $paypal_donation ) && ( isset( $paypal_donation->post_type ) && $paypal_donation->post_type == WPPD_POST_TYPE ) ) { // If donation not found
			
			$wppd_enable_sandbox	= get_post_meta( $post_id, 'wppd_enable_sandbox', true );
			$wppd_open_newtab		= get_post_meta( $post_id, 'wppd_open_newtab', true );
			$wppd_paypal_account	= get_post_meta( $post_id, 'wppd_paypal_account', true );
			$wppd_currency			= get_post_meta( $post_id, 'wppd_currency', true );
			$wppd_return_url		= get_post_meta( $post_id, 'wppd_return_url', true );
			$wppd_default_amount	= get_post_meta( $post_id, 'wppd_default_amount', true );
			$wppd_default_purpose	= get_post_meta( $post_id, 'wppd_default_purpose', true );
			$wppd_default_reference	= get_post_meta( $post_id, 'wppd_default_reference', true );
			$wppd_button_type		= get_post_meta( $post_id, 'wppd_button_type', true );
			$wppd_custom_button		= get_post_meta( $post_id, 'wppd_custom_button', true );
			$wppd_button_localized	= get_post_meta( $post_id, 'wppd_button_localized', true );
			
			if( $wppd_enable_sandbox == 1 ) {
				$paypal_donations_url	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			} else {
				$paypal_donations_url	= 'https://www.paypal.com/cgi-bin/webscr';
			}
			
			// Final PayPal Donation URL
			$paypal_donations_url	= apply_filters( 'wppd_paypal_donations_url', $paypal_donations_url, $post_id );
			$wppd_default_purpose	= apply_filters( 'wppd_paypal_donations_purpose_html', $wppd_default_purpose, $post_id );
			$wppd_default_amount	= apply_filters( 'wppd_paypal_donations_amount', $wppd_default_amount, $post_id );
			$wppd_notify_url		= apply_filters( 'wppd_paypal_donations_notify_url', site_url() . '/?wppd_paypal_ipn=process&donation_id='.$post_id, $post_id );
			
			if( $wppd_button_type == 'custom' ) {
				$wppd_button_url	= $wppd_custom_button;
			} else {
				$donate_buttons		= wppd_donate_buttons();
				$wppd_button_url	= $donate_buttons[$wppd_button_type];
			}
			
			$wppd_button_url		= str_replace( 'en_US', $wppd_button_localized, $wppd_button_url );
			
			ob_start();
			
			?>
			
			<form action="<?php echo $paypal_donations_url;?>" method="POST"<?php if( $wppd_open_newtab == 1 ) { echo ' target="_blank"';}?>>
				<input type="hidden" name="cmd" value="_donations" />
				<input type="hidden" name="bn" value="TipsandTricks_SP" />
				<input type="hidden" name="business" value="<?php echo $wppd_paypal_account; ?>" />
				<?php 
				if( !empty( $wppd_return_url ) ) { ?>
					<input type="hidden" name="return" value="<?php echo esc_url( $wppd_return_url );?>" /><?php 
				}
				if( !empty( $wppd_default_purpose ) ) { ?>
					<input type="hidden" name="item_name" value="<?php echo esc_attr( $wppd_default_purpose );?>" /><?php 
				}
				if( !empty( $wppd_default_reference ) ) { ?>
					<input type="hidden" name="item_number" value="<?php echo esc_attr( $wppd_default_reference );?>" /><?php 
				}
				if( !empty( $wppd_default_amount ) ) { ?>
					<input type="hidden" name="amount" value="<?php echo $wppd_default_amount;?>" /><?php 
				}?>
				
				<input type="hidden" name="notify_url" value="<?php echo esc_url( $wppd_notify_url );?>" />
				<input type="hidden" name="currency_code" value="<?php echo esc_attr( $wppd_currency );?>" />
				<input type="hidden" name="lc" value="<?php echo esc_attr( $wppd_button_localized );?>" />
				<input type="image" src="<?php echo esc_url( $wppd_button_url );?>" name="submit" alt="<?php echo __( 'PayPal - The safer, easier way to pay online.', 'wppd' );?>" />
				
			</form><?php
			
			$html = ob_get_clean();
		}
		
		return apply_filters( 'wppd_paypal_donation_html', $html, $post_id );
	}
}