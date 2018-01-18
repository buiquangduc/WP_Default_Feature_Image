<table class="form-table">
	<tbody>
		<tr>
			<th>{{ __('Status of a post to add default feature image:', 'wpdfi') }}</th>
			<td>
				<select class="regular-select" name="options[status_for_update]">
					<!-- Display all status option for user to choose -->
					@foreach(wpdfi()->post_type->get_all_statuses() as $status_id => $status_label)
						<!-- Default option will be  -->
						@if($status_id == $options['options']['status_for_update'])
							<option value="{{ $status_id }}" selected="selected">{{ $status_label }}</option>
						@else
							<option value="{{ $status_id }}">{{ $status_label }}</option>
						@endif
					@endforeach
				</select>
			</td>
		</tr>
	</tbody>
</table>
@php($post_types_details = wpdfi()->post_type->get_pt_details_fl_settings())
@php($post_no_fimage_ids_json = json_encode(wpdfi()->post_type->get_posts_no_fimage_id()))
<div id="generate_fimage_wrapper" @if($post_types_details) data-post_no_fimage_ids="{{ $post_no_fimage_ids_json }}" @endif
data-nonce="{{ wp_create_nonce('wpdfi-ajax-nonce') }}">
	<button id="generate_fimage_button" @if(!$post_types_details) disabled @endif>{{ __('Generate Feature Image with values from "Sections" tab', 'wpdfi') }}</button>
	<div id="generate_fimage_progressbar"></div>
</div>
<p class="description generate-fimage-information">
	@if($post_types_details)
		<p>{{ __('Your site has (only include post types which are set from "Sections" tab):', 'wpdfi') }}</p>
		@foreach($post_types_details as $pt_id => $number_posts_no_fimage)
			@php($post_type_name = wpdfi()->post_type->get_singular_name($pt_id))
			<p>{{ $number_posts_no_fimage }}{{ __(' post/posts with no feature image in ', 'wpdfi') }}{{ $post_type_name }}{{ ' post type', 'wpdfi' }}</p>
		@endforeach
	@else
		{{ __('There is no post without feature image founded or you need to section a section first.', 'wpdfi') }}
	@endif
</p>
