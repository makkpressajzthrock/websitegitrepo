<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

 
    // check if FAQ exists
    $sql = "SELECT * FROM add_faq WHERE id = '".$_REQUEST["id"]."'";
    $statement = mysqli_query($conn, $sql);
    
    $faq = mysqli_fetch_assoc($statement);
 
    if (!$faq)
    {
        die("FAQ not found");
    }
    // update query 

    if (isset($_POST["submit"]))
{
    // update the FAQ in database
    $updatesql = "UPDATE add_faq SET question = '".$_POST["question"]."', answer = '".$_POST["answer"]."' WHERE id = '".$_REQUEST["id"]."'";
    $result = mysqli_query($conn, $updatesql);
   
 
    // redirect back to previous page
   if($result){
   	  header("Location: viewfaq.php?Success=updated-faq");
   }
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

				<!-- Page content-->
				<div class="container-fluid faq_edit content__up">

            <h1>Edit FAQ</h1>
            <div class="back_btn_wrap ">
						<a href="viewfaq.php" type="button" class="btn btn-primary">Back</a>
					</div>
            <div class="form_h">
            <!-- form to edit FAQ -->
            <form method="POST" action="#">
 
                <!-- hidden ID field of FAQ -->
                <input type="hidden" name="id" value="<?php echo $faq['id']; ?>" required />
 
                <!-- question, auto-populate -->
                <div class="form-group">
                    <label>Enter Question</label>
                    <input type="text" name="question" class="form-control" value="<?php echo $faq['question']; ?>" required />
                </div>
 
                <!-- answer, auto-populate -->
                <div class="form-group">
                    <label>Enter Answer</label>
                    <textarea name="answer" id="answer" class="form-control" required><?php echo $faq['answer']; ?></textarea>
                </div>
 
                <!-- submit button -->
                <div class="form_h_submit">
                <input type="submit" name="submit" class="btn btn-primary" value="Edit FAQ" />
</div>
            </form>
            </div>
</div>
        


			</div>
		</div>

	</body>
</html>
