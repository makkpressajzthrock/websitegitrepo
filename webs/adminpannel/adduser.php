<?php 
include('config.php');
include('session.php');

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$result = mysqli_query($conn, "select * from admin_users where id='$session_id'")or die('Error In Session');
$row = mysqli_fetch_array($result);

$msg = '';
//var_dump($row1);
if( isset($_POST['adduser'])){
  $username = $_POST['username'];
  $email_id = $conn -> real_escape_string($_POST['email']);
  $password = $_POST['password'];
  $hashpassword = md5($password);
  $status = 2;
  $userstatus = 'active';

  $sql ="SELECT * FROM admin_users WHERE email = '".$email_id."'";
  $user = mysqli_query($conn, $sql);
  $arr = mysqli_fetch_assoc($user);
  $email = isset($arr['email']);

  if($email_id == $email ){

    $msg = '<p style="color: red;">Email Id already Exsit</p>';

  }else{

    $query = "INSERT INTO admin_users(username,password,email,status,userstatus) 
    values ('$username','$hashpassword','$email_id','$status','$userstatus')";

    if($result = $conn -> query($query)){
//$msg =  "<p style='color:green;'>User Added Successfully.</p>";
      $mail->SMTPDebug = 0;  
      $mail->isSMTP();   
      $mail->Host = "smtp.gmail.com";
      $mail->SMTPAuth = true; 
      $mail->Username = "developer00020@gmail.com";                 
      $mail->Password = "Mypwd!@#123";
      $mail->SMTPSecure = "tcp";   
      $mail->Port = 587;
      $mail->addAddress($email_id);
      $mail->isHTML(true);
      $token = bin2hex(random_bytes(10));
      $fname= $email_id; 
      $subject= ' Stately Panel Login Info'; 
      $link="<a href='https://pure-coast-35590.herokuapp.com/adminpanel/'>login</a>";
      $message= 'Please Login in Stately Panel useing Below info :'.$link;

      $bodyContent=$message;

      $mail->Subject =$subject;
      $bodyContent = 'Dear '.$username;
      $bodyContent .='<p>'.$message.'</p>';
      $bodyContent .='<p>Username:-'.$username.'</p>';
      $bodyContent .='<p>Password:-'.$password.'</p>';

      $mail->Body = $bodyContent;

      if(!$mail->send()) {

        $msg = '<p style="color: red;">Some thing wrong!</p>';
//echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        $msg = '<p style="color: green;">Staff Added Successfully. Username and password  be sent on given email address. </p>';



      }



    }
    else{
      $msg = "<p style='color:red;'>Some thing wrong!</p>";
    }

  }

}


?>

<?php require_once("inc/style-and-script.php") ; ?>
  <style type="text/css">
/*#getcsv {
float: right;
margin-bottom: 1em;
}
.custom-tabel .display{padding-top: 20px;}
.custom-tabel .display th{min-width: 50px;}

table.display.dataTable.no-footer {
width: 1600px !important;
}*/

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
  <h1 class="mt-4">Add Staff </h1>
  <p><?php echo $msg; ?></p>
  <div class="container">
    <form action="#" method="post">
      <div class="row">
        <div class="col-25">
          <label for="fname">Username</label>
        </div>
        <div class="col-75">
          <input type="text" id="fname" name="username" placeholder="Username" required>
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="email">Email</label>
        </div>
        <div class="col-75">
          <input type="email" id="email" name="email" placeholder="Email Address" required>
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="password">Password</label>
        </div>
        <div class="col-75">
          <input type="password" id="password" name="password" placeholder="password">
        </div>
      </div>
      <br>
      <div class="row">
        <input type="submit" name="adduser" value="Submit">
      </div>
    </form>
  </div>


</div>
</div>
</div>

</body>
</html>

<style type="text/css">
* {
  box-sizing: border-box;
}

input[type=text],input[type=email],input[type=password], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}



label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #c89f43;;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
}

input[type=submit]:hover {
  background-color: #c89f43;;
}

.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}

</style>