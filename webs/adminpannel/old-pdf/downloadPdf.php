<?php
	ob_start();
	ob_clean();
	include('config.php');

	include('pdf.php');

	$id = base64_decode($_GET['id']);
	$userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '5'";
	$user_data = mysqli_query($conn,$userSubscription);
	$userData=mysqli_fetch_assoc($user_data);
	$userName = $userData['firstname'].$userData['lastname'];
	$userEmail = $userData['email'];
	$paymentMethod = $userData['payment_method'];
	$paidAmount = $userData['paid_amount'];
	$startPlan = $userData['plan_period_start'];
	$endPlan = $userData['plan_period_end'];
	$currentDate = date("Y-m-d");

	if ($currentDate >= $userData['plan_period_end']) 
	{	
		$status = "Expired";
	}
	else
	{
		$status = "Active";
	}

	$pdfData = "<table>
			<tr>
				<td>User Name</td>
				<td>User Email</td>
				<td>Payment Method</td>
				<td>Paid Amount</td>
				<td>Start Plan Period</td>
				<td>End Plan Period</td>
				<td>Plan Status</td>
			</tr>
			<tr>
				<td>"."$userName"."</td>
				<td>"."$userEmail"."</td>
				<td>"."$paymentMethod"."</td>
				<td>"."$paidAmount"."</td>
				<td>"."$startPlan"."</td>
				<td>"."$endPlan"."</td>
				<td>"."$status"."</td>
			</tr>
	</table>";
	// echo $pdfData;
	$file_name = 'pdf.pdf';

	$pdf->set_option('isRemoteEnabled', TRUE);

	$pdf->load_html($pdfData);
	$pdf->render();
	$file = $pdf->output();
  file_put_contents($file_name, $file);
?>
			