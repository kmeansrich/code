<?php 
include('header.php');
require('config.php');
require('razorpay-php/Razorpay.php');

// Create the Razorpay Order
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
$api = new Api($keyId, $keySecret);

$seedata = $see->SITE_DATA('see_order_status');
$datasee = explode(',',$seedata);
$firststatus = $datasee[0]; 
$secondstatus = $datasee[1];
$orderSubtype = '';
$getOd = 0;
if(!empty($helper->get_session('USER_ID'))){
    if(!empty($helper->get_session('USER_ID'))){
    $uid = $helper->get_session('USER_ID');
    $getuser = $see->getrow("see_users", "see_u_id = $uid");
    $UID = $getuser['see_u_id'];
    
    $getlastOrder = $see->getrow("see_orders","see_uo_u_id = '$uid' ORDER BY see_uo_id DESC");
    $grand_total = $helper->get_session('payble_amt') + $helper->get_session('cod_charge');
   
    //$userproducts->LastUserOrder($UID);
    $lastOrderId = $getlastOrder['see_uo_id'];
    $uniqueOrderID = $getlastOrder['see_uo_id'];
    $orderPayMode = $getlastOrder['see_uo_pay_mode'];
    $orderamount = $getlastOrder['see_uo_amt'];
    $onumber = $getuser['see_u_mobile']; 
    
    
    if($orderPayMode == 'ccavenue' || $orderPayMode == 'razorpay' || $orderPayMode == 'partialpayment'){  
        
        if($orderPayMode == 'partialpayment'){
            
            if(isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id'])){
        	    if(!empty($helper->get_session('orderId'))){
            	    $order_id_Detail = $_SESSION['orderId'];    
            		@$transactionId = $_POST['razorpay_payment_id'];
            		$success = true;
            		try{
            			// Please note that the razorpay order ID must
            			$attributes = array(
            				'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            				'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            				'razorpay_signature' => $_POST['razorpay_signature']
            			);
            			$api->utility->verifyPaymentSignature($attributes);
            		}
            		catch (SignatureVerificationError $e) {
            			$success = false;
            			$payerror = 'Razorpay Error : ' . $e->getMessage();
            		}
            		
                    if($success){
                        $getpartial = $see->getrows('see_partial_payment',"see_pp_uid = '$uid' AND see_pp_oid = '$uniqueOrderID' ");
                        $dataarray = [
                            'see_pp_uid' => $uid,
                            'see_pp_oid' => $uniqueOrderID,
                            'see_pp_amount' => $helper->get_session('partialprice'),
                            'see_pp_type' => '',
                            'see_pp_trans' => '',
                            'see_pp_status' => 'paid'
                        ];
                        if($getpartial){ $addpartial = $see->updaterow("see_partial_payment",$dataarray,"see_pp_oid = '$uniqueOrderID' AND see_pp_uid = '$uid'"); }
                        else{ $addpartial = $see->addrow("see_partial_payment",$dataarray); }
                        $orderPayMode = 'cod';
                        $orderSubtype = 'partialpayment';
                        
                        
                        if($orderPayMode == 'cod'){ 
                            $firststatus = 'processing';
                            $dataaraay = ['see_uo_trans_id' => '', 'see_uo_pay_status' => '1', 'see_uo_status' =>  $firststatus, 'see_uo_pay_date' => $date];
                            $statusArray = [["datetime" => $date, "status"  =>  $firststatus, "comment" => " "]];
                            $statusArray = json_encode($statusArray);
                            $dataaraay2 = ['see_uop_status' => $statusArray];
                            $paystatusupdate = $see->updaterow("see_orders",$dataaraay,"see_uo_id = $lastOrderId");
                            $paystatusupdate2 = $see->updaterow("see_order_products",$dataaraay2,"see_uop_orderID = $lastOrderId");
                            unset($_SESSION['cart']);
                            unset($_SESSION['orderId']);
                            unset($_SESSION['coupon_code']);
                            unset($_SESSION['coupon_amt']);
                            @header("Refresh:0");
                        }
                    }
                    else{ echo "Session Expired"; }
        	    }
            }
            else{ 
                echo "Session Expired";
                exit();
                
            }
        }
        
        if(isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id'])){
    	    if(!empty($helper->get_session('orderId'))){
        	    $order_id_Detail = $_SESSION['orderId'];    
        		@$transactionId = $_POST['razorpay_payment_id'];
        		$success = true;
        		try {
        			// Please note that the razorpay order ID must
        			$attributes = array(
        				'razorpay_order_id' => $_SESSION['razorpay_order_id'],
        				'razorpay_payment_id' => $_POST['razorpay_payment_id'],
        				'razorpay_signature' => $_POST['razorpay_signature']
        			);
        			$api->utility->verifyPaymentSignature($attributes);
        		} 
        		catch (SignatureVerificationError $e) {
        			$success = false;
        			$payerror = 'Razorpay Error : ' . $e->getMessage();
        		}
    
                $secondstatus = 'processing';
                $failurestatus = "pending";
                if($success){ echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
                    $dataaraay = ['see_uo_trans_id' => $transactionId, 'see_uo_pay_status' => '1', 'see_uo_status' =>  $secondstatus, 'see_uo_pay_date' => $date];
                    $paystatusupdate = $see->updaterow("see_orders",$dataaraay,"see_uo_id = $order_id_Detail");
                    $statusArray = [["datetime" => $date, "status"  =>  $secondstatus, "comment" => " "]];
                }
                else{
                    $statusArray = [["datetime" => $date, "status"  =>  "$failurestatus", "comment" => " "]];
                }
                $statusArray = json_encode($statusArray);
                $dataaraay2 = ['see_uop_status' => $statusArray];
                $paystatusupdate2 = $see->updaterow("see_order_products",$dataaraay2,"see_uop_orderID = $order_id_Detail");
            }
        }
        
        unset($_SESSION['cart']);
        unset($_SESSION['orderId']);
        @header("Refresh:0");
    }

    $getuserConfirmedOrders = $see->getrows("see_orders","see_uo_id= '$lastOrderId' ORDER BY see_uo_id DESC");  ?>
        <section class="see-full section-padding">
            <div class="see-width">
                <?php 
                $getOrderDetail = $see->getrow("see_orders","see_uo_id = '$uniqueOrderID'");
                $orderDate = $getOrderDetail['see_uo_pay_date'];
                $orderPayMode = $getOrderDetail['see_uo_pay_mode'];
                @$getDeliveryAddress = json_decode($getOrderDetail['see_uo_billing'],true);  
                $userId = $uid;
                $getUserDetail = $see->getrow("see_users","see_u_id = '$userId'");
                $userEmail = $getUserDetail['see_u_email'];
                $contact_name = $getDeliveryAddress['b_name'];
                $contact_phone1 = $getDeliveryAddress['b_mob1'];
                $contact_phone2 = $getDeliveryAddress['b_mob2'];
                $delivery_address = $getDeliveryAddress['b_add'];
                $wallet_pay_amount = $getOrderDetail['see_wallet_pay'];
                $delivery_city = $getDeliveryAddress['b_city'];
                $delivery_pincode = $getDeliveryAddress['b_pin'];
                $selectallorders = $see->getrows("see_order_products","see_uop_orderID = '$uniqueOrderID'");
                @$orderstatus = $getOrderDetail['see_uo_status'];
                $productsName = '';
                $orderTotal = '';
                $i = '1';
                $orderedStatus = '';
                foreach($selectallorders as $selectallorder){
                    $orderedAmnt = $selectallorder['see_uop_order_amnt'];
                    @$orderedStatus = json_decode($selectallorder['see_uop_status'],true);
                    @$orderTotal = $orderTotal+$orderedAmnt;
                } 

                if(isset($_POST['status_update'])){
                    $new_orderStatus = $_POST['Order_Status'];
                    $statusArray = [["datetime" => $date, "status"  =>  $new_orderStatus, "comment" => ""]];
                    $new_status_array = json_encode($statusArray);
                    $dataaraay = ['see_uop_status' => $new_status_array];
                    $updateOrderHistory = $see->updaterow("see_order_products",$dataaraay,"see_uop_orderID = '$orderID'");
                    if($updateOrderHistory){ $helper->redirectTo('order-details.php?orderid='.$orderID); }
                }
                
                
              $productamount = $_SESSION['xorder_total'];
              $codcharges = '0';
              if($orderPayMode == 'partialpayment'){ $codcharges = $helper->get_session('cod_charge'); }
              
              $finalordertotal = $productamount + $codcharges;
              ?>
                
            	<!-- new des -->
            	<div class="container-fluide conta">
                <?php
                $sendwamessage = "Hey $contact_name ! ThankYou for your order on %0a *MyPhotoPrint.in*  %0a *Order No:* $uniqueOrderID  %0a *Payment Mode:* $orderPayMode %0a *Total Payment:* $grand_total %0a  Next step:For COD Order 20% advance booking amount Required & balance amount you have to pay on cash on delivery %0a 20% अभी भुगतान करे ओर शेष राशि का भुगतान नगद में delivery पर करना होगा ।"; 
                $whatsappRedirect = "whatsapp://send?phone=919319748388&text=$sendwamessage"; ?>
	            	<div class="col-sm-12 col">
	            	    <img src="<?php echo SITE_URL; ?>images/greentick.png" class="track">
			  			<h5 class="y_center">Your order has been placed successfully!</h5>
			  		</div>
	                <div class="col-sm-12 col_p">
	                    <div class="table table-bordered table-condensed table_p">
                            <div class="title">Summery:</div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Order Id: </b></span>
                                <span> #<?php echo $uniqueOrderID; ?></span>
                            </div>
                            <div class="upper_case">
                                <span><b>Name : </b></span>
                                <span><?php echo $contact_name; ?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Order Date : </b></span>
                                <span> <?php echo date('Y-m-d h:i:s',strtotime($orderDate)); ?></span>
                            </div>
                            <div class="upper_case">
                                <span><b> Address : </b></span>
                                <span><?php echo $delivery_address; ?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Updated : </b></span>
                                <span><?=$orderedStatus[0]['datetime'];?></span>
                            </div>
                            <div class="upper_case">
                                <span> <b>Email : </b></span>
                                <span><?php echo $userEmail; ?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Order Total : </b></span>
                                <span> <?='₹ '.$finalordertotal;?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Paid Amount : </b></span>
                                <?php $totaldatapric = (($orderPayMode == 'ccavenue' || $orderPayMode == 'razorpay' || $orderPayMode == 'partialpayment') ? ($grand_total - $helper->get_session('cod_charge')) :  $grand_total); ?>
                                <span> ₹ <?=$totaldatapric;?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Payment : </b></span>
                                <span class="hide_border"><?php echo $orderPayMode ?></span>
                            </div>
                            <div class="upper_case">
                                <span><b> Phone : </b></span>
                                <span><?php echo $contact_phone1;if(!empty($contact_phone2)){ echo ', '.$contact_phone2; } ?></span>
                            </div>
                            <div class="upper_case">
                                <span class="hide_border"><b>Status : </b></span>
                                <span class="hide_border"><?=$orderedStatus[0]['status'];?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="add-cart-main see-full">
                        <div class="cart-left see-full">
                            <span>Product List</span>
                            <div class="left-img">
                            <?php 
                            foreach($selectallorders as $selectallord){
                                $orderProID = $selectallord['see_uop_proid'];
                                $orderProAmount = $selectallord['see_uop_order_amnt'];
                                $orderProQty = $selectallord['see_uop_pro_quantity'];
                                $selectProduct = $see->getrow("see_products","see_pro_id = $orderProID");
                                $productName = $selectProduct['see_pro_name'];
                                $productMedia = $selectProduct['see_pro_media'];
                                @$selectSingleMedia = ((!empty($productMedia)) ? unserialize($productMedia) : ''); ?>
                              <div class="see-full order-details see-padding-24">
                                <div class="see-4 see-ltb-4 see-tb-4 see-sm-4 see-xsm-4 see-padding-left-0 tcmt">
                                  <div class="see-full img">
                                     <img src="<?php echo SITE_PATH.'products/'.$selectSingleMedia[0]['url'];?>" alt="Product img"/>
                                  </div>
                                </div>
                                <div class="see-8 see-ltb-8 see-tb-8 see-sm-8 see-xsm-8 see-padding-left-0">
                                  <div class="see-full details">
                                    <h6><a class="theme-second-hover-text" href=""><?php echo $productName; ?></a></h6>
                                    <input type="hidden" value="352" id="cart_order_id1">
                                                                    
                                    <p><?=$orderProQty;?> Qty</p>
                                    <div class="see-full see-padding-8 price">
                                      <span id="finalprice"><?php  echo $_SESSION['product_amount']; ?></span>	
                                    </div>
                                    
                                  </div>
                                </div>
                              </div>
                        <?php }
                        
                        ?>
            </div>
          </div>
          <div class="cart-right see-full">
            <div class="right-cont">
              <span>My Order</span>
            </div>
            <div class="right-cont">
                <span> Product Amount </span>
                <span> ₹<?=$productamount;?> </span>
            </div>
            <div class="right-cont">
                <span>SHIPPING CHARGES(COD FEE) </span>
                <span>₹ <?=$codcharges;?> </span>
            </div>
            <?php 
            $finalpaybleamount = $productamount+$codcharges;
            if($selectallorders[0]['see_coupon_code']!=''){ ?>
                <div class="right-cont">
                    <span>Coupon Ammount:</span>
                    <span>₹<?php echo $selectallorders[0]['see_coupon_amt']; ?></span>
                </div>
               <?php
               $finalpaybleamount = $finalpaybleamount - $selectallorders[0]['see_coupon_amt']; 
            } ?>
            <div class="right-cont">
                <span>Less booking amount</span>
                <span>₹<?=$totaldatapric;?></span>
            </div>
            <div class="right-cont">
                <span>Total</span>
                <span>₹ <?php echo $finalpaybleamount;
                // if($orderPayMode == 'ccavenue' || $orderPayMode == 'razorpay'){  
                //     if($wallet_pay_amount>0 && $selectallorders[0]['see_coupon_amt']<1){ echo ($grand_total-$helper->get_session('cod_charge'))-$wallet_pay_amount;  } 
                //     elseif($selectallorders[0]['see_coupon_amt']>0 && $wallet_pay_amount<1){ echo ($grand_total-$helper->get_session('cod_charge'))-$selectallorders[0]['see_coupon_amt']; }
                //     elseif($wallet_pay_amount>0 && $selectallorders[0]['see_coupon_amt']>0){ echo ($grand_total-$helper->get_session('cod_charge'))-$wallet_pay_amount-$selectallorders[0]['see_coupon_amt']; }
                //     else{ echo ($grand_total-$helper->get_session('cod_charge')); } 
                // }
                // else{  
                //     if($wallet_pay_amount >0 && $selectallorders[0]['see_coupon_amt']<1){  echo $grand_total-$wallet_pay_amount;  }
                //     elseif($selectallorders[0]['see_coupon_amt']>0 && $wallet_pay_amount<1){ echo $grand_total-$selectallorders[0]['see_coupon_amt']; } 
                //     elseif($wallet_pay_amount>0 && $selectallorders[0]['see_coupon_amt']>0){ echo $grand_total-$selectallorders[0]['see_coupon_amt']-$wallet_pay_amount; }
                //     else { echo $grand_total.'.00'; } 
                // } ?>
             </span>
            </div>
          </div>
        </div>          
		        </div>   
            </div>
            <?php
            if($wallet_pay_amount>0){ ?>
                <tr>
                    <td></td>
                    <td >Wallet Money:</td>
                    <td></td>
                    <td> ₹ <?php echo $wallet_pay_amount; ?></td>
                </tr> 
                <?php 
                // Check for old payment!
                $checkAddedWallet = $see->getrowcolumn("see_user_wallet", ['see_uw_id'], "see_uw_u_id ='$uid' AND see_uw_o_id='$uniqueOrderID'");
                if(!$checkAddedWallet){
                    $data_Array = [
                        'see_uw_u_id' => $uid,
                        'see_uw_o_id' => $uniqueOrderID,
                        'see_uw_amount' => '-' . $wallet_pay_amount,
                        'see_uw_remarks' => 'Deducted Rs.'.$wallet_pay_amount.' For ORDER ID ' . $uniqueOrderID,
                    ];
                    $do = $see->addrow("see_user_wallet", $data_Array);
                }
            }
            ?>
        </section> 
        <?php
        // Message Send  
        $msg = "Thank you for your Order at myphotoprint.in Your Order Id is ".$uniqueOrderID."  Delivery Time will be 5 to 7 days, You will get a ADDRESS VERIFICATION CALL for better delivery experiece to your doorstep kindly attend the call. For Tracking your order click this link: http://bit.ly/MyPhotoPrintAPP   ";
        $helper->sendsmsGET($onumber, O_SENDER_ID, O_ROUTE_ID, $msg, O_AUTH,TEMPID);
        // Whatsapp Message 
        $senddata = '{
            "template_name": "new_order_done_template", 
            "broadcast_name": "message", 
            "parameters": [
                    { "name": "name", "value": "'.$contact_name.'" },
                    { "name": "order_number", "value": "'.$uniqueOrderID.'" },
                    { "name": "payment_mode", "value": "'.$orderPayMode.'" },
                    { "name": "total_payment", "value": "'.$totaldatapric.'" },
                    { "name": "tracking_url", "value": "https://bit.ly/3tY6xAv" }
                ]
        }';
        $msgsend = $helper->sendWhatsapp($onumber,$senddata);
        
    
        // Mail Send
        $to =  $userEmail; 
        $subject = "myphotoprint Order";
        $headers = "From: support@myphotoprint.in" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        ob_start();
        include "mailers/order.php";
        $message = ob_get_clean(); 
        mail($to,$subject,$message,$headers); 
        
        ob_start();
        echo $sdata = str_replace("{GRAND_TOTAL}", $grand_total,$see->SITE_DATA('see_site_success_code'));
        $ob_str=ob_get_contents();
        ob_end_clean();
        echo $ob_str;  
    
    // Facebook Traking
    $fbtimenow = time();
    $fbproductprice = $grand_total;
    $fbpayload = [
                [
                "action_source" =>"website",
                "event_name" =>"Purchase",
                "event_time" => $fbtimenow,
                "custom_data" => ["currency"=>"INR","value"=>$fbproductprice],
                "user_data"=> ["em" => ["7b17fb0bd173f625b58636fb796407c22b3d16fc78302d79f0fd30c2fc2fc068"],"ph"=>[]]
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
    if($fberr){ curl_getinfo($fbcurl); }
    ?>
    <script>
        console.log(<?=$fbresponse;?>); 
        gtag('event', 'conversion', { 'send_to': 'AW-982171696/SRrNCPab75EBELCAq9QD', 'transaction_id': '' }); </script>
    <?php 
    }
    else{ include("./error404.php"); } ?>
     <script>
        var ele1=document.getElementById('popup1');
        function interval(){
            ele1.classList.add('appear');
            window.location.href="<?=$whatsappRedirect;?>";
        }
        setTimeout(interval, 5000);
        document.getElementById('popup1_close').onclick = function(){
            ele1.classList.remove('appear');
        }
    </script>
    <!-- Event snippet for Purchase conversion page -->
    <script>
      gtag('event', 'conversion', {
          'send_to': 'AW-925593001/kocTCLiP-LgBEKnbrbkD',
          'transaction_id': ''
      });
    </script>
    <!-- Event snippet for Sales conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-982171696/SRrNCPab75EBELCAq9QD',
      'transaction_id': ''
  });
</script>
<script>scq('Purchase', 'pre_defined')</script>
<?php
}
else{ $helper->redirectTo('logout.php'); }
include('footer.php');