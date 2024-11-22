@php
    $children = $item->children();
    $isChildren = $children->count() > 0;
    $url = $item->url();
@endphp
@if ($isChildren)
    <li class="nav-item dropdown " wire:key="menu-{{ $item->key }}" menu-sort="{{ $item->sort }}">
        <a class="nav-link dropdown-toggle {{ $item->classItem }}" href="{{ $url }}" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" role="button" aria-expanded="false">
            @if ($item->icon)
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    {!! $item->getIcon() !!}
                </span>
            @endif
            <span class="nav-link-title">
                {{ $item->title }}
            </span>
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            @foreach ($children as $child)
                {!! $child->render($level + 1) !!}
            @endforeach
        </div>
    </li>
@else
    <li wire:key="menu-{{ $item->key }}" class="nav-item" menu-sort="{{ $item->sort }}">
        <a class="nav-link {{ $item->classItem }}" href="{{ $url }}" wire:navigate.hover>
            @if ($item->icon)
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    {!! $item->getIcon() !!}
                </span>
            @endif
            <span class="nav-link-title">
                {{ $item->title }}
            </span>
        </a>
    </li>
@endif
