<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once('config.php');
require_once('inc/functions.php') ;



?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">

					<?php 

require_once("inc/alert-status.php") ; 
 
// Include pagination library file 
include_once 'Pagination.class.php'; 
 
// Include database configuration file 
 
// Set some useful configuration 
$limit = 10; 
 
// Count of all records 
$query   = $conn->query("SELECT COUNT(*) as rowNum FROM admin_users "); 
$result  = $query->fetch_assoc(); 
$rowCount= $result['rowNum']; 
 
// Initialize pagination class 
$pagConfig = array( 
    'totalRows' => $rowCount, 
    'perPage' => $limit, 
    'contentDiv' => 'dataContainer', 
    'link_func' => 'columnSorting' 
); 
$pagination =  new Pagination($pagConfig); 

						$query = $conn->query("SELECT * FROM `admin_users` ORDER BY id DESC LIMIT $limit") ;

						// print_r($query) ;



					?>

<div class="datalist-wrapper">
    <!-- Loading overlay -->
    <div class="loading-overlay"><div class="overlay-content">Loading...</div></div>
    
    <!-- Data list container -->
    <div id="dataContainer">
        <table class="table table-striped sortable">
        <thead>
            <tr>
                <th scope="col" class="sorting" coltype="id" colorder="">#ID</th>
                <th scope="col" class="sorting" coltype="first_name" colorder="">First Name</th>
                <th scope="col" class="sorting" coltype="last_name" colorder="">Last Name</th>
                <th scope="col" class="sorting" coltype="email" colorder="">Email</th>
                <th scope="col" class="sorting" coltype="country" colorder="">Country</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($query->num_rows > 0){ 
                while($row = $query->fetch_assoc()){ 
            ?>
                <tr>
                    <th scope="row"><?php echo $row["id"]; ?></th>
                    <td><?php echo $row["firstname"]; ?></td>
                    <td><?php echo $row["lastname"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["phone"]; ?></td>
                    <td>Active</td>
                </tr>
            <?php 
                } 
            }else{ 
                echo '<tr><td colspan="6">No records found...</td></tr>'; 
            } 
            ?>
        </tbody>
        </table>
        
        <!-- Display pagination links -->
        <?php echo $pagination->createLinks(); ?>
    </div>
</div>


	</body>
</html>

<script type="text/javascript">
$(function(){
    $(document).on("click", "th.sorting", function(){
        let current_colorder = $(this).attr('colorder');
        $('th.sorting').attr('colorder', '');
        $('th.sorting').removeClass('asc');
        $('th.sorting').removeClass('desc');
        if(current_colorder == 'asc'){
            $(this).attr("colorder", "desc");
            $(this).removeClass("asc");
            $(this).addClass("desc");
        }else{
            $(this).attr("colorder", "asc");
            $(this).removeClass("desc");
            $(this).addClass("asc");
        }
        columnSorting();
    });
});
	
function columnSorting(page_num){
    page_num = page_num?page_num:0;
	
    let coltype='',colorder='',classAdd='',classRemove='';
    $( "th.sorting" ).each(function() {
        if($(this).attr('colorder') != ''){
            coltype = $(this).attr('coltype');
            colorder = $(this).attr('colorder');
			
            if(colorder == 'asc'){
                classAdd = 'asc';
                classRemove = 'desc';
            }else{
                classAdd = 'desc';
                classRemove = 'asc';
            }
        }
    });
    
    $.ajax({
        type: 'POST',
        url: 'getData.php',
        data:'page='+page_num+'&coltype='+coltype+'&colorder='+colorder,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#dataContainer').html(html);
            
            if(coltype != '' && colorder != ''){
                $( "th.sorting" ).each(function() {
                    if($(this).attr('coltype') == coltype){
                        $(this).attr("colorder", colorder);
                        $(this).removeClass(classRemove);
                        $(this).addClass(classAdd);
                    }
                });
            }
            
            $('.loading-overlay').fadeOut("slow");
        }
    });
}
</script>

