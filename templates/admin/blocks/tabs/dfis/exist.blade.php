@foreach($options['dfis'] as $dfi_index => $option)
	<section class="item-option list-group-item" id="item-option-origin-{{ $dfi_index }}" data-index="{{ $dfi_index }}">
		<p class="dfi-label">DFI {{ $dfi_index }}</p>
		<!-- Include post type. -->
		@include('admin.blocks.posttype.exist', [
			'id' => $option['post_type'], 'text' => wpdfi()->post_type->get_singular_name($option['post_type'])
		])
		<!-- Include taxonomies. -->
		<!--  
			Check if isset exist values for these taxonomies.
			If yes, pass exist values to the taxonomies template.
		-->
		@isset($option['taxonomy'])
			@include('admin.blocks.taxonomy.exist', ['taxonomies'=> wpdfi()->taxonomy->get($option['post_type']), 'exist_values' => $option['taxonomy']])
		@else
			@include('admin.blocks.taxonomy.exist', ['taxonomies'=> wpdfi()->taxonomy->get($option['post_type'])])
		@endisset
		<!-- Include image upload -->
		@include('admin.blocks.imageupload.exist', ['image_id' => $option['image_id'], 'image_source' => $option['image_source']])
		<!-- Include delete button -->
		<!-- 
			Check if this dfi is not the first dfi 
			If yes, include the delete button
		-->
		@if($dfi_index != 1) 
			<a href="#" class="btn-remove">-</a>
		@endif
		<!-- @include('admin.blocks.imagesize.exist', ['image_size' => $option['image_size']]) -->
	</section><!--  .item-option -->
@endforeach
