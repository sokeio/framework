@php
    $buttonInTable = collect($manager->getButtonInTable() ?? [])
        ->where(function ($item) {
            return $item->getWhen() == true;
        })
        ->toArray();
    $ButtonInAction = $manager->getButtonInAction();
    $buttonOnPage = $manager->getButtonOnPage();
    $columnInTables = collect($manager->getItems() ?? [])
        ->where(function ($item) {
            return $item->getWhen() == true;
        })
        ->toArray();
    $checkBoxInTable = $manager->getCheckBoxRow();
    $pageSizeList = $manager->getPageSizeList();
@endphp
<div class="table-responsive" style="min-height: 50px;" x-data="{
    checkAll: false,
    intCheckAll() {
        this.checkAll = $wire.pageIds.length == $wire.selectIds.filter(function(item) { return $wire.pageIds.includes(item + 0); }).length;
    }
}" x-init="intCheckAll();
$watch('checkAll', (value) => {
    let pageIds = [...$wire.pageIds];
    let selectIds = [...$wire.selectIds.map(function(item) { return parseInt(item); })];
    let selectIdsNotInPages = selectIds.filter(function(item) {
        return !(pageIds.includes(item));
    });
    if (value) {
        selectIds = [...selectIdsNotInPages, ...pageIds]
    } else {
        selectIds = selectIdsNotInPages.length == 0 ? [] : [...selectIdsNotInPages];

    }
    $wire.selectIds = selectIds;
})">
    <div class="row mb-2 mt-2 align-items-center">
        @if ($checkBoxInTable)
            <div class="col btn-bulk-actions">

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        @if ($ButtonInAction)
                            @foreach ($ButtonInAction as $button)
                                <li class="dropdown-item" :disabled="!$wire.selectIds.length">
                                    {!! $button->render() !!}
                                </li>
                            @endforeach
                        @endif
                        <li class="dropdown-item">
                            <button @click="$wire.selectIds=[];checkAll=false;" class="btn btn-block">
                                Clear selection
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                @if ($buttonOnPage)
                    @foreach ($buttonOnPage as $item)
                        {!! $item->render() !!}
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                @if ($checkBoxInTable)
                    <th class="w-1 col-checkbox-all  fw-bold">
                        <input wire:key="selectIds-all" value="1" class="form-check-input" x-model="checkAll"
                            type="checkbox">
                    </th>
                @endif
                @foreach ($columnInTables as $column)
                    <th class="col-field-{{ $column->getField() }}">
                        @include('byte::tables.column-header', [
                            'column' => $column,
                            'manager' => $manager,
                            'items' => $items,
                            'filters' => $filters,
                            'sorts' => $sorts,
                            'formTable' => $formTable,
                        ])
                    </th>
                @endforeach
                @if ($buttonInTable && count($buttonInTable) > 0)
                    <th class="w-auto">
                        Actions
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if (isset($items) && count($items) > 0)
                @php
                    $manager->Data($items);
                @endphp
                @foreach ($items as $item)
                    <tr wire:key='data-row-{{ time() }}-{{ $item->id }}'>
                        @if ($checkBoxInTable)
                            <td class="col-checkbox-id col-checkbox-id-{{ $item->id }}">
                                <input wire:key="selectIds-{{ $item->id }}" wire:model='selectIds'
                                    class="form-check-input" type="checkbox" value="{{ $item->id }}">
                            </td>
                        @endif
                        @foreach ($columnInTables as $column)
                            @php
                                $column->ClearCache()->Data($item);
                            @endphp
                            <td
                                class="col-field-{{ $column->getField() }} col-field-{{ $column->getField() }}-{{ $item->id }}">
                                @include('byte::tables.cell-data', [
                                    'column' => $column,
                                    'manager' => $manager,
                                    'item' => $item,
                                    'items' => $items,
                                    'filters' => $filters,
                                    'sorts' => $sorts,
                                    'formTable' => $formTable,
                                ])
                            </td>
                        @endforeach
                        @if ($buttonInTable && count($buttonInTable) > 0)
                            <td class="w-auto">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top " data-bs-toggle="dropdown"
                                        aria-expanded="true">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end " data-popper-placement="bottom-end"
                                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-0.5px, 43.5px, 0px);">
                                        @foreach ($buttonInTable as $button)
                                            @php
                                                $button->ClearCache()->Data($item);
                                            @endphp
                                            @if ($button->getWhen())
                                                {!! $button->Class('dropdown-item btn')->render() !!}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
            @endif
        </tbody>
    </table>
    <div class="row  align-items-center">
        <div class="col-auto py-3">
            <select wire:model.live='pageSize' class="form-select form-select-sm">
                @foreach ($pageSizeList as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            {{ $items->links('byte::pagination') }}
        </div>
    </div>
</div>
