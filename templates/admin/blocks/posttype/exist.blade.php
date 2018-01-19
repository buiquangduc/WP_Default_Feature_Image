<div class="form-group row post-type-row">
	<label for="" class="col-sm-3 col-form-label">
		<span class="text-label"><?php echo __('Post Type', 'wpdfi'); ?></span>
		<span class="icon-tootip" data-toggle="tooltip" data-placement="top" title="Choose your post type which support thumbnail feature"><i class="fa fa-question-circle-o" aria-hidden="true"></i></span>
	</label>
	<div class="col-sm-9">
		<select class="custom-select post-types-select" name="dfis[{{ $dfi_index }}][post_type]">
			<option value=""><?php echo __('Please choose your Post Type', 'wpdfi'); ?>
			@foreach(wpdfi()->post_type->get_id_and_text() as $post_type)
				<!-- 
					Update selected option for this Post Type.
				-->
				@if($post_type['id'] == $id)
					<option value="{{ $post_type['id'] }}" selected="selected">{{ $post_type['text'] }}</option>
				@else
					<option value="{{ $post_type['id'] }}">{{ $post_type['text'] }}</option>
				@endif
			@endforeach
		</select><!-- #post-type -->
	</div>
</div><!-- .form-group -->