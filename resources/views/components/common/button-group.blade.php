<div class="btn-group  {{ $column->getClassName() ?? '' }}" {!! $column->getAttribute() ?? '' !!} role="group">
    <button type="button" class="btn dropdown-toggle {{ $column->getClassButtonGroup() }}" data-bs-toggle="dropdown"
        aria-expanded="false">
        {!! $column->getTitle() ?? 'Actions' !!}
    </button>
    <ul class="dropdown-menu">
        @if ($ButtonInAction = $column->getContent())
            @foreach ($ButtonInAction as $button)
                <li class="dropdown-item">
                    @includeIf($button->getView(), ['column' => $button])
                </li>
            @endforeach
        @endif
    </ul>
</div>
