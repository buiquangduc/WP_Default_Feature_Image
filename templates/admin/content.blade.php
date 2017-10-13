<form method="post" action="<?php \admin_url( 'options-general.php?page=wpdt-settings.php' ); ?>">
	<?php wp_nonce_field( "wpdt-settings-page" ); ?>
	@include("admin.tabs.{$current_tab}")
	<p class="submit" style="clear: both;">
	  	<input type="submit" class="button-primary" value="Update Settings" />
	</p>
</form>