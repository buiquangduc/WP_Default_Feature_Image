<table class="form-table">
	<tbody>
		<tr>
			<th><?php echo __('Status of a post to add default feature image:', 'wpdfi'); ?></th>
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
