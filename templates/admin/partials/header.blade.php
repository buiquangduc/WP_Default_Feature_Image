<h2 class="nav-tab-wrapper">
    @foreach( $tabs as $tab => $name )
        <?php $class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';?>
        <a class='nav-tab{{ $class }}' href='?page=wpdt-settings.php&tab={{ $tab }}'>{{ $name }}</a>
    @endforeach
</h2>