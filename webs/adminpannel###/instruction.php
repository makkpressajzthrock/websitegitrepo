<?php
require_once('config.php');
require_once('inc/functions.php');
require_once('smtp/PHPMailerAutoload.php');
require_once('dompdf/autoload.inc.php'); // Include autoloader 

ob_clean();

$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}





?>
<?php require_once("inc/style-and-script.php"); ?>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>


</head>

<body class="custom-tabel">
 
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid script_I content__up">
				<h1 class="mt-4">Instruction</h1>
				<?php require_once("inc/alert-status.php"); ?>
 
				<div class="script_Icontainer">
					<div class="paltform">
						<h2>Install Website Speedy On Shopify:</h2>
						<p>Setup instructions:</p>
						<ol>
							<li>
								<span>Go to Shopify Admin > Online Store > Themes.</span>
								<img src="/adminpannel/img/shopify-inst/1.png" alt="">
							</li>
							<li>
								<span>Find your theme and click on "Edit code".</span>
								<img src="/adminpannel/img/shopify-inst/2.png" alt="">
							</li>
							<li>
								<span>Open the "theme.liquid" file.</span>
								<img src="/adminpannel/img/shopify-inst/3.png" alt="">
							</li>
							<li>
								<span>Add the script to the <code>&lt;head&gt;</code> section after any <code>&lt;meta&gt;</code> tags.</span>
								<img src="/adminpannel/img/shopify-inst/4.png" alt="">
							</li>
							<li>
								<span>Save and enjoy the website speedy!</span>
							</li>
								
						</ol>
					</div>

					<div class="paltform">
						<h2>Install Website Speedy On Big Commerce:</h2>
						<p>Setup instructions:</p>
						<ol>
							<li>
								<span>
									Log in to your BigCommerce store's admin panel.
								</span>
								<img src="/adminpannel/img/bigcommerce-inst/1.png" alt="">
							</li>
							<li>
								<span>Go to "Storefront" from the left-hand menu and select "Theme".</span>
								<img src="/adminpannel/img/bigcommerce-inst/2.png" alt="">
							</li>
							<li>
								<span>Click "Advance" then "Edit theme file".</span>
								<img src="/adminpannel/img/bigcommerce-inst/3.png" alt="">
							</li>
							<li>
								<span>In the left-hand menu, go to "Templates" > "Layout" > "base.html".</span>
								<img src="/adminpannel/img/bigcommerce-inst/4.png" alt="">
							</li>
							<li>
								<span>Add your script to the <code>&lt;head&gt;</code> section after any <code>&lt;meta&gt;</code> tags.</span>
								<img src="/adminpannel/img/bigcommerce-inst/5.png" alt="">
							</li>	
							<li>
								<span>Click Save and enjoy the website speedy!</span>
							</li>							
						</ol>
					</div>


					<div class="paltform">
						<h2>Install Website Speedy On WordPress:</h2>
						<p>Setup instructions:</p>
						<ol>
							<li>
								<span>Log in to cPanel and navigate to the File Manager.</span>

							</li>
							<li>
								<span>Open the public_html folder and find the wp-content folder.</span>

							</li>
							<li>
								<span>Open the themes folder and then select the theme you're using.</span>
							</li>
							<li>
								<span>Locate the header.php file and right-click on it.</span>
							</li>
							<li>
								<span>Add the script after any <code>&lt;meta&gt;</code> tags.</span>
							</li>
							<li>
								<span>Click "Save Changes" to save the file.</span>

							</li>
								
						</ol>
					</div>

					<div class="paltform">
						<h2>Install Website Speedy On Shift4Shop:</h2>
						<p>Setup instructions:</p>
						<ol>
							<li>
								<span>Log in to your Shift4Shop account and navigate to the "Design" tab.</span>
							</li>
							<li>
								<span>Click on "Edit HTML/CSS" under the "Theme" section.</span>

							</li>
							<li>
								<span>Select "Edit Template" and choose frame.html to add your code.</span>
								
							</li>
							<li>
								<span>Add the script after any <code>&lt;meta&gt;</code> tags.</span>
							</li>
							<li>
								<span>Click "Save" to save the changes.</span>
							</li>								
						</ol>
					</div>

					

				</div>
			</div>
		</div>
	</div>
</body>

</html>
