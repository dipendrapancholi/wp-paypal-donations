<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add/Edit paypal donation
 * 
 * Handle Add / Edit paypal donation
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
global $wppd_model, $errmsg, $error, $args; //make global for error message to showing errors

$model	= $wppd_model;

// get available currency
$available_currency	= wppd_currency_codes();

// get available donate buttons
$donate_buttons	= wppd_donate_buttons();

// get available localize country
$available_localized	= wppd_localized_buttons();

// default value for preventing notice and warnings
$data	= array( 
			'wppd_title'				=> '',
			'wppd_enable_sandbox'		=> '',
			'wppd_open_newtab'			=> '',
			'wppd_paypal_account'		=> '',
			'wppd_currency'				=> '',
			'wppd_return_url'			=> '',
			'wppd_default_amount'		=> '',
			'wppd_default_purpose'		=> '',
			'wppd_default_reference'	=> '',
			'wppd_button_type'			=> 'small',
			'wppd_custom_button'		=> '',
			'wppd_button_localized'		=> '',
		);
		
if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && !empty( $_GET['donation_id'] ) ) { //check action & id is set or not
	
	//donation page title
	$donation_lable	= __( 'Edit PayPal Donation', 'wppd' );
	
	//donation page submit button text either it is Add or Update
	$donation_btn	= __( 'Update', 'wppd' );
	
	//get the donation id from url to update the data and get the data of product to fill in editable fields
	$post_id		= $_GET['donation_id'];
	
	//get the data from paypal donation id
	$getpost	= get_post( $post_id );
	
	if( $error != true ) {
		
		$data['wppd_title']				= $getpost->post_title;
		$data['wppd_enable_sandbox']	= get_post_meta( $post_id, 'wppd_enable_sandbox', true );
		$data['wppd_open_newtab']		= get_post_meta( $post_id, 'wppd_open_newtab', true );
		$data['wppd_paypal_account']	= get_post_meta( $post_id, 'wppd_paypal_account', true );
		$data['wppd_currency']			= get_post_meta( $post_id, 'wppd_currency', true );
		
		$data['wppd_return_url']		= get_post_meta( $post_id, 'wppd_return_url', true );
		$data['wppd_default_amount']	= get_post_meta( $post_id, 'wppd_default_amount', true );
		$data['wppd_default_purpose']	= get_post_meta( $post_id, 'wppd_default_purpose', true );
		$data['wppd_default_reference']	= get_post_meta( $post_id, 'wppd_default_reference', true );
		$data['wppd_button_type']		= get_post_meta( $post_id, 'wppd_button_type', true );
		$data['wppd_custom_button']		= get_post_meta( $post_id, 'wppd_custom_button', true );
		$data['wppd_button_localized']	= get_post_meta( $post_id, 'wppd_button_localized', true );
		
	} else {
		
		$data = $_POST;
	}
	
} else {
	
	//donation page title
	$donation_lable = __('Add New PayPal Donation', 'wppd');
	
	//product page submit button text either it is Add or Update
	$donation_btn = __('Save', 'wppd');
	
	//if when error occured then assign $_POST to be field fields with none error fields
	if($_POST) { //check if $_POST is set then set all $_POST values
		$data = $_POST;
	}

	$post_id = '';
}?>

<div class="wrap">

	<h2>
		<?php echo $donation_lable; ?>
		<a class="add-new-h2" href="admin.php?page="><?php echo __( 'Back to PayPal Donations', 'wppd' ) ?></a>
	</h2>
	
	<!-- beginning of the coupon meta box -->
	<div id="wwwp-ltable-coupon" class="post-box-container">
		<div class="metabox-holder">
			<form action="" method="POST" id="wppd-add-edit-form" enctype="multipart/form-data">
				
				<table class="form-table wppd-box"> 
					<tbody>
						
						<tr>
							<th scope="row">
								<label for="wppd-enable-sandbox"><strong><?php echo __( 'Enable Sandbox:', 'wppd' ) ?></strong>
							</th>
							
							<td colspan='2'>
								<input type="checkbox" id="wppd-enable-sandbox" name="wppd_enable_sandbox" value="1" <?php if( $data['wppd_enable_sandbox'] == 1 ) echo 'checked="checked"'; ?> />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_enable_sandbox'] ) ) echo $errmsg['wppd_enable_sandbox'] ?> </span>
								<br/><span class="description"><?php echo __( 'Checked this checkbox if you want to enable PayPal test mode.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-title"><strong><?php echo __( 'PayPal Donation Title:', 'wppd' ) ?></strong><span class="wppd-require"> * </span></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-title" class="regular-text" name="wppd_title" value="<?php echo $model->wppd_escape_attr( $data['wppd_title'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_title'] ) ) echo $errmsg['wppd_title'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter The PayPal Donation Title.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-paypal-account"><strong><?php echo __( 'PayPal Account:', 'wppd' ) ?></strong><span class="wppd-require"> * </span></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-paypal-account" class="regular-text" name="wppd_paypal_account" value="<?php echo $model->wppd_escape_attr( $data['wppd_paypal_account'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_paypal_account'] ) ) echo $errmsg['wppd_paypal_account'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter Your PayPal Email or Secure Merchant Account ID.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-currency"><strong><?php echo __( 'Currency:', 'wppd' ) ?></strong><span class="wppd-require"> * </span></label>
							</th>
							
							<td colspan='2'>
								<select id="wppd-currency" name="wppd_currency"><?php
									
									$wppd_currency	= !empty( $data['wppd_currency'] ) ? $data['wppd_currency'] : 'USD';
									
									foreach ( $available_currency as $currency_code => $currency_name ) {
										$selected	= ( $wppd_currency == $currency_code ) ? ' selected="selected"' : '';
										echo '<option value="' . $currency_code . '"' . $selected . '>' . $currency_name . '</option>';
									}
								?>
								</select>
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_currency'] ) ) echo $errmsg['wppd_currency'] ?> </span>
								<br/><span class="description"><?php echo __( 'Select the currency to use for the donations.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-return-url"><strong><?php echo __( 'Return Page URL:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-return-url" class="regular-text" name="wppd_return_url" value="<?php echo $model->wppd_escape_attr( $data['wppd_return_url'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_return_url'] ) ) echo $errmsg['wppd_return_url'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter URL to which the donator comes to after completing the donation.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-default-amount"><strong><?php echo __( 'Default Amount:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-default-amount" name="wppd_default_amount" value="<?php echo $model->wppd_escape_attr( $data['wppd_default_amount'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_default_amount'] ) ) echo $errmsg['wppd_default_amount'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter The default amount for a donation (Optional).', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-default-purpose"><strong><?php echo __( 'Default Purpose:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-default-purpose" class="regular-text" name="wppd_default_purpose" value="<?php echo $model->wppd_escape_attr( $data['wppd_default_purpose'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_default_purpose'] ) ) echo $errmsg['wppd_default_purpose'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter The default purpose of a donation (Optional).', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-default-reference"><strong><?php echo __( 'Default Reference:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-default-reference" class="regular-text" name="wppd_default_reference" value="<?php echo $model->wppd_escape_attr( $data['wppd_default_reference'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_default_reference'] ) ) echo $errmsg['wppd_default_reference'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter Default reference for the donation (Optional).', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-open-newtab"><strong><?php echo __( 'Open New Window:', 'wppd' ) ?></strong>
							</th>
							
							<td colspan='2'>
								<input type="checkbox" id="wppd-open-newtab" name="wppd_open_newtab" value="1" <?php if( $data['wppd_open_newtab'] == 1 ) echo 'checked="checked"'; ?> />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_open_newtab'] ) ) echo $errmsg['wppd_open_newtab'] ?> </span>
								<br/><span class="description"><?php echo __( 'Checked this checkbox if you want to open new window topay.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-button-type"><strong><?php echo __( 'PayPal Button Type:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'><?php
								$wppd_button_type	= $data['wppd_button_type'];
								
								foreach ( $donate_buttons as $button_name => $button_url ) {
									
									$checked	= ( $wppd_button_type == $button_name ) ? ' checked="checked"' : '';
									
									echo '<label title="'.$button_name.'">
										<input style="padding: 10px 0 10px 0;" type="radio" name="wppd_button_type" value="' . $button_name . '"' . $checked . ' />
										<img src="'.$button_url.'" alt="' . $button_name . '" style="vertical-align: middle;">
									</label><br /><br />';
								}
								?>
								<label title="<?php echo __( 'Custom Button', 'wppd' );?>">
									<input style="padding: 10px 0 10px 0;" type="radio" name="wppd_button_type" value="<?php echo 'custom';?>" <?php if( $wppd_button_type == 'custom' ) { echo 'checked="checked" ';}?>/>
									<?php echo __( 'Custom Button', 'wppd' );?>
								</label><br />
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-custom-button"><strong><?php echo __( 'Custom Button URL:', 'wppd' ) ?></strong></label>
							</th>
							
							<td colspan='2'>
								<input type="text" id="wppd-custom-button" class="regular-text" name="wppd_custom_button" value="<?php echo $model->wppd_escape_attr( $data['wppd_custom_button'] ) ?>" />
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_custom_button'] ) ) echo $errmsg['wppd_custom_button'] ?> </span>
								<br/><span class="description"><?php echo __( 'Enter a URL to a custom donation button.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="wppd-button-localized"><strong><?php echo __( 'Country and Language:', 'wppd' ) ?></strong><span class="wppd-require"> * </span></label>
							</th>
							
							<td colspan='2'>
								<select id="wppd-button-localized" name="wppd_button_localized"><?php
									
									$wppd_button_localized	= !empty( $data['wppd_button_localized'] ) ? $data['wppd_button_localized'] : 'en_US';
									
									foreach ( $available_localized as $localized_code => $localized_name ) {
										$selected	= ( $wppd_button_localized == $localized_code ) ? ' selected="selected"' : '';
										echo '<option value="' . $localized_code . '"' . $selected . '>' . $localized_name . '</option>';
									}
								?>
								</select>
								<span class="wppd-require" ><?php if( isset( $errmsg['wppd_button_localized'] ) ) echo $errmsg['wppd_button_localized'] ?> </span>
								<br/><span class="description"><?php echo __( 'Localize the language and the country for the button.', 'wppd' ) ?></span>
							</td>
						</tr>
						
						<tr>
							<td colspan="2">
								<input name="wppd_donation_save" id="submit" class="button button-primary" value="<?php echo __( 'Save Changes', 'wppd' );?>" type="submit">
							</td>
						</tr>
						
					</tbody>
				</table>
				
			</form>
		</div>
	</div>
	
</div>