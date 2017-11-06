<div class="form-group row item-type image-size-row">
	<label><?php echo __('Feature Image Size:', 'wpdfi'); ?></label>
	<select name="image_size" class="image-size-select">		
		@foreach($sizes as $name => $dimension)
			<option value="{{ $name }}">{{ $dimension['width'] }}x{{ $dimension['height'] }}</option>
		@endforeach
	</select>
</div>