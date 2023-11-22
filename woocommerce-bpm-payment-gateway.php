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


register_activation_hook( __FILE__, 'table_create');
	
$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));

// 注文詳細にBPM決済詳細を表示するためのフック
add_action('woocommerce_admin_order_data_after_shipping_address','bpm_payment_detail');

if(wpruby_bpm_payment_is_woocommerce_active()){
	add_filter('woocommerce_payment_gateways', 'add_bpm_payment_gateway');
	function add_bpm_payment_gateway( $gateways ){
		$gateways[] = 'WC_Bpm_Payment_Gateway';
		return $gateways; 
	}
	// register_activation_hook('woocommerce-bpm-payment/woocommerce-bpm-payment-gateway.php', 'test2' );
	// function test2(){
	// 	file_put_contents(
	// 		'/var/www/logs/payment.log',
	// 		"register_activation2".PHP_EOL,
	// 		FILE_APPEND,
	// 		);
	// }

	// add_filter('woocommerce_payment_gateways','test_file');
	// 	function test_file(){
	// 	file_put_contents(
	// 		'/var/www/logs/payment.log',
	// 		"payment_gateways".PHP_EOL,
	// 		FILE_APPEND,
	// 	  );
	// }

	add_action('plugins_loaded', 'init_bpm_payment_gateway');
	function init_bpm_payment_gateway(){

		// file_put_contents(
		// 	'/var/www/logs/payment.log',
		// 	"plugin_loaded".PHP_EOL,
		// 	FILE_APPEND,
		//   );
		require 'class-woocommerce-bpm-payment-gateway.php';

		// 以下Returnまで追記
		// if ( is_admin() && current_user_can( 'active_plugins') && !is_plugin_active( 'woocommerce/woocommerce.php') ) {
 
		// 	// Show dismissible error notice
		// 	add_action( 'admin_notices', 'my_plugin_woocommerce_check_notice' );
	 
		// 	// Deactivate this plugin
		// 	deactivate_plugins( plugin_basename( __FILE__) );
		// 	if ( isset( $_GET['activate'] ) ) {
		// 		unset( $_GET['activate'] );
		// 	}
		// 	return;
		// }  
	
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

// 以下追記

function my_plugin_woocommerce_check_notice() {
    ?>
    <div class="alert alert-danger notice is-dismissible">
        <p>Sorry, WooCommerce plugin sholud be installed and activated.
        </p>
    </div>
    <?php
}

function table_create(){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		// $table_name = $wpdb->prefix . 'BPM_payment_table_example';

		$sql = "CREATE TABLE `wp_wc_bpm_payment_trans` (
			`id` int unsigned NOT NULL AUTO_INCREMENT,
			`wp_wc_orders_id` BIGINT unsigned NOT NULL COMMENT 'wp_wc_orders.id',
			`tran_code` VARCHAR(20) NOT NULL COMMENT '決済承認番号',
			`dba` VARCHAR(128) NOT NULL COMMENT '請求表記名',
			PRIMARY KEY (`id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=100001;";
		

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
}

function bpm_payment_detail(){
	$payment_id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '';

	// wpdbオブジェクトを生成
	global $wpdb;
	$table_name = $wpdb->prefix .'wc_bpm_payment_trans';

	// wp-wc-ordersから最新のIDを取得
	$data = $wpdb -> get_row($wpdb -> prepare("SELECT * FROM $table_name WHERE wp_wc_orders_id = %d" , $payment_id));

	if($data){
		$tran_code = $data -> tran_code;
		$dba = $data -> dba;
	
		echo "<div class='order_data_column_container'>
				<div class='order_data_column' style='width:1000px;'>
					<h3>BPMクレジット決済<a href='https://merchant.bpmc.jp' style='margin:0 0 0 10rem;'>加盟店管理画面へ</a></h3>
					<p>承認番号：$tran_code</p>
					<p>請求番号：$dba</p>
				</div>
			</div>";
	}
	
};