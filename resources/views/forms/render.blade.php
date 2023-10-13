@php
    $manager->Data($form);
    $layout = $manager->getLayout();
    $layoutDefault = $manager->getLayoutDefault();
    $itemsInForm = collect($manager->getItems() ?? [])
        ->where(function ($item) {
            return $item->getWhen() == true && $item->getInputHidden() !== true;
        })
        ->toArray();
    $itemHiddensInForm = collect($manager->getItems() ?? [])
        ->where(function ($item) {
            return $item->getWhen() == true && $item->getInputHidden() === true;
        })
        ->toArray();
@endphp
<div {!! $manager->getAttributeContent() !!}>
    @if (count($itemHiddensInForm))
        <div style="display: none" wire:key='form-input-hidden'>
            @foreach ($itemHiddensInForm as $item)
                <input type="hidden" {!! $item->getAttribute() ?? '' !!} wire:model='{{ $item->getModelField() }}'
                    name="{{ $item->getModelField() }}" placeholder="{{ $item->getPlaceholder() }}" />
            @endforeach
        </div>
    @endif
    @if ($layout && is_array($layout) && count($layout) > 0)
        @php
            if (!$layoutDefault) {
                $layoutDefault = $layout[0]['key'];
            }
        @endphp
        <div class="row" wire:key='form-input-layout'>
            @foreach ($layout as $item)
                @php
                    ['key' => $key, 'class' => $class, 'column' => $column] = $item;
                    $itemsInFormItem = collect($itemsInForm)
                        ->where(function ($item) use ($layoutDefault, $key) {
                            if ($item->getLayout()) {
                                return $item->getLayout() === $key;
                            } else {
                                return $key == $layoutDefault;
                            }
                        })
                        ->toArray();
                @endphp
                <div class=" {{ $class }} {{ column_size($column ?? 'col-6') }}">
                    @foreach ($itemsInFormItem as $item)
                        {!! field_render($item, $form, $dataId) !!}
                    @endforeach
                </div>
            @endforeach
        </div>
    @else
        <div class="row" wire:key='form-input-data'>
            @foreach ($itemsInForm as $item)
                {!! field_render($item, $form, $dataId) !!}
            @endforeach
        </div>
    @endif
</div>
