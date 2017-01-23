@if(!$sidebarItem->hasParent())
    <li class="treeview {{ ($sidebarItem->isActive()) ? 'active' : '' }}">
        <a href="{{ $sidebarItem->getHref() }}">
            <i class=" fa {{ $sidebarItem->getIconClass() }}"></i>
            <span>{{ $sidebarItem->getName() }}</span>

            @if($sidebarItem->hasChildren())
                <i class="fa fa-angle-left pull-right"></i>
            @endif
        </a>
        @if($sidebarItem->hasChildren())
            @include($sidebarItem->getChildTemplate(), ['sidebarItems' => $sidebarItem->getChildren()])
        @endif
    </li>
@endif