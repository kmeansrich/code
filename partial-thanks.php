<?php
include('library/see_function.php'); ?>
<title> Partial Payment </title>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<?php 
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
$getOd = 0;
if(!empty($helper->get_session('USER_ID'))){
    $uid = $helper->get_session('USER_ID');
    $getuser = $see->getrow("see_users", "see_u_id = $uid");
    if(isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id'])) {
        if (!empty($helper->get_session('orderId'))) {
    	    $order_id_Detail = $_SESSION['orderId'];    
    		$transactionId = $_POST['razorpay_payment_id'];
    		$success = true;
    		try {
    			// Please note that the razorpay order ID must
    			$attributes = array(
    				'razorpay_order_id' => $_SESSION['razorpay_order_id'],
    				'razorpay_payment_id' => $transactionId,
    				'razorpay_signature' => $_POST['razorpay_signature']
    			);
    			$api->utility->verifyPaymentSignature($attributes);
    		} 
    		catch (SignatureVerificationError $e) {
    			$success = false;
    			$payerror = 'Razorpay Error : ' . $e->getMessage();
    		}

            if($success){ ?>
                <style>
                .thankyou-wrapper{ width:100%; height:auto; margin:auto; background:#ffffff;  padding:10px 0px 50px; }
                .thankyou-wrapper h1{ font:60px Arial, Helvetica, sans-serif; text-align:center; color:#333333; padding:0px 10px 10px; }
                .thankyou-wrapper p{ font:26px Arial, Helvetica, sans-serif; text-align:center; color:#333333; padding:5px 10px 10px; }
                .thankyou-wrapper a{ font:26px Arial, Helvetica, sans-serif; text-align:center; color:#ffffff; display:block; text-decoration:none; width:40%; background:#E47425; margin:10px auto  0px; padding:15px 20px 15px; border-bottom:5px solid #F96700; }
                </style>
                <section class="login-main-wrapper">
                  <div class="main-container">
                      <div class="login-process">
                          <div class="login-main-container">
                              <div class="thankyou-wrapper">
                                  <h1>Thank You. </h1>
                                    <p>Congratulations your order has been confirmed</p>
                                    <a href="<?php echo SITE_URL; ?>"> Continue Shopping </a>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </section>
                <?php
                $dataaraay = ['see_pp_trans' => $transactionId, 'see_pp_status' => 'paid'];
            }
            else{ 
                echo "No Transtion Found";
                $dataaraay = ['see_pp_trans' => $transactionId, 'see_pp_status' => 'unpaid']; 
            }
            $paystatusupdate = $see->updaterow("see_partial_payment",$dataaraay,"see_pp_oid = '$order_id_Detail' AND see_pp_uid = '$uid'");
        }
    }
}
else{ $helper->redirectTo('logout.php'); }