<?php
/*
 * increases options to the form
 */
?>

<h4>Create a new Person using fields</h4>
<table cellspacing="5" cellpadding="5">
	<?php
		foreach(self::$gftooltips_default as $key=>$value) :
			$key = 'customcrm_' . $key;
	?>
	
			<tr>
				<td align="right"><?php echo $value[0] ?></td>
				<td><?php echo self::get_field_selector($form_id, $key); ?> <?php gform_tooltip($key) ?></td>
			</tr>
	
	<?php endforeach; ?>
</table>