<?php 
require_once("functions.php");
class WC_Bpm_Payment_Gateway extends WC_Payment_Gateway{
  // function WC_Bpm_Payment_Gateway_class(){} 
  // protected $api_endpoint = $this->get_option('api_endpoint');
  // protected $api_token = $this->get_option('api_token');
  // protected $api_secret = $this->get_option('api_secret');
  // protected $cc_brand = $this->get_option('cc_brand');
  public $api_endpoint;
  public $api_token;
  public $api_secret;
  // public $cc_brand;
  // public $cc_brand_master;
  // public $cc_brand_jcb;
  // public $cc_brand_amex;
  // public $cc_brand_diners;
  public $cc_brand_array;
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

    // $this->jpy_shop_id = $this->get_option('jpy_shop_id');
    $this->api_endpoint = $this->get_option('api_endpoint');
    $this->api_token = $this->get_option('api_token');
    $this->api_secret = $this->get_option('api_secret');

    //$this->jpy_endpoint = $this->get_option('jpy_endpoint');

    //$this->bpm_shop_id = $this->get_option('bpm_shop_id');
    //$this->bpm_endpoint = $this->get_option('bpm_endpoint');
    //$this->uds_api_token = $this->get_option('jpy_api_token');
    //$this->jpy_api_secret = $this->get_option('jpy_api_secret');
    
    // $this->description = $this->get_option('description');
    // $this->hide_text_box = $this->get_option('hide_text_box');
    // $this->cc_brand = $this->get_option('cc_brand');
    // $this->cc_brand_master = $this->get_option('cc_brand2');
    // $this->cc_brand_jcb = $this->get_option('cc_brand3');
    // $this->cc_brand_amex = $this->get_option('cc_brand4');
    // $this->cc_brand_diners = $this->get_option('cc_brand5');
    
    $this->cc_brand_array = array($this->get_option('cc_brand_visa'),$this->get_option('cc_brand_master'),$this->get_option('cc_brand_jcb'),$this->get_option('cc_brand_amex'),$this->get_option('cc_brand_diners'));
    $this->log_permission = $this->get_option('log_permission');
    
    // セレクトボックスの値を保存するためのアクションフックを設定
    add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
    // add_action( 'woocommerce_update_options_payment_gateways_', 'add_custom_settings_fields', 20 );
  }

  /*
   * 設定画面の初期化
   +++++++++++++++++++++++++++++++++++++++*/
  public function init_form_fields(){
    // $options = array(
    //   'option1' => 'オプション1',
    //   'option2' => 'オプション2',
    //   'option3' => 'オプション3',
    //   // 他のチェックボックスオプションを追加
    // );
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
        'api_endpoint' => array(
          'title'     => __( 'Endpoint URL', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'JPY Endpoint', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'https://payment.bpmc.jp/gateway/:API_TOKEN/payment', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'api_token' => array(
          'title'     => __( 'API TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'api_secret' => array(
          'title'     => __( 'API SECRET', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'API_SECRET', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'API_SECRET', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        /*
        'jpy_shop_id' => array(
          'title'     => __( 'Shop ID for yen currency', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'Shop ID for yen currency', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'ShopId', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        
        'bpm_shop_id' => array(
          'title'     => __( 'Shop ID for bpm currency', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'Shop ID for bpm currency', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'ShopId', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'bpm_endpoint' => array(
          'title'     => __( 'bpm Endpoint', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'bpm Endpoint', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'https://mc.bpmc.jp /gateway/v2/payment.php', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        */
        // 'description' => array(
        //   'title' => __( 'Description', 'woocommerce-bpm-payment-gateway' ),
        //   'type' => 'textarea',
        //   'css' => 'width:500px;',
        //   'default' => 'None of the bpm payment options are suitable for you? please drop us a note about your favourable payment option and we will contact you as soon as possible.',
        //   'description'   => __( 'The message which you want it to appear to the customer in the checkout page.', 'woocommerce-bpm-payment-gateway' ),
        // ),
        // 'hide_text_box' => array(
        //   'title'     => __( 'Hide The Payment Field', 'woocommerce-bpm-payment-gateway' ),
        //   'type'      => 'checkbox',
        //   'label'     => __( 'Hide', 'woocommerce-bpm-payment-gateway' ),
        //   'default'     => 'no',
        //   'description'   => __( 'If you do not need to show the text box for customers at all, enable this option.', 'woocommerce-bpm-payment-gateway' ),
        // ),
        // $this->form_fields['cc_brand'] = array(
        //   'title' => __( 'cc_brand', 'woocommerce-bpm-payment-gateway' ),
        //   'type' => 'multi_select',              
        //   'description'  => __( 'card brand', 'woocommerce-bpm-payment-gateway' ),
        //   'desc_tip'    => true,
        //   'options'   => array(
        //         'option1' => 'VISA',
        //         'option2' => 'Master Card',
        //         'option3' => 'JCB',
        //   ),
        // );  

        'cc_brand_visa' => array(
          'title' => __( '利用可能なブランド', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'VISA', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
       
        ),
        'cc_brand_master' => array(
          'title' => __( '', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'Master', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
       
        ),
        'cc_brand_jcb' => array(
          'title' => __( '', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'JCB', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
       
        ),
        'cc_brand_amex' => array(
          'title' => __( '', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'AMEX', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
       
        ),
        'cc_brand_diners' => array(
          'title' => __( '', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'Diners Card', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'yes'
       
        ),
        'log_permission' => array(
          'title' => __( 'ログ出力をする', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'checkbox',                            
          'label'     => __( 'はい', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'no'
       
        ),
    
      //   'options' => array(
      //     'title'   => 'オプション',
      //     'type'    => 'checkbox',
      //     'default' => array(), // チェックボックスの選択状態は空の配列で初期化
      //     'options' => array(
      //         'option1' => 'オプション 1',
      //         'option2' => 'オプション 2',
      //         'option3' => 'オプション 3',
      //         // 他のオプションを追加
      //     ),
      // ),
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
            <!-- <table class="form-table"> -->
              <?php 
              $this->generate_settings_html();
              
              ?>
              
            </table><!--/.form-table-->
          </div>
          <!-- <div id="postbox-container-1" class="postbox-container">
                          <div id="side-sortables" class="meta-box-sortables ui-sortable"> 
                             
                  <div class="postbox">
                                  <div class="handlediv" title="Click to toggle"><br></div>
                                  <h3 class="hndle"><span><i class="dashicons dashicons-update"></i>&nbsp;&nbsp;Upgrade to Pro</span></h3>
                                  <div class="inside">
                                      <div class="support-widget">
                                          <ul>
                                              <li>» Full Form Builder</li>
                                              <li>» Custom Gateway Icon</li>
                                              <li>» Order Status After Checkout</li>
                                              <li>» Custom API Requests</li>
                                              <li>» Debugging Mode</li>
                                              <li>» Auto Hassle-Free Updates</li>
                                              <li>» High Priority Customer Support</li>
                                          </ul>
                      <a href="https://wpruby.com/plugin/woocommerce-custom-payment-gateway-pro/" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> Upgrade Now</a> 
                                      </div>
                                  </div>
                              </div>
                              <div class="postbox ">
                                  <div class="handlediv" title="Click to toggle"><br></div>
                                  <h3 class="hndle"><span><i class="dashicons dashicons-editor-help"></i>&nbsp;&nbsp;Plugin Support</span></h3>
                                  <div class="inside">
                                      <div class="support-widget">
                                          <p>
                                          <img style="width: 70%;margin: 0 auto;position: relative;display: inherit;" src="https://wpruby.com/wp-content/uploads/2016/03/wpruby_logo_with_ruby_color-300x88.png">
                                          <br/>
                                          Got a Question, Idea, Problem or Praise?</p>
                                          <ul>
                        <li>» Please leave us a <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/woocommerce-bpm-payment-gateway?filter=5#postform">★★★★★</a> rating.</li>
                                              <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank">Support Request</a></li>
                                              <li>» <a href="https://wpruby.com/knowledgebase_category/woocommerce-custom-payment-gateway-pro/" target="_blank">Documentation and Common issues.</a></li>
                                              <li>» <a href="https://wpruby.com/plugins/" target="_blank">Our Plugins Shop</a></li>
                                          </ul>

                                      </div>
                                  </div>
                              </div>
                         
                              <div class="postbox rss-postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                      <h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
                      <div class="inside">
                      <div class="rss-widget">
                        <!-- <?php
                            wp_widget_rss_output(array(
                                'url' => 'https://wpruby.com/feed/',
                                'title' => 'WPRuby Blog',
                                'items' => 3,
                                'show_summary' => 0,
                                'show_author' => 0,
                                'show_date' => 1,
                            )); 
                          ?> -->
                        <!-- </div>
                      </div>
                  </div> 
                          </div>
                      </div> -->
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

    global $woocommerce;
    $order = new WC_Order( $order_id );
    // $card_info;

    $ccname = esc_html($_POST[ 'bpm-payment-ccname']);
    $cardnumber = esc_html($_POST['bpm-payment-cardnumber']);
    $cvv = esc_html($_POST['bpm-payment-cvv']);
    $expmonth = esc_html($_POST['bpm-payment-expmonth']);
    $expyear = esc_html($_POST['bpm-payment-expyear']);

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

    //print_r($order);
    ////////////////////////////////////////////////////////
    $data = array(
      'shop_tracking' => $order->get_order_key(),
      'currency_code' => $order->currency,
      'amount' => $order->get_total(),
      'product' => $item_name,
      'cc_number' => str_replace(' ','',$cardnumber),
      'cc_name' => $ccname,
      'cc_exp_month' => $expmonth,
      'cc_exp_year' => $expyear,
      'cc_cvv' => $cvv,
      'tel' => $order->get_billing_phone(),
      'email' => $order->get_billing_email()
    );

    $chksum_plain = strtolower($this->api_token . $data['amount'] . $data['currency_code'] . $data['shop_tracking'] . $this->api_secret);
    $data['checksum'] = hash('sha256', $chksum_plain);

    $url = esc_attr($this->api_endpoint);

    ////////////////////////////////////////////////////////
    /*
    if($order->currency == 'JPY'){
      $shop_id = $this->jpy_shop_id;
      $end_point = $this->jpy_endpoint;
    }else{
      $shop_id = $this->bpm_shop_id;
      $end_point = $this->bpm_endpoint;
    }

    

    $url = esc_attr($this->jpy_endpoint);

    $data = array(
     'ShopId' => $shop_id,
     'Job' => 'CAPTURE',
     'Amount' => $order->get_total(),
     'ShopCode' => $order->get_order_key(),
     'Currency' => $order->get_currency(),
     'CardName' => $ccname,
     'CardNumber' => $cardnumber,
     'CardYear' => $expyear,
     'CardMonth' => $expmonth,
     'CardCVV' => $cvv,
     'Phone' => $order->get_billing_phone(),
     'Email' => $order->get_billing_email(),
     'ResType' => 0,
     'ItemType' => 0,
     'Item' => $item_name
    );
    */

    $content = http_build_query($data);
    $options = array('http' => array(
      'method' => 'POST',
      'content' => $content,
      'ignore_errors' => true
    ));
    $resBody = file_get_contents($url, false, stream_context_create($options));


    // file_put_contents(
    //     '/var/www/logs/payment.log',
    //     $resBody.PHP_EOL,
    //     FILE_APPEND,
    //   );

    if($this -> log_permission == 'yes'){
      $date_detail = date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3);

      $message = array(
        $date_detail."  "."- bpm-payment-start -".PHP_EOL,
        $date_detail."  ".$url.PHP_EOL,
        $date_detail."  ".json_encode($data).PHP_EOL,
        $date_detail."  ".$resBody.PHP_EOL,

      );
      
      
      output_log($message);

      file_put_contents(
        '/var/www/logs/payment.log',
        $_SERVER['DOCUMENT_ROOT'].PHP_EOL,
        FILE_APPEND,
      );
      $date = date('Ymd');

      
      
      // file_put_contents(
      //   "/var/www/logs/bpm-payment-gateway_{$date}.log",
      //   date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3).PHP_EOL,
      //   FILE_APPEND,
      // );
      // $date = date('Ymd');
  
      // file_put_contents(
      // "/var/www/logs/bpm-payment-gateway_{$date}.log",
      // date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3)."  "."- bpm-payment-start -".PHP_EOL,
      // FILE_APPEND,
      // );
      // file_put_contents(
      // "/var/www/logs/bpm-payment-gateway_{$date}.log",
      // date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3)."  ".$url.PHP_EOL,
      // FILE_APPEND,
      // );
      // file_put_contents(
      // "/var/www/logs/bpm-payment-gateway_{$date}.log",
      // date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3)."  ".json_encode($data).PHP_EOL,
      // FILE_APPEND,
      // );

      // file_put_contents(
      // "/var/www/logs/bpm-payment-gateway_{$date}.log",
      // date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3)."  ".$resBody.PHP_EOL,
      // FILE_APPEND,
      // );

  }

    
    //echo $url;
    // print_r($data);
    // echo json_encode($data);
    // print_r($resBody);
    //print_r($resBody);
    $res = json_decode($resBody);
    //print_r($res->Result);

    // Remove cart
    if($res->result_code == '0000'){
      // Mark as on-hold (we're awaiting the cheque)
      $order->update_status('on-hold', __( 'Awaiting payment', 'woocommerce-bpm-payment-gateway' ));
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
      $tran_code = $res -> tran_code;
      $dba = $res -> dba;
      
      $data = array(
        'wp_wc_orders_id' => $id,
        'tran_code' => $tran_code,
        'dba' => $dba,
      );
      
      $wpdb -> insert($table_name, $data);
      
      if(isset($_POST[ $this->id.'-admin-note']) && trim($_POST[ $this->id.'-admin-note'])!=''){
        $order->add_order_note(esc_html($_POST[ $this->id.'-admin-note']),1);
      }
      $order->payment_complete();
      // Return thankyou redirect
      return array(
        'result' => 'success',
        'redirect' => $this->get_return_url( $order )
      );
    }else{
      return array(
        'result' => 'failure',
        'messages' => $order
      );
    }
    
      
  }

  public function payment_fields(){
    // if($this->hide_text_box !== 'yes'){
    //   ?>
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> -->
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.4/jquery.card.js"></script> 
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.min.css">
   
      <div id="bpm_checkout" style="max-width:400px; width:100%;font-size:min(16px,30px);margin: 0 auto;">
        <div class='bpm-card-wrapper' style="max-width:100%;"></div>
        <!-- <form class="form-row" id="form-row" method="POST"> -->
          <!-- <p id="form" class="form-row"> -->
            <div class="card-input-form" style="justify-content: center;">
                <div class="row" style="width:90%;align-items:center;margin: 0 auto;">
                  <div style="display:flex;">
                    <div style="margin-right:1rem;margin-top:1rem;">
                      <div>
                        <b class="lt">カード番号<span style="color:#ff0000;"> *</span></b><br>
                        <input style="background-color:#ffffff; max-width:160px;" style="" type="text" id="ccnum" name="bpm-payment-cardnumber" class="form-controll" placeholder="XXXX XXXX XXXX XXXX" data-description="ハイフン不要|半角数字を入力してください"  inputmode="numeric" required="required" autocomplete="cc-number">
                      </div>
                      <div class="row" style="width: 100%; min-width:160px;">
                        <label style="font-size:12px;">利用可能なカードブランド</label>
                        <div style="display:flex;">
                          <?php if($this->cc_brand_array[0] == 'yes') : ?>
                            <img src="https://ec-test.bpmc.tech/wp-content/plugins/woocommerce-bpm-payment/images/visa.jpg" alt="VISA" style="width:50%; margin-right: 5px;">
                            <!-- <i class="fab fa-cc-visa fa-3x" style="color:navy;">VISA</i> -->
                          <?php endif ?>
                          <?php if($this->cc_brand_array[1] == 'yes') : ?>
                            <img src="https://ec-test.bpmc.tech/wp-content/plugins/woocommerce-bpm-payment/images/master.png" alt="MASTER" style="width:50%; margin-right: 5px;">
                            <!-- <i class="fab fa-cc-mastercard fa-3x" style="color:red;">master</i> -->
                          <?php endif ?>
                          <?php if($this->cc_brand_array[2] == 'yes') : ?>
                            <img src="https://ec-test.bpmc.tech/wp-content/plugins/woocommerce-bpm-payment/images/jcb.png" alt="JCB" style="width:50%; margin-right: 5px;">
                            <!-- <i class="fab fa-cc-jcb fa-3x" style="color:orange;">JCB</i> -->
                          <?php endif ?>
                          <?php if($this->cc_brand_array[3] == 'yes') : ?>
                            <img src="https://ec-test.bpmc.tech/wp-content/plugins/woocommerce-bpm-payment/images/amex-logo.png" alt="AMEX" style="width:50%; margin-right: 5px;">
                          <?php endif ?>
                          <?php if($this->cc_brand_array[4] == 'yes') : ?>
                            <img src="https://ec-test.bpmc.tech/wp-content/plugins/woocommerce-bpm-payment/images/diners.png" alt="diners" style="width:50%;">
                          <?php endif ?>
                        </div>
                      </div>
                    </div>
                    <div style="width:100%; margin-top:1rem;">
                      <b class="lt">有効期限<span style="color:#ff0000;"> *</span></b><br>
                      <select style="width:40%;" class="select2" id="expmonth" name="bpm-payment-expmonth" placeholder="01" required >
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                        /
                      <select class="select2" style="width:50%;" id="expyear" name="bpm-payment-expyear" placeholder="2023" >
                        <?php
                          for($i = 0; $i < 10; $i++){
                            $year = date('Y')+$i;
                            echo "<option value='".$year."'>".$year."</option>";
                          }
                        ?>
                      </select required>
                      <input type="hidden" name="cc_exp" id="cc_exp" value="" placeholder="MM/YYYY" required /> 
                    </div>
                  </div>
                <br>
                <div class="row" style="display:flex;">
                  <div style="margin-right:1rem;">
                    <b class="lt">カード名義<span style="color:#ff0000;"> *</span></b><br>
                    <input type="text" id="cname" name="bpm-payment-ccname" style="background-color:#ffffff;width: 100%;" placeholder="YAMADA TARO" autocomplete="cc-name" required />
                    <div style="font-size: 10px;color: #666; padding: 5px;">
                      半角英文字(大)
                    </div>
                  </div> 
                  <div>
                    <b class="lt">CVV<span style="color:#ff0000;"> *</span></b><br>
                    <input type="tel" id="cvv" name="bpm-payment-cvv" style="background-color:#ffffff;width:100%;" class="txt mono" placeholder="XXX" data-description="カード裏面にある下3桁の半角数字|Amexの場合は、カード表面の右側の4桁の半角数字" pattern="[0-9]*" inputmode="numeric" required="required" autocomplete="cc-csc" maxlength="4"  />
                    <div style="font-size: 10px;color: #666; padding: 5px;">
                      セキュリティコード
                    </div>
                        </div>
                </div>
                  <!-- <br>
                  <br>
                  <label for="cvv">CVV</label>
                  <br>
                  <input type="text" id="cvv" name="<?php echo $this->id ?>-cvv" placeholder="352"> -->
            </div>
          <!-- </p>    -->
                      <!-- </form> -->
    
        <div class="clear"></div>
      </div>    
                <!-- <input style="width: 100%;" type="text" id="bpm-payment-ccname" name="bpm-payment-ccname" placeholder="<?php echo $this->id ?>" required> -->
                <br>
                <br>
                <!-- <label for="ccnum">Credit card number</label>
                <br>
                <input style="width: 100%;" type="text" id="ccnum" name="<?php echo $this->id ?>-cardnumber" placeholder="1234123412341234" required>
                <input style="width: 100%;" type="text" id="bpm-payment-cardnumber" name="bpm-payment-cardnumber" placeholder="1234123412341234" required> -->
                <!-- <br>
                <br>
                <label for="expmonth">Expiry Date(MM/YYYY)</label>
                <br>
                -->
  <?php $foo = json_encode($this->cc_brand_array); ?>
  <script type="text/javascript">
    var cc_brand_permission = JSON.parse('<?php echo $foo ?>');
    // console.log(cc_brand_permission);

    var CcBrands = [];

    // Visa
    if(cc_brand_permission[0] == "yes"){
    CcBrands.push({
      pattern: /^4/,
      name: "Visa",
      shortname: "visa",
      grouping: [4, 4, 4, 4],
      lengths: [13, 14, 15, 16],
      cvcLength: 3
    });
  }
  if(cc_brand_permission[1] == "yes"){
    // Mastercard
    CcBrands.push({
      pattern: /^5[1-5]/,
      name: "Mastercard",
      shortname: "master",
      grouping: [4, 4, 4, 4],
      lengths: [16],
      cvcLength: 3
    });
  }
  if(cc_brand_permission[2] == "yes"){
    // JCB
    CcBrands.push({
      pattern: /^35/,
      name: "JCB",
      shortname: "jcb",
      grouping: [4, 4, 4, 4],
      lengths: [16],
      cvcLength: 3
    });
  }

  if(cc_brand_permission[3] == "yes"){
    // American Express
    CcBrands.push({
      pattern: /^3[47]/,
      name: "American Express",
      shortname: "amex",
      grouping: [4, 6, 5],
      lengths: [15],
      cvcLength: 4
    });
  }

  if(cc_brand_permission[4] == "yes"){
    // Diners Club
    CcBrands.push({
      pattern: /^(36|38|30[0-5])/,
      name: "Diners Club",
      shortname: "diners",
      grouping: [4, 6, 4],
      lengths: [14],
      cvcLength: 3
    });
  }

  console.log(CcBrands);
    // // Discover
    // CcBrands.push({
    //   pattern: /^(6011|65|64[4-9]|622)/,
    //   name: "Discover",
    //   shortname: "discover",
    //   grouping: [4, 4, 4, 4],
    //   lengths: [16],
    //   cvcLength: 3
    // });


  // function checkCCBrand(cc_number) {
  //   cc_number =cc_number.replace(/\s+/g, "");
  //   var bincode = parseInt(cc_number.substr(0,6));
  //   var bin4code = parseInt(cc_number.substr(0,4));
  //   if(cc_number.substr(0,1) === '4' && cc_number.substr(0,4) !== '4903' && cc_number.substr(0,4) !== '4905' && cc_number.substr(0,4) !== '4911' && cc_number.length === 16)
  //     return 'visa';
  //   if(((bincode >= 510000 && bincode <= 559999) || (bincode >= 222100 && bincode <= 272099)) && cc_number.length === 16)
  //     return 'master';
  //   if((bin4code >= 3528 && bin4code <= 3589) && cc_number.length === 16)
  //     return 'jcb';
  //   if( (cc_number.substr(0,2) === '34' || cc_number.substr(0,1) === '37') && cc_number.length === 15)
  //     return 'amex';
  //   if((cc_number.substr(0,2) === '30' || cc_number.substr(0,2) === '36' || cc_number.substr(0,2) === '38' || cc_number.substr(0,2) === '39') && cc_number.length === 14)
  //     return 'diners'; 
  //   if((cc_number.substr(0,2) === '60' || cc_number.substr(0,2) === '62' || cc_number.substr(0,2) === '64' || cc_number.substr(0,2) === '65') && cc_number.length === 16) 
  //     return 'discover';
  //   return null;
  // }

  // function example(){
  //   console.log('example');
  //   var support_cc_brand = ['visa', 'master', 'jcb'];
  //   var cc_number = jQuery('#ccnum').val();
  //   var cc_brand = checkCCBrand(cc_number);
  //   if(cc_brand && support_cc_brand.indexOf(cc_brand) > -1)
  //     return true;
  //   return false;
  // }
  function cc_exp_change(){
    var month = jQuery('#expmonth').val();
    var year = jQuery('#expyear').val();
    var val = [month, year].join(' / ');
    //console.log('val = ', val);
    jQuery('#cc_exp').val(val);

      var event;
      try {
        event = new CustomEvent('change');
      } catch (e) {
        event = document.createEvent('HTMLEvents');
        event.initCustomEvent('change');
      }
      jQuery('#cc_exp')[0].dispatchEvent(event);
  }
  function getCcBrand(cc_number){
    for (let i = 0; i < CcBrands.length; i++) {
          if (CcBrands[i].pattern.test(cc_number)) {
            return CcBrands[i];
          }
        }
        return null;
  }
  function validate(cc_number,cc_cvv){
    for (let i = 0; i < CcBrands.length; i++) {
          if (CcBrands[i].pattern.test(cc_number)) {
            var cb = CcBrands[i];
            if (cb.lengths.indexOf(cc_number.length) > -1) {
              if (cc_cvv.length == cb.cvcLength) {
                return true;
              }
            }
            break;
          }
        }
        return false;
  }

  jQuery(document).ready(function(){
    jQuery(document).on('change', '#expmonth,#expyear', cc_exp_change);
    // jQuery('select2').on('change',cc_exp_change);

    jQuery('#expyear,#expmonth').select2({
      minimumResultsForSearch: -1
    });


    let card = new Card({
              form: '.card-input-form',
              container: '.bpm-card-wrapper',
              width: 350,
              formatting: true,
              // masks: {
              //     cardNumber: maskChar
              // },
              formSelectors: { nameInput: 'input[name="bpm-payment-ccname"]' ,numberInput: 'input[name="bpm-payment-cardnumber"]', expiryInput: 'input[name="cc_exp"]', cvcInput: 'input[name="bpm-payment-cvv"]'},
              messages: {
                validDate: 'valid\nthru',
                monthYear: 'month/year'
              },
          });

        

    //jQuery('#place_order').attr('disabled', 'disabled');
    var cname = false;
    var ccnum = false;
    var expmonth = false;
    var expyear = false;
    var cvv = false;

    var error_msg = '';

    jQuery('#cname').on('keyup focus blur', __validation);
    jQuery('#ccnum').on('keyup focus blur', __validation);
    jQuery('#expmonth').on('keyup focus blur', __validation);
    jQuery('#expyear').on('keyup focus blur', __validation);
    jQuery('#cvv').on('keyup focus blur', __validation);

    function __validation(){
      var cc_number = jQuery('#ccnum').val();
      cc_number =cc_number.replace(/\s+/g, "");

      var cc_cvv = jQuery('#cvv').val();

      var cb;
      for (let i = 0; i < CcBrands.length; i++) {
          if (CcBrands[i].pattern.test(cc_number)) {
            cb = CcBrands[i];
            // console.log(cb);
            break;
          }
        }

      jQuery('.error', '#bpm_checkout').remove();

      var ret = true;
      if(!jQuery('#cname').val()){
        jQuery('#cname').after('<div style="color: red;" class="error">必須項目です</div>');
        ret = false;
      }else{
        if(!jQuery('#cname').val().match(/[a-zA-Z0-9]+ [a-zA-Z0-9]+/)){
          jQuery('#cname').after('<div style="color: red;" class="error">無効な値です</div>');
          ret = false;
        }
      }
      if(!jQuery('#ccnum').val()){
        jQuery('#ccnum').after('<div style="color: red;" class="error">必須項目です</div>');
        ret = false;
      }
      if(!jQuery('#expmonth').val()){
        jQuery('#expmonth').after('<div style="color: red;" class="error">必須項目です</div>');
        ret = false;
      }
      if(!jQuery('#expyear').val()){
        jQuery('#expyear').after('<div style="color: red;" class="error">必須項目です</div>');
        ret = false;
      }
      if(!jQuery('#cvv').val()){
        jQuery('#cvv').after('<div style="color: red;" class="error">必須項目です</div>');
        ret = false;
      }else{
        if(cb && jQuery('#cvv').val().length != cb.cvcLength){
          jQuery('#cvv').after('<div style="color: red;" class="error">無効なセキュリティコードです</div>');
          ret = false;
        }
      }
      
      if(validate(cc_number,cc_cvv) == false){
        // console.log("aaa",validate(cc_number,cc_cvv));
        if(!cb && cc_number.length != 0){
          jQuery('#ccnum').after('<div style="color: red;" class="error">そのカード番号は使えません</div>');
          ret = false;
        }
        else if(cb && cc_cvv.length == cb.cvcLength && cc_number.length != 0){
          jQuery('#ccnum').after('<div style="color: red;" class="error">そのカード番号は使えません</div>');
          ret = false;
        }
        else if(cb && cc_cvv.length != cb.cvcLength && cc_number.length != 0){
          jQuery('#cvv').after('<div style="color: red;" class="error">セキュリティコードを入力してください</div>');
          ret = false;
        }
      }

      if(ret) {
        //jQuery('#place_order').removeAttr('disabled');
      } else {
        //jQuery('#place_order').attr('disabled', 'disabled');
      }

    }
                      
                      
    jQuery('#place_order').on('click', function() {
      jQuery('#cname').val();
      jQuery('#expmonth').val();
      jQuery('#expyear').val();
      jQuery('#cvv').val();
      if(validate(cc_number,cc_cvv) == false){
        alert('入力したカードブランドは対応していません。');
      }else{
        jQuery("form[name='checkout']").submit(); 
      }
    });

  });
  </script>
             
             

      <?php
      // }
    }
  }
