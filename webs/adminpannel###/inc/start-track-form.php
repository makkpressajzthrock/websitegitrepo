
<div class="col-md-12">
<?php 
	if($web_data["script_installed"] == 1 ) {
		?>
		<form>
			<div class="form-group">
				<label class="form-check-label mb-2 ml-4"><input type="checkbox" class="form-check-input" checked disabled>I have completed the all installation process.</label>
		  	</div>
		</form>
		<?php
	} 
	else {
		?>
		<form method="POST" id="start-track-form">
			<input type="hidden" name="website" value="<?=$web_data["id"];?>">
			<div class="form-group">
				<label class="form-check-label mb-2 ml-4" for="complete-installation"><input type="checkbox" class="form-check-input" id="complete-installation" name="complete-installation" value="1">I have completed the all installation process.</label>
		  	</div>
		  	<button type="submit" name="start-track" value="start-track" class="btn btn-success" disabled>Start Tracking</button>
		</form>
		<?php
	}
?>
</div>