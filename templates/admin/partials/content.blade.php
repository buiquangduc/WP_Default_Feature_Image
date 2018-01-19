<form method="POST" action="<?php \admin_url( 'options-general.php?page=wpdfi-settings.php' ); ?>" id="wpdfi-form">
	<div class="error-wrapper" id="error_wrapper"></div>
	<?php wp_nonce_field( "wpdfi-settings-page" ); ?>
	<div class="wpdfi-content">
		<div class="wpdfi-content-inner clearfix">
			@if($current_tab == 'sections')
			<div class="wpdfi-main-content">
				<div class="postbox">
					<h2 class="postbox-heading">Sections</h2>
					<div class="inside">
						<div class="option-group list-group" id="section_wrapper">
							@include("admin.blocks.tabs.{$current_tab}.{$layout_name}", ['options' => $options])
						</div>					
						@if($current_tab == 'sections')
						<div class="text-right">
							<a class="button button-primary" id="add_section_button" href="#">Add Row</a>
						</div>
						@endif
					</div>
				</div>
			</div>
			<section class="submit wpdfi-sidebar">
				<div class="wpdfi-box">
					<h2 class="wpdfi-box-heading">Settings</h2>
					<div class="wpdfi-box-content">
						<p>Settings</p>
					</div>
					<div class="wpdfi-box-footer">
						<button class="button button-primary" id="save_form_button">Save</button>
					</div>
				</div>
			</section>
			@else
			<div class="option-group list-group" id="section_wrapper">
				@include("admin.blocks.tabs.{$current_tab}.{$layout_name}", ['options' => $options])
			</div>
			<section class="submit">
				@if($current_tab == 'sections')
				<a class="btn-add" id="add_section_button" href="#">+</a><br>
				@endif
				<button class="button button-primary" id="save_form_button">Save</button>
			</section>
			@endif			
		</div>
	</div>
</form>