<?php
 include('config.php');
include('session.php');
require_once('inc/functions.php');
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);
	$manager_id = $_POST['manager_id'];
	$remove_id = $_POST['remove_id'];
	$remove__id=rtrim($remove_id, ",");
// echo	$remove__id;

	if(isset($manager_id)){



											
												$website_url = getTableData($conn, " boost_website ", " manager_id ='" . $manager_id . "' AND id NOT IN (".$remove__id.") ", "", 1);

												
												foreach ($website_url as $key => $run) {
													// code...




												?>
													<div class="col-md-4 p-1 pt-1">
														<div class="card">
															<div class="body-card">
																<div class="form-check" id="group1" required>

																	<input style="width: 8%;"  class="form-check-input position-static" type="checkbox" name="website" value="<?php echo $run['id']; ?>">
																	<h4><?php echo $run['website_url']; ?></h4>


																</div>

																<div class="form-check">
																	<input style="width: 8%;" class="form-check-input position-static" type="checkbox" class="group1" name="dashboard" value="1">Dashboard
																</div>

																<div class="form-check">
																	<input style="width: 8%;" class="form-check-input position-static" type="checkbox" class="group1" name="exhelp" value="1">Expert Help
																</div>
																<div class="form-check">
																	<input style="width: 8%;" class="form-check-input position-static" type="checkbox" class="group1" name="pgsped" value="1">Page Speed
																</div>
																<div class="form-check">
																	<input style="width: 8%;" class="form-check-input position-static" type="checkbox" class="group1" name="scinst" value="1"> script Instalattion
																</div>
																<div class="form-check">
																	<input style="width: 8%;" class="form-check-input position-static" type="checkbox" class="group1" name="nohelp" value="1">Need Other Help
																</div>

															</div>
														</div>
													</div>
												
												<?php }
												if (count($website_url)>0){ ?>
								<button type="submit" name="submit_url" class="btn btn-primary text-center mt-1">Update</button>

												
<?php
	} }
?>