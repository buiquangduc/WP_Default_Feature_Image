@empty($section_index)
<?php $section_index = 1; ?> 
@endempty
<section class="item-option list-group-item" id="item-option-origin-{{ $section_index }}" data-index="{{ $section_index }}">
	<p class="section-label">Section {{ $section_index }}</p>
	@include('admin.blocks.posttype.default')
	@if(isset($include_delete) and $include_delete == true)
		<a href="#" class="btn-remove">-</a>
	@endif
</section><!--  .item-option -->
