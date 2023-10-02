<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortocde UI
 *
 * This is the code for the pop up editor, which shows up when an user clicks
 * on the fb review engine icon within the WordPress editor.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
 global $wppd_model;
 
?>

<div class="wppd-popup-content">

	<div class="wppd-header-popup">
		<div class="wppd-popup-header-title"><?php _e( 'Add a PayPal Donation', 'wppd' );?></div>
		<div class="wppd-popup-close"><a href="javascript:void(0);" class="wppd-popup-close-button"><img src="<?php echo WPPD_URL;?>assets/images/tb-close.png"></a></div>
	</div>
	<div class="wppd-popup-error"><?php _e( 'Select a Shortcode', 'wppd' );?></div>
	<div class="wppd-popup-container">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php _e( 'Select a PayPal Donation', 'wppd' );?></label>		
					</th>
					<td>
						<select id="wppd_shortcode">	
						<option value=""><?php _e( '--Select PayPal Donation--', 'wppd' );?></option>
							<?php
								
								$args	= array(
												  'post_status'		=> 'publish',
												  'posts_per_page'	=> -1,								
											); 
								
								$wppd_data	= $wppd_model->wppd_get_paypal_donations( $args );
								
								foreach( $wppd_data['data'] as $key => $value) { ?>		
									<option value="<?php echo $value['ID'] ?>"><?php _e( $value['post_name'], 'wppd' );?></option><?php
								}?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<div id="wppd_insert_container" >
			<input type="button" class="button-secondary" id="wppd_insert_shortcode" value="<?php _e( 'Insert Shortcode', 'wppd' ); ?>">
		</div>
</div>

</div><!--.wppd-popup-content-->
<div class="wppd-popup-overlay" ></div><!--.wppd-popup-overlay-->