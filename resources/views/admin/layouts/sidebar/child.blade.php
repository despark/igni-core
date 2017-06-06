<ul class="treeview-menu">
    @foreach($sidebarItems as $sidebarItem )
        <li class="{{ ($sidebarItem->isActive()) ? 'active' : '' }}">
            <a href="{{ $sidebarItem->getHref() }}">{{ $sidebarItem->getName() }}</a>
        </li>
    @endforeach
</ul>