@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $datasources = $column->getDataSource() ?? [];
    $FieldKey = $column->getFieldKey();
    $FieldText = $column->getFieldText();
    $searchDatasource = $column->getSearchDataSource();
    $textNoData = $column->getTextNoData();
    $viewTemplate = $column->getViewTemplate();
@endphp
<div x-data="{
    searchText: '',
    Datasources: @js($datasources ?? []),
    FieldKey: '{{ $FieldKey }}',
    FieldText: '{{ $FieldText }}',
    valueText: '',
    getValueText() {
        let text = this.Datasources?.find((item) => item[this.FieldKey] === $wire.{{ $formField }});
        this.valueText = text && text[this.FieldText] != '' ? text[this.FieldText] : '{{ $modelLabel }}';
    },
    async doSearch() {
        this.Datasources = (await $wire.{{ $searchDatasource ?? 'searchData' . $modelField }}(this.searchText, $wire.{{ $formField }})) ?? [];
    },
    changeValue(value) {
        this.$wire.{{ $formField }} = value;
        this.getValueText();
    }

}" x-init="$watch('searchText', async () => await doSearch());
getValueText();" class="form-control dropdown" name="field-{{ $modelField }}"
    placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!}>
    <a class="nav-link dropdown-toggle w-100" href="#field-{{ $modelField }}" data-bs-toggle="dropdown" role="button"  data-bs-auto-close="outside"
        aria-expanded="false">
        <template x-if="valueText">
            <span class="nav-link-title" x-text="valueText"></span>
        </template>
    </a>
    <div class="dropdown-menu">
        <div class="p-2">
            <input class="form-control" type="text" placeholder="@lang('Search...')" x-model="searchText" />
        </div>
        <div class="p-2" style="max-height: 300px; overflow-y: auto;">
            <div x-text="Datasources.length === 0 ? '{{ $textNoData }}': ''"></div>
            <template x-for="item in Datasources">
                <div class="dropdown-item p-0" x-on:click="changeValue(item.{{ $FieldKey }})">
                    @if ($viewTemplate)
                        {!! $viewTemplate !!}
                    @else
                        <div class="p-2 w-100 mt-1 bg-azure text-azure-fg" x-text="item.{{ $FieldText }}"></div>
                    @endif
                </div>
            </template>
        </div>
    </div>
</div>
