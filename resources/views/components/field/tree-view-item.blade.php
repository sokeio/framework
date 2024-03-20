<ul class=" list-unstyled @if (isset($parentKey)) ps-4 pt-1 @endif">
    @if (isset($arrayTree['_treeview_items']))
        <li><label class="form-check">
                <input wire:model='{{ $formField }}' name="{{ $formField }}" class="form-check-input" type="checkbox"
                    value=" {{ $arrayTree['_treeview_items']['id'] }}">
                <span class="form-check-label"> @lang($arrayTree['_treeview_items']['label'])</span>
            </label></li>
    @elseif(isset($parentKey))
        <li> @lang($parentKey)</li>
    @endif
    @foreach ($arrayTree as $key => $item)
        @if ($key === '_treeview_items')
            @continue
        @endif
        @include('sokeio::components.field.tree-view-item', [
            'arrayTree' => $item,
            'parentKey' => $key,
            'formField' => $formField,
        ])
    @endforeach
</ul>
