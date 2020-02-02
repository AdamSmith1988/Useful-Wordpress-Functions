<?php
/**
 * Useful Wordpress Functions
 */
 
 
//Allow SVG uploads
function add_file_types_to_uploads($file_types){
$new_filetypes = array();
$new_filetypes['svg'] = 'image/svg+xml';
$file_types = array_merge($file_types, $new_filetypes );
return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');


// Auto Complete all WooCommerce orders.
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}



// CURRENT FIX TO SHOW ORDERS IN WOOCOMMERCE 
function fix_request_query_args_for_woocommerce( $query_args ) {
	if ( isset( $query_args['post_status'] ) && empty( $query_args['post_status'] ) ) {
		unset( $query_args['post_status'] );
	}
	return $query_args;
}
add_filter( 'request', 'fix_request_query_args_for_woocommerce', 1, 1 );



//ALLOW ANY WOOCOMMERCE URL TO BE BYPASSED
function my_forcelogin_bypass( $bypass ) {
  if ( class_exists( 'WooCommerce' ) ) {
    if ( is_woocommerce() || is_wc_endpoint_url() ) {
      $bypass = true;
    }
  }
  return $bypass;
}
add_filter( 'v_forcelogin_bypass', 'my_forcelogin_bypass' );



// HIDE BACK TO LOGIN SCREEN
add_action( 'login_head', 'hide_login_nav' );

function hide_login_nav()
{
    ?><style>#backtoblog{display:none}</style><?php
}



// ALLOW ARRAY OF PAGES TO CORPORATE, IF NOT CORPORATE REDIRECT TO ACCESS-DENIED
add_action( 'template_redirect', 'adam_test_redirect' );
function adam_test_redirect() {
    if( is_page( array( 1488, 2413, 2379, 2265, 2396, 2370, 2366, 4600 ) ) ) { //check the list of "corporate" pages
        $user = wp_get_current_user();
        $valid_roles = [ 'administrator', 'corporate', 'editor' ];

        $the_roles = array_intersect( $valid_roles, $user->roles );

        // The current user does not have any of the 'valid' roles.
        if ( empty( $the_roles ) ) {
            wp_redirect( home_url( '/access-denied/' ) );
            exit;
        }
    }
}


 // ACCESS AND PAY FOR A CERTAIN ITEM WITHOUT LOGGING IN
add_filter('v_forcelogin_whitelist', 'my_forcelogin_whitelist', 10, 1);
	function my_forcelogin_whitelist() {
		return array(
			home_url( '/cart/' ),
			home_url( '/checkout/' ),
			home_url( '/cart/?add-to-cart=1465' ), //CV Critique to test
			home_url( '/cart/?add-to-cart=5332' ), // industry 4.0
			home_url( '/cart/?add-to-cart=5331' ), // Artificial Intelligence
			home_url( '/cart/?add-to-cart=5330' ), // Customer Centric Supply Chains
			home_url( '/cart/?add-to-cart=5329' ), // Logistics 4.0
			home_url( '/cart/?add-to-cart=5328' ), // Supply Chain 4.0
			home_url( '/cart/?add-to-cart=5339' ), // Full Package of Courses

			home_url( '/cart/?add-to-cart=5409' ), // 3 Month Course Extension
			home_url( '/cart/?add-to-cart=5410' ), // 6 Month Course Extension
			home_url( '/cart/?add-to-cart=5411' ), // 9 Month Course Extension
			home_url( '/cart/?add-to-cart=5412' ), // 12 Month Course Extension
			home_url( '/cart/?add-to-cart=5413' ), // 18 Month Course Extension
			
			home_url( '/cart/?add-to-cart=5415' ), // 3 Month Course Time Renewal
			home_url( '/cart/?add-to-cart=5416' ), // 6 Month Course Time Renewal
			home_url( '/cart/?add-to-cart=5417' ), // 9 Month Course Time Renewal
			home_url( '/cart/?add-to-cart=5418' ), // 12 Month Course Time Renewal
			home_url( '/cart/?add-to-cart=5419' ), // 18 Month Course Time Renewal

			home_url( '/cart/?add-to-cart=2626' ), // Graded Membership
			home_url( '/cart/?add-to-cart=2627' ), // Graded Membership - fellow

			home_url( '/cart/?add-to-cart=6201' ), // Admin Fee 1 - £25
			home_url( '/cart/?add-to-cart=6203' ), // Admin Fee 2 - £50
			home_url( '/cart/?add-to-cart=6204' ), // Admin Fee 3 - £75

			home_url( '/cart/?add-to-cart=5621' ), // Studies on hold

			// home_url('/recruitment/jobs/'),		  // See all Jobs

			

		);
	} 


//if role = complimentary redirect user from Learning and Development page
add_action( 'template_redirect', 'redirect_to_specific_page' );
	function redirect_to_specific_page() {

		if ( is_page('learning-and-development') && current_user_can('complimentary') ) {
			wp_redirect( '/', 301 ); 
  			exit;
    }
}

  // chacnge the role of a user after buying a certain product.
/*

add_action( 'woocommerce_order_status_completed', 'change_role_on_purchase' );
function change_role_on_purchase( $order_id ) {
    $order = wc_get_order( $order_id );
    $items = $order->get_items();

    $products_to_check = array( '4681' );

    foreach ( $items as $item ) {
        if ( $order->user_id > 0 && in_array( $item['product_id'], $products_to_check ) ) {
        	$user = new WP_User( $order->user_id );

        	// Change role
        	$user->remove_role( 'customer' );
        	$user->remove_role( 'subscriber' );
        	$user->add_role( 'new-role' );

            // Exit the loop
            break;
    	}
    }
}

*/