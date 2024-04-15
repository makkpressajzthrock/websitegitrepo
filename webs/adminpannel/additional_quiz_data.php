<?php 
include('config.php');
include('session.php');
$result = mysqli_query($conn, "select * from admin_users where id='$session_id'")or die('Error In Session');
$row = mysqli_fetch_array($result);

$result1 = mysqli_query($conn, "select * from stately_quiz_data_additional limit 10000");





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
				

                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="additional_quiz_data.php">Additional Style Preference Data</a>

                <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="alluser.php">All Staff</a>
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
	<h1 class="mt-4">Additional User Information</h1>
	<!-- <a class="btn btn-primary " href="adduser.php" id="adduser">Add Staff</a> -->
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
                <th>Email</th>
                <th>Photo</th>
                <th>Inspo 1</th>
                <th>Inspo 2</th>
                <th>Inspo 3</th>
                <th>Inspo 4</th>
                <th>Inspo 5</th>
                <th>What is your height</th>
                <th>what is your weight</th>
                <th>What is your dress shirt_si</th>
                <th>Premium unhemmed pants</th>
                <th>What is your size in blazer</th>
                <th>Do you wear shorts</th>
                <th>Do you dislike any colors</th>
                <th>Clothing to avoid</th>
                <th>Are you on instagram</th>
                <th>DOB</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
        	<?php 

while($array=mysqli_fetch_assoc($result1))
{	


	echo '<tr>
	<td>'. $array['email'].'</td>
	<td><img src="https://clctivlbs.com/statelymen/'.$array['photo'].'"   class="additional_image"></td>
	<td><img src="https://clctivlbs.com/statelymen/'.$array['inspo_1'].'" class="additional_image" ></td>
	<td><img src="https://clctivlbs.com/statelymen/'.$array['inspo_2'].'" class="additional_image" ></td>
    <td><img src="https://clctivlbs.com/statelymen/'.$array['inspo_3'].'" class="additional_image" ></td>
    <td><img src="https://clctivlbs.com/statelymen/'.$array['inspo_4'].'" class="additional_image" ></td>
    <td><img src="https://clctivlbs.com/statelymen/'.$array['inspo_5'].'" class="additional_image" ></td>
    <td>'.$array['what_is_your_height'].'</td>
    <td>'.$array['what_is_your_weight'].'</td>
    <td>'.$array['what_is_your_dress_shirt_si'].'</td>
    <td>'.$array['premium_unhemmed_pants'].'</td>
    <td>'.$array['what_is_your_size_in_blazer'].'</td>
    <td>'.$array['do_you_wear_shorts'].'</td>
    <td>'.$array['do_you_dislike_any_colors'].'</td>
    <td>'.$array['clothing_to_avoid'].'</td>
    <td>'.$array['are_you_on_instagram'].'</td>
    <td>'.$array['DOB'].'</td>
    <td>'.$array['feedback'].'</td>

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
   var userDataTable = $('#example1').DataTable({
    "scrollX": true,
   });
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

<style type="text/css">
    .additional_image{max-width: 100px;}
</style>