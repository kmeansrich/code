<?php 
include('../library/see_function.php');
$res = file_get_contents('php://input');
$LogFile = fopen("webhook_log_shiproket.txt", "w") or die("Unable to open file!");
fwrite($LogFile, $res);

$after45days = date("Y-m-d", strtotime( "+45 days" ) );
if(!empty($res)){
    $response = json_decode($res,true);
    $orderid = preg_replace('/[^0-9]/', '', $response['order_id']);
    if(strtolower($response['current_status']) == 'delivered'){
        if(!empty($orderid)){
            $orderfind = $see->getrow('see_orders',"see_uo_id = '$orderid'");
            if($orderfind){
                $userid = $orderfind['see_uo_u_id'];
                $totalamount = $orderfind['see_uo_amt'];
                $orderstatus = $orderfind['see_uo_status'];
                @$getDeliveryAddress = ((!empty($orderfind['see_uo_billing'])) ? json_decode($orderfind['see_uo_billing'],true) : '');
                $contact_name = $getDeliveryAddress['b_name'];
                $contact_phone1 = $getDeliveryAddress['b_mob1'];
                $contact_phone2 = $getDeliveryAddress['b_mob2'];
                $delivery_address = $getDeliveryAddress['b_add'];
                $delivery_city = $getDeliveryAddress['b_city'];
                $delivery_pincode = $getDeliveryAddress['b_pin'];
                
                $getuser = $see->getrow("see_users", "see_u_id = $userid");
                $onumber = $getuser['see_u_mobile']; 
                $userEmail = $getuser['see_u_email']; 
                
                $updateorder = $see->updaterow('see_orders',['see_uo_status' => 'delivered'],"see_uo_id = '$orderid'");
                $totalcashback = round(($totalamount * 10) / 100);
                
                $remarks = "Receive Rs.$totalcashback From ORDER ID $orderid";
                $addtowallet = $see->addrow('see_user_wallet',['see_uw_u_id' => $userid, 'see_uw_o_id' =>$orderid, 'see_uw_remarks' => $remarks, 'see_uw_expire' => $after45days, 'see_uw_amount' => '+'.$totalcashback]);
                
                // Whatsapp Message  
                $senddata = '{
                    "template_name": "orderdeliver_cashback", 
                    "broadcast_name": "message", 
                    "parameters": [
                            { "name": "name", "value": "'.$contact_name.'" },
                            { "name": "amount", "value": "'.$totalcashback.'" },
                            { "name": "tracking_url", "value": "https://bit.ly/3tY6xAv" }
                        ]
                }';
                $msgsend = $helper->sendWhatsapp($onumber,$senddata);
                
                
                $senddatayoutub = '{
                    "template_name": "instafollowus", 
                    "broadcast_name": "message", 
                    "parameters": [
                            { "name": "name", "value": "'.$contact_name.'" },
                        ]
                }';
                $helper->sendWhatsapp($onumber,$senddatayoutub);
                
                if($msgsend){ echo $msgsend;  }
                
                // Mail Send 
                if($userEmail){
                    $to =  $userEmail; 
                    $subject = "myphotoprint Order";
                    $headers = "From: support@myphotoprint.in" . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    ob_start();
                    include "../mailers/delivered.php";
                    $message = ob_get_clean(); 
                    mail($to,$subject,$message,$headers);
                }
            }
        }
    }
    elseif($response['current_status_id'] == 19){
        if(!empty($orderid)){
            // fwrite($LogFile, "Order Id - " . $orderid);
            $orderfind = $see->getrow('see_orders',"see_uo_id = '$orderid'");
            if($orderfind){
                $userid = $orderfind['see_uo_u_id'];
                $orderstatus = $orderfind['see_uo_status'];
                @$getDeliveryAddress = ((!empty($orderfind['see_uo_billing'])) ? json_decode($orderfind['see_uo_billing'],true) : '');
                $contact_name = $getDeliveryAddress['b_name']; 
                $getuser = $see->getrow("see_users", "see_u_id = $userid");
                $onumber = $getuser['see_u_mobile']; 
                $userEmail = $getuser['see_u_email'];
                $updateorder = $see->updaterow('see_orders',['see_uo_status' => 'outfordelivery'],"see_uo_id = '$orderid'");
                if($updateorder){
                    // Whatsapp Message
                    $senddata = '{
                        "template_name": "outofdeliveryy",
                        "broadcast_name": "message",
                        "parameters": [ { "name": "name", "value": "'.$contact_name.'" }, ]
                    }';
                    
                    $msgsend = $helper->sendWhatsapp($onumber,$senddata);
                    $senddatayoutub = '{
                        "template_name": "instafollowus", 
                        "broadcast_name": "message", 
                        "parameters": [
                            { "name": "name", "value": "'.$contact_name.'" },
                        ]
                    }';
                    $helper->sendWhatsapp($onumber,$senddatayoutub);
                }
                else{ fwrite($LogFile, " -- Unable to Update Order " . $orderid . " - " . $orderstatus); }
                if($msgsend){ echo $msgsend;  }
            }
        }
    }
    else{ echo "Error Found"; }
}