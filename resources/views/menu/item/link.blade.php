@php
    $link = $link ?? '#';
@endphp
@if ($item->getParent()->checkSub())
    @if ($item->checkSubMenu())
        <div id="{{ $item->getId() }}" class="dropend" data-sort="{{ $item->getValueSort() }}">
            <a class="dropdown-item dropdown-toggle" href="{{ $link }}" role="button">
                @if ($icon = $item->getValueIcon())
                    <span class="nav-link-icon d-flex align-items-center">
                        <i class="{{ $icon }} fs-2"></i>
                    </span>
                @endif
                {{ $item->getValueText() }}
            </a>
            {!! $item->getSubMenu()->toHtml() !!}
        </div>
    @else
        <a wire:navigate id="{{ $item->getId() }}" class="dropdown-item " href="{{ $link }}"
            data-sort="{{ $item->getValueSort() }}">
            @if ($icon = $item->getValueIcon())
                <span class="nav-link-icon d-flex align-items-center">
                    <i class="{{ $icon }} fs-2"></i>
                </span>
            @endif
            {{ $item->getValueText() }}
        </a>
    @endif
@else
    @if ($item->checkSubMenu())
        <li id="{{ $item->getId() }}" class="nav-item dropdown {{ $item->getValueContentColor() }}"
            data-sort="{{ $item->getValueSort() }}">
            <a class="nav-link dropdown-toggle " href="{{ $link }}" role="button">

                @if ($icon = $item->getValueIcon())
                    <span class="nav-link-icon d-flex align-items-center">
                        <i class="{{ $icon }} fs-2"></i>
                    </span>
                @endif
                <span class="nav-link-title">
                    {{ $item->getValueText() }}
                </span>
            </a>
            {!! $item->getSubMenu()->toHtml() !!}
        </li>
    @else
        <li id="{{ $item->getId() }}" class="nav-item {{ $item->getValueContentColor() }}"
            data-sort="{{ $item->getValueSort() }}">
            <a wire:navigate class="nav-link " href="{{ $link }}">
                @if ($icon = $item->getValueIcon())
                    <span class="nav-link-icon d-flex align-items-center">
                        <i class="{{ $icon }} fs-2"></i>
                    </span>
                @endif
                {{ $item->getValueText() }}
            </a>

        </li>

    @endif
@endif
