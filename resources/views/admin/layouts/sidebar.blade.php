<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            @foreach($sidebarItems as $sidebarItem)
                <li class="treeview {{ ($sidebarItem['isActive']) ? 'active' : '' }}">
                    <a href="{{ $sidebarItem['link'] !== '#' ? route($sidebarItem['link']) : '#' }}">
                        <i class="fa {{ $sidebarItem['iconClass'] }}"></i>
                        <span>{{ $sidebarItem['name'] }}</span>
                        @if(isset($sidebarItem['subMenu']))
                            <i class="fa fa-angle-left pull-right"></i>
                        @endif
                    </a>
                    @if(isset($sidebarItem['subMenu']))
                        <ul class="treeview-menu">
                            @foreach($sidebarItem['subMenu'] as $sidebarSubItems=>$sidebarSubItem )
                                <li class="{{ ($sidebarSubItem['isActive']) ? 'active' : '' }}">
                                    <a href="{{ $sidebarSubItem['link'] !== '#' ? route($sidebarSubItem['link']) : '#' }}">{{ $sidebarSubItem['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
