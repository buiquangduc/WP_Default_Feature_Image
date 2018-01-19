@foreach($taxonomies as $taxonomy)
	<div class="form-group row item-type taxonomy-row" data-name="{{ $taxonomy['name'] }}">
		<label for="" class="col-sm-3 col-form-label">
			<span class="text-label">{{ $taxonomy['label'] }}</span>
		</label>
		<div class="col-sm-9">
			<select class="form-control taxonomy-multiple-select" name="dfis[{{ $dfi_index }}][taxonomy][{{ $taxonomy['name'] }}][]" multiple="multiple">
				@foreach(\wpdfi()->term->get($taxonomy['name']) as $term)
					<option value="{{ $term['id'] }}">{{ $term['text'] }}</option>
				@endforeach
			</select>
		</div>
	</div>
@endforeach