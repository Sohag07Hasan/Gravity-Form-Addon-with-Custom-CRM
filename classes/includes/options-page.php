<div class="wrap">
	<h2>CRM Options</h2>
	
	<?php
		if($_POST['Crm_saved'] == 'Y'){
			echo "<div class='updated'><p>saved</p></div>";
		}
	?>
	
	<form action="" method="post">
		<input type="hidden" name="Crm_saved" value="Y" />
		<table class="form-table">
			<tr>
				<td>CRM URL</td>
				<td cospan="2"><input size="60" type="text" name="crm_url" value="<?php echo self::get_crm_url(); ?>" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="save" class="button-primary"  /></td>
			</tr>
		</table>
	</form>
</div>
