<?php include('../library/see_function.php');
if(isset($_GET['type'])){
    $type = $_GET['type'];
    if($type == "order"){
        $startDate = $_GET['start'];
        $endDate = $_GET['end'];
        $finalData = [];
        $abc = [];
        $orders = $see->getJoinData($startDate, $endDate);
        if($orders){
            foreach($orders as $or){
                $orderId = $or['orderId'];
    		    $ordered_pro_attributes = json_decode($or['attribute'],true); 
                $billing = @json_decode($or['billing'],true);
                $reciever        = @$billing['b_name'];
                $recieverNo      = @$billing['b_mob1'];
                $recieverNo2     = @$billing['b_mob2'];
                $recieverCity    = @$billing['b_city'];
                $recieverAddress = @$billing['b_add'];
                $recieverLandmark = @$billing['b_land'];
                $rpin 			 = @$billing['b_pin'];
                $addOns = explode('@', $ordered_pro_attributes['Add Ons:']);
                if(strpos(strtolower($ordered_pro_attributes['Add Ons:']), "gift wrap") !== false){ $Gift_text = "Yes"; }else{ $Gift_text = "No"; }
                if(strpos(strtolower($ordered_pro_attributes['Add Ons:']), "greeting card") !== false){ $greeting_text = "Yes"; }else{ $greeting_text = "No"; }
                if(strpos(strtolower($ordered_pro_attributes['Add Ons:']), "golden rose") !== false){ $gold_rose = "Yes"; }else{ $gold_rose = "No"; }
                if(strpos(strtolower($ordered_pro_attributes['Add Ons:']), "Bluetooth Speaker") !== false){ $bluetooth_speaker = "Yes"; }else{ $bluetooth_speaker = "No"; }
                $productStatus = @json_decode($or['order_status'],true);
                $statusData = @$productStatus[0]['status'];
    		    $updatedOn  = @$productStatus[0]['datetime'];
    			$orderDate = @$or['orderDate'];
    			$utmcampaign = @$or['utmcampaign'];
    			$utmsource = @$or['utmsource'];
    			$utmmedium = @$or['utmmedium'];
    			$utmurl = @$or['utmurl'];
    			$folder = $_SERVER['DOCUMENT_ROOT'].'/images/order/';
    			$img_path = $folder.date("d-m-Y", strtotime($orderDate))."/";
    			$zip = new ZipArchive();
    			$zip_name= $folder.'images_'.$orderId.'.zip';
    			if($zip->open($zip_name, ZipArchive::CREATE) === TRUE){
    				foreach($ordered_pro_attributes as $key => $p_att){
    				    if(preg_match('(jpg|png|gif|bmp)', $p_att) === 1){
            				  $fimg = preg_replace('/^.+\\\\/', '', $p_att);
            				  if(file_exists($zip_name)){ unlink($zip_name); }						  
            				  if(file_exists($img_path.$fimg)){ $zip->addFile($img_path.$fimg,basename($img_path.$fimg)); }
    				    }
    				}
    				$zip->close();
    				if(file_exists($zip_name)){ $link_url = 'https://'.$_SERVER['HTTP_HOST'].'/images/order/'.'images_'.$orderId.'.zip'; }
    				else{ $link_url = ''; }
    			}
    			
    			$getpartial = $see->getrow('see_partial_payment',"see_pp_oid = '$orderId' ORDER BY see_pp_id DESC LIMIT 0,1");
    			$partaialpayment = $getpartial['see_pp_amount'];
    			
    			$billingid = $or['billingId'];
    			$getstate = $see->getrowcolumn('see_user_billing_detail bd JOIN see_location sl ON bd.see_ubd_state = sl.see_l_id',['sl.see_l_name'],"bd.see_ubd_id = '$billingid'");
    			$getstatedata = $getstate['see_l_name'];
    			
    			$getLocationDetail = $see->getrow('see_location',"see_l_id = '$delivery_stateid'");
                $delivery_state = $getLocationDetail['see_l_name'];
                
                $abctemp = [
                    'DATE'			 	=>	'',
                	'ORDER ID'			=>	'',
                	'MATERIAL' 			=>	'',
                	'Images'			=>	'',
                	'Gift Wrap'			=>	'',
                	'Golden Rose'	    =>	'',
                	'Greeting Card'		=>	'',
                	'GREETING MSG'		=>	'',
                	'Bluetooth Speaker'	=>	'',
                	'NO OF ORDERS'		=>	'',
                	'NAME'				=>	'',
                	'Total Amount'		=>	'',
                	'Paid Via'			=>	'',
                	'Partial Payment'   =>  '',
                	'Reciever No'		=>	'',
                	'Reciever Pincode'	=>	'',
                	'STATUS'			=>	'',
                	'UTM Campaign'      =>	'',
                	'UTM Source'        =>	'',
                	'Utm Medium'        =>	'',
                	'Utm Url'           =>	'',
                	'recieverAddress'   =>	'',
                	'recieverLandmark'  =>  '',
                	'recievercity'      =>  '',
                	'recieverstate'     =>  ''
                  ];
    			
    			$detailstemp = array(
                    'DATE'			 	=>	$orderDate,
                    'ORDER ID'			=>	$orderId,
                    'MATERIAL' 			=>	@$or['product_name'],
                    'Images'			=>	$link_url, 
                    'Gift Wrap'			=>	$Gift_text,
                    'Golden Rose'		=>	@$gold_rose,
                    'Bluetooth Speaker'	=>	$bluetooth_speaker,
                    'Greeting Card'		=>	$greeting_text,
                    'GREETING MSG'		=>	@isset($ordered_pro_attributes['Enter Greeting Card Message - if tick']) ? $ordered_pro_attributes['Enter Greeting Card Message - if tick'] : @$ordered_pro_attributes['Enter Greeting Card Message - Only if tick ( Which You Want On Greeting Card)'],
                    'NO OF ORDERS'		=> 	@$or['quantity'],
                    'NAME'				=>	$reciever,
                    'Total Amount'		=>	@$or['amount'],
                    'Paid Via'			=>	@$or['paymentMode'],
                    'Partial Payment'   =>	$partaialpayment,
                    'Reciever No'		=>	$recieverNo,
                    'Reciever Pincode'	=>	$rpin,
                    'STATUS'			=>	$statusData,
                    'UTM Campaign'      =>  utf8_decode(urldecode($utmcampaign)),
                    'UTM Source'        =>  utf8_decode(urldecode($utmsource)),
                    'Utm Medium'        =>  utf8_decode(urldecode($utmmedium)),
                    'Utm Url'           =>  $utmurl,
                    'recieverAddress'   =>  $recieverAddress,
                    'recieverLandmark'  =>  $recieverLandmark,
                    'recievercity'      =>  $billing['b_city'],
                    'recieverstate'     =>  $getstatedata,
    			);
    				
    			if(!empty($ordered_pro_attributes)){					
    			    foreach($ordered_pro_attributes as $key => $ordered_pro_attribute){
    				    if(preg_match('(jpg|png|gif|bmp)', $ordered_pro_attribute) === 1) { 
    					    $fimg = preg_replace('/^.+\\\\/', '', $ordered_pro_attribute);
    					    if(file_exists('../images/order/'.$udate2.'/'.$fimg)){ $images = $fimg; }
    		            }
    		            else{ 
    		               $detailstemp[] = $ordered_pro_attribute;
    		               $abctemp[] = $ordered_pro_attribute;
    		            }
    		        } 
    	        }
    	        
    	        $details[] = $detailstemp;
    	        $abc[] = $abctemp;
    		}
        }
		$date = date('Y-m-d').time();
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=order_export_'.$date.'.csv');
		$output = fopen('php://output', 'w');
		fputcsv($output, array('DATE', 'ORDER ID', 'MATERIAL','Images', 'Gift Wrap','Golden Rose','Bluetooth Speaker','Greeting Card','GREETING MSG','NO OF ORDERS','NAME','Total Amount','Paid Via','Partial Payment','Reciever No','Reciever Pincode','STATUS','UTM Campaign','UTM Source','Utm Medium','Utm Url','recieverAddress','recieverLandmark','recievercity','recieverstate'));
		foreach($details as $mykey => $ord){
			  fputcsv($output, $ord);
			 // fputcsv($output,$abc[$mykey]);
		}
	}
}