<div class="option-wrapper">
	<label>{{ __('Status of a post to add default feature image:', 'wpdfi') }}</label>
	<select class="regular-select" name="options[status_for_update]">
		<!-- Display all status option for user to choose -->
		@foreach(wpdfi()->post_type->get_all_statuses() as $status_id => $status_label)
		<!-- Default option will be  -->
		@if($status_id == 'publish')
		<option value="{{ $status_id }}" selected="selected">{{ $status_label }}</option>
		@else
		<option value="{{ $status_id }}">{{ $status_label }}</option>
		@endif
		@endforeach
	</select>
</div>

@php
$post_types_details = wpdfi()->post_type->get_pt_details_fl_settings();
$post_no_fimage_ids_json = json_encode(wpdfi()->post_type->get_posts_no_fimage_id());
$json_valid = ($post_no_fimage_ids_json != '[]');
@endphp
<div id="generate_fimage_wrapper" @if($json_valid) data-post_no_fimage_ids="{{ $post_no_fimage_ids_json }}" @endif
data-nonce="{{ wp_create_nonce('wpdfi-ajax-nonce') }}">
<button class="button" id="generate_fimage_button" @if(!$json_valid) disabled @endif>{{ __('Generate Feature Image with values from "DFIs" tab', 'wpdfi') }}</button>
<div class="progress-wrapper" id="generate_fimage_progressbar"></div>
</div>

<p class="description generate-fimage-information">
	@if($post_types_details)
	<p>{{ __('Your site has (only include post types which are set from "DFIs" tab):', 'wpdfi') }}</p>
	@foreach($post_types_details as $pt_id => $number_posts_no_fimage)
	@php($post_type_name = wpdfi()->post_type->get_singular_name($pt_id))
	<p>{{ $number_posts_no_fimage }}{{ __(' post/posts with no feature image in ', 'wpdfi') }}{{ $post_type_name }}{{ ' post type', 'wpdfi' }}</p>
	@endforeach
	@else
	{{ __('There is no post without feature image or you need to set a default feature image first.', 'wpdfi') }}
	@endif
</p>
<div class="generate-fimage-result"></div>
