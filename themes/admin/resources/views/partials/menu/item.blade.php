@php
    $children = $item->children();
    $isChildren = $children->count() > 0;
@endphp
@if ($isChildren)
    <li class="nav-item dropdown " wire:key="menu-{{ $item->key }}">
        <a class="nav-link dropdown-toggle" href="{{ $item->url() }}" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" role="button" aria-expanded="false">
            @if ($item->icon)
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    {!! $item->icon() !!}
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
    <li class="nav-item" wire:key="menu-{{ $item->key }}">
        <a class="nav-link " href="{{ $item->url() }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                    <path d="M12 12l8 -4.5"></path>
                    <path d="M12 12l0 9"></path>
                    <path d="M12 12l-8 -4.5"></path>
                    <path d="M16 5.25l-8 4.5"></path>
                </svg>
            </span>
            <span class="nav-link-title">
                {{ $item->title }}
            </span>
        </a>
    </li>
@endif
