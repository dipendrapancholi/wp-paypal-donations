<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * PayPal Donation List Page
 * 
 * The html markup for the product list
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */

// Load WP_List_Table if not loaded
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wppd_Donation_List extends WP_List_Table {
	
	public $model, $per_page;
	
	public function __construct() {
		
		global $wppd_model, $page;
		
		// Set parent defaults
		parent::__construct( array(
									'singular'  => wppd_paypal_donation_lable(),
									'plural'    => wppd_paypal_donation_lable( true ),
									'ajax'      => false
								) );
		
		$this->model	= $wppd_model;
		$this->per_page	= apply_filters( 'wppd_donation_list_posts_per_page', 10 ); // Per page
    }
    
    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
     */
	public function get_columns() {
	
		
        $columns = array(
    					'cb'      		=> '<input type="checkbox" />',
			            'post_title'	=> __( 'Title', 'wppd' ),
			            'post_content'	=> __( 'Shortcode', 'wppd' ),
			        );
		
		return apply_filters( 'wppd_paypal_donation_list_columns', $columns );
    }
    
    /**
     * Sortable Columns
     * 
     * Handles soratable columns of the table
     * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
     */
	public function get_sortable_columns() {
		
        $sortable_columns = array(
					            'post_title'		=> array( 'post_title', true ),
					        );
		
        return apply_filters( 'wppd_donation_list_sortable_columns', $sortable_columns );
    }
    
    /**
	 * Mange column data
	 * 
	 * Default Column for listing table
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function column_default( $item, $column_name ) {
		
        switch( $column_name ) {
            case 'post_title':
            case 'post_content':
           		return $item[ $column_name ];
			default:
				return print_r( $item, true );
        }
    }
    
    /**
	 * Render the checkbox column
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
    public function column_cb( $item ) {
    	
    	$checkbox_html	= '<input type="checkbox" name="%1$s[]" value="%2$s" />';
        return sprintf( $checkbox_html, $this->_args['singular'], $item['ID'] );
    }
    
	/**
	 * Manage Edit/Delete Link
	 * 
	 * Does to show the edit and delete link below the column cell
	 * function name should be column_{field name}
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function column_post_title( $item ) {
		
		// Build row actions
		$actions = array(
			'edit'      => sprintf( '<a href="?page=%s&action=%s&donation_id=%s">' . __( 'Edit', 'wppd' ) . '</a>', 'wppd_add_edit_donation', 'edit', $item['ID'] ),
			'delete'    => sprintf( '<a href="?page=%s&action=%s&donation[]=%s">' . __( 'Delete', 'wppd' ) . '</a>', $_REQUEST['page'], 'delete', $item['ID']),
			'duplicate' => sprintf( '<a href="?page=%s&action=%s&donation=%s">' . __( 'Duplicate', 'wppd' ) . '</a>', $_REQUEST['page'], 'duplicate', $item['ID']),
		);
		
		// Return the title contents
		return sprintf( '%1$s %2$s', $item['post_title'], $this->row_actions( $actions ) );
	}
    
    /**
     * Bulk actions field
     * 
     * Handles Bulk Action combo box values
     * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
     */
	public function get_bulk_actions() {
		
		// Bulk action combo box parameter
        $actions	= array( 'delete' => 'Delete' );
		
		return apply_filters( 'wppd_table_bulk_actions', $actions );
    }
    
    /**
	 * Process the bulk actions
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
    public function process_bulk_action() {
    	
        // Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        	wp_die(__( 'Items deleted (or they would be if we had items to delete)!', 'wppd' ));
        }
    }
    
    /**
	 * Display message when there is no items
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
    public function no_items() {
		// Message to show when no records in database table
		_e( 'No PayPal Donation found.', 'wppd' );
	}
    
    /**
	 * Displaying Prodcuts
	 * 
	 * Does prepare the data for displaying the products in the table.
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function display_paypal_donations() {
		
		$prefix 	= WPPD_META_PREFIX;
		$resultdata = array();
		
		// Taking parameter
		$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'ID';
		$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';
		$search 	= isset( $_GET['s'] ) 		? sanitize_text_field( trim($_GET['s']) )	: null;
		
		$args = array(
						'posts_per_page'	=> $this->per_page,
						'page'				=> isset( $_GET['paged'] ) ? $_GET['paged'] : null,
						'orderby'			=> $orderby,
					 	'order'				=> $order,
						'offset'  			=> ( $this->get_pagenum() - 1 ) * $this->per_page
					);
		
		
		// If search is call then pass searching value to function for displaying searching values
		if( is_string( $search ) ) {
			
			$search_param 	= explode(':', $search);
			$search_act 	= isset($search_param[0]) ? strtolower(trim($search_param[0])) : '';
			$search_value 	= isset($search_param[1]) ? trim($search_param[1]) : $search;
			
			// If meta query is not there then make search param
			if( empty($args['meta_query']) ) {
				$args['s'] = $search;
			}
		}
		
		// Function to retrive data
		$data	= $this->model->wppd_get_paypal_donations( $args );
		
		if( !empty( $data['data'] ) ) {
			
			// Re generate data
			foreach( $data['data'] as $key => $value ) {
				
				$resultdata[$key]['ID'] 			= $value['ID'];
				$resultdata[$key]['post_title'] 	= $value['post_title'];
				$resultdata[$key]['post_content'] 	= '[paypal_donation id = '.$value['ID'].']';
			}
		}
		
		$result_arr['data']		= !empty($resultdata) 	? $resultdata 		: array();
		$result_arr['total'] 	= isset($data['total']) ? $data['total'] 	: ''; // Total no of data
		
		return $result_arr;
	}
	
	/**
	 * Setup the final data for the table
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function prepare_items() {
		
		// Get how many records per page to show
        $per_page	= $this->per_page;
        
        // Get All, Hidden, Sortable columns
        $columns	= $this->get_columns();
        $hidden		= array();
		$sortable	= $this->get_sortable_columns();
        
		// Get final column header
		$this->_column_headers	= array( $columns, $hidden, $sortable );
        
		// Proces bulk action
		$this->process_bulk_action();
        
		// Get Data of particular page
		$data_res 	= $this->display_paypal_donations();
		$data 		= $data_res['data'];
       	
		// Get current page number
		$current_page	= $this->get_pagenum();
		
		// Get total count
        $total_items	= $data_res['total'];
		
        // Get page items
        $this->items	= $data;
        
		// We also have to register our pagination options & calculations.
		$this->set_pagination_args( array(
										'total_items' => $total_items,
										'per_page'    => $per_page,
										'total_pages' => ceil($total_items/$per_page)
									) ); 
    }
}

// Create an instance of our package class...
$PayPalDonationListTable = new Wppd_Donation_List();

// Fetch, prepare, sort, and filter our data...
$PayPalDonationListTable->prepare_items();

// Creating page link
$manage_product_page = add_query_arg( array( 'page' => 'wppd_add_edit_donation' ), admin_url( 'admin.php' ) );

?>

<!-- List Table Output Starts Here -->
<div class="wrap ww-spt-wrap">
    <h2><?php 
    	_e( 'PayPal Donations', 'wppd' ); ?>
    	<a class="add-new-h2" href="<?php echo $manage_product_page; ?>"><?php _e( 'Add New','wppd' ); ?></a>
    </h2><?php
		
	if( isset( $_GET['message'] ) && !empty( $_GET['message'] ) ) { //check message
		if( $_GET['message'] == '1' ) { // Check insert message
			$msg = __( 'PayPal Donation Inserted Successfully.','wppd' );
		} else if( $_GET['message'] == '2' ) { // Check update message
			$msg = __( 'PayPal Donation Updated Successfully.','wppd' );
		} else if( $_GET['message'] == '3' ) { // Check delete message
			$msg = __( 'PayPal Donation deleted Successfully.','wppd' );
		} else if( $_GET['message'] == '4' ) { // Check delete message
			$msg = __( 'Status Changed Successfully.','wppd' );
		}  else if( $_GET['message'] == '5' ) { // Check duplicate message
			$msg = __( 'PayPal Donation Create Duplicate.','wppd' );
		}
	}
		
	// Displaying message
	if( !empty( $msg ) ) {
		$html = '<div class="updated" id="message">
					<p><strong>'.$msg.'</strong></p>
				</div>';
		echo $html;
	} ?>
	
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        
        <?php //$PayPalDonationListTable->views() ?>
        
        <!-- Search Title -->
        <?php $PayPalDonationListTable->search_box( __( 'Search', 'wppd' ), 'wppd_search' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $PayPalDonationListTable->display() ?>
        
    </form>
</div><!-- end .ww-spt-wrap -->
<!-- List Table Output Ends Here -->