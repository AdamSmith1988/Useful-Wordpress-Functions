<?php
/**
 * Extra files & functions are hooked here.
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package Avada
 * @subpackage Core
 * @since 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( ! defined( 'AVADA_VERSION' ) ) {
	define( 'AVADA_VERSION', '7.11.9' );
}

if ( ! defined( 'AVADA_MIN_PHP_VER_REQUIRED' ) ) {
	define( 'AVADA_MIN_PHP_VER_REQUIRED', '5.6' );
}

if ( ! defined( 'AVADA_MIN_WP_VER_REQUIRED' ) ) {
	define( 'AVADA_MIN_WP_VER_REQUIRED', '4.9' );
}

// Developer mode.
if ( ! defined( 'AVADA_DEV_MODE' ) ) {
	define( 'AVADA_DEV_MODE', false );
}

/**
 * Compatibility check.
 *
 * Check that the site meets the minimum requirements for the theme before proceeding.
 *
 * @since 6.0
 */
if ( version_compare( $GLOBALS['wp_version'], AVADA_MIN_WP_VER_REQUIRED, '<' ) || version_compare( PHP_VERSION, AVADA_MIN_PHP_VER_REQUIRED, '<' ) ) {
	require_once get_template_directory() . '/includes/bootstrap-compat.php';
	return;
}

/**
 * Bootstrap the theme.
 *
 * @since 6.0
 */
require_once get_template_directory() . '/includes/bootstrap.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */


//////////
//////////
//////////
//////////////// START OF ADAMS FIXES
//////////
//////////
//////////

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



//ALLOW ANY WOOCOMMERCE UL TO BE BYPASSED
function my_forcelogin_bypass( $bypass ) {
  if ( class_exists( 'WooCommerce' ) ) {
    if ( is_woocommerce() || is_wc_endpoint_url() ) {
      $bypass = true;
    }
  }
  return $bypass;
}
add_filter( 'v_forcelogin_bypass', 'my_forcelogin_bypass' );



// bHIDE BACK TO IOSCM HUB FROM LOGIN SCREEN
add_action( 'login_head', 'hide_login_nav' );

function hide_login_nav()
{
    ?><style>#backtoblog{display:none}</style><?php
}



 //ALLOW ARRAY OF PAGES TO CORPORATE, IF NOT CORPORATE REDIRECT TO ACCESS-DENIED
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
			home_url( '/cart/?add-to-cart=6245' ), // Global Membership

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

			
			home_url( '/cart/?add-to-cart=2518' ), // Student Membership

			home_url( '/cart/?add-to-cart=15931' ), // Graded Membership
			home_url( '/cart/?add-to-cart=2627' ), // Graded Membership - [FELLOW]
			home_url( '/cart/?add-to-cart=2626' ), // Graded Membership - [EXPERT]
			home_url( '/cart/?add-to-cart=5739' ), // Graded Membership - [AFFILIATE]
			home_url( '/cart/?add-to-cart=5738' ), // Graded Membership - [ASSOCIATE]
			home_url( '/cart/?add-to-cart=5737' ), // Graded Membership - [PROFESSIONAL]
			home_url( '/student-application-form/' ), //indian applciation form

			home_url( '/cart/?add-to-cart=16002' ), // Awards Reservation full price
			home_url( '/cart/?add-to-cart=16001' ), // Awards Reservation Discount


			home_url( '/the-sustain-chain/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/the-sustain-chain-accreditation/' ), //
			//home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/sustainability/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/carbon-footprint/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/recycle-reuse/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/corporate-social-responsibility/' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/legislation-standards-and-initiatives//' ), //
			home_url( '/corporate-area/toolkits/the-sustain-chain-toolkit/the-sustain-chain-accreditation/' ), //

			home_url( '/corporate-area/toolkits/coronavirus-toolkit/' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/news-briefings/' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/covid-19-facts/' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/official-communications/' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/business-support//' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/individual-resources/' ), //
			home_url( '/corporate-area/toolkits/coronavirus-toolkit/recovery-plans/' ), //

			home_url( '/membership-options/' ), //
			home_url( '/contact-us/' ), //

			home_url( '/ixion-registration-page/' ), //Ixion Registration Page
			home_url( '/petroc-registration-page/' ), //Petroc Registration Page

			home_url( '/post-a-job-vacancy/' ), //Petroc Registration Page

			

		);
	} 


add_filter( 'rest_authentication_errors', '__return_true' ); //this allows the submission of a contact form 7 form on an authenticated page

//military-membership
add_action('wp_head', 'hidingCss');
function hidingCss() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $role = (array) $user->roles;
        if($role[0] == 'military'){ 
            echo '<style>#military-membership { display: inline-block !important; }</style>';  
        }
    } else {
        return true;
    }
}


//////***Watermark for certificates***//////
 function write_something_after_waterwoo_writes( $pdf, $pageno ) {

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('courier', 'B', 11);
        $pdf->SetLeftMargin(114);
        $pdf->Write(15, "Valid Until: " . date('j, F Y', strtotime("+1 years")));
        
    } 
    add_action( 'wwpdf_after_write', 'write_something_after_waterwoo_writes', 10, 2);





//////***Add a description to "Additional Information" tab"***//////
function custom_woocommerce_order_notes_placeholder( $placeholder ) {
	$placeholder['order']['order_comments']['placeholder']= 'NOTE:: If you are purchasing this product for another learner/learners, please provide their name here';
	$placeholder['order']['order_comments']['label']='Order Notes';
    return $placeholder;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_woocommerce_order_notes_placeholder' );


/* Increase Woocommerce Variation Threshold */
 function wc_ajax_variation_threshold_modify( $threshold, $product ){
  $threshold = '1111';
  return  $threshold;
 }
add_filter( 'woocommerce_ajax_variation_threshold','wc_ajax_variation_threshold_modify', 10, 2 );


///TAKES off the "/ for one month" only for pay in full options
/*add_filter( 'woocommerce_subscriptions_product_price_string_inclusions', 'wcs_remove_length_from_one_off_subscriptions', 10, 2 );

function wcs_remove_length_from_one_off_subscriptions( $inclusions, $product ) {
    if ( isset( $inclusions['subscription_length'] ) && isset( $inclusions['subscription_period'] ) && WC_Subscriptions_Product::get_length( $product ) == 1 ) {
      $inclusions['subscription_length'] = false;
      $inclusions['subscription_period'] = false;
}
    return $inclusions;
}*/


/*--------------------------------------
Woocommerce - Allow Guest Checkout on Certain products
----------------------------------------*/

// Display Guest Checkout Field
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
function woo_add_custom_general_fields() {
  global $woocommerce, $post;
  
  echo '<div class="options_group">';
  
  // Checkbox
  woocommerce_wp_checkbox( 
  array( 
	'id'            => '_allow_guest_checkout', 
	'wrapper_class' => 'show_if_simple', 
	'label'         => __('Checkout', 'woocommerce' ), 
	'description'   => __('Allow Guest Checkout', 'woocommerce' ) 
	)
   );
  
  echo '</div>';
}

// Save Guest Checkout Field
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );
function woo_add_custom_general_fields_save( $post_id ){
	$woocommerce_checkbox = isset( $_POST['_allow_guest_checkout'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_allow_guest_checkout', $woocommerce_checkbox );
}

// Enable Guest Checkout on Certain products
add_filter( 'pre_option_woocommerce_enable_guest_checkout', 'enable_guest_checkout_based_on_product' );
function enable_guest_checkout_based_on_product( $value ) {

  if ( WC()->cart ) {
    $cart = WC()->cart->get_cart();
    foreach ( $cart as $item ) {
      if ( get_post_meta( $item['product_id'], '_allow_guest_checkout', true ) == 'yes' ) {
        $value = "yes";
      } else {
        $value = "no";
        break;
      }
    }
  }
  
  return $value;
}




