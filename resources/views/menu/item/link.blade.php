@php
    $link = $link ?? '#';
    $info = $item->getValueInfo();
@endphp
@if ($item->getParent()->checkSub())
    @if ($item->checkSubMenu())
        <div id="{{ $item->getId() }}" class="dropend" data-sort="{{ $item->getValueSort() }}">
            <a class="dropdown-item dropdown-toggle flex-column {{ $item->getValueContentColor() }}"
                href="{{ $link }}" role="button">
                <div class="w-100 d-flex align-items-center">
                    @if ($icon = $item->getValueIcon())
                        <span class="nav-link-icon d-flex align-items-center">
                            <i class="{{ $icon }} fs-2 {{ $item->getValueContentColor() }}"></i>
                        </span>
                    @endif
                    {{ $item->getValueText() }}
                </div>
                @if ($info)
                    <div class="w-100 menu-item-info">{{ $info }}</div>
                @endif
            </a>
            {!! $item->getSubMenu()->toHtml() !!}
        </div>
    @else
        <a wire:navigate id="{{ $item->getId() }}" class="dropdown-item flex-column {{ $item->getValueContentColor() }}"
            href="{{ $link }}" data-sort="{{ $item->getValueSort() }}">
            <div class="w-100 d-flex align-items-center">
                @if ($icon = $item->getValueIcon())
                    <span class="nav-link-icon d-flex align-items-center">
                        <i class="{{ $icon }} fs-2 {{ $item->getValueContentColor() }}"></i>
                    </span>
                @endif
                {{ $item->getValueText() }}
            </div>
            @if ($info)
                <div class="w-100 menu-item-info">{{ $info }}</div>
            @endif
        </a>
    @endif
@else
    @if ($item->checkSubMenu())
        <li id="{{ $item->getId() }}" class="nav-item dropdown" data-sort="{{ $item->getValueSort() }}">
            <a class="nav-link dropdown-toggle{{ $item->getValueContentColor() }}"
                href="{{ $link }}" role="button">
                <div class="w-100 d-flex align-items-center">
                    @if ($icon = $item->getValueIcon())
                        <span class="nav-link-icon d-flex align-items-center">
                            <i class="{{ $icon }} fs-2 {{ $item->getValueContentColor() }}"></i>
                        </span>
                    @endif
                    <span class="nav-link-title">
                        {{ $item->getValueText() }}
                    </span>
                </div>
                @if ($info)
                    <div class="w-100 menu-item-info">{{ $info }}</div>
                @endif
            </a>
            {!! $item->getSubMenu()->toHtml() !!}
        </li>
    @else
        <li id="{{ $item->getId() }}" class="nav-item " data-sort="{{ $item->getValueSort() }}">
            <a wire:navigate class="nav-link flex-column {{ $item->getValueContentColor() }}"
                href="{{ $link }}">
                <div class="w-100 d-flex align-items-center">
                    @if ($icon = $item->getValueIcon())
                        <span class="nav-link-icon d-flex align-items-center">
                            <i class="{{ $icon }} fs-2 {{ $item->getValueContentColor() }}"></i>
                        </span>
                    @endif
                    {{ $item->getValueText() }}
                </div>
                @if ($info)
                    <div class="w-100 menu-item-info">{{ $info }}</div>
                @endif
            </a>
        </li>

    @endif
@endif
