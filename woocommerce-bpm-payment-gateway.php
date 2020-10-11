<?php
/* @wordpress-plugin
 * Plugin Name:       WooCommerce BPM payment plugin
 * Version:           1.0.0
 * WC requires at least: 2.6
 * WC tested up to: 3.5
 * Author:            BPM inc
 * Author URI:        https://bpmc.co.jp
 * Domain Path: /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if(wpruby_bpm_payment_is_woocommerce_active()){
	add_filter('woocommerce_payment_gateways', 'add_bpm_payment_gateway');
	function add_bpm_payment_gateway( $gateways ){
		$gateways[] = 'WC_Bpm_Payment_Gateway';
		return $gateways; 
	}

	add_action('plugins_loaded', 'init_bpm_payment_gateway');
	function init_bpm_payment_gateway(){
		require 'class-woocommerce-bpm-payment-gateway.php';
	}

	add_action( 'plugins_loaded', 'bpm_payment_load_plugin_textdomain' );
	function bpm_payment_load_plugin_textdomain() {
	  load_plugin_textdomain( 'woocommerce-bpm-payment-gateway', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}



}


/**
 * @return bool
 */
function wpruby_bpm_payment_is_woocommerce_active()
{
	$active_plugins = (array) get_option('active_plugins', array());

	if (is_multisite()) {
		$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	}

	return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
}