<div class="see-2">
    Today Cart Alert - <?php
    $datdedn = $see->getrowscolumn('see_cart',['DISTINCT(user_id)'],"status =  'Y' AND DATE(created_date) = '2022-04-06'");
    echo count($datdedn);
    ?>
</div>
<!-- cart-list.php -->

<!-- Add a date range input and export button -->
<form>
  <label for="start-date">Start date:</label>
  <input type="date" id="start-date" name="start-date">
  <label for="end-date">End date:</label>
  <input type="date" id="end-date" name="end-date">
  <button type="button" id="export-btn">Export to Excel</button>
</form>

<!-- Add a table to display the data -->
<table id="order-table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Mobile No</th>
      <th>Product Name / Quantity</th>
      <th>Attribute</th>
      <th>Created</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // Retrieve the data from the database based on the selected date range
      if (isset($_GET['start-date']) && isset($_GET['end-date'])) {
        $startDate = $_GET['start-date'];
        $endDate = $_GET['end-date'];
        $sql = "SELECT see_c_name, see_c_mobile, see_c_product, see_c_attribute, see_c_create, see_c_action FROM your_table_name WHERE see_c_create >= '$startDate' AND see_c_create <= '$endDate'";
      } else {
        $sql = "SELECT see_c_name, see_c_mobile, see_c_product, see_c_attribute, see_c_create, see_c_action FROM your_table_name";
      }
      $result = mysqli_query($conn, $sql);
    
      // Display the data in the HTML table
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['see_c_name'] . "</td>";
        echo "<td>" . $row['see_c_mobile'] . "</td>";
        echo "<td>" . $row['see_c_product'] . "</td>";
        echo "<td>" . $row['see_c_attribute'] . "</td>";
        echo "<td>" . $row['see_c_create'] . "</td>";
        echo "<td>" . $row['see_c_action'] . "</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
  var checkedBoxArray = [];
  var checked = false;
$(document).ready( function () {
    var checkedBoxArray = [];
    $('#myTable').DataTable({
        <?php $dataID = 'id'; ?>
        // "order": [[4, "desc" ]],
        "lengthMenu": [15,25, 50, 100, 500],
        "processing": true,
        "serverSide": true,
        "serverMethod": 'post',
        "ajax": {
            'url': 'ajax/cart.php?list' 
        },
        "columns": [
            {  data: 'see_c_name', orderable: false, },
            {  data: 'see_c_mobile', orderable: false, },
            {  data: 'see_c_product', orderable: false, },
            {  data: 'see_c_attribute', orderable: false, },
            {  data: 'see_c_create', orderable: true, },
            {  data: 'see_c_action', orderable: false, },
        ],
        "language": {
            "emptyTable": "No Record Found"
        },
    });
});
</script>
<div class="template">
	<div class="see-full ">
	  	<div class="see-full ">
		    <div class="see-6 see-ltb-6 see-tb-12 see-sm-12 see-xsm-12">
		      <div class="see-full">
		        <h3 class="f-32">Cart </h3>
		      </div>
		    </div> 
		</div>
        <div class="page-list showser">
            <table class="see-table see-table-each" id="myTable">
               <thead>
                   <tr>
                       <th>Name</th>
                       <th>MOBILE NO</th>
                       <th>PRODUCT NAME / QUANTITY	</th>
                       <th>Attribute </th>
                       <th>Created</th>
                       <th>ACTION</th>
                   </tr>
               </thead>
               <tbody>
               </tbody>
            </table> 
        </div> 
    </div>
</div>

<!-- Modal -->


  <script> 
 
  $(document).on('click',".placedorder",function(){     
        var userid = $(this).data("id") ;
                $.ajax({
                      url:'<?php echo MANAGE_URL;?>ajaxplacedorder.php',
                      type: 'post',
                      data: {userid: userid},
                      success: function(response){
                      
                      if(response == "S"){
                        swal("Done!", "Order has been successfully placed!", "success").then(function(){
                                          location.reload();
                                      });
                            
                          }else if(response == "F"){
                                     swal("Opps!", "Something went wrong!", "error");
                          }else{
                                alert("Unknown Error, Try Again" + response);
                          } 
                      }
                    });
       
    });


  //add Address
 
  $(document).on('click','.addUserAdress',function(){        
    $('#userid').val($(this).data("id"));
    $('#addAddress').modal('show'); 
  });
 
</script>
<?php
if(isset($_POST['add_address'])){
    $userid = $_POST['userid']; 
    $u_contact_name = $_POST['name']; 
    $ustateID = $_POST['state'];
    $ucity = $_POST['city'];
    $uaddress = $_POST['address'];
    $upincode = $_POST['pincode'];
    $uphone1 = $_POST['phone1'];
    $uphone2 = $_POST['phone2'];
   $cdate = date('Y-m-d H:i:s');
    $dataarray = ['see_ubd_u_id' => $userid, 'see_ubd_contact_name' => $u_contact_name, 'see_ubd_mob1' => $uphone1, 'see_ubd_mob2' => $uphone2, 'see_ubd_taxno' => '', 'see_ubd_state' => $ustateID , 'see_ubd_city' => $ucity, 'see_ubd_address' => $uaddress, 'see_ubd_pincode' => $upincode, 'see_ubd_create' => $cdate];
    
    $insertUserAddress = $see->addrow("see_user_billing_detail",$dataarray);

    if($insertUserAddress){
        $message = "Address Added!"; 
    }
    else{ $message = "Some Server Error"; }
} 
elseif(isset($_GET['old'])){
$selectAllUserOrders = $see->getrows("see_cart where status='Y' group by user_id ORDER BY id DESC"); ?>
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
 
<style type="text/css">
.product-table-scroll {
    padding: 15px;
}
input[type="text"] {
    background-color: #d2d2d2;
}
a {
    color: #fff !important; 
    text-decoration: none;
}
</style>
 <div class="slidebody see-trans5s">
<div class="template">
	<div class="see-full ">
	  	<div class="see-full ">
		    <div class="see-6 see-ltb-6 see-tb-12 see-sm-12 see-xsm-12">
		      <div class="see-full">
		        <h3 class="f-32">Cart </h3>
		      </div>
		    </div> 
        
		</div>
	  <!-- all contact list start here -->
	  <div class="see-full product-list-block see-padding-bottom-0">
	    <div class="product-list">
	      	    <div class="all-contact-filter-head">
	        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">

			</div>
	        <div class="see-12 see-ltb-412 see-tb-12 see-sm-12 see-xsm-12 see-text-right">	

				</div>

	        </div>
            
	        <div class="product-table-scroll">


		        <table class="table display" id="example">
                    <thead>
		        	<tr>
                        
 		        		<th>Name</th>
		        		<th>Mobile No</th>
		        		<th>Product Name / Quantity</th>
                          <th>Date</th>
                          <th>Action</th>
                         
		        	</tr> <!--getAllUserOrdersByOrderIDAndPayStatus-->
                     </thead>
        <tbody>
		        <?php
                if($selectAllUserOrders){
                    $i =1;
                    foreach($selectAllUserOrders as  $selectAllUserOrder){
                        $uid = $selectAllUserOrder['user_id'];  
                        $getUserDetail = $see->getrow("see_users","see_u_id = $uid");
                        $userName = $getUserDetail['see_u_name'];
                        $userMob = $getUserDetail['see_u_mobile']; 
                        $selectAllUserProduct = $see->getrows("see_cart where user_id='$uid ' and  status='Y' ");
                        $proName  = array('');
                        $proNameArr = '';          
                        foreach ($selectAllUserProduct  as $pvalue) {
                            $pid = $pvalue['product_id'];
                            $getProductDetail = $see->getrow("see_products","see_pro_id = $pid ");
                            $proName[] = $getProductDetail['see_pro_name'].' ( Quan. - '.$pvalue['quantity'].' )';
                            $proNameArr = implode(' | ', $proName);
                        } ?>
                        <tr>
                            <td><?=$userName;?></td>
                            <td><?=$userMob;?></td>
                            <td><?=$proNameArr;?></td>
                            <td><?php 
                                $date = $selectAllUserOrder['created_date'];
                                $convertDate = date('j - F, Y h:i:s', strtotime($date));
                                echo $convertDate; ?>
                            </td>
                            <td>
                             	<?php
                             	$oldBilling = $see->getrow("see_user_billing_detail", "see_ubd_u_id ='$uid'");
       							if($oldBilling){ ?><a href="#" class="orange-bg placedorder" data-id="<?php echo $uid; ?>">Place Order</a> <?php }
       							else{ ?> <a href="#" class="orange-bg addUserAdress" data-id="<?php echo $uid; ?>">Add Address</a> <?php } ?>
               				</td>
                        </tr>
                    </tbody>
                    <?php  
                    $i++; }
                } ?>
		    	</table>
		        <!--Pagination-->
	      	</div>
	    </div>
	  </div>
	  <!-- all contact list end here -->
	</div>
</div>
</div>
<!-- Modal -->
<div id="addAddress" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address</h4>
      </div>
      <div class="modal-body">
  			
  			<form method="post">
  				<input type="hidden" name="userid" id="userid">
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Name</label>
      <input type="text" name="name" class="form-control" placeholder="Enter Contact Person name" required=""  style="background: #fff;">
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>State</label>
      <select name="state" required="" class="form-control" style="background: #fff;">
         <option hidden="hidden" value="">Select State</option>
         <option value="241">Andaman And Nicobar</option>
         <option value="242">Andra Pradesh</option>
         <option value="243">Arunachal Pradesh</option>
         <option value="244">Assam</option>
         <option value="245">Bihar</option>
         <option value="246">Chandigarh</option>
         <option value="247">Chhattisgarh</option>
         <option value="248">Dadar And Nagar Haveli</option>
         <option value="249">Daman And Diu</option>
         <option value="250">Delhi</option>
         <option value="251">Goa</option>
         <option value="252">Gujarat</option>
         <option value="253">Haryana</option>
         <option value="254">Himachal Pradesh</option>
         <option value="255">Jammu And Kashmir</option>
         <option value="256">Jharkhand</option>
         <option value="257">Karnataka</option>
         <option value="258">Kerala</option>
         <option value="259">Lakshadeep</option>
         <option value="260">Madya Pradesh</option>
         <option value="261">Maharashtra</option>
         <option value="262">Manipur</option>
         <option value="263">Meghalaya</option>
         <option value="264">Mizoram</option>
         <option value="265">Nagaland</option>
         <option value="266">Orissa</option>
         <option value="267">Pondicherry</option>
         <option value="268">Punjab</option>
         <option value="269">Rajasthan</option>
         <option value="270">Sikkim</option>
         <option value="271">Tamil Nadu</option>
         <option value="272">Telagana</option>
         <option value="273">Tripura</option>
         <option value="274">Uttaranchal</option>
         <option value="275">Uttar Pradesh</option>
         <option value="276">West Bengal</option>
      </select>
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>City</label>
      <input type="text" name="city" placeholder="Enter your City" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Mobile No</label>
      <input type="tel" name="phone1" placeholder="Enter your Mobile No" required="" maxlength="10" class="form-control"  style="background: #fff;" >
   </div>
   <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-8 see-pading-bottom-0">
      <label>Address</label>
      <input type="text" name="address" placeholder="Enter your Address" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Pincode</label>
      <input type="number" name="pincode" placeholder="Enter your Pincode" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Alternate Mobile</label>
      <input type="tel" name="phone2" placeholder="Enter your Alternate Mobile(optional)" class="form-control" style="background: #fff;">
   </div>
   <div class="see-full see-padding-8 see-pading-bottom-0">
      <input class=" theme-second-color theme-white-text orange-bg cart-shadow" type="submit" name="add_address" value="SAVE " >
   </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




  
<!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!--  <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->
<script> 
$(document).ready(function() {
    $('#example').DataTable({
        
    });
});
</script>
  <script> 
 
  $(document).on('click',".placedorder",function(){     
        var userid = $(this).data("id") ;
                $.ajax({
                      url:'<?php echo MANAGE_URL;?>ajaxplacedorder.php',
                      type: 'post',
                      data: {userid: userid},
                      success: function(response){
                      
                      if(response == "S"){
                        swal("Done!", "Order has been successfully placed!", "success").then(function(){
                                          location.reload();
                                      });
                            
                          }else if(response == "F"){
                                     swal("Opps!", "Something went wrong!", "error");
                          }else{
                                alert("Unknown Error, Try Again" + response);
                          } 
                      }
                    });
       
    });


  //add Address
 
  $(document).on('click','.addUserAdress',function(){        
    $('#userid').val($(this).data("id"));
    $('#addAddress').modal('show'); 
  });
 
</script>
<script>
  // Handle the export button click event
  document.getElementById("export-btn").addEventListener("click", function() {
    // Get the selected start and end dates
    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    
    // Create a new Excel workbook and worksheet
    var workbook = XLSX.utils.book_new();
    var worksheet = XLSX.utils.table_to_sheet(document.getElementById("order-table"));
    
    // Add the column names to the worksheet
    XLSX.utils.sheet_add_aoa(worksheet, [[
      'Name',
      'Mobile No',
      'Product Name / Quantity',
      'Attribute',
      'Created',
      'Action'
    ]], {origin: "A1"});
    
    // Set the worksheet name
    XLSX.utils.book_append_sheet(workbook, worksheet, "Orders");
    
        // Export the workbook to an Excel file
        var date = new Date().toISOString().slice(0, 10);
    var filename = "orders_" + startDate + "_to_" + endDate + "_" + date + ".xlsx";
    XLSX.writeFile(workbook, filename);
  });
</script>
<?php } ?>
</div>
</div>

<!-- Modal -->
<div id="addAddress" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address</h4>
      </div>
      <div class="modal-body">
  			
  			<form method="post">
  				<input type="hidden" name="userid" id="userid">
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Name</label>
      <input type="text" name="name" class="form-control" placeholder="Enter Contact Person name" required=""  style="background: #fff;">
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>State</label>
      <select name="state" required="" class="form-control" style="background: #fff;">
         <option hidden="hidden" value="">Select State</option>
         <option value="241">Andaman And Nicobar</option>
         <option value="242">Andra Pradesh</option>
         <option value="243">Arunachal Pradesh</option>
         <option value="244">Assam</option>
         <option value="245">Bihar</option>
         <option value="246">Chandigarh</option>
         <option value="247">Chhattisgarh</option>
         <option value="248">Dadar And Nagar Haveli</option>
         <option value="249">Daman And Diu</option>
         <option value="250">Delhi</option>
         <option value="251">Goa</option>
         <option value="252">Gujarat</option>
         <option value="253">Haryana</option>
         <option value="254">Himachal Pradesh</option>
         <option value="255">Jammu And Kashmir</option>
         <option value="256">Jharkhand</option>
         <option value="257">Karnataka</option>
         <option value="258">Kerala</option>
         <option value="259">Lakshadeep</option>
         <option value="260">Madya Pradesh</option>
         <option value="261">Maharashtra</option>
         <option value="262">Manipur</option>
         <option value="263">Meghalaya</option>
         <option value="264">Mizoram</option>
         <option value="265">Nagaland</option>
         <option value="266">Orissa</option>
         <option value="267">Pondicherry</option>
         <option value="268">Punjab</option>
         <option value="269">Rajasthan</option>
         <option value="270">Sikkim</option>
         <option value="271">Tamil Nadu</option>
         <option value="272">Telagana</option>
         <option value="273">Tripura</option>
         <option value="274">Uttaranchal</option>
         <option value="275">Uttar Pradesh</option>
         <option value="276">West Bengal</option>
      </select>
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>City</label>
      <input type="text" name="city" placeholder="Enter your City" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Mobile No</label>
      <input type="tel" name="phone1" placeholder="Enter your Mobile No" required="" maxlength="10" class="form-control"  style="background: #fff;" >
   </div>
   <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-8 see-pading-bottom-0">
      <label>Address</label>
      <input type="text" name="address" placeholder="Enter your Address" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Pincode</label>
      <input type="number" name="pincode" placeholder="Enter your Pincode" required="" class="form-control" style="background: #fff;" >
   </div>
   <div class="see-6 see-padding-8 see-pading-bottom-0">
      <label>Alternate Mobile</label>
      <input type="tel" name="phone2" placeholder="Enter your Alternate Mobile(optional)" class="form-control" style="background: #fff;">
   </div>
   <div class="see-full see-padding-8 see-pading-bottom-0">
      <input class=" theme-second-color theme-white-text orange-bg cart-shadow" type="submit" name="add_address" value="SAVE " >
   </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
