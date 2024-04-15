<div class="alert-status-side">
<?php

	if ( !empty($_SESSION['side_message']) ) {
		echo '<div class="side_message">'.$_SESSION['side_message'].'</div>' ;
		// unset($_SESSION['side_message']) ;
	}
 
?>
</div>