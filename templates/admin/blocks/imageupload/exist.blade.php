<div class="wrapper-upload form-group row item-type image-upload-row">
	<label for="" class="col-sm-3 col-form-label">
		<span class="text-label"><?php echo __('Choose Your Feature Image', 'wpdfi'); ?></span>
	</label>
	<div class="col-sm-9">
		<input type="button" name="upload-btn" class="upload-btn" value="Upload Image">
		<div class="wrapper-image">
			<img class="image" style="width: 150px" src="{{ $image_source }}"></img>
			<input type="hidden" name="dfis[{{ $dfi_index }}][image_id]" value="{{ $image_id }}" class="image-input-id">
			<input type="hidden" name="dfis[{{ $dfi_index }}][image_source]" value="{{ $image_source }}" class="image-input-source">
		</div>
	</div>
</div>