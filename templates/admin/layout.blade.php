<div class="wrap wpdfi-wrap">
@include('admin.partials.head')
@include('admin.partials.header', ['tabs' => $tabs, 'current_tab' => $current_tab])
@include('admin.partials.content', ['current_tab' => $current_tab, 'options' => $options, 'layout_name' => $layout_name])
</div>
@include('admin.partials.footer')