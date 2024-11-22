@php
    $children = $item->children();
    $isChildren = $children->count() > 0;
    $url = $item->url();
@endphp
@if ($isChildren)
    <div class="dropend">
        <a class="dropdown-item dropdown-toggle {{ $item->classItem }}" href="{{ $url }}"
            data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            @if ($item->icon)
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    {!! $item->getIcon() !!}
                </span>
            @endif
            {{ $item->title }}
        </a>
        <div class="dropdown-menu">
            @foreach ($children as $child)
                {!! $child->render($level + 1) !!}
            @endforeach
        </div>
    </div>
@else
    <a class="dropdown-item {{ $item->classItem }}" href="{{ $url }}" wire:navigate.hover>
        {{ $item->title }}
    </a>
@endif
