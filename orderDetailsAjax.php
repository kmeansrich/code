<?php 
include('../library/see_function.php');
$response = ['code' => 0, 'msg' => ''];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://apiv2.shiprocket.in/v1/external/auth/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "email": "sanjayjoon.13@gmail.com",
    "password": "sanjayjoon"
    }',
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
    ),
));
$responsenew = json_decode(curl_exec($curl),true);
curl_close($curl); 

$shiprockettoken = $responsenew['token']; 

if(isset($_POST['changeId'])){
    $cid = $_POST['changeId'];
    $status = $_POST['statusVal'];
    $senddata = $_POST['sendTo'];
    $where = "see_uop_id = '$cid'";
    $comment = ((!empty($_POST['commentVal'])) ? $_POST['commentVal'] : '');
    $oldarray = $see->getrowcolumn("see_order_products", ['see_uop_status'], $where);
    $oldarray = ((!empty($oldarray['see_uop_status'])) ? json_decode($oldarray['see_uop_status'],true) : '');
    $statusArray = [["datetime" => $date, "status"  =>  $status, "comment" => $comment]];
    if(!empty($oldarray)){ $feedArray =  array_merge($statusArray,$oldarray); }
    else{ $feedArray = $statusArray; }
    $finalarray = json_encode($feedArray);
    // Feed Into Database;
    $toUpdate = ['see_uop_status' => $finalarray, 'see_uop_ship' => $senddata];
    // Implement Tracking System
    switch($status){
        case 'confirmed':
            // Fetch ALL Details for confirming
            $item = $see->getrow("see_order_products",$where);
            $orderId = $item['see_uop_orderID'];
            $productID = $item['see_uop_proid'];
            $orders = $see->getrow("see_orders", "see_uo_id = '$orderId'");
            $orderPayMode = $orders['see_uo_pay_mode'];
            $orderWalletBalance = !empty($orders['see_wallet_pay']) ?  (float) $orders['see_wallet_pay'] : 0;
            $orderCouponAmount = !empty($orders['see_coupon_amt']) ?  (float) $orders['see_coupon_amt'] : 0;
            $billing = $orders['see_uo_billing'];
            $orderDate = $orders['see_uo_pay_date'];
            $orderBillingID = $orders['see_uo_billing_id'];
            
            $getDeliveryAddress = $see->getrow('see_user_billing_detail',"see_ubd_id = '$orderBillingID'");
            $delivery_stateid = $getDeliveryAddress['see_ubd_state'];
            $getLocationDetail = $see->getrow('see_location',"see_l_id = '$delivery_stateid'");
            $delivery_state = (isset($getLocationDetail['see_l_name']) ? $getLocationDetail['see_l_name'] : 'unknown');
            
            if($orderPayMode == 'razorpay'){ $iscod = false; $ordertype = 'prepaid'; }
            elseif($orderPayMode == 'phonepay'){  $iscod = false; $ordertype = 'prepaid'; }
            else{ $ordertype = 'cod'; $iscod = true; }
            
            if($orderDate=='0000-00-00 00:00:00'){ $neworderDate = date("Y-m-d"); }
            else{ $neworderDate = date("Y-m-d", strtotime($orderDate)); }
            $orderTotal = ($orders['see_uo_amt'] - $orderWalletBalance) - $orderCouponAmount;
            $i = '1';
            if(!empty($billing)){ $billing = json_decode($billing,true); } else{$billing = ''; }
            
            $userId = $orders['see_uo_u_id'];
            $getUserDetail = $see->getrow('see_users',"see_u_id = '$userId'");
            $userEmail = $getUserDetail['see_u_email']; 
            
            $orderProID = $item['see_uop_proid'];
            $orderProAmount = $item['see_uop_order_amnt'];
            $selectProduct = $see->getrow('see_products',"see_pro_id = '$orderProID'");
            $prdID = $selectProduct['see_pro_id'];
            $productName = $selectProduct['see_pro_name'];
            $productSku = $selectProduct['see_pro_sku'];
            $dimId = $selectProduct['see_pro_dimension'];
            $size_where = "see_dm_id = '$dimId'";
            $productSize = $see->getrow('see_dimension',$size_where);
            $productSize = unserialize($productSize['see_dm_value']);
            
            // Shipify Function.... 
            $timestamp = time(); 
            $productlength = $productSize['length'];
            $productwidth = $productSize['width'];
            $productheight = $productSize['height'];
            $productweight = $productSize['weight'] / 1000;
            $contact_name = $billing['b_name'];
            $delivery_address = $billing['b_add'];
            $delivery_landmark = (isset($billing['b_land']) ? $billing['b_land'] : '');
            $delivery_city = $billing['b_city'];
            $delivery_pincode = $billing['b_pin'];
            $delivery_country = 'India';
            $contact_phone1 = trim($billing['b_mob1']);
            $productqty = $item['see_uop_pro_quantity'];
            
            
            if($senddata == 'shiprocket'){
                $pay_load = [
                    "order_id" => $orderId,
                    "order_date" => $neworderDate,
                    "channel_id" => "",
                    "comment" => "",
                    "billing_customer_name" => $contact_name,
                    "billing_last_name" => "",
                    "billing_address" => $delivery_address.' '.$delivery_landmark,
                    "billing_address_2" => $delivery_landmark,
                    "billing_city" => $delivery_city,
                    "billing_pincode" => $delivery_pincode,
                    "billing_state" => $delivery_state,
                    "billing_country" => $delivery_country,
                    "billing_email" => $userEmail,
                    "billing_phone" => !empty($contact_phone1) ? $contact_phone1 : "",
                    "shipping_is_billing" => true,
                    "shipping_customer_name" => "",
                    "shipping_last_name" => "",
                    "shipping_address" => "",
                    "shipping_address_2" => "",
                    "shipping_city" => "",
                    "shipping_pincode" => "",
                    "shipping_country" => "",
                    "shipping_state" => "",
                    "shipping_email" => "",
                    "shipping_phone" => "",
                    "order_items" => [
                        [
                            "name" => $productName,
                            "sku" => $productSku,
                            "units" =>  $productqty,
                            "selling_price" => $orderTotal,
                            "discount" => "0",
                            "tax" => '0',
                            "hsn" => "" 
                        ]
                    ],
                    "payment_method" => $ordertype,
                    "shipping_charges" =>  0,
                    "giftwrap_charges" =>  0,
                    "transaction_charges" => 0,
                    "total_discount" => $orderCouponAmount,
                    "sub_total" => $orderTotal,
                    "length" => $productlength,
                    "breadth" => $productwidth,
                    "height" => $productheight,
                    "weight" => $productweight
                ];
                
                # Shipping Partner
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode($pay_load),
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer {$shiprockettoken}"
                  ),
                ));
                $abcd = curl_exec($curl);
                curl_close($curl);
            }
            elseif($senddata == 'eshipz'){
                $pay_load = [ 
                    "data" => [ 
                        [
                            "order_id" => $orderId,
                            "store_name" => "other",
                            "order_created_on" => date('Y-m-d h:i',strtotime($neworderDate)),
                            "is_cod" => $iscod,
                            "shipment_value" => $orderTotal,
                            "order_currency" => "INR",
                            "order_status" => "pending",
                            "shipment_type" => "Parcel",
                            "receiver_address" => [
                                "first_name" => $contact_name,
                                "last_name" => "",
                                "company_name" => "",
                                "address" => $delivery_address.' '.$delivery_landmark,
                                "city" => $delivery_city,
                                "state" => $delivery_state,
                                "country" => 'IN',
                                "zipcode" => $delivery_pincode,
                                "landmark" =>$delivery_landmark,
                                "gst_number" => "",
                                "phone" => !empty($contact_phone1) ? $contact_phone1 : "",
                                "email" => $userEmail
                            ],
                            "items" => [
                                [
                                  "description" => $productName,
                                  "quantity" => $productqty,
                                  "weight" => [
                                    "unit_of_measurement" => "kg",
                                    "value" => $productweight
                                  ],
                                  "dimensions" => [
                                    "unit_of_measurement" => "cms",
                                    "length" => $productlength,
                                    "width" => $productwidth,
                                    "height" => $productheight,
                                  ],
                                  "value" => [
                                    "currency" => INR,
                                    "amount" => $orderTotal
                                  ],
                                  "sku" => $productSku
                                ]
                            ],
                            "is_mps" => false,
                            "parcels" => [
                                [
                                    "quantity" => 1,
                                    "weight" => [
                                        "unit_of_measurement" => "kg",
                                        "value" => $productweight
                                    ],
                                    "dimensions" => [
                                        "length" => $productlength,
                                        "width" => $productwidth,
                                        "unit_of_measurement" => "cm",
                                        "height" => $productheight
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                
                if($iscod){ $pay_load['data'][0]['cod_amount'] = $orderTotal; }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://app.eshipz.com/api/v1/orders',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => json_encode($pay_load),
                  CURLOPT_HTTPHEADER => array(
                    "X-API-TOKEN: 63525e080afce05cc9bd5e3f",
                    "Content-Type: application/json"
                  ),
                ));
                $abcd = curl_exec($curl);
                curl_close($curl);
            }
            else{ $response = ['code' => 0, 'msg' => 'Error Found', 'number' => 'Error Found']; }
        break;
    }
    // Order Table Update
    $orderID = $see->getrowcolumn("see_order_products", ['see_uop_orderID'], "see_uop_id = '$cid'");
    $orderID = $orderID['see_uop_orderID'];
    $update = $see->updaterow("see_orders",['see_uo_status' => $status], "see_uo_id = '$orderID'");
    // End Tracking System
    $update = $see->updaterow("see_order_products",$toUpdate, $where);
    if($update){ $response = ['code' => 1, 'msg' => $abcd, 'nm' => $pay_load ]; }
    else{ $response = ['code' => 0, 'msg' => $abcd, 'number' => $contact_phone1]; }
    echo json_encode($response);
}

if(isset($_POST['changeBulk'])){
    $change_orderid = $_POST['changeBulk'];
    $order_where = "see_uop_orderID = '$change_orderid'";
    $selectallorders = $see->getrows('see_order_products',$order_where);
    $status = $_POST['statusVal'];
    $comment = ((!empty($_POST['commentVal'])) ? $_POST['commentVal'] : '');
    if($selectallorders){
        foreach($selectallorders as $slorder){
            $cid = $slorder['see_uop_id'];
            $where = "see_uop_id = '$cid'";
            $oldarray = $see->getrowcolumn("see_order_products", ['see_uop_status'], $where);
            $oldarray = ((!empty($oldarray['see_uop_status'])) ? json_decode($oldarray['see_uop_status'],true) : '');
            $statusArray = [["datetime" => $date, "status"  =>  $status, "comment" => $comment]];
            if(!empty($oldarray)){ $feedArray =  array_merge($statusArray,$oldarray); }
            else{ $feedArray = $statusArray; }
            
            $finalarray = json_encode($feedArray);
            // Feed Into Database;
            $toUpdate = ['see_uop_status' => $finalarray];
            // Implement Tracking System
            switch($status){
                case 'confirmed':
                    // Fetch ALL Details for confirming
                    $item = $see->getrow("see_order_products", $where);
                    $orderId = $item['see_uop_orderID'];
                    $productID = $item['see_uop_proid'];
                    $orders = $see->getrow("see_orders", "see_uo_id = '$orderId'");
                    $orderPayMode = $orders['see_uo_pay_mode'];
                    $billing = $orders['see_uo_billing'];
                    $orderDate = $orders['see_uo_pay_date'];
                    $orderBillingID = $orders['see_uo_billing_id'];
                    $orderWalletBalance = !empty($orders['see_wallet_pay']) ?  (float) $orders['see_wallet_pay'] : 0;
                    $orderCouponAmount = !empty($orders['see_coupon_amt']) ?  (float) $orders['see_coupon_amt'] : 0;
                    $getDeliveryAddress = $see->getrow('see_user_billing_detail',"see_ubd_id = '$orderBillingID'");
                    $delivery_stateid = $getDeliveryAddress['see_ubd_state'];
                    $getLocationDetail = $see->getrow('see_location',"see_l_id = '$delivery_stateid'");
                    $delivery_state = (isset($getLocationDetail['see_l_name']) ? $getLocationDetail['see_l_name'] : 'unknown');
                    
                    if($orderPayMode == 'razorpay'){ $ordertype = 'prepaid'; }
                    elseif($orderPayMode == 'phonepay'){ $ordertype = 'prepaid'; }
                    else{ $ordertype = 'cod';  }
                    
                    
                    
                    if($orderDate=='0000-00-00 00:00:00'){ $neworderDate = date("Y-m-d"); }
                    else{ $neworderDate = date("Y-m-d", strtotime($orderDate)); }
                    $orderTotal = ($orders['see_uo_amt'] - $orderWalletBalance) - $orderCouponAmount;
                    $i = '1';
                    if(!empty($billing)){ $billing = json_decode($billing,true); } else{$billing = ''; }
                    
                    $userId = $orders['see_uo_u_id'];
                    $getUserDetail = $see->getrow('see_users',"see_u_id = '$userId'");
                    $userEmail = $getUserDetail['see_u_email']; 
                    
                    
                    $orderProID = $item['see_uop_proid'];
                    $orderProAmount = $item['see_uop_order_amnt'];
                    $selectProduct = $see->getrow('see_products',"see_pro_id = '$orderProID'");
                    $prdID = $selectProduct['see_pro_id'];
                    $productName = $selectProduct['see_pro_name'];
                    $productSku = $selectProduct['see_pro_sku'];
                    $dimId = $selectProduct['see_pro_dimension'];
                    $size_where = "see_dm_id = '$dimId'";
                    $productSize = $see->getrow('see_dimension',$size_where);
                    $productSize = unserialize($productSize['see_dm_value']);
                    
                    // Shipify Function.... 
                    $timestamp = time(); 
                    $productlength = $productSize['length'];
                    $productwidth = $productSize['width'];
                    $productheight = $productSize['height'];
                    $productweight = $productSize['weight'] / 1000;
                    $contact_name = $billing['b_name'];
                    $delivery_address = $billing['b_add'];
                    $delivery_address_land = $billing['b_land'];
                    $delivery_city = $billing['b_city'];
                    $delivery_pincode = $billing['b_pin'];
                    $delivery_country = 'India';
                    $contact_phone1 = trim($billing['b_mob1']);
                    $productqty = $item['see_uop_pro_quantity'];
                    $pay_load = [
                        "order_id" => $orderId,
                        "order_date" => $neworderDate,
                        "channel_id" => "",
                        "comment" => "",
                        "billing_customer_name" => $contact_name,
                        "billing_last_name" => "",
                        "billing_address" => $delivery_address,
                        "billing_address_2" => "$delivery_address_land",
                        "billing_city" => $delivery_city,
                        "billing_pincode" => $delivery_pincode,
                        "billing_state" => $delivery_state,
                        "billing_country" => $delivery_country,
                        "billing_email" => $userEmail,
                        "billing_phone" => !empty($contact_phone1) ? $contact_phone1 : "",
                        "shipping_is_billing" => true,
                        "shipping_customer_name" => "",
                        "shipping_last_name" => "",
                        "shipping_address" => "",
                        "shipping_address_2" => "",
                        "shipping_city" => "",
                        "shipping_pincode" => "",
                        "shipping_country" => "",
                        "shipping_state" => "",
                        "shipping_email" => "",
                        "shipping_phone" => "",
                        "order_items" => [
                            [
                                "name" => $productName,
                                "sku" => $productSku,
                                "units" =>  $productqty,
                                "selling_price" => $orderTotal,
                                "discount" => "0",
                                "tax" => '0',
                                "hsn" => "" 
                            ]
                        ],
                        "payment_method" => $ordertype,
                        "shipping_charges" =>  0,
                        "giftwrap_charges" =>  0,
                        "transaction_charges" => 0,
                        "total_discount" => 0,
                        "sub_total" => $orderTotal,
                        "length" => $productlength,
                        "breadth" => $productwidth,
                        "height" => $productheight,
                        "weight" => $productweight
                    ];
                    # Shipping Partner
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => json_encode($pay_load),
                      CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Bearer {$shiprockettoken}"
                      ),
                    ));
                    $abcd = curl_exec($curl);
                    curl_close($curl);
                    break;
            }
            
            // Order Table Update
            $orderID = $see->getrowcolumn("see_order_products", ['see_uop_orderID'], "see_uop_id = '$cid'");
            $orderID = $orderID['see_uop_orderID'];
            $update = $see->updaterow("see_orders",['see_uo_status' => $status], "see_uo_id = '$orderID'");
            // End Tracking System
            $update = $see->updaterow("see_order_products",$toUpdate, $where);
        }
        echo "S";
    }else{   echo "Error";  }
}

if(isset($_POST['value'])){
    // Details Changing Data
    $field = $_POST['field'];
    $value = $_POST['value'];
    $pincode = $_POST['pincode'];
    @$editid = $_POST['id'];
    @$orderID = $_POST['oid'];
    $type = 'b';
    $editcol = '';
    switch($field){
        case 'name': $editcol = 'b_name'; $type = 'b'; break;
        case 'add': $editcol = 'b_add'; $type = 'b'; break;
        case 'land': $editcol = 'b_land'; $type = 'b'; break;
        case 'phone': $editcol = 'b_mob1'; $type = 'b'; break;
        case 'pmode': $editcol = 'see_uo_pay_mode'; $type = 'o'; break;
        case 'mrp': $editcol = 'see_uo_amt'; $type = 'o'; break;
        case 'pinco': $editcol = 'b_pin'; $type = 'b'; break;
    }
    $column = [$editcol => $value];
    if($type == 'b'){
        // GET Details and do array..
        $oldData = $see->getrowcolumn("see_orders", ['see_uo_billing'], "see_uo_id = '$orderID'");
        if(!empty($oldData['see_uo_billing'])){
            $oldData = json_decode($oldData['see_uo_billing'],true);
            $updateData = $oldData[$editcol] = $value;
            $updateDataS = json_encode($oldData);
            $see->updaterow("see_orders",['see_uo_billing' => $updateDataS],"see_uo_id = '$orderID'");
        }
        else{ echo "aa"; }   
    }else{
        echo $value;
        $see->updaterow("see_orders",$column,"see_uo_id = '$orderID'");
    }
}

if(isset($_POST['uservalue'])){
    $field = $_POST['field'];
    $value = $_POST['uservalue'];
    if($value){
        $editid = $_POST['id'];
        $editcol = '';
        switch($field){
            case 'username': $editcol = 'see_u_name'; break;
            case 'useremail': $editcol = 'see_u_email'; break;
            case 'usermobile': $editcol = 'see_u_mobile'; break;
        }
        $column = [$editcol => $value];
        $see->updaterow("see_users",$column,"see_u_id = '$editid'");
    }
    else{ echo 'Need Value'; }
    
}