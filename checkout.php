<?php 
$noindex = 'yes';
$metatitle = "Checkout - MyphotoPrint.in";
$metadesc = "Your Myphotoprint Checkout Page";
$metaurl = "https://myphotoprint.in/checkout.php";

include('header.php'); 
if(!empty($helper->get_session('USER_ID'))){
    $uid = $helper->get_session('USER_ID');
    $couponamount = $helper->get_session('coupon_amt');
  
    $codcharges = $helper->get_session('cod_charge');
    $getUserDetail = $see->getrow("see_users","see_u_id = $uid");
    if($getUserDetail){
        $userid = $getUserDetail['see_u_id'];
        if(isset($_POST['add_address'])){
            $u_contact_name = $_POST['name']; 
            $ustateID = $_POST['state'];
            $ucity = $_POST['city'];
            $uaddress = $_POST['address'];
            $upincode = $_POST['pincode'];
            $uphone1 = $_POST['phone1'];
            $uphone2 = $_POST['phone2'];
            $dataarray = ['see_ubd_u_id' => $userid, 'see_ubd_contact_name' => $u_contact_name, 'see_ubd_mob1' => $uphone1, 'see_ubd_mob2' => $uphone2, 'see_ubd_taxno' => '', 'see_ubd_state' => $ustateID , 'see_ubd_city' => $ucity, 'see_ubd_address' => $uaddress, 'see_ubd_pincode' => $upincode, 'see_ubd_create' => $date];
            $insertUserAddress = $see->addrow("see_user_billing_detail",$dataarray);
            if($insertUserAddress){ $helper->redirectTo('checkout.php'); }
            else{ $message = "Some Server Error"; }
        } ?>
        <div class="page-loder">
    <span class="f-52">
        <i class="fa fa-spinner fa-spin"></i>
    </span>
</div>
<form method="post" name="orderproduct" id="finalform" action="<?php echo SITE_URL;?><?=@$_SESSION['USER_ID'] == 811 ? 'process.php' : 'do_process.php';?>">
    <section class="add-cart checkout">
    <div class="container">
      <div class="add-cart-main checkout-main see-full">
        <div class="cart-left see-full">
            <?php 
            $stateid = '';
            $userLocationId = !empty($helper->currency('location')) ? $helper->currency('location') : 95;
            $selectAllAddresse = $see->getrow("see_user_billing_detail","see_ubd_u_id = $userid LIMIT 1");
            if($selectAllAddresse){
                $addID = $selectAllAddresse['see_ubd_id'];
                $contactName = $selectAllAddresse['see_ubd_contact_name'];
                $stateid = $selectAllAddresse['see_ubd_state'];
                $getstateDetail = $see->getrow("see_location","see_l_id = $stateid");
                $state_Name = $getstateDetail['see_l_name'];
                $cityName = $selectAllAddresse['see_ubd_city'];
                $address = $selectAllAddresse['see_ubd_address'];
                $landmark = $selectAllAddresse['see_ubd_landmark'];
                $pincode = $selectAllAddresse['see_ubd_pincode'];
                $phone1 = $selectAllAddresse['see_ubd_mob1'];
                $phone2 = $selectAllAddresse['see_ubd_mob2']; 
                $email = $see->getrowcolumn("see_users", ['see_u_email'], "see_u_id = '$userid'")['see_u_email'];
            } ?>
            
          <div class="left-cont">
            <span>Billing details</span>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>Full Name</span>
              <input type="text" name="billing_name" value="<?=@$contactName;?>" placeholder="Name" required>
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>Calling Mobile No. for Delivery</span>
              <input type="tel" name="billing_mob" value="<?=@$phone1;?>" placeholder="enter number" required>
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>Email Address</span>
                <input type="email" name="billing_email" value="<?=@$email;?>" placeholder="enter Your Email Address">
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>House No./ Street No./ Area Name</span>
              <input type="text" name="billing_add" value="<?=@$address;?>" placeholder="House No.Near By" required>
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>Landmark ( Near By )</span>
              <input type="text" name="billing_landmark" value="<?=@$landmark;?>" placeholder="Landmark ( Near By )" required>
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>PinCode</span>
              <input type="text" name="billing_pin" value="<?=@$pincode;?>" placeholder="pincode" required>
            </div>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                <span>City Name</span>
              <input type="text" name="billing_city" value="<?=@$cityName;?>" placeholder="town / city" required>
            </div>	
            <?php $selectStates = $see->getrows("see_location","see_l_parent = '$userLocationId'");?>
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12 " <?=!$selectStates ? 'style="display:none;"' : '';?>>
              	<span>Choose State</span>
              	<select name="state" required class="see-12" required>
					<option hidden="hidden" value="<?=!$selectStates ? $country_id : '';?>">Select State</option>
		            <?php
                    foreach($selectStates as $selectState){
                        $choosestateID = $selectState['see_l_id'];
                        $stateName = $selectState['see_l_name']; ?>
					    <option value="<?php echo $choosestateID; ?>" <?=$stateid == $choosestateID ? 'selected' : '';?>><?php echo $stateName; ?></option>
					<?php } ?>
			    </select>
            </div>
            
            <div class="input-field see-12 see-tb-12 see-ltb-12 see-sm-12 see-xsm-12">
                 <span>WhatsApp Mobile No.</span>
              <input type="tel" name="billing_mob2" value="<?=@$phone2;?>" placeholder="WhatsApp Mobile No">
            </div>
            
          </div>
        </div>
      
            <input id='user_address' type="hidden" value="" name="user_address">
            <div class="cart-right check-right see-full">
                <?php 
                $couonammount = (isset($_SESSION['coupon_amt']) ? $_SESSION['coupon_amt'] : 0);
                if($couonammount<1){ ?>
                        <div class="coupon-head">Offer & Benefits</div>
                        
                        <div class="coupondata" id="hide-show">Apply Coupon</div>
                        <div class="couponshide" style="display:none;">
                            <div class="row see-margin-bottom" style="display:flex;">
                                <div class="see-8">
                                    <div class="form-group">
                                        <input name="coupon_code" style="border: 1px solid #000; height:45px; text-align: center;" type="text" id="coupon_code" placeholder="Apply a Coupon Code" class="see-full">
                                        <input type="hidden" name="cart_value" value="<?=$totalCartAmount?>" id="cart_value">
                                    </div>
                                </div>
                                <div class="see-4">
                                    <input type="button" name="check_coupon" class="theme-second-color see-full" style="border:none;padding-left:10px;padding-right:10px;height:46px;width: 100%;color:#fff;background-color:#cc9832;"  id="apply_coupon_code" value="Apply">
                                </div>
                            </div>
                            <div id="check" style="margin-left: 51px;" class="col-12"></div>
                            
                            <div class="coupons-bott">
                                <ul> 
                                
                                <?php
                                $getcpupons = $see->getrows('see_coupon',"see_coupon_status = 'active' AND see_end_date >= date(now()) AND see_coupon_show = 'yes' ORDER BY see_id DESC");
                                if($getcpupons){
                                    foreach($getcpupons as $gcoupon){
                                        $couponcode = $gcoupon['see_coupon_code'];
                                        $couponprice = $gcoupon['see_coupon_amt'];
                                        $coupontype = $gcoupon['see_coupon_type'];
                                        $minumvalue = $gcoupon['see_min_shopping_amt'];
                                        $amounttype = '';
                                        if($coupontype == 'percentage'){ 
                                            $amounttype = '%';
                                        }
                                        ?>
                                        <li class="data-coupon" data-code="<?=$couponcode;?>">
                                        <div class="coupon-in-main">
                                          <div class="coupon-code"><?=$couponcode;?></div>
                                          <apply class="see-button see-round">Apply</apply>
                                        </div>
                                        <coupon_desc>Use code <?=$couponcode;?> & get <?=$couponprice.$amounttype;?> off on order above ₹<?=$minumvalue;?>. Applicable for All Users.</coupon_desc>
                                         
                                      </li>
                                <?php
                                    }
                                } ?>
                                </ul>
                            </div>
                            
                        </div>
                        <style>
                            .coupon-head{ line-height:36px; }
                            .coupondata{ box-shadow: rgba(17, 17, 26, 0.1) 0px 0px 16px; line-height:36px; border-radius:40px; margin-bottom:20px; padding:0 20px; }
                            .coupons-bott {text-align: center;padding: 20px 0;}
                            .coupons-bott ul li:last-child {margin-bottom: 0;border: none;}
                            .coupons-bott ul li {text-align: left;margin-bottom: 20px;border-bottom: 1px solid #ececec;}
                            .coupon-in-main {display: flex;justify-content: space-between;margin-bottom: 10px;}
                            .coupon-code {background: var(--see_prime_color);padding: 5px 10px;border-radius: 5px;position: relative;color: var(--see_light_color);overflow: hidden;}
                            .coupons-bott details {padding-top: 0;padding-bottom: 5px;}
                            .coupons-bott details dl {margin: 5px 15px;position: relative;}
                            .coupons-bott details dl::before {position: absolute;  top: 0;  left: -15px;width: 20px;height: 20px;content: '\✓';color: green;}
                            coupon_desc{ margin-bottom:10px; }
                            .coupon-code::after {position: absolute;top: 50%;right: -8px;content: '';width: 14px;height: 12px;background: var(--see_light_color);transform: translateY(-50%);overflow: hidden;border-radius: 50%;}
.coupon-code::before {position: absolute;top: 50%;left: -8px;content: '';width: 14px;height: 12px;  background: var(--see_light_color);transform: translateY(-50%);overflow: hidden;border-radius: 50%;}

                            </style>
                        <script>
                            $('apply').click(function(){
                               $('#coupon_code').val($(this).closest('li').attr('data-code'));
                               $('#apply_coupon_code').click();
                            });
                            
                            $('#hide-show').click(function() {
                              $('.couponshide').toggle();     
                            });
                            
                            $('#apply_coupon_code').click(function(){
                                var coupon_code = $('#coupon_code').val();
                                var cart_value  = $('#cart_value').val();
                                $.ajax({
                                    type:"POST",
                                    url:'<?php echo SITE_URL; ?>manage_cart.php',
                                    data:{ coupon_code:coupon_code, cart_value:cart_value },
                                    success:function(response){
                                      if(response == '0'){ $('#check').html( '<p style="color:red;">Invalid Coupon</p>'); }
                                      else if(response == '1'){
                                        $('#check').html( '<p style="color:green;">Coupon Applied</p>');
                                        couponses(coupon_code,cart_value);
                                      } 
                                      else{   $('#check').html(response); }
                                    }
                                });
                                
                                function couponses(coupon_code,cart_value){
                                    $.ajax({
                                        type:"POST",
                                        url:'<?php echo SITE_URL; ?>check.php',
                                        data:{ coupon_code:coupon_code,cart_value:cart_value},
                                        success:function(response){
                                          $('.page-loder').show();
                                           location.reload();
                                        }
                                    });
                                }
                            });
                        </script>
                        <?php
                    } 
                if($couonammount>0){ ?>
                    <div style="color:green;margin-bottom:10px"><?=@$_SESSION['coupon_code_status']?> - <span class="see-button see-red see-circle" onclick="removecoupon()">X</span></div>
                    <script>
                    function removecoupon(){
                        $('.page-loder').show();
                        $.ajax({
                            type:"POST",
                            url:'<?php echo SITE_URL; ?>removecoupon.php',
                            success:function(response){ location.reload(); }
                        });
                    }
                    </script>
                <?php } ?>
                <div class="right-cont"><span>Cart order</span></div>
                    <div class="right-cont">
                        <span>product</span>
                        <span>subtotal</span>
                    </div>
                  <?php
                $paypartial = 0;
                $paypartialamount = 0;
                foreach($helper->get_session('cart') as $id => $valz){
                  foreach($valz as $pizza => $val){ ?>
                        <div class="right-cont">
                    <?php 
                    $proQuantiy = $val['quantity'];
                    @$user_pro_attribute = array_slice($val,1)['ATTR'];
                    $getProductDetail = $see->getrow("see_products","see_pro_id = $id "); // $product->productDetailById($id);
                    $proName = $getProductDetail['see_pro_name'];
                    $proPrice = $getProductDetail['see_pro_price'];
                    $codChargez = $getProductDetail['see_pro_pincode_price'];
                    $pro_disc_type = $getProductDetail['see_pro_price_discount_type'];
                    $pro_disc = $getProductDetail['see_pro_price_discount'];
                    $product_media = unserialize($getProductDetail['see_pro_media']);
                    $product_media = $product_media[0];
                    $paypartial = $getProductDetail['see_pro_partial'];
                    $paypartialamount += $getProductDetail['see_pro_partial_price'];
                    $sale_price = '';
                    $sale_price_qantityWise = '';
                    if(!empty($pro_disc_type)){
                        if($pro_disc_type == 'percentage'){ 
                            $sale_price = round($proPrice-$proPrice*$pro_disc/100);
                            $sale_price_qantityWise = $proQuantiy*$sale_price;
                        }
                        elseif($pro_disc_type == 'rupees'){ 
                            $sale_price = $proPrice-$pro_disc;
                            $sale_price_qantityWise = $proQuantiy*$sale_price;
                        }
                    }
                    if(!empty($sale_price_qantityWise)){ $proPayAmnt = $sale_price_qantityWise; }
                    else{ $proPayAmnt = $proQuantiy*$proPrice;  }
                    
                    $proPayAmnt = $helper->currency('price',$proPayAmnt);
                    $attriuteprice = 0;
                    if(!empty($user_pro_attribute)){
                        $attr_array = array("Enter","Message","upload");
                        foreach($user_pro_attribute as $cheks => $dts){ 
                            $proc = 'yes';
                            foreach($attr_array as $l){ if(strpos(strtoupper($cheks),strtoupper($l)) !==  false){ $proc = 'no';  } }
                            if($proc == 'yes'){ $m[$cheks]=$dts;  }
                        }
                        $ms = array_values(($m));
                        foreach($ms as $att => $aval){
                            $v = explode("|",$aval);
                            // $find = preg_quote('x','~');
                            // $remove_element = preg_grep('~'. $find.'~',$v);
                            // foreach($remove_element as $key=>$value){ unset($v[$key]); }
                            $v = array_values($v);
                            $x = array_sum($v);
                            $attriuteprice += $x; 
                        }
                    }
                    $attributeprice = $attriuteprice * $proQuantiy;
                    $proPayAmnt = $proPayAmnt + $attributeprice; ?>
                    <span><?=$proName;?>  × <?=$proQuantiy;?></span>
                    <div><?=$helper->currency('symbol');?> <?php echo $proPayAmnt; ?></div>
                  </div>
                <?php }
                } 
                
                $helper->set_session('partialprice',$paypartialamount);
                
                ?>
                <div class="right-cont" id="cod_charge">
                    <span> Shipping Charges(COD Fee) </span>
                    <div> + <?=$helper->currency('symbol');?> 0 </div>
                </div> 
                
                <!--<div class="right-cont" id="bookingammount" style="display:none">-->
                <!--    <span> Booking Amount </span>-->
                <!--    <span class="see-red-text"> - <?=$helper->currency('symbol');?> <span class="partial"><?php echo $paypartialamount;?> </span> </span>-->
                <!--</div> -->
                <div class="right-cont">
                    <span class="circle"><i class="fa fa-wallet" aria-hidden="true"></i> Your Wallet Balance</span>
                    <span class="see-red-text"> <?=(!empty($wallectamount) ? '-' : ''); ?> <?=$helper->currency('symbol');?> <span class="walletbal"><?php echo $wallectamount; ?></span></span>
                </div>
                <?php
                if($couponamount>0){ ?>
                    <div class="right-cont">
                        <span>coupon <br><small><?=@$helper->get_session('coupon_code')?></small></span>
                        <span>- <?=$helper->currency('symbol');?> <span class="coupon"><?php echo $couponamount; ?></span></span> 
                    </div>
                <?php } ?>
                
                <div class="right-cont">
                    <span>total</span>
                    <span><?=$helper->currency('symbol');?> <span class="totval"><?php echo $proPayAmnt-$wallectamount-$couponamount; ?></span></span>
                </div>
                
                <!--Start of checkout buttons-->
                
                <div class="right-cont">
                    <?php if($helper->currency('code') == "INR"){ $nlabel =  ", NetBanking, UPI, Wallet)"; }?>
                    <div class="radio check">
                        <input type="radio" value="ccavenue" name="pay_mode" id="net_ccavenue" checked />
                        <b><label for="net_ccavenue" class="dyn_check"> Prepaid Payment Rs <?php echo $proPayAmnt-$wallectamount-$couponamount; ?>/- ( Free Shipping ) </label></b>
                        <p>&nbsp;</p>
                        <?php if(!empty($nlabel)){?><p>Get 10% Cashback & Save Rs <?=$paypartialamount;?>/- Shipping Charges</p><p>&nbsp;+ Free and Fastest delivery</p><?php } ?>
                    </div>
                     <?php 
                     if(($country_id == '95' || $country_id == '') && $paypartial == '1'){ ?>
                    <div class="radio check see-border-top">
                        <input type="radio" value="partialpayment" name="pay_mode" id="paybooking" />
                        <b><label for="paybooking" class="dyn_check">Pay Only Rs <?=$paypartialamount;?>/- Shipping Charges Now & Rest on Cash on Delivery</label></b> <div class="click-cashbackimg"> Why? </div>
                        <p>&nbsp;</p>
                        <?php if(!empty($nlabel)){?><p>अभी ₹<?=$paypartialamount;?> भुगतान करें और <?=$helper->currency('symbol'
                        );?><?php echo $helper->get_session('payble_amt') +$codcharges - $wallectamount-$couponamount-$paypartialamount;?> का भुगतान नकद में डिलीवरी पर करना होगा।</p><?php } ?>
                    </div>
                    <?php } ?>
                </div>
                
                <!--End of checkout buttons-->
                
                <div class="shoping">
                    <button name="action" hidden style="display:none;"></button>
                    <a href="javascript:void(0);" class="place_ord paymsg">Place Order <?=$helper->currency('symbol');?><?php echo $helper->get_session('payble_amt') - $wallectamount-$couponamount;?></a>
                </div>
                <div class="cashback-main">
                    <div class="cashback background" style="font-size:20px;">Cashback with this Order is<span style="font-size:20px !important;"><?=$helper->currency('symbol');?><?php echo round($proPayAmnt*10/100); ?></span></div> 
                </div>
                <center><img src="https://myphotoprint.b-cdn.net/Trust.gif" alt="MyPhotoPrint Review"></center>
            </div>
            <style> .circle i{ font-size: 16px; text-align: center; color: #de1738; } </style>
            
      </div>
    </div>
    </section>
  </form>
  <div class="cart-popup">
        <div class="popup-in">
            <div class="media">
                <img src="https://myphotoprint.in/bookingpaymentinfo.jpeg" alt="cashback">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <script type="text/javascript">
        $(".click-cashbackimg").on('click', function() { $(".cart-popup").addClass('model-open'); }); 
        $(".media i, .bg-overlay").click(function(){ $(".cart-popup").removeClass('model-open'); });
        $("#finalform").submit(function(){
        $('.page-loder').show(); 
        });
    </script>
    <style>
    /***popup****/
    .click-cashbackimg{ color:#007cff; display:inline; cursor:pointer; }
    .cart-popup{position: fixed;top: 50%;left: 50%;background: #1111119c;transform: translate(-50%, -50%);z-index: 99999;width: 100%;height: 100%;display: none;}
    .cart-popup .popup-in{margin: auto;height: 100%;/*display: table;*/display: flex;justify-content: center;align-items: center;width: 30%;}
    .cart-popup .popup-in .media{width: 100%;z-index: 9999999;position: relative;}
    .cart-popup .popup-in .media i{position: absolute;top: -26px;right: 15px;color: #fff;font-size: 25px;}
    .cart-popup .popup-in img{width: 100% !important;height: 500px !important;object-fit: contain !important;}
    .cart-popup .popup-in .close-btn{text-align: right;color: #fff;font-size: 30px;cursor: pointer;position: absolute;top: 30px;right: 0;z-index: 9;}
    .model-open {z-index: 99999;overflow: hidden;display: block;}
    .bg-overlay {background: rgba(0, 0, 0, 0);height: 100vh;width: 100%;position: fixed;left: 0;top: 0;right: 0;bottom: 0;z-index: 0;-webkit-transition: background 0.15s linear;-o-transition: background 0. linear;transition: background 0.15s linear;}
    
    @media (max-width:992px){ .cart-popup .popup-in img{height: auto !important;} }
    @media (max-width:768px){
      .cart-popup .popup-in{width: 80%;}
      .cart-popup .popup-in img{height: auto !important;}
    }
    </style>
    <?php 
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) { $client_ip = $_SERVER['HTTP_CLIENT_IP']; }
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
    else{ $client_ip = $_SERVER['REMOTE_ADDR']; }
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $fbtimenow = time();
    $fbproductprice = $helper->get_session('payble_amt') - $wallectamount -$couponamount;
    $fbpayload = [
            [
                "action_source" =>"website",
                "event_name" =>"InitiateCheckout",
                "event_time" => $fbtimenow,
                "custom_data" => ["currency"=>"INR","value"=>$fbproductprice],
                "user_data"=> ["client_ip_address" => $client_ip, "client_user_agent" =>$user_agent,"em" => [],"ph"=>[]]
            ]
        ];
    $fbquery = [];
    $fbquery['data'] = $fbpayload;
    $fbquery['access_token'] = 'EAAC2YOuTMMIBAMsK6zwaQTk6v5r83OfGYpdHf0aTGYIxjPKA4aP2Ya6sh4gcHNFGHC1y28wluEaaYZANOZBsDGbUZByIW66ATCRNWm0ajiwQgjwmrSZAhLqZAc6ZCMoYByaIfwdilSLn8egHiYvBO6y4YZBdqsmnb8B6tz3e7QCYsmk2KFiyRwv';
    $fbcurl = curl_init();
    curl_setopt_array($fbcurl, array(
        CURLOPT_URL => "https://graph.facebook.com/v13.0/312002919430356/events",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  http_build_query($fbquery),
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "Accept: application/json"),
    ));
    $fbresponse = curl_exec($fbcurl);
    $fberr = curl_error($fbcurl);
    if($fberr){ curl_getinfo($fbcurl); } ?>
    <script>
    console.log(<?=$fbresponse;?>);
    $('.place_ord').click(function(){   $(this).prev('button').trigger('click'); });
    $('input[name="pay_mode"]').on('change',function(){
        var currentVal = $(this).val();
        console.log(currentVal);
        if(currentVal == 'cod' || currentVal == 'partialpayment'){
            $('#cod_charge div').html('+ ₹<?=$codcharges;?>');
            $('#bookingammount').fadeIn(function(){
              $('.totval').html("<?php if($couponamount > 0){ echo ($helper->get_session('payble_amt') - $helper->get_session('coupon_amt') + $codcharges) - $wallectamount - $paypartialamount; } 
              else { echo ($helper->get_session('payble_amt') + $codcharges - $couponamount ) - $wallectamount - $paypartialamount; } ?>");
              $('.paymsg').html("Place Order ₹<?=$paypartialamount;?>");
            });
        }
        else{
            $('#cod_charge div').html('+ ₹0');
            $('#bookingammount').fadeOut(function(){ 
                $('.paymsg').html("Place Order ₹<?php 
                if($couponamount>0) { echo ($helper->get_session('payble_amt')-$couponamount) - $wallectamount; }
                else { echo $helper->get_session('payble_amt') - $wallectamount-$couponamount; } ?>");
            });
        }
    });
  </script>
  
  <?php }
} ?>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<style> .responsive { width: 100%; height: auto; } </style>
<script> scq('Initiate checkout', 'pre_defined') </script>
</head>
<body>
<img loading="lazy" data-src="https://myphotoprint.b-cdn.net/reviewsphotos/instagram%20followers%20trust%20photo.png?quality=40" alt="Trust" class="responsive asyncImage" >
&nbsp;
<img loading="lazy" data-src="https://myphotoprint.b-cdn.net/reviewsphotos/googletrsutsupportshipping.png?quality=40" alt="Trust" class="responsive asyncImage" >
&nbsp;
<img loading="lazy" data-src="https://myphotoprint.b-cdn.net/reviewsphotos/Meetourteam.png?quality=40" alt="Trust" class="responsive asyncImage" >
<?php include('footer.php'); ?>