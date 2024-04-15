<?php
	include('config.php');
	include('session.php');
	if (isset($_POST['plan'])) 
	{
	   	$id = $_POST['plan'];
	   	$name = $_POST['name'];
	  	$price = $_POST['price'];

	   	$plan_name = "";
	   	if ($id == 1) 
	   	{
	   		$plan_name = "Silver";
	   	}
	   	elseif ($id == 2) 
	   	{
	   		$plan_name = "Gold";
	   	}
	   	elseif ($id == 3) 
	   	{
	   		$plan_name = "Diamond";
	   	}
	   	else
	   	{
	   		$plan_name = "Pro";
	   	}
	   	$edit = "UPDATE plan_price SET name = '$name', price = '$price' WHERE id = '$id'";
	   	$update = mysqli_query($conn,$edit);

	   	if ($update) 
	   	{
	   		$selected = "SELECT * FROM discount";
			$result = mysqli_query($conn,$selected);
			while ($data_row = mysqli_fetch_assoc($result)) 
			{
				// ******************For Monthly Interval*****************
				if ($data_row['id'] == 1) 
				{
					$amount = ($price * 1) - 0;
				  	$edit_plans = "UPDATE `plans` SET `price` = '$amount', `s_price` = '$amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '1 Website' AND `interval` = 'month'";
	   				$update_plans = mysqli_query($conn,$edit_plans);
	   				if ($update_plans) 
	   				{
 						$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
 						$_SESSION['error'] = "Something went wrong.";
	   				}
				}
				elseif ($data_row['id'] == 2) 
				{
					$web_count  = $price * 2;
					$percent = 5/100 * $web_count;
					$amount = $web_count - $percent;
					$edit_plans = "UPDATE `plans` SET `price` = '$amount', `s_price` = '$amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '2 Websites' AND `interval` = 'month'";
	   				$update_plans = mysqli_query($conn,$edit_plans);
	   				if ($update_plans) 
	   				{
	   					$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
	   					$_SESSION['error'] = "Something went wrong.";
	   				}
				}
				elseif ($data_row['id'] == 3) 
				{
					$web_count  = $price * 3;
					$percent = 10/100 * $web_count;
					$amount = $web_count - $percent;
					$edit_plans = "UPDATE `plans` SET `price` = '$amount', `s_price` = '$amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '3+ Websites' AND `interval` = 'month'";
	   				$update_plans = mysqli_query($conn,$edit_plans);
	   				if ($update_plans) 
	   				{
	   					$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
	   					$_SESSION['error'] = "Something went wrong.";
	   				}
				}


				// ******************For Yearly Interval*****************
				if ($data_row['id'] == 1) 
				{
					$amount = ($price * 1) - 0;
					$y_amount = $amount * 10;
					$edit_y_plans = "UPDATE `plans` SET `price` = '$y_amount', `s_price` = '$y_amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '1 Website' AND `interval` = 'year'";
	   				$update_y_plans = mysqli_query($conn,$edit_y_plans);
	   				if ($update_y_plans) 
	   				{
	   					$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
	   					$_SESSION['error'] = "Something went wrong.";
	   				}
				}
				elseif ($data_row['id'] == 2) 
				{
					$web_count  = $price * 2 * 10;
					$percent = 5/100 * $web_count;
					$amount = $web_count - $percent;
					$edit_plans = "UPDATE `plans` SET `price` = '$amount', `s_price` = '$amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '2 Websites' AND `interval` = 'year'";
	   				$update_plans = mysqli_query($conn,$edit_plans);
	   				if ($update_plans) 
	   				{
	   					$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
	   					$_SESSION['error'] = "Something went wrong.";
	   				}
				}
				elseif ($data_row['id'] == 3) 
				{
					$web_count  = $price * 3 * 10;
					$percent = $web_count * 10/100;
					$amount = $web_count - $percent;
					$edit_plans = "UPDATE `plans` SET `price` = '$amount', `s_price` = '$amount' WHERE `name` = '$plan_name' AND `plan_frequency` = '3+ Websites' AND `interval` = 'year'";
	   				$update_plans = mysqli_query($conn,$edit_plans);
	   				if ($update_plans) 
	   				{
	   					$_SESSION['success'] = "Saved";
	   				}
	   				else
	   				{
	   					$_SESSION['error'] = "Something went wrong.";
	   				}
				}
			}
	   	}
	   	else
	   	{
	   		$_SESSION['error'] = "Something went wrong.";
	   	}
	}
?>