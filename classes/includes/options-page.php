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
				<td cospan="2"><input size="60" type="text" name="crm_url" value="<?php echo $url_info['crm_url']; ?>" /></td>
			</tr>
			<tr>
				<td>Enable SSL</td>
				<td cospan="2"><input size="60" type="checkbox" name="crm_ssl" value="1"  <?php checked('1', $ssl);?> /></td>
			</tr>
			<tr>
				<td>Username</td>
				<td cospan="2"><input size="60" type="text" name="crm_user" value="<?php echo $url_info['crm_user']; ?>" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td cospan="2"><input size="60" type="text" name="crm_pass" value="<?php echo $url_info['crm_pass']; ?>" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="save" class="button-primary"  /></td>
			</tr>
		</table>
	</form>
</div>
