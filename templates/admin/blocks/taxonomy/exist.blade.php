@foreach($taxonomies as $taxonomy)
	<div class="form-group row item-type taxonomy-row" data-name="{{ $taxonomy['name'] }}">
		<label for="" class="col-sm-3 col-form-label">
			<span class="text-label">{{ $taxonomy['label'] }}</span>
		</label>
		<div class="col-sm-9">
			<select class="form-control taxonomy-multiple-select" name="dfis[{{ $dfi_index }}][taxonomy][{{ $taxonomy['name'] }}][]" multiple="multiple">
				@foreach(wpdfi()->term->get($taxonomy['name']) as $term) 
					<!-- 
						Check if this taxonomy has exist value.
						If yes, update selected option for this taxonomy.
					-->
					@if(isset($exist_values[$taxonomy['name']]) and in_array($term['id'], $exist_values[$taxonomy['name']])) 
						<option value="{{ $term['id'] }}" selected="selected">{{ $term['text'] }}</option>
					@else 
					<option value="{{ $term['id'] }}">{{ $term['text'] }}</option>
					@endif
				@endforeach
			</select>
		</div>
	</div>
@endforeach