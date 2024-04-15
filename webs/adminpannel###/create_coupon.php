<?php 
//  error_reporting(E_ALL);
// ini_set('display_errors', 1);
include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;



if(isset($_POST['delete_coupon'])){


// Include the database connection file 
require_once '../payment/config.php';
$country = "USD";
include '../payment/dbConnect.php';

// Include the Stripe PHP library 
require_once '../payment/stripe-php/init.php';



$strip_coupon_id= $_POST['strip_coupon_id']; 
$coupon_id= $_POST['coupon_id']; 

 $location = $_POST['location'];

 // die;
 
if($strip_coupon_id!="No" && $location == "Other"){
 
    \Stripe\Stripe::setApiKey(getGetway("USD",$conn)['STRIPE_API_KEY']);
    $stripe = new \Stripe\StripeClient(getGetway("USD",$conn)['STRIPE_API_KEY']);
    $coupon = $stripe->coupons->delete($strip_coupon_id, []);
}
else if($strip_coupon_id!="No" && $location == "India"){
 echo "inn";
    \Stripe\Stripe::setApiKey(getGetway("IND",$conn)['STRIPE_API_KEY']);
    $stripe = new \Stripe\StripeClient(getGetway("IND",$conn)['STRIPE_API_KEY']);
    $coupon = $stripe->coupons->delete($strip_coupon_id, []);
}

else{
    $coupon['id'] ="D";
}

// echo "<pre>";

// print_r($coupon);
if($coupon['id']=="D")
{
      $update_sql= "UPDATE `coupons` SET deleted_at = now() WHERE `id`='$coupon_id'";
    $results = mysqli_query($conn,$update_sql);
        $_SESSION['success'] = "Coupon Deleted Successfully!" ;
        header("location: ".HOST_URL."adminpannel/create_coupon.php") ;
        die();
          
}
else if($coupon['id']!="")
{
    
    $update_sql= "UPDATE `coupons` SET deleted_at = now() WHERE `strip_coupon_id`='$strip_coupon_id'";
    $results = mysqli_query($conn,$update_sql);
        $_SESSION['success'] = "Coupon Deleted Successfully!" ;
        header("location: ".HOST_URL."adminpannel/create_coupon.php") ;
        die();  
}

}



// export data
if(isset($_POST['export'])){
    $cate = $_POST['selected_category'];
    $query = mysqli_query($conn,"SELECT * FROM coupons where deleted_at IS NULL AND coupon_category LIKE '%$cate%'"); 
 
    if($query->num_rows > 0){ 
        $delimiter = ","; 
        $filename = "coupon_details.csv"; 
         
        // Create a file pointer 
        $f = fopen('php://memory', 'w');  
        // Set column headers 
        $fields = array('CATEGORY','COUPON NAME', 'COUPON CODE', 'STRIPE ID', 'LOCATION', 'DISCOUNT TYPE', 'DISCOUNT AMOUNT', 'DURATION', 'USED', 'START DATE', 'EXPIRY DATE'); 
        fputcsv($f, $fields, $delimiter); 
      
        while($row = $query->fetch_assoc()){ 


                                $d = "";
                                $cat = $row["coupon_category"]; 
                                if($cat!=""){
                                        $sqlP = "SELECT category_name FROM coupon_categories where id in (".$cat.") ";
                                        $resultP = mysqli_query($conn, $sqlP);
                                        
                                        while($couponsP = mysqli_fetch_assoc($resultP)){

                                            if($d ==""){
                                                $d .= $couponsP['category_name']; 
                                            }
                                            else{
                                                $d .= ', '.$couponsP['category_name']; 
                                            }

                                        }
                                         
                                    }



            $lineData = array($d, $row['name'], $row['code'], $row['strip_coupon_id'], $row['location'], $row['type'], $row['discount_amount'], $row['duration'], $row['number_of_uses'], $row['start_date'], $row['expiry_date']); 
            fputcsv($f, $lineData, $delimiter); 
        } 
         
        // Move back to beginning of file 
        fseek($f, 0); 
         
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
         
        //output all remaining data on a file pointer 
        fpassthru($f); 
        exit;
    }
    else{
        $_SESSION['error'] = "There are no data to export";
    }     
}




?>
<?php require_once("inc/style-and-script.php") ; ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
 <style>

    .overlay_flight_traveldil {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    transition: opacity 500ms;
    visibility: hidden;
    opacity: 0;
    background: rgba(0, 0, 0, 0);
    }
    .overlay_flight_traveldil:target {
    visibility: visible;
    opacity: 1;
    z-index: 5;
    }
    .popup_flight_travlDil {
    padding: 35px 20px 25px;
    background: #fff;
    border-radius: 5px;
    transform: translate(-50%, -50%);
    margin: 0px;
    top: 50%;
    left: 50%;
    width: fit-content;
    position: absolute;
    width: 400px;
    transition: none;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    }
    .popup_flight_travlDil .close_flight_travelDl {
    position: absolute;
    top: -10px;
    right: 10px;
    transition: all 200ms;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
    color: #333;
    }
    .popup_flight_travlDil .content_flightht_travel_dil {
    /* max-height: 30%; */
    text-align:center;
    overflow: auto;
    }
    #select_box{
        padding: 0px 10px;
    }
    .popup_flight_travlDil br { display:none; }
    .popup_flight_travlDil .content_flightht_travel_dil #select_box {
        margin-bottom: 15px;
    }   

</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

<div class="container-fluid faq content__up"  >    
<h1>Coupons</h1>         
<div class="  faqAdded">

				<!-- show all FAQs added -->
 
<?php require_once("inc/alert-status.php") ; 
    $sql1 = mysqli_query($conn,"SELECT * FROM coupon_categories");
    $category_array = [];
    $id_array = [];
?>



<div class="profile_tabs">
<a href="addcoupon.php" class="btn btn-primary">Create Coupon</a>

 
<a href="#popup_flight_travlDil1" class="btn btn-primary">Export</a>
<form method="POST">
    <div id="popup_flight_travlDil1" class="overlay_flight_traveldil">
        <div class="popup_flight_travlDil">
            <a class="close_flight_travelDl" href="#">&times;</a><br>
            <div class="content_flightht_travel_dil"> 
                
                    <select name="selected_category" id="select_box" class="form-control">
                    <option value="0">Select Category</option>
                    <?php while($coupons = mysqli_fetch_assoc($sql1)){ ?>
                    <option value="<?= $coupons['id'] ?>">  <?= $coupons['category_name'] ?>  
                        </option>
                        <?php 
                    $category_array[$coupons['id']] = $coupons['category_name'];

                    } 
                    
                    // print_r($category_array);die;
                    ?>
                    </select>
                    <input name="export" type="submit" class="btn btn-primary" value="Export" style="z-index: 99999;">
            
            </div>
        </div>
    </div>
</form>
<br><br>


<?php  
$sql = "SELECT * FROM coupons where deleted_at Is null ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){ 
?>     

        <div class="table_S">
        <table id="example" class="coupon__table display" style="width:100%">
            <!-- table heading -->


            <thead>
                <tr>
                    <th>Coupon Name</th>
                    <th>Coupon Code</th>
                    <th>Category</th>
                    <th>Location</th>                    
                    <th>Stripe Id</th>
                    <th>Discount Type</th>
                    <th>Discount Amount</th>
                    <th>Duration</th>
                    <th>No. of Uses</th>
                    <th>Used</th>
                    <th>Enabled</th>
                    <th>Applies To</th>
                    <th>Start Date</th>
                    <th>Expiry Date</th>
                    <th>Action</th>

                </tr>
            </thead>
 
            <!-- table body -->
            <tbody>
                <?php while($coupons = mysqli_fetch_assoc($result)){


                    $start_date = $coupons["start_date"] ;
                    $current_date = date('Y-m-d H:i:s') ;
                    $diff_start = date_diff(date_create($current_date) , date_create($start_date) ) ;
                    $started = 1;
                    if($diff_start->invert !=1)
                    {
                        $started = 0;
                    } 


                    $expiry_date = $coupons["expiry_date"] ;
                    $current_date = date('Y-m-d H:i:s') ;
                    $diff = date_diff(date_create($current_date) , date_create($expiry_date) ) ;
                    $expired = 0;
                    if($diff->invert ==1)
                    {
                        $expired = 1;
                    }  
                 ?>
                    <tr>
                        <td><?php echo $coupons["name"]; ?></td>
                        <td><?php echo $coupons["code"]; ?></td>
                        <td><?php 

                                $cat = $coupons["coupon_category"]; 
                                if($cat!=""){
                                        $sqlP = "SELECT category_name FROM coupon_categories where id in (".$cat.") ";
                                        $resultP = mysqli_query($conn, $sqlP);
                                        $d = "";
                                        while($couponsP = mysqli_fetch_assoc($resultP)){

                                            if($d ==""){
                                                $d .= $couponsP['category_name']; 
                                            }
                                            else{
                                                $d .= ', '.$couponsP['category_name']; 
                                            }

                                        }
                                        echo $d;
                                }                                

                        ?></td>
                        <td><?php echo $coupons["location"]; ?></td>

                        <td><?php echo $coupons["strip_coupon_id"]; ?></td>
                        <td><?php echo $coupons["type"]; ?></td>
                        <td><?php 
                               if($coupons["type"]!="Lifetime"){ 
                                    echo $coupons["discount_amount"]; 
                                    if($coupons["type"] == "Percentage"){
                                        echo " % Off";

                                    }
                                    else{
                                        echo " Off";
                                    }
                                }
                        ?></td>
                        <td><?php 
                             echo $coupons["duration"]; 
                            if($coupons["duration"] !=""){
                                echo " Month";
                                if($coupons["duration"]>1)
                                {
                                    echo "s";
                                }
                            }

                        ?></td>
                        <td><?=$coupons["number_of_uses"].'/'.$coupons["uses_per_customer"];?></td>
                        <td><?php 
                        if($coupons["uses_per_customer"]=='unlimited'){
                                echo "---";
                         }           
                        else if($coupons["number_of_uses"]==1){
                                echo "<span class='bg-success p-1 rounded px-2 text-light'>Used</span>";
                        }else{
                                echo "No";
                        } ?>
                            
                        </td>
                        <td><?php 
                            if($started==0){
                                echo "<i class='fa fa-check-circle text-dark'></i>" ;
                            }
                            elseif($expired==1){
                                echo "<i class='fa fa-circle text-danger'></i>" ;
                            }
                            elseif($coupons["status"]==1){
                                    echo "<i class='fa fa-check text-success'></i>";
                                }elseif($coupons["status"]==2){
                                    echo "<i class='fa fa-check-circle text-success'></i>" ;
                            }
                            else{
                                     echo "<i class='fa fa-close text-danger'></i>" ;
                            } ?>
                            
                                
                            </td>
                        <td><?php 
                                if($coupons["plan_category_id"]=="9999"){
                                        echo "All Plans";
                                } 
                                else{

                                $sqlP = "SELECT * FROM plans where id = '".$coupons["plan_category_id"]."' ";
                                $resultP = mysqli_query($conn, $sqlP);
                                if($couponsP = mysqli_fetch_assoc($resultP)){ 
                                        echo $couponsP['name'];
                                        echo "<small> (";
                                        echo $couponsP['interval'];
                                        echo ")</small>";
                                }


                                }
                        ?></td>
                        
                        <td><?php 
                        $date=date_create($coupons["start_date"]);
                        echo date_format($date,"Y-m-d");
                        
                        ?></td>
                        
                        <td><?php echo $coupons["expiry_date"]; ?></td>
                        <td>

                               <a href="javascript:void(0);" class="btn btn-primary delete_coupon" coupon_id="<?php echo $coupons["id"]; ?>">
                                   <i class="fa fa-trash"></i>
                                   
                               </a>
                               <form method="post" style="display: none;"><input type="text" name="coupon_id" value="<?php echo $coupons["id"]; ?>">
                                <input type="text" name="strip_coupon_id" value="<?php echo $coupons["strip_coupon_id"]; ?>">
                                <input type="text" name="location" value="<?php echo $coupons["location"]; ?>">
                                <button class="coupon_<?php echo $coupons["id"]; ?>" name="delete_coupon">delete</button></form>
 
                    
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
                </div>
    <?php }else{?>

        <div class="container my-4 py-4">
            <div class="col-12 py-4 my-4 text-center">
                <h3>No coupons</h3>
                <p>Create a coupons to offer discounts.</p>
            </div>
        </div>

    <?php } ?>
    </div>
				
                </div>

				</div>
			</div>
		</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#example').DataTable({
         "ordering": false
    });

    // $(".delete_coupon").click(function(){
    $("body").on("click",".delete_coupon",function(){
        var coupon_id = $(this).attr("coupon_id");

        
            Swal.fire({
              title: 'Are you sure?',
              text: "This Coupon will be deleted from the strip too.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                 $(".coupon_"+coupon_id).click();
              }
            })
    });


});
</script>

<!-- <script>
var tableCoupon = document.querySelector('.coupon__table').cloneNode(true);
var newOverWrap = document.createElement('div');
newOverWrap.classList.add('overflow-scroll-table')
newOverWrap.appendChild(tableCoupon)
setTimeout(()=>{
    document.querySelector('#example_filter').insertAdjacentElement('afterEnd', newOverWrap);
}, 1000)
</script> -->

	</body>
</html>
