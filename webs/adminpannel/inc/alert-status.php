<div class="alert-status">
<?php

	if ( !empty($_SESSION['error']) ) {
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$_SESSION['error'].'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' ;
		unset($_SESSION['error']) ;
	}

	if ( !empty($_SESSION['success']) ) {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$_SESSION['success'].'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' ;
		unset($_SESSION['success']) ;
	}

	if ( !empty($_SESSION['success_']) ) {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$_SESSION['success_'].'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' ;
		unset($_SESSION['success_']) ;
	}	

	if ( !empty($_SESSION['error_']) ) {
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$_SESSION['error_'].'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' ;
		unset($_SESSION['error_']) ;
	}

?>
</div>