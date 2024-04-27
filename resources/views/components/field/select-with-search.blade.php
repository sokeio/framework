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
    getValueText() {
        if (this.elTimer) {
            clearTimeout(this.elTimer);
        }
        this.elTimer = setTimeout(() => {
            let rsItem = this.Datasources?.find((item) => item[this.FieldKey] == $wire.{{ $formField }});
            this.valueText = rsItem && rsItem[this.FieldText] != '' ? rsItem[this.FieldText] : '{{ $modelLabel }}';
            this.elTimer = null;
        }, 100);
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
getValueText();" class="dropdown p-0" name="field-{{ $modelField }}"
    placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!} @click.away="showList = false">
    <pre x-text="JSON.stringify(Datasources, null, 2)"></pre>
    <input class="form-control" type="text" placeholder="@lang('Search...')" x-model="valueText"
        @focus="showList = true" />
    <div class="dropdown-menu" :class="{ 'show': showList }" wire:ignore>
        <template x-if="Datasources.length === 0">
            <div>{{ $textNoData }}</div>
        </template>
        
        <template x-for="item in Datasources">
            <div class="dropdown-item border-top hover" x-on:click="changeValue(item.{{ $FieldKey }})"
                :class="{ 'active': item.{{ $FieldKey }} == $wire.{{ $formField }} }">
                @if ($viewTemplate)
                    {!! $viewTemplate !!}
                @else
                    <div class="p-2 w-100 mt-1 " x-text="item.{{ $FieldText }}"></div>
                @endif
            </div>
        </template>
    </div>
</div>
