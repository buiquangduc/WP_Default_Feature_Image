@empty($dfi_index)
<?php $dfi_index = 1; ?> 
@endempty
<section class="item-option list-group-item" id="item-option-origin-{{ $dfi_index }}" data-index="{{ $dfi_index }}">
	<p class="dfi-label">DFI {{ $dfi_index }}</p>
	@include('admin.blocks.posttype.default')
	@if(isset($include_delete) and $include_delete == true)
		<a href="#" class="btn-remove">-</a>
	@endif
</section><!--  .item-option -->
