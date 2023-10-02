// JavaScript Document
jQuery( document ).ready( function( $ ) {
	
	/*** Try to add Shortcode **/
	// Start Shortcodes Click
	( function() {
		
		tinymce.create( 'tinymce.plugins.wppdshortcodes', {
			
	        init : function( ed, url ) {
	        	
	            ed.addButton( 'wppdshortcodes', {
	                
	            	title : 'PayPal Donation Shortcode',
	                image : url+'/images/wppd-shortcode.png',
	                onclick : function() {
	                    
						jQuery('.wppd-popup-overlay').fadeIn();
	                    jQuery('.wppd-popup-content').fadeIn();
	                    
	                    jQuery('#wppd_shortcode').val('');
						
	                    jQuery('#wppd_box_type_select').val('');
	                    jQuery('#wppd_box_content').val('');
	                    jQuery('.wppd-shortcodes-options').hide();
	                    jQuery('.wppd_box_content').val('');
	 				}
	            });
	        },
	        createControl : function( n, cm ) {
	            return null;
	        },
	    });
		
	    tinymce.PluginManager.add( 'wppdshortcodes', tinymce.plugins.wppdshortcodes );
	    
	})();
	
	jQuery( document ).on( 'click', '.wppd-popup-close-button, .wppd-popup-overlay', function () {
		jQuery( '.wppd-popup-overlay' ).fadeOut();
		jQuery( '.wppd-popup-content' ).fadeOut();
	});
	
	jQuery( document ).on( 'click', '#wppd_insert_shortcode', function () {
		
		var shortcode = jQuery( '#wppd_shortcode' ).val();
		//alert(shortcode);
		
		var shortcodestr = '';
		if( shortcode == '' ) {
			
			jQuery( '.wppd-popup-error' ).fadeIn();
			return false;
			
		} else {
			
			jQuery( '.wppd-popup-error' ).hide();
			
			shortcodestr += '[paypal_donation id = '+shortcode+']';
			
			//send_to_editor(str);
	        //tinymce.get('content').execCommand('mceInsertContent',false, shortcodestr);
	        window.send_to_editor( shortcodestr );
	  		jQuery( '.wppd-popup-overlay' ).fadeOut();
			jQuery( '.wppd-popup-content' ).fadeOut();
		}
	});
});