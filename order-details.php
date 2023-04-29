<?php $showInvoice = false; ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="see-full">
<?php
if(isset($_GET['orderid'])){ 
    $timestamp = time();
    $shipappID = SHIP_APPID;
    $shipkey = SHIP_KEY;
    $secret = SHIP_TOKEN;
    $sellerid = SHIP_SELLERID;
    $selleraddressid = SHIP_ADDRESSID;
    
    $orderID = $_GET['orderid'];
    $getOrderDetail = $see->getrow('see_orders',"see_uo_id = '$orderID'");
    $orderDate = $getOrderDetail['see_uo_pay_date'];
	$orderDate2 = $getOrderDetail['see_uo_add_date'];
    $orderPayMode = $getOrderDetail['see_uo_pay_mode'];
    $orderBillingID = $getOrderDetail['see_uo_billing_id'];
    $utmcampaing = $getOrderDetail['see_uo_utm_campaign'];
    $utmsource = $getOrderDetail['see_uo_utm_source'];
    $utmmedium = $getOrderDetail['see_uo_utm_medium'];
    $utmurl = $getOrderDetail['see_uo_utm_url'];
    $getDeliveryAddress = $see->getrow('see_user_billing_detail',"see_ubd_id = '$orderBillingID'");
    if($getDeliveryAddress){ $userId = $getDeliveryAddress['see_ubd_u_id']; }
    else{ $userId = $getOrderDetail['see_uo_u_id']; }
    $getUserDetail = $see->getrow('see_users',"see_u_id = '$userId'");
    $userEmail = $getUserDetail['see_u_email']; 
    $userName = $getUserDetail['see_u_name'] ?: 'Edit Data';
    $userMobile = $getUserDetail['see_u_mobile'];
    
    $uid = $getUserDetail['see_u_id']; 
    $billing =  ((!empty($getOrderDetail['see_uo_billing'])) ? json_decode($getOrderDetail['see_uo_billing'],true) : ''); 
    $contact_name =   $billing['b_name'];
    $contact_phone1 = $billing['b_mob1'];
    $contact_phone2 = $billing['b_mob2'];
    $delivery_address = $billing['b_add'];
    $delivery_landmark = (isset($billing['b_land']) ? $billing['b_land'] : '');
    $delivery_city = $billing['b_city'];
    $delivery_stateid = $getDeliveryAddress['see_ubd_state'];
    $getLocationDetail = $see->getrow('see_location',"see_l_id = '$delivery_stateid'");
    $delivery_state = $getLocationDetail['see_l_name'];
    $delivery_pincode = $billing['b_pin'];
    @$orderstatus = json_decode($getOrderDetail['see_uo_status'],true);
    $ordered_pro_where = "see_uop_orderID = '$orderID'";
    $selectallorders = $see->getrows('see_order_products',$ordered_pro_where);
    $productsName = '';
    $orderTotal = '';
    $i = '1';
    $process = "processing";
    $canel_order = "cancelled";
    $confirmed_order = "confirmed";
    $pending_order = "pending";
    $return_order ="returned";
    $get_all_order = $see->getrows("see_orders","see_uo_u_id = '$uid' ORDER BY see_uo_id DESC");
    $get_all_order_processing = $see->getrows("see_orders","see_uo_u_id = '$uid' AND see_uo_status = '$process' ");
    $get_all_canel_order = $see->getrows("see_orders","see_uo_u_id = '$uid' AND see_uo_status = '$canel_order' ");
    $get_all_confirmed_order = $see->getrows("see_orders","see_uo_u_id = '$uid' AND see_uo_status = '$confirmed_order' ");
    $get_all_pending_order = $see->getrows("see_orders","see_uo_u_id = '$uid' AND see_uo_status = '$pending_order' ");
    $get_all_return_order = $see->getrows("see_orders","see_uo_u_id = '$uid' AND see_uo_status = '$return_order' ");
    
    $couponname = $getOrderDetail['see_coupon_code'];
    $couponamount = $getOrderDetail['see_coupon_amt'] ? $getOrderDetail['see_coupon_amt'] : 0;
   

    foreach($selectallorders as $selectallorder){
        $orderedAmnt = $selectallorder['see_uop_order_amnt'];
        @$orderTotal = $getOrderDetail['see_uo_amt'];
    }  

    $name = $getUserDetail['see_u_name'];
    // Confirmation Mail

    $admin_email = $see->getrow("see_data","see_d_name = 'see_email'");
    $mailto = $admin_email['see_d_value'];
    // Send Email to Admin
    $mailto = $mailto;

    $v = "";
    $body  = "The following confirms the details of your order:\n";
    $body .= "\n\n";
    $body .= "Order Details: \n";
    if(!empty($orderstatus)){
        foreach($orderstatus as $key => $val){
            if($key == '1'){ $v = date('Y-m-d h:i:s',strtotime($val));  } 
        }
    }
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $userEmail . "\n";
    $body .= "Order ID: " . $orderID . "\n";
    $body .= "Payment Method:  ".$orderPayMode. "\n";
    $body .= "\n\n";
    $body .= "TOTALS: " . $orderTotal . "\n\n";
    $body .= "Created At:".date('Y-m-d h:i:s',strtotime($orderDate))." \n";
    $body .= "Updated On:".$v." \n";
    $udate = date('d-m-Y', strtotime($orderDate));
    $udate2 = date('d-m-Y', strtotime($orderDate2));
    if(isset($_POST['status_update'])){
        $new_orderStatus = $_POST['Order_Status'];
        switch($new_orderStatus){
            case 'complete': 
                    $subject = "Order Completed - ".$orderID;
                    mail($mailto, $subject, $body);
                break;
            case 'cancel':  $subject = "Order Cancelled - ".$orderID;
                    mail($mailto, $subject, $body);
                break;
            case 'confirmed': 
                $sign = "key:". $shipkey ."id:". $shipappID. ":timestamp:". $timestamp;
                $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                $ch = curl_init();
                foreach($selectallorders as $item){
                    $orderProID = $item['see_uop_proid'];
                    $orderProAmount = $item['see_uop_order_amnt'];
                    $product_detail_where = "see_pro_id = '$orderProID'";
                    $selectProduct = $see->getrow('see_products',$product_detail_where);
                    $prdID = $selectProduct['see_pro_id'];
                    $productName = $selectProduct['see_pro_name'];
                    $dimId = $selectProduct['see_pro_dimension'];
                    $size_where = "see_dm_id = '$dimId'";
                    $productSize = $see->getrow('see_dimension',$size_where);
                    $productSize = unserialize($productSize['see_dm_value']);
                    $ordertype = '';
                    if($orderPayMode == 'cod'){ $ordertype = $orderPayMode; }
                    else{ $ordertype = 'prepaid';  }
                    $data = array('orders'=>[
                        array(
                            "orderId"=> $orderID,
                            "customerName"=> $contact_name,
                            "customerAddress"=> $delivery_address.' '.$delivery_landmark,
                            "customerLandmark"=> $delivery_landmark,
                            "customerCity"=> $delivery_city,
                            "customerPinCode"=> $delivery_pincode,
                            "customerContact"=> $contact_phone1,
                            "orderDate"=> date("Y-m-d", strtotime($orderDate)),
                            "modeType"=> "Air",
                            "orderType"=> $ordertype,
                            "totalValue"=> $orderTotal,
                            "categoryName"=> 'other',
                            "packageName"=> $productName,
                            "quantity"=> $item['see_uop_pro_quantity'], 
                            "packageLength"=> $productSize['length'],
                            "packageWidth"=> $productSize['width'],
                            "packageHeight"=> $productSize['height'],
                            "packageWeight"=> $productSize['weight'] / 1000,
                            "sellerAddressId"=> $selleraddressid
                        )
                    ]);

                    $data_json = json_encode($data);
                    $header = array(
                        "x-appid: $shipappID",
                        "x-sellerid:$sellerid",
                        "x-timestamp: $timestamp",
                        "x-version:3",
                        "Authorization: $authtoken",
                        "Content-Type: application/json",
                        "Content-Length: ".strlen($data_json)
                    );
                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, 'https://api.shyplite.com/order');
                    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
                    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$data_json);
                    @curl_setopt($ch,CURLOPT_ETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                }
                break;
            default:
        }
        $new_status_array = array($new_orderStatus,$date);
        $new_status_array = array_merge($new_status_array,$orderstatus);
        $dataaraay = ['see_uo_status' => json_encode($new_status_array)];
        $status_update_where = "see_uo_id = '$orderID'"; 
        $updateOrderHistory = $see->updaterow('see_orders',$dataaraay,$status_update_where);
        if($updateOrderHistory){ $helper->redirectTo('order-details.php?orderid='.$orderID); }
    }  ?> 
    <div class="container">
        <button type="button" class="btn btn-primary">Total Orders : <span class="bold-font"><?php echo $count = count($get_all_order);?></span></button>
        <button type="button" class="btn btn-success">Processing: <span class="bold-font"><?php echo $count1 = count($get_all_order_processing);?></span></button>
        <button type="button" class="btn btn-danger">Pending: <span class="bold-font"><?php echo $count4 = count($get_all_pending_order);?></span></button>
        <button type="button" class="btn btn-danger">Confirmed: <span class="bold-font"><?php echo $count2 = count($get_all_confirmed_order);?></span></button>
        <button type="button" class="btn btn-danger">Cancelled: <span class="bold-font"><?php echo $count3 = count($get_all_canel_order);?></span></button> 
        <button type="button" class="btn btn-danger">Returned: <span class="bold-font"><?php echo $count5 = count($get_all_return_order);?></span></button>
    </div>
	  <div class="see-full product-list-block see-padding-bottom-0">
	    <div class="product-list">
	        <?php 
                    function track($orderID){
                        $neworder = 'PP'.$orderID; 
                        $sign = "key:". $shipkey ."id:". $shipappID. ":timestamp:". $timestamp;
                        $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                        
                        $body = array('orders' => array($neworder));
                        $data_json = json_encode($body);
                        
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.shyplite.com/track/?oid='.$orderID,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $data_json,
                            CURLOPT_HTTPHEADER => array(
                              'x-timestamp:'.$timestamp,
                              'Authorization: '.$authtoken,
                              'x-appid: '.$shipappID,
                              'x-sellerid:'.$sellerid,
                              'x-version: 3',
                              'Content-Type: application/json'
                            ),
                        ));
                        
                        $response = curl_exec($curl);
                        
                        curl_close($curl);
                        return $response;
                    }
                    
                    $curentorderstatus = track($orderID);
                    $newdata = json_decode($curentorderstatus,true);
                    if(isset(reset($newdata)['events'])){ $currentdatas = end(reset($newdata)['events'])['status']; }
                    else{ $currentdatas = '--'; }
            	  ?> 
	    	<div class="see-full see-padding-12 see-padding-left-right-8 see-border-bottom">
			    <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-margin-bottom">
			      <div class="see-full">
			        <h4 class="f-24 bold-font see-4">Orders #<?php echo $orderID; ?> Details</h4> 
			        <div class="f-24 bold-font see-4"> Current Status <?=$currentdatas;?></div>
                     <span style="float:right;padding:10px;" class="see-orange see-4"><a target="_blank" href="<?php echo SITE_URL;?>invoice/download.php?user=<?php echo $userId;?>&invoice=<?php echo base64_encode($orderID);?>">Generate Invoice</a></span> 
			      </div>
			    </div>
			    
			    <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-margin-bottom">
			      <div class="see-full">
			            <?php 
			            $getpartaildata = $see->getrow('see_partial_payment',"see_pp_oid = '$orderID' ORDER BY see_pp_id DESC");
			            if($getpartaildata){ ?>
			            <p class="f-24 see-3">Partial Payment - <?php echo $getpartaildata['see_pp_amount'].' ( '.$getpartaildata['see_pp_status'].')'; ?> </p> 
			            <p class="f-24 see-3">Trans ID - <?php echo $getpartaildata['see_pp_trans']; ?> </p> 
			            <p class="see-3"> Date <?=$getpartaildata['see_pp_modified'];?> </p>
			            <p class="see-3">  <?=$getpartaildata['see_pp_type'];?>  Payment</p>
			            <?php } ?>
			            
			      </div>
			 </div>
			    
			    
			    <div class="see-4 see-ltb-4 see-tb-4 see-sm-12 see-xsm-12 see-margin-top">
			    	<div class="see-full">
			    		<h6 class="bold-font f-18">General Details</h6>
			    	</div>
			    	<ul class="see-full see-padding-8 f-14">
			    		<li class="see-margin-bottom"><span class="bold-font">Created At: </span><?php echo date('Y-m-d h:i:s',strtotime($orderDate)); ?></li>
			    		<li class="see-margin-bottom"><span class="bold-font">Last Update: </span><?php if(!empty($orderstatus)){ foreach($orderstatus as $key => $val){ if($key == '1'){ echo date('Y-m-d h:i:s',strtotime($val)); } } }else{ echo 'Never'; } ?></li>
			    		<li class="see-margin-bottom"><span class="bold-font">Payment: </span>
                            <select name="pmode" class="pmode_select">
                              <option value="cod" <?=(($orderPayMode == 'cod') ? 'selected' : '');?> >COD</option>
                              <option value="razorpay" <?=(($orderPayMode == 'razorpay') ? 'selected' : '');?>>Prepaid</option>
                              
                            </select>
                        </li>
                        <li class="see-margin-bottom"><span class="bold-font">Order Price: </span> <i class="fas fa-rupee-sign see-lh-20 see-padding-left-right-4 see-padding-left-0"></i><b class="edit" id='mrp_<?=$orderID;?>'><?php echo $orderTotal; ?></b>.00</li>
                        <li class="see-margin-bottom"><span class="bold-font ">Wallet: <i class="fas fa-rupee-sign see-lh-20 see-padding-left-right-4 see-padding-left-0"></i><?php 
                            $walletprice = '0';
                            $alletus =  @$see->getrowcolumn('see_user_wallet',['see_uw_amount'],"see_uw_o_id = '$orderID'")['see_uw_amount'];
                            if($alletus){ $walletprice = $alletus; }
                            echo $walletprice; ?></span></li>
                            
                        <li class="see-margin-bottom"><span class="bold-font">Coupon Applied: </span> <i class="fas fa-rupee-sign see-lh-20 see-padding-left-right-4 see-padding-left-0"></i><?php echo "$couponamount ( $couponname )"; ?></li>
                        <li class="see-margin-bottom"><span class="bold-font">Total Price: </span> <i class="fas fa-rupee-sign see-lh-20 see-padding-left-right-4 see-padding-left-0"></i><b class="edit" id='mrp_<?=$orderID;?>'><?php echo ($orderTotal+$walletprice) - $couponamount; ?></b>.00</li>
			    	</ul>
			    	<div id="orderDetailsPopup" class="see-dp-none">
                        <form name="ajaxSubmit">
                            <input type="text" name="clientName" value="" placeholder="Enter Client Name" />
                            <input type="text" name="clientAdd" placeholder="Enter Client Address" />
                            <input type="tel" name="clientPhone" placeholder="Enter Client Phone Number" />
                            <select name="clientPayment">
                                <option value="cod">Cash On Delivery</option>
                                <option value="prepaid">Prepaid</option> 
                            </select>
                            <input type="submit" value="Save Changes" />
                        </form>
                    </div>
			    </div> 
                
              <div class="see-3 see-ltb-3 see-tb-4 see-sm-12 see-xsm-12 see-margin-top">
                	<div class="see-full">
			    		<h6 class="bold-font f-18">Register Details</h6>
			    	</div>
			    	<ul class="see-full see-padding-8 f-14">
			    		<li class="see-margin-bottom"><span class="bold-font ">Name: </span><b class="edituser" id='username_<?=$userId;?>'><?php echo $userName; ?></b></li>
			    		<li class="see-margin-bottom"><span class="bold-font ">Mobile: </span><b class="edituser" id='usermobile_<?=$userId;?>'> <?php echo $userMobile;?></b></li>
			    		<li class="see-margin-bottom"><span class="bold-font ">Email address: </span> <b class="edituser" id='useremail_<?=$userId;?>'> <?=($userEmail ? $userEmail : 'edit');?></b></li>
			    	</ul>
			    </div>

			  <div class="see-4 see-ltb-4 see-tb-4 see-sm-12 see-xsm-12 see-margin-top">
			    	<div class="see-full">
			    		<h6 class="bold-font f-18">Billing Details</h6>
			    	</div>
			    	<ul class="see-full see-padding-8 f-14">
			    		<li class="see-margin-bottom"><span class="bold-font"> Name: </span><b class="edit" id='name_<?=$orderID;?>'><?php echo $contact_name; ?></b> </li>
			    		<li class="see-margin-bottom"><span class="bold-font"> Address: </span><b class="edit" id='add_<?=$orderID;?>'><?php echo $delivery_address; ?></b> </li>
			    		<li class="see-margin-bottom"><span class="bold-font"> Landmark: </span><b class="edit" id='land_<?=$orderID;?>'><?php echo $delivery_landmark; ?></b> </li>
			    		<li class="see-margin-bottom"><span class="bold-font"> Pincode: </span><b class="edit" id='pinco_<?=$orderID;?>'><?php echo $delivery_pincode;?></b> </li>
			    		<li class="see-margin-bottom"><span class="bold-font"> Phone: </span><b class="edit" id='phone_<?=$orderID;?>'><?php echo $contact_phone1;?></b> </li>
			    	</ul>
			    </div> 
			   <div calss="see-full">
			        <div class="see-full">
			    		<h6 class="bold-font f-18">Process From</h6>
			    	</div>
			    	<ul class="see-full see-padding-8 f-14">
			    		<li class="see-margin-bottom see-4"><span class="bold-font ">UTM Source : </span><?php echo $utmsource; ?></li>
			    		<li class="see-margin-bottom see-4"><span class="bold-font ">UTM Campaign: </span><?php echo $utmcampaing;?></li>
			    		<li class="see-margin-bottom see-4"><span class="bold-font ">UTM Medium: </span><?php echo $utmmedium;?></li>
			    		<li class="see-margin-bottom see-12 see-border-top"><span class="bold-font ">UTM URL: </span><?php echo $utmurl;?></li>
			    	</ul>
			   </div>
			</div> 
	        <div class="product-table-scroll">
		        <table class="order-table see-table see-table-each">
		        	<tr>
		        	    <th>Status</th>
		        		<th>ID</th>
		        		<th>Product ID</th>
		        		<th class="see-2"> Product Name </th>
		        		<th>Thumbnail</th> 
		        		<th>Product Attributes</th>
		        		<th>Subtotal</th>
		        	</tr>
    		        <?php 
                    if(isset($_POST['orderPro'])){
                        $order____ID = $_POST['orderPro'];
                        $pro_attr = $_POST['attrib'];
                        $do = $see->updaterow("see_order_products", ['see_uop_pro_attributes' => $pro_attr], "see_uop_id ='$order____ID' LIMIT 1");
                        echo $pro_attr;
                        exit();
                    }
    		        foreach($selectallorders as $selectallord){
                        $orderProID = $selectallord['see_uop_proid'];
                        $order_uop_id = $selectallord['see_uop_id'];
                        $pro_orderstatus = json_decode($selectallord['see_uop_status'],true);
                        $product_shipby = $selectallord['see_uop_ship'];
                        $ordered_pro_options = !empty($selectallord['see_uop_pro_options']) ? json_decode($selectallord['see_uop_pro_options'], true) : [];
                        $ordered_pro_attributes = !empty($selectallord['see_uop_pro_attributes']) ? json_decode($selectallord['see_uop_pro_attributes'], true) : [];
                        $ordered_pro_customize = !empty($selectallord['see_uop_pro_customize']) ? $selectallord['see_uop_pro_customize'] : '';
                        $orderProAmount = $selectallord['see_uop_order_amnt'];
                        $orderProQty = $selectallord['see_uop_pro_quantity'];
                        $product_detail_where = "see_pro_id = '$orderProID'";
                        $selectProduct = $see->getrow('see_products',$product_detail_where);
                        $productName = $selectProduct['see_pro_name'];
                        $productUrl = $selectProduct['see_pro_url'];
                        $pro_media_where = "see_pm_pro_id = '$orderProID' ORDER BY see_pm_id ASC";
                        @$selectSingleMedia = unserialize($selectProduct['see_pro_media']);
                        if(!empty($selectSingleMedia[0])){
                            $product_media = SITE_PATH."products/".$selectSingleMedia[0]['url'];
                            $product_media_alt = $selectSingleMedia[0]['alt']; 
                        }
                        else{
                            $product_media = SITE_URL."images/no-image.jpeg";
                            $product_media_alt = $productName;
                        } ?>
                        <tbody id="prd_<?php echo $orderID; ?>">
    		        	<tr>
    		        	    <td><input type="checkbox" name="statusCheck" class="contact-check-item"></td>
    		        		<td>#<?php echo $orderID; ?></td>
    		        		<td><?php echo $orderProID; ?></td>
    		        		<td class="see-2"><a href="https://myphotoprint.in/product/<?=$productUrl;?>" target="_blank"><?php echo $productName; ?> </a> <strong>* <?=$orderProQty;?></strong></td>
    		        		<td>
    		        		<?php if(!empty($ordered_pro_customize)){?>
                            <a href="<?php echo SITE_URL.$ordered_pro_customize; ?>" target="_blank" title="Click to Enlarge"><img src="<?php echo SITE_URL.$ordered_pro_customize; ?>" alt="<?php echo $product_media_alt; ?>"></a>
                            <?php }else{?>
    		        	    <a href="<?php echo $product_media;?>" download>
                              <img src="<?php echo $product_media;?>" alt="<?php echo $product_media_alt; ?>" width="104" height="142">
                            </a> <?php } ?>
    		        		</td> 
                            <td>
                                <?php 
                                if(!empty($ordered_pro_attributes)){
                                    foreach($ordered_pro_attributes as $key => $ordered_pro_attribute){
                                            if(preg_match('(jpg|png|gif|bmp|webp)', $ordered_pro_attribute) === 1) { 
                                            $fimg = preg_replace('/^.+\\\\/', '', $ordered_pro_attribute);
                                            
                                            $imglinl = SITE_URL.'images/order/'.$udate2.'/'.$fimg;
                                            echo "<a href='$imglinl' target='_blank'> $imglinl </a><br>";
                                       }
                                       else{ 
                                           echo '<br /><b>'.$key.' </b> -';
                                           if (preg_match('/(\.jpg|\.jpeg|\.png|\.bmp|\.webp)$/', $ordered_pro_attribute)){ 
                                               $imglinl = SITE_URL.'images/order/'.$udate2.'/'.$ordered_pro_attribute;
                                                echo "<a href='$imglinl' target='_blank'> $imglinl </a><br>";
                                           }
                                           else{ echo $ordered_pro_attribute.'<br>'; }
                                        }
                                    }
                                }
                                else{ 
                                    echo "Data Invalid <br> Cart ID : ";
                                    $getcartid = $see->getrows("see_cart","user_id = '$userId' AND product_id = '$orderProID' ORDER BY id DESC");
                                    if($getcartid){
                                        foreach($getcartid as $cid){
                                            echo $cid['id'].'<br>';
                                        }
                                    }
                                    else{ echo "Not Found"; }
                                } ?> 
                                <edit_attributes dataorder="<?=$order_uop_id;?>" style="display:none;">
                                    <?php
                                    if(!empty($ordered_pro_attributes)){
                                        foreach($ordered_pro_attributes as $key => $ordered_pro_attribute){?>
                                        <attrb class="f-16 see-text-left see-padding-4">
                                            <span class="data_key f-14 b see-full"><?=$key;?></span><?php
                                            if(preg_match('(jpg|png|gif|bmp|webp)', $ordered_pro_attribute) === 1 || strpos($key,'Upload') !== false) { 
                                                $fimg = preg_replace('/^.+\\\\/', '', $ordered_pro_attribute);
                                                $imglinl = SITE_URL.'images/order/'.$udate2.'/'.$fimg;  ?>
                                                    <input type="file" accept="image/*" hidden style="display:none;">
                                                    <input type="text" class="data_value data_img see-full see-border see-round see-padding-left-right-8 see-padding-4 see-cursor" value="<?=@$ordered_pro_attribute;?>" />
                                                <?php
                                            }
                                            else{ 
                                               //echo '<br /><b>'.$key.' </b> -';
                                               if (preg_match('/(\.jpg|\.jpeg|\.png|\.bmp|\.webp)$/', $ordered_pro_attribute) || strpos($key,'Upload') !== false){ 
                                                   $imglinl = SITE_URL.'images/order/'.$udate2.'/'.$ordered_pro_attribute;
                                                    //echo "<a href='$imglinl' target='_blank'> $imglinl </a><br>";
                                                    ?>
                                                    <input type="file" accept="image/*"  style="display:none;" hidden>
                                                    <input type="text" class="data_value see-border see-full see-round see-padding-left-right-8 see-padding-4 see-cursor" value="<?=@$ordered_pro_attribute;?>" />
                                                <?php
                                                }
                                                else{ ?>
                                                <input type="text" class="data_value see-border see-full see-round see-padding-left-right-8 see-padding-4" value="<?=@$ordered_pro_attribute;?>" /> <br><?php }
                                            } ?>
                                        </attrb><?php
                                        }
                                    } 
                                ?>
                                </edit_attributes>
                                <a href="<?php echo SITE_URL.$ordered_pro_customize; ?>" download >
                                <img src="<?php echo SITE_URL.$ordered_pro_customize; ?>" alt="<?php echo $product_media_alt; ?>"></a>
                                <a class="see-button see-red see-margin edit_attribute" dataorder="<?=$order_uop_id;?>">Edit Attributes</a>
                            </td>
    		        		<td>₹<?php echo $orderProAmount; ?></td>
    		        	</tr>
    		        	<tr class="listStatus">
    		        	    <td colspan="2">
                                <table>
                                    <tr class="see-white see-no-border">
                                        <td>Status</td>
                		        	    <td>
                    		        	    <select name="prdStatus">
                    		        	        <?php
                                                $Order_status = $see->SITE_DATA('see_order_status');
                                                foreach(explode(',',$Order_status) as $Order_status){
                                                    echo '<option value="'.$Order_status.'" '.((!empty($pro_orderstatus) && (str_replace(' ','',$pro_orderstatus[0]["status"])) == $Order_status) ? 'selected' : '').'>'.$Order_status.'</option>';
                                                } ?>
                    		        	    </select>
                		        	    </td>
            		        	    </tr>
                                    <tr class="see-white see-no-border">
                                        <td class='see-no-border'>Ship BY</td>
                                        <td class="see-no-border"> 
                                            <select name="send_to">
                                                <option value="" >Choose</option>
                                                <option value="shiprocket">Shiprocket</option>
                                                <option value="eshipz">eshipz</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="see-white see-no-border">
                                        <td class='see-no-border'>Comment</td>
                                        <td class="see-no-border"> 
                                            <input type="text" name="status_comment" class="see-border-1 see-padding-8 see-padding-left-right-8"/>
                                        </td>
                                    </tr>
                                    <tr class="see-white see-no-border">
                                        <td class="see-no-border">
                                            <button class="changeMe see-orange see-margin-left see-dp-inblock see-padding-8 see-padding-left-right-12 see-border-0" uop-id='<?php echo $selectallord['see_uop_id'];?>'>Change</button>
                                        </td>
                                    </tr>
                                </table>
    		        	    </td>
    		        	    <td colspan="3">
                               <table class="logShow">
                                   <tr><td>Ship BY</td>  <td><?=$product_shipby;?> </td></tr>
                                    <?php
                                    if(!empty($pro_orderstatus) && count($pro_orderstatus) > 0){
                                        foreach($pro_orderstatus as $statusKey => $statusVal){
                                            echo "<tr>";
                                            echo "<td><strong>".$statusVal['datetime'].": </strong></td>";
                                            echo "<td><strong>".$statusVal['status']." </strong></td>";
                                            echo "<td><strong>".$statusVal['comment']." </strong></td>";
                                            echo "</tr>";
                                        }
                                    } ?>
                                </table>
    		        	    </td>
    		        	</tr>
    		        	</tbody>
    		  <?php } ?>
    		        <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td class="bold-font">Coupon Code:</td>
                            <td class="bold-font">  <?php echo $getOrderDetail['see_coupon_code']; ?></td>
                        </tr>  
                        <tr>
                            <td colspan="4"></td>
                            <td class="bold-font">Coupon Amount:</td>
                            <td class="bold-font"> ₹ <?php echo $getOrderDetail['see_coupon_amt']; ?></td>
                        </tr>
    	        		<tr>
    	        			<td colspan="4"></td>
    	        			<td class="bold-font">Order Total:</td>
    	        			<td class="bold-font"> ₹  <?php echo $orderTotal+$walletprice; ?></td>
    	        		</tr>
    		        </tfoot>
    		    </table>
	        </div>
	    </div>
    </div>
<style type="text/css">
    .overlay-black { width: 100%; height: 100%; position: fixed; left: 0; top: 0; background: rgba(0, 0, 0, 0.5); display: none; }
    .edit{ height: 25px; display:inline-block; min-width:10px; }
    .editMode{ border: 1px solid black; }
</style>

<script type="text/javascript">
    var total_attr = {};
    $('.edit_attribute').click(function(){
      let myOrderId = $(this).attr('dataorder');
      let vast_html = $(`edit_attributes[dataorder="${myOrderId}"]`).html();
      Swal.fire({
          title: 'Edit Attributes',
          html: `<div id="__vst"> ` + vast_html + `</div>`,
          showDenyButton:true,
          width:600,
          confirmButtonText: 'Edit Attribute',
          preConfirm: () => {
              let FormID = $("#__vst");
                FormID.find('attrb').each(function(){
                    let mykey = $(this).find('.data_key').text();
                    let myvalue = $(this).find('.data_value').val();
                    total_attr[mykey] = myvalue;
                });
              $.post("", {orderPro:myOrderId, attrib: JSON.stringify(total_attr)}, (res) => {
                  Swal.fire("Success!", "Attribute has been updated!", "success").then(() => location.reload());
              });
          }
      })
    });
      
    $(document).on('click', '.data_img', function(){
      $(this).prev('input').click();
    })
    $(document).on('change', "input:file",function(){
      let DOM = $(this);
      let FILE_NAME = DOM.val();
        var file = this.files[0];
        var formData = new FormData();
        formData.append('file[]', file);
        $('#button_id_').attr('disable', true);
        $('.page-loder').show();
        $.ajax({
            url: "https://myphotoprint.in/uploadIMG.php?udate=<?=@$udate2;?>",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(html){  DOM.next('input').val(file.name);  },
            fail:function(data){
              
            }
        });
    });
        
    $(document).ready(function(){
 
    // Add Class
    $('.edit, .edituser').click(function(){
        $(this).addClass('editMode');
        $(this).attr('contentEditable', true)
    });


 // Save data
$(".edit").focusout(function(){
    $(this).removeClass("editMode");
    $(this).attr('contentEditable', false)
    var id = this.id;
    var split_id = id.split("_");
    var field_name = split_id[0];
    var edit_id = '<?=$orderBillingID;?>';
    var value = $(this).text();
    var pincode = $(this).text();
    var pid = '<?=$orderID;?>';
    $.ajax({
       url: '<?php echo MANAGE_URL;?>orderDetailsAjax.php',
       type: 'post',
       data: { field:field_name, value:value, id:edit_id, oid:pid,pincode:pincode},
       success:function(response){
         
        alert('Order Details Saved successfully' + response); 
       }
    });
 });
 
 $(document).on('focusout','.edituser',function(){
    $(this).removeClass("editMode");
    $(this).attr('contentEditable', false);
    
    var id = this.id;
    var split_id = id.split('_');
    var field_name = split_id[0];
    var edit_id = '<?=$userId;?>';
    var value = $(this).text();
    var pincode = $(this).text();
    var pid = '<?=$userId;?>';
    $.ajax({
       url: '<?php echo MANAGE_URL;?>orderDetailsAjax.php',
       type: 'post',
       data: { field:field_name, uservalue:value, id:edit_id, oid:pid,pincode:pincode},
       success:function(response){ alert('Order Details Saved successfully' + response);  }
    });
 });


 $('.pmode_select').change(function(){
    var currentMode = $(this).val();
    var modeField = 'pmode';
    var pid = '<?=$orderID;?>';

    $.ajax({
       url: 'orderDetailsAjax.php',
       type: 'post',
       data: { field:modeField, value:currentMode, id:1, oid:pid},
       success:function(response){
          //alert(response);
          alert('Payment Mode Updated'); 
       }
    });

 })

});
          
    $(document).ready(function(){
        $('input[name="statusCheck"]').click(function(){
            var myrow = $(this).closest('tbody');
            myrow.children('.listStatus').fadeIn();
        })
    })
          
    $('.changeMe').click(function(e){
        e.preventDefault();
        var changeMe = $(this);
        var selectVal = $(this).closest('table');
        var cid = changeMe.attr('uop-id');
        var curStatus = selectVal.find('select').val();
        var sendTo = selectVal.find(`select[name='send_to']`).val();
        var curComment = selectVal.find('input[name="status_comment"]').val();
        var dtime = "<?php echo $date;?>";
        $(this).removeClass("see-orange");
        $(this).css("background-color", "#797369 !important");
        $(this).html('<i class="fas fa-spinner fa-spin see-lh-22 see-white-text"></i>');
        $.post("<?php echo MANAGE_URL;?>orderDetailsAjax.php",{changeId: cid, statusVal: curStatus, commentVal: curComment, sendTo: sendTo},(res) => {
                changeMe.html("Change");
                changeMe.addClass('see-orange');
                if(res.code == '1'){
                    selectVal.children('td').last().prepend("<tr><td><strong>" + dtime + "</strong></td><td><strong>" + curStatus + "</strong></td><td><strong>" + curComment + "</strong></td></tr>");
                    alert(res.msg);
				    location.reload();  
                } 
                else{
                    alert("Unknown Error, Try Again" + res.msg);
					location.reload();
                }
        },'json'); 
    });
</script>

	  <!-- all contact list end here -->
<?php }
else{ echo 'No Detail Found'; } ?>
	</div>
</div>
</div>
<?php include('footer.php');?>