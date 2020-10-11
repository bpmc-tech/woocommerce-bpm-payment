<?php 

class WC_Bpm_Payment_Gateway extends WC_Payment_Gateway{
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

    //$this->jpy_shop_id = $this->get_option('jpy_shop_id');
    $this->api_endpoint = $this->get_option('api_endpoint');
    $this->api_token = $this->get_option('api_token');
    $this->api_secret = $this->get_option('api_secret');

    //$this->jpy_endpoint = $this->get_option('jpy_endpoint');

    //$this->bpm_shop_id = $this->get_option('bpm_shop_id');
    //$this->bpm_endpoint = $this->get_option('bpm_endpoint');
    //$this->uds_api_token = $this->get_option('jpy_api_token');
    //$this->jpy_api_secret = $this->get_option('jpy_api_secret');
    
    $this->description = $this->get_option('description');
    $this->hide_text_box = $this->get_option('hide_text_box');

    add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
  }

  /*
   * 設定画面の初期化
   +++++++++++++++++++++++++++++++++++++++*/
  public function init_form_fields(){
      $this->form_fields = array(
        'enabled' => array(
          'title'     => __( 'Enable/Disable', 'woocommerce-bpm-payment-gateway' ),
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
          'title'     => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'text',
          'description'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'default'   => __( 'API_TOKEN', 'woocommerce-bpm-payment-gateway' ),
          'desc_tip'    => true,
        ),
        'api_secret' => array(
          'title'     => __( 'API_SECRET', 'woocommerce-bpm-payment-gateway' ),
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
        'description' => array(
          'title' => __( 'Description', 'woocommerce-bpm-payment-gateway' ),
          'type' => 'textarea',
          'css' => 'width:500px;',
          'default' => 'None of the bpm payment options are suitable for you? please drop us a note about your favourable payment option and we will contact you as soon as possible.',
          'description'   => __( 'The message which you want it to appear to the customer in the checkout page.', 'woocommerce-bpm-payment-gateway' ),
        ),
        'hide_text_box' => array(
          'title'     => __( 'Hide The Payment Field', 'woocommerce-bpm-payment-gateway' ),
          'type'      => 'checkbox',
          'label'     => __( 'Hide', 'woocommerce-bpm-payment-gateway' ),
          'default'     => 'no',
          'description'   => __( 'If you do not need to show the text box for customers at all, enable this option.', 'woocommerce-bpm-payment-gateway' ),
        ),

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
    <h3><?php _e( 'BPM Payment Settings', 'woocommerce-bpm-payment-gateway' ); ?></h3>
      <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
          <div id="post-body-content">
            <table class="form-table">
              <?php $this->generate_settings_html();?>
            </table><!--/.form-table-->
          </div>
          <div id="postbox-container-1" class="postbox-container">
                          <div id="side-sortables" class="meta-box-sortables ui-sortable"> 
                             
                  <div class="postbox ">
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
                        <?php
                            wp_widget_rss_output(array(
                                'url' => 'https://wpruby.com/feed/',
                                'title' => 'WPRuby Blog',
                                'items' => 3,
                                'show_summary' => 0,
                                'show_author' => 0,
                                'show_date' => 1,
                            ));
                          ?>
                        </div>
                      </div>
                  </div>

                          </div>
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
   * 設定画面の初期化
   +++++++++++++++++++++++++++++++++++++++*/
  public function process_payment( $order_id ) {

    global $woocommerce;
    $order = new WC_Order( $order_id );
    $card_info;

    $ccname = esc_html($_POST[ $this->id.'-ccname']);
    $cardnumber = esc_html($_POST[ $this->id.'-cardnumber']);
    $cvv = esc_html($_POST[ $this->id.'-cvv']);
    $expmonth = esc_html($_POST[ $this->id.'-expmonth']);
    $expyear = esc_html($_POST[ $this->id.'-expyear']);

    $item_name = '';
    $i = 0;
    foreach( WC()->cart->get_cart() as $cart_item ){
      if(i == 0){
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
      'cc_number' => $cardnumber,
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
        'messages' => $order_note
      );
    }
    
      
  }

  public function payment_fields(){
    if($this->hide_text_box !== 'yes'){
      ?>
      <fieldset id="bpm_checkout">
      <p class="form-row">
              <div>
<label>Supported Card Brands</label><br>
                <i class="fab fa-cc-visa fa-3x" style="color:navy;"></i>
                <i class="fab fa-cc-mastercard fa-3x" style="color:red;"></i>
                <i class="fab fa-cc-jcb fa-3x" style="color:orange;"></i>
              </div>
<br>
              <label for="cname">Name on Card</label>
              <br>
              <input style="width: 100%;" type="text" id="cname" name="<?php echo $this->id ?>-ccname" placeholder="John More Doe" required>
              <br>
              <br>
              <label for="ccnum">Credit card number</label>
              <br>
              <input style="width: 100%;" type="text" id="ccnum" name="<?php echo $this->id ?>-cardnumber" placeholder="1234123412341234" required>
              <br>
              <br>
              <label for="expmonth">Expiry Date(MM/YYYY)</label>
              <br>
              <select style="width:100px" class="select2" id="expmonth" name="<?php echo $this->id ?>-expmonth" placeholder="01" required>
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
                <select class="select2" id="expyear" name="<?php echo $this->id ?>-expyear" placeholder="2018" style="width: 100px;">
                  <?php
                    for($i = 0; $i < 10; $i++){
                      $year = date('Y')+$i;
                      echo "<option value='".$year."'>".$year."</option>";
                    }
                  ?>
                </select required>
<script type="text/javascript">
function checkCCBrand(cc_number) {
  var bincode = parseInt(cc_number.substr(0,6));
  var bin4code = parseInt(cc_number.substr(0,4));
  if(cc_number.substr(0,1) === '4' && cc_number.substr(0,4) !== '4903' && cc_number.substr(0,4) !== '4905' && cc_number.substr(0,4) !== '4911' && cc_number.length === 16)
    return 'visa';
  if(((bincode >= 510000 && bincode <= 559999) || (bincode >= 222100 && bincode <= 272099)) && cc_number.length === 16)
    return 'master';
  if((bin4code >= 3528 && bin4code <= 3589) && cc_number.length === 16)
    return 'jcb';
  if( (cc_number.substr(0,2) === '34' || cc_number.substr(0,1) === '37') && cc_number.length === 15)
    return 'amex';
  if((cc_number.substr(0,2) === '30' || cc_number.substr(0,2) === '36' || cc_number.substr(0,2) === '38' || cc_number.substr(0,2) === '39') && cc_number.length === 14)
    return 'diners'; 
  if((cc_number.substr(0,2) === '60' || cc_number.substr(0,2) === '62' || cc_number.substr(0,2) === '64' || cc_number.substr(0,2) === '65') && cc_number.length === 16) 
    return 'discover';
  return null;
}


function example(){
  console.log('example');
  var support_cc_brand = ['visa', 'master', 'jcb'];
  var cc_number = jQuery('#ccnum').val();
  var cc_brand = checkCCBrand(cc_number);
  if(cc_brand && support_cc_brand.indexOf(cc_brand) > -1)
    return true;
  return false;
}

jQuery(document).ready(function(){

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

    jQuery('.error', '#bpm_checkout').remove();

    var ret = true;
    if(!jQuery('#cname').val()){
      jQuery('#cname').after('<div style="color: red;" class="error">Required Input</div>');
      ret = false;
    }else{
      if(!jQuery('#cname').val().match(/[a-zA-Z0-9]+ [a-zA-Z0-9]+/)){
        jQuery('#cname').after('<div style="color: red;" class="error">Invalid Value</div>');
        ret = false;
      }
    }
    if(!jQuery('#ccnum').val()){
      jQuery('#ccnum').after('<div style="color: red;" class="error">Required Input</div>');
      ret = false;
    }
    if(!jQuery('#expmonth').val()){
      jQuery('#expmonth').after('<div style="color: red;" class="error">Required Input</div>');
      ret = false;
    }
    if(!jQuery('#expyear').val()){
      jQuery('#expyear').after('<div style="color: red;" class="error">Required Input</div>');
      ret = false;
    }
    if(!jQuery('#cvv').val()){
      jQuery('#cvv').after('<div style="color: red;" class="error">Required Input</div>');
      ret = false;
    }else{
      if(jQuery('#cvv').val().length != 3){
        jQuery('#cvv').after('<div style="color: red;" class="error">Invalid Value</div>');
        ret = false;
      }
    }
    if(!example()){
      jQuery('#ccnum').after('<div style="color: red;" class="error">Not Supported Card Brand</div>');
      ret = false;
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
    if(!example()){
      alert('入力したカードブランドは対応していません。');
    }else{
      jQuery("form[name='checkout']").submit(); 
    }
  });
  jQuery('#expyear,#expmonth').select2();
});
</script>
                <br>
                <br>
                <label for="cvv">CVV</label>
                <br>
                <input type="text" id="cvv" name="<?php echo $this->id ?>-cvv" placeholder="352">
      </p>            
      <div class="clear"></div>
    </fieldset>
    <?php
    }
  }
}
