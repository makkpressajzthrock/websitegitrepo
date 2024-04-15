<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
$user_id = $_SESSION["user_id"] ;
// This The Code for Add the Faq In A Database.
if(isset($_POST['submit'])){
// die;


$question =$conn->real_escape_string( $_POST['question']);
$answer = $conn->real_escape_string($_POST['answer']);

 $sql = "INSERT INTO `add_faq` (question, answer) VALUES('".$question."','".$answer."')"; 


    if (!$conn -> query($sql)) {
  printf("%d Row inserted.\n", $conn->affected_rows);
}


$conn -> close();

// echo $result = mysqli_query($conn, $sql);
// if($result){
//     header("Location: viewfaq.php?Success=faqAdded");
// }

}


?>
<?php require_once("inc/style-and-script.php") ; ?>


    </head>
    <body class="custom-tabel">
        <div class="d-flex" id="wrapper">
        <div class="top-bg-img"></div>
            <?php require_once("inc/sidebar.php"); ?>

            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <?php require_once("inc/topbar.php"); ?>
                
  <!-- layout for form to add FAQ -->
<div class="container-fluid add_faq content__up">
            <h1>Add FAQ</h1>
            <div class="back_btn_wrap ">
						<a href="viewfaq.php" type="button" class="btn btn-primary">Back</a>
					</div>
            <div class="form_h">
        <form method="POST" action="#">
 
                <!-- question -->
                <div class="form-group">
                    <label>Enter Question</label>
                    <input type="text" name="question" class="form-control" required />
                </div>
 
                <!-- answer -->
                <div class="form-group">
                    <label>Enter Answer</label>
                    <textarea name="answer" id="answer" class="form-control" required></textarea>
                </div>
 
                <!-- submit button -->
                <div class="form_h_submit">
                <input type="submit" name="submit" class="btn btn-primary" value="Add FAQ" />
</div>
            </form>
</div>
 
</div>

            </div>
        </div>



    </body>
</html>

