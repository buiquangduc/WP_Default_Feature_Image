<div class="wrapper-upload form-group row item-type image-upload-row">
	<label for="" class="col-sm-3 col-form-label">
		<span class="text-label"><?php echo __('Choose Your Feature Image', 'wpdfi'); ?></span>
	</label>
	<div class="col-sm-9">
		<input type="button" class="upload-btn" value="Upload Image">
		<div class="wrapper-image" style="display: none;">
			<img class="image" style="width: 150px"></img>
			<input type="hidden" name="dfis[{{ $dfi_index }}][image_id]" class="image-input-id">
			<input type="hidden" name="dfis[{{ $dfi_index }}][image_source]" class="image-input-source">
		</div>
	</div>
</div>