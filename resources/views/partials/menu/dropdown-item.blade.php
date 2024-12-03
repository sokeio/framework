@php
    $children = $item->children();
    $isChildren = $children->count() > 0;
    $url = $item->url();
@endphp
@if ($isChildren)
    <div class="dropend @if ($item->isActive()) menu-active @endif">
        <a class="dropdown-item dropdown-toggle {{ $item->classItem }}" href="{{ $url }}"
            data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            @if ($item->icon)
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    {!! $item->getIcon() !!}
                </span>
            @endif
            <span class="nav-link-title">
                {{ $item->title }}
            </span>
        </a>
        <div class="dropdown-menu">
            @foreach ($children as $child)
                {!! $child->render($level + 1) !!}
            @endforeach
        </div>
    </div>
@else
    <a class="dropdown-item {{ $item->classItem }} @if ($item->isActive()) active @endif"
        href="{{ $url }}" wire:navigate.hover>
        @if ($item->icon && setting('SOKEIO_SHOW_ICON_IN_SUBMENU', true))
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                {!! $item->getIcon() !!}
            </span>
        @endif
        <span class="nav-link-title">
            {{ $item->title }}
        </span>
    </a>
@endif
