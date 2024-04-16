@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $datasources = $column->getDataSource() ?? [];
    $FieldKey = $column->getFieldKey();
    $FieldText = $column->getFieldText();
    $searchFn = $column->getSearchFn();
    $textNoData = $column->getTextNoData();
    $viewTemplate = $column->getViewTemplate();
@endphp
<div x-data="{
    searchText: '',
    showList: false,
    Datasources: @js($datasources ?? []),
    FieldKey: '{{ $FieldKey }}',
    FieldText: '{{ $FieldText }}',
    valueText: '',
    getValueText() {
        let text = this.Datasources?.find((item) => item[this.FieldKey] === $wire.{{ $formField }});
        this.valueText = text && text[this.FieldText] != '' ? text[this.FieldText] : '{{ $modelLabel }}';
    },
    async doSearch() {
        this.Datasources = (await $wire.callActionUI('{{ $searchFn ?? 'searchData' . $modelField }}',
            this.searchText, $wire.{{ $formField }})) ?? [];
    },
    changeValue(value) {
        this.$wire.{{ $formField }} = value;
        this.getValueText();
        this.showList = false;
    }

}" x-init="$watch('searchText', async () => await doSearch());
getValueText();" class="form-control dropdown" name="field-{{ $modelField }}"
    placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!} @click.away="showList = false">
    <a class="nav-link dropdown-toggle w-100" @click="showList = !showList">
        <template x-if="valueText">
            <span class="nav-link-title d-inline-block w-100" x-text="valueText"></span>
        </template>
    </a>
    <div class="dropdown-menu mt-1 w-75" :class="{ 'show': showList }" style="max-width: 300px" wire:ignore>
        <div class="p-2">
            <input class="form-control" type="text" placeholder="@lang('Search...')" x-model="searchText" />
        </div>
        <div class="pb-2" style="max-height: 300px; overflow-y: auto;">
            <div x-text="Datasources.length === 0 ? '{{ $textNoData }}': ''"></div>
            <template x-for="item in Datasources">
                <div class="dropdown-item p-0 border-top hover" x-on:click="changeValue(item.{{ $FieldKey }})">
                    @if ($viewTemplate)
                        {!! $viewTemplate !!}
                    @else
                        <div class="p-2 w-100 mt-1 " x-text="item.{{ $FieldText }}"></div>
                    @endif
                </div>
            </template>
        </div>
    </div>
</div>
