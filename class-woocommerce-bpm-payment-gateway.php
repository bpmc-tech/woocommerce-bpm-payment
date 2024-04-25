<?php 
require_once("functions.php");
class WC_Bpm_Payment_Gateway extends WC_Payment_Gateway{

  public $api_token;
  public $log_permission;
  /*
   * construct
   +++++++++++++++++++++++++++++++++++++++*/
  public function __construct(){
    $this->id = 'bpm_payment';
    $this->method_title = __('BPM Payment','woocommerce-bpm-payment-gateway');
    $this->title = __('BPM Payment','woocommerce-bpm-payment-gateway');
    $this->has_fields = true;
    $this->init_form_fields();
    $this->init_settings();
    $this->enabled = $this->get_option('enabled');
    $this->title = $this->get_option('title');
    $this->api_token = $this->get_option('api_token');

    $this->log_permission = $this->get_option('log_permission');
    
    // セレクトボックスの値を保存するためのアクションフックを設定
    add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options')); 
    // 支払いページにJUMP用
    add_action( 'woocommerce_api_bpm_payment_bridge', array( $this, 'bridge' ) );
    // 決済結果受信Webhookポイント
    add_action( 'woocommerce_api_bpm_payment_ipn', array( $this, 'ipn' ) );
    // 決済結果戻りURL
    add_action( 'woocommerce_api_bpm_payment_callback', array( $this, 'callback' ) );

  }

  /*
   * 設定画面の初期化
   +++++++++++++++++++++++++++++++++++++++*/
  public function init_form_fields(){

      $this->form_fields = array(
        'enabled' => array(
          'title'     => __( '支払い名称', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'checkbox',
          'label'     => __( 'Enable BPM Payment', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
        ),
        'title' => array(
          'title'     => __( 'Method Title', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'This controls the title', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'BPM Payment', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'api_token' => array(
          'title'     => __( 'API TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'log_permission' => array(
          'title' => __( 'ログ出力をする', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'はい', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'no'
       
        )
    );
  }

  /**
   * Admin Panel Options
   * - Options for bits like 'title' and availability on a country-by-country basis
   *
   * @since 1.0.0
   * @return void
   */
  public function admin_options() {
    ?>
    <style>
      table{
        line-height: 0.5em;
      }
    </style>
    <h3><?php _e( 'BPM Payment Settings', 'woocommerce-bpm-payment-gateway' ); ?></h3>
      <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
          <div id="post-body-content">
            <table style='width:20rem; line-height: 2rem;'>
              <?php $this->generate_settings_html(); ?>
              
            </table>
          </div>
        </div>
        </div>
        <div class="clear"></div>
        <style type="text/css">
        .wpruby_button{
          background-color:#4CAF50 !important;
          border-color:#4CAF50 !important;
          color:#ffffff !important;
          width:100%;
          padding:5px !important;
          text-align:center;
          height:35px !important;
          font-size:12pt !important;
        }
        </style>
<?php
  }

  /*
   * 店舗画面支払い処理
   +++++++++++++++++++++++++++++++++++++++*/
  public function process_payment( $order_id ) {

    $redirect_url = get_bloginfo('url')."/wc-api/bpm_payment_bridge/?order_id=".$order_id;
    return array(
      'result'    => 'success',
      'redirect'  => $redirect_url
    );    
  }

  public function bridge(){

    global $woocommerce;
    
    $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : null;
    $order = wc_get_order( $order_id );


    $item_name = '';
    $i = 0;
    foreach( WC()->cart->get_cart() as $cart_item ){
      if($i == 0){
        $item_name = $cart_item['data']->get_name();
      }
      $i++;
    }
    if($i > 1){
      $item_name.= ' and more '. ($i-1);
    }


    $callback_url = get_bloginfo('url')."/wc-api/bpm_payment_callback/";


    //header( 'HTTP/1.1 200 OK' );
    $html = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
      <title>BPM PAYMENT</title>
      <meta charset="utf-8">
    </head>
    <body>
      <form method="post" id="myForm" action="https://payment.bpmc.jp/link/{$this->api_token}/payment">
        <input type="hidden" name="product" value="{$item_name}" />
        <input type="hidden" name="amount" value="{$order->get_total()}" />
        <input type="hidden" name="currency_code" value="{$order->currency}" />
        <input type="hidden" name="shop_tracking" value="{$order_id}" />
        <input type="hidden" name="callback_url" value="{$callback_url}" />
        <input type="hidden" name="email" value="{$order->get_billing_email()}" />
        <input type="hidden" name="tel" value="{$order->get_billing_phone()}" />
        <input type="hidden" name="shop_data1" value="" />
        <input type="hidden" name="shop_data2" value="" />
        <input type="hidden" name="shop_data3" value="" />   
        <!-- <button type="submit">aaa<button> -->
      </form>
      <script type="text/javascript">
        window.onload = function() {
            // フォームのsubmit()メソッドを呼び出す
            document.getElementById("myForm").submit();
        };
      </script>
    </body>
    </html>
    HTML;

    echo $html;
    exit();
  }

  public function ipn(){
    global $woocommerce;
    $order_id = isset($_REQUEST['shop_tracking']) ? $_REQUEST['shop_tracking'] : null;
    $order = wc_get_order( $order_id );

    $tran_code = isset($_REQUEST['tran_code']) ? $_REQUEST['tran_code'] : null;
    $dba = isset($_REQUEST['dba']) ? $_REQUEST['dba'] : null;

    output_log('ipn'.$tran_code);
    output_log('ipn'.$dba);

    // 完了処理

    // Reduce stock levels
    wc_reduce_stock_levels( $order_id );

    // データ挿入処理
    // wpdbオブジェクトを生成
    global $wpdb;

    // デバッグ用
    $wpdb->show_errors();
    $wc_orders = $wpdb->prefix .'wc_orders';

    // wp-wc-ordersから最新のIDを取得
    $id = $wpdb -> get_var("SELECT MAX(id) FROM $wc_orders");
  
    // insert処理
    $table_name = $wpdb->prefix ."wc_bpm_payment_trans";
    $data = array(
      'wp_wc_orders_id' => $id,
      'tran_code' => $tran_code,
      'dba' => $dba,
    );

    $wpdb -> insert($table_name, $data);
    
    if(isset($_POST[ $this->id.'-admin-note']) && trim($_POST[ $this->id.'-admin-note'])!=''){
      $order->add_order_note(esc_html($_POST[ $this->id.'-admin-note']),1);
    }

    // Payment complete
    $order->payment_complete();


  }


  public function callback(){
    global $woocommerce;
    $order_id = isset($_REQUEST['shop_tracking']) ? $_REQUEST['shop_tracking'] : null;
    $cancel = isset($_REQUEST['cancel']) ? $_REQUEST['cancel'] : '0';
    $order = new WC_Order($order_id);
    
    

    $redirect_url = get_bloginfo('url')."/checkout/";

    if($cancel === '1'){
      // do_action( 'woocommerce_set_cart_cookies',  true );
      //　支払いキャンセルされたときの処理
      wc_add_notice( '支払いがキャンセルされました.', 'error' );
      wp_redirect( $redirect_url);

      exit;
    }

    // 決済成功時
    // 各種DB等々更新
    wp_redirect( $this->get_return_url( $order ));

    // return array(
    //   'result' => 'success',
    //   'redirect' => $this->get_return_url( $order )
    // );

  }

  public function payment_fields(){
    ?>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.4/jquery.card.js"></script> 
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.min.css">
        <div>
            <h4>BPMでの決済の場合は以下の『注文する』をクリックしてください</h4>
        </div>
      <script type="text/javascript">
      </script>
      <?php
    }
  } 