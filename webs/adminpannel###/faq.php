<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php');

  // fetch all FAQs from database
  $fetchCode = "SELECT * FROM `add_faq`";

$fetchResult = mysqli_query($conn,$fetchCode);
                        $sno = 0;
                   


 // print_r($num);
 // die(); 

?>
<?php require_once("inc/style-and-script.php") ; ?>
<style>

    .accordion__answer {
            display: none;
        }
        .accordionss.active .accordion__answer {
            display: block;
        }

</style>
    <?php require_once('inc/style-and-script.php') ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
<div class="container-fluid faq_qa content__up">
<h1>Most Asked Questions</h1>
    <div class="profile_tabs">
 
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for FAQ..">
            <div class="panel-group">
                <?php 
                 // $count = 1;
                 while(     $num = mysqli_fetch_assoc($fetchResult)){ ?>
 
                        <!-- button to show the question -->
                       
                        <div class="accordionss">
                        <!-- <div class="panel-heading"> -->

                            <h4 class="accordion__question">
                                <a>
                                    <?php echo $num['question']; ?>
                                </a>
                                <i class="las la-minus fone"></i>
                                <i class="las la-minus ftwo"></i>
                            </h4>
                        <!-- </div> -->
 
                        <!-- accordion for answer -->
                        <div id="faq-<?php echo $num['id']; ?>" >
                            <!-- <div class="panel-body"> -->
                                <div class="accordion__answer">
                                  
                                   <p> <?php echo $num['answer']; ?> </p>
                                </div>
                            <!-- </div> -->
                    </div>
                 </div>
                <?php 
                // $count++; 
                 } ?>
        </div>
    </div>
</div>
						

						</div>


					</div>
<script>
        let answers=document.querySelectorAll(".accordionss");
        answers.forEach((event)=>{
            event.addEventListener('click',()=>{
                if(event.classList.contains("active")){
                    event.classList.remove("active");
                }
                else{
                    event.classList.add("active");
                }
            })
        })
    </script>
    <script>
        jQuery("#myInput").keyup(function(){
let input = document.getElementById('myInput').value
input=input.toLowerCase();
let x = jQuery(".accordionss");

for (i = 0; i < x.length; i++) {
if (!x[i].innerHTML.toLowerCase().includes(input)) {
x[i].style.display="none";
}
else {
x[i].style.display="block";}
}
});
    </script>
	</body>
</html>