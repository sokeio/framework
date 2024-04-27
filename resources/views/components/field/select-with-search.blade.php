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
    elTimer: null,
    elTimerSearch: null,
    getValueText() {
        if (this.elTimer) {
            clearTimeout(this.elTimer);
        }
        this.elTimer = setTimeout(() => {
            let rsItem = this.Datasources?.find((item) => item[this.FieldKey] == $wire.{{ $formField }});
            this.valueText = rsItem && rsItem[this.FieldText] != '' ? rsItem[this.FieldText] : '';
            this.searchText = this.valueText;
            this.elTimer = null;
        }, 100);
    },
    doSearch() {
        if (this.elTimerSearch) {
            clearTimeout(this.elTimerSearch);
        }
        this.elTimerSearch = setTimeout(async () => {
            let rs = (await $wire.callActionUI('{{ $searchFn ?? 'searchData' . $modelField }}',
                this.searchText, $wire.{{ $formField }})) ?? [];
            this.Datasources = rs;
            this.elTimerSearch = null;
        }, 100);

    },
    changeValue(value) {
        this.$wire.{{ $formField }} = value;
        this.getValueText();
        this.showList = false;
    },
    blurSearch() {
        if (this.searchText != this.valueText) {
            this.valueText = '';
            this.searchText = '';
            this.$wire.{{ $formField }} = '';
        }
    }

}" x-init="$watch('searchText', ()=>{doSearch()});
getValueText();" class="dropdown p-0" name="field-{{ $modelField }}"
    placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!} @click.away="showList = false">
    <input class="form-control" type="text" placeholder="@lang('Search...')" x-model="searchText" @focus="showList = true"
        @keyup.escape="searchText= valueText" @blur="blurSearch()" />
    <div class="dropdown-menu" :class="{ 'show': showList }" wire:ignore>
        <template x-if="Datasources.length === 0">
            <div class="dropdown-item">{{ $textNoData }}</div>
        </template>
        <template x-for="item in Datasources">
            <div class="dropdown-item hover" x-on:click="changeValue(item.{{ $FieldKey }})"
                :class="{ 'active': item.{{ $FieldKey }} == $wire.{{ $formField }} }">
                @if ($viewTemplate)
                    {!! $viewTemplate !!}
                @else
                    <div x-text="item.{{ $FieldText }}"></div>
                @endif
            </div>
        </template>
    </div>
</div>
