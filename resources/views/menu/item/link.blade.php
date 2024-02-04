@if ($item->getParent()->checkSub())
@else
    <li id="{{ $item->getId() }}" class="nav-item dropdown {{ $item->getValueContentColor() }}"
        data-sort="{{ $item->getValueSort() }}">
        <a class="nav-link dropdown-toggle " data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
            aria-expanded="true">

            @if ($icon = $item->getValueIcon())
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $icon }} fs-2"></i>
                </span>
                <span class="nav-link-title">
                    {{ $item->getValueText() }}
                </span>
            @endif
        </a>
    </li>
@endif
