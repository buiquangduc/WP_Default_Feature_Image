<form method="POST" action="<?php \admin_url( 'options-general.php?page=wpdfi-settings.php' ); ?>" id="wpdfi-form">
	<div class="error-wrapper" id="error_wrapper"></div>
	<?php wp_nonce_field( "wpdfi-settings-page" ); ?>
	<div class="container">
		<div class="option-container">
			<div class="option-group" id="section_wrapper" class="list-group">
				@include("admin.blocks.tabs.{$current_tab}.{$layout_name}", ['options' => $options])
			</div>
			<section class="option-footer text-center">
				@if($current_tab == 'sections')
				<a class="btn-add" id="add_section_button" href="#">+</a><br>
				@endif
				<button class="btn btn-primary" id="save_form_button">Save</button>
			</section>
		</div>
	</div>
</form>