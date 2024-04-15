<?php 
include('config.php');
include('session.php');
$result = mysqli_query($conn, "select * from admin_users where id='$session_id'")or die('Error In Session');
$row = mysqli_fetch_assoc($result);

$result1 = mysqli_query($conn, "select * from admin_users where status != 1 ");





?>

<?php require_once("inc/style-and-script.php") ; ?>


<style type="text/css">
	#adduser {
  float: right;
    margin-bottom: 1em;
}
</style>
</head>
<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
		<!-- Sidebar-->
		<div class="border-end bg-white" id="sidebar-wrapper">
			<div class="sidebar-heading border-bottom bg-light">Admin</div>
			<div class="list-group list-group-flush">
				<a class="list-group-item list-group-item-action list-group-item-light p-3" href="home.php">Stately Quiz Customer Data</a>
				<?php if($row['status'] == 1) {?>
				
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="additional_quiz_data.php">Additional Style Preference Data</a>
				<a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="alluser.php">All Staff</a>

                <?php }?>
				<a class="list-group-item list-group-item-action list-group-item-light p-3" href="logout.php">Log out</a>
<!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Overview</a>
<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Events</a>
<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Status</a> -->
</div>
</div>
<!-- Page content wrapper-->
<div id="page-content-wrapper">
	<!-- Top navigation-->
	<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
		<div class="container-fluid">
			<button class="btn btn-primary" id="sidebarToggle">Toggle Menu</button>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ms-auto mt-2 mt-lg-0">
					<li class="nav-item active">Welcome: <?php echo $row['email']; ?></li>
					<!-- <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li> -->
					<!-- <li class="nav-item"><a class="nav-link" href="#!">Link</a></li> -->
<!-- <li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
<a class="dropdown-item" href="#!">Action</a>
<a class="dropdown-item" href="#!">Another action</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="#!">Something else here</a>
</div>
</li> -->
</ul>
</div>
</div>
</nav>
<!-- Page content-->
<div class="container-fluid">
	<h1 class="mt-4">All Staff Information</h1>
	<a class="btn btn-primary " href="adduser.php" id="adduser">Add Staff</a>
<!-- Modal -->
            <div id="updateModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" >Staffname</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter name" required>            
                            </div>
                            <div class="form-group">
                                <label for="email" >Email</label>    
                                <input type="email" class="form-control" id="email"  placeholder="Enter email">                          
                            </div>      
                            <div class="form-group">
                                <label for="userstatus" >Staff Status</label>
                                <select id='userstatus' class="form-control">
                                    <option value='active'>Active</option>
                                    <option value='inactive'>Inactive</option>
                                </select>              
                            </div>   
                            
                            
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="txt_userid" value="0">
                            <button type="button" class="btn btn-success btn-sm" id="btn_save">Save</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
<!-----Popup modal -------->  
<div class="table-scroll">
	<table id="example1" class="display" style="width:100%">
		<thead>
            <tr>
                <th>Staffname</th>
                <th>Email</th>
                <th>Staff Status</th>
                <th>Action</th>
                
            </tr>
        </thead>

        
        <tbody>
        	<?php 
     while($row1 = mysqli_fetch_assoc($result1)){
	// Update Button
        $updateButton = "<button class='btn btn-primary btn-info updateUser' data-id='".$row1['id']."' data-toggle='modal' data-target='#updateModal' >Staff Status</button>";

        // Delete Button
        $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='".$row1['id']."'>Delete</button>";
        
        $action = $updateButton." ".$deleteButton;

		if($row1['userstatus'] == 'active'){
		$userstatus = "<span style='color: green;font-weight: 600;font-size: 18px;text-transform: capitalize;'>".$row1['userstatus']."</span>";

		}else{
		$userstatus = "<span style='color: red;font-weight: 600;font-size: 18px;text-transform: capitalize;'>".$row1['userstatus']."</span>";

		}

	
	echo '<tr>
	<td>'. $row1['username'].'</td>
	<td>'. $row1['email'].'</td>
	<td>'. $userstatus.'</td>
	<td>'.$action.'</td>
	</tr>';
	
}


	


?>
        </tbody>
	</table>
</div>


</div>
</div>
</div>

</body>
</html>

<script type="text/javascript">
	$(document).ready(function() {
   var userDataTable = $('#example1').DataTable();
} );

// Delete record
            $('#example1').on('click','.deleteUser',function(){
                var id = $(this).data('id');

                var deleteConfirm = confirm("Are you sure? ");
                if (deleteConfirm == true) {
                    // AJAX request
                    $.ajax({
                        url: 'ajaxfile.php',
                        type: 'post',
                        data: {request: 4, id: id},
                        success: function(response){
                        	console.log(response);


                            if(response == 1){
                                alert("Record deleted.");

                                // Reload DataTable
                                location.reload();
                            }else{
                                alert("Invalid ID.");
                            }
                            
                        }
                    });
                } 
                
            });

// Update record
            $('#example1').on('click','.updateUser',function(){


                var id = $(this).data('id');

                $('#txt_userid').val(id);

                // AJAX request
                $.ajax({
                    url: 'ajaxfile.php',
                    type: 'post',
                    data: {request: 2, id: id},
                    dataType: 'json',
                    success: function(response){
                        if(response.status == 1){

                            $('#name').val(response.data.name);
                            $('#email').val(response.data.email);
                            $('#userstatus').val(response.data.userstatus);
                            

                        }else{
                            alert("Invalid ID.");
                        }
                    }
                });

            });


// Save user 
            $('#btn_save').click(function(){
                var id = $('#txt_userid').val();

                var name = $('#name').val().trim();
                var email = $('#email').val().trim();
                var userstatus = $('#userstatus').val().trim();
                

                if(name !='' && email != '' ){

                    // AJAX request
                    $.ajax({
                        url: 'ajaxfile.php',
                        type: 'post',
                        data: {request: 3, id: id,name: name, email: email, userstatus: userstatus},
                        dataType: 'json',
                        success: function(response){
                            if(response.status == 1){
                                alert(response.message);

                                // Empty the fields
                                $('#name','#email').val('');
                                $('#userstatus').val('active');
                                $('#txt_userid').val(0);

                                // Reload DataTable
                                location.reload();

                                // Close modal
                                //$('#updateModal').modal('toggle');
                            }else{
                                alert(response.message);
                            }
                        }
                    });

                }else{
                    alert('Please fill all fields.');
                }
            });

</script>