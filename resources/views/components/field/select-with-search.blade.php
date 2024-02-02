@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $datasources = $column->getDataSource() ?? [];
    $FieldKey = $column->getFieldKey();
    $FieldText = $column->getFieldText();
    $searchDatasource = $column->getSearchDataSource();
@endphp
<div x-data="{
    searchText: '',
    Datasources: @js($datasources),
    FieldKey: '{{ $FieldKey }}',
    FieldText: '{{ $FieldText }}',
    getValueText() {
        return this.Datasources?.find((item) => item[this.FieldKey] === $wire.{{ $formField }})?.[this.FieldText] || '{{ $modelLabel }}';
    },
    doSearch() {
        this.Datasources = $wire.{{ $searchDatasource ?? 'searchData' . $modelField }}(this.searchText, $wire.{{ $formField }});
    }

}" x-init="$watch('searchText', value => this.doSearch())" class="form-control dropdown" name="field-{{ $modelField }}"
    placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!}>
    <a class="nav-link dropdown-toggle w-100" href="#field-{{ $modelField }}" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" role="button" aria-expanded="false">
        <span class="nav-link-title" x-text="getValueText()"></span>

    </a>
    <div class="dropdown-menu" data-bs-popper="static">
        Test
    </div>
</div>
