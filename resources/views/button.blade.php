@php
    $buttonAtrr = $button->getAttribute() ?? '';
    $buttonClass = $button->getClass() ?? '';
    if ($url = $button->getModalUrl()) {
        $buttonAtrr .= ' byte:modal="' . $url . '" ';
        if ($size = $button->getModalSize()) {
            $buttonAtrr .= ' byte:modal-size="' . $size . '" ';
        }
        if ($title = $button->getModalTitle()) {
            $buttonAtrr .= ' byte:modal-title="' . $title . '" ';
        }
    }
    
    if ($confirm = $button->getConfirm()) {
        $buttonAtrr .= ' byte:confirm="' . $confirm . '" ';
        if ($confirmYes = $button->getConfirmYes()) {
            $buttonAtrr .= ' byte:confirm-yes="' . $confirmYes . '" ';
        }
        if ($confirmNo = $button->getConfirmNo()) {
            $buttonAtrr .= ' byte:confirm-no="' . $confirmNo . '" ';
        }
        if ($title = $button->getConfirmTitle()) {
            $buttonAtrr .= ' byte:confirm-title="' . $title . '" ';
        }
    }
    if ($target = $button->getTarget()) {
        $buttonAtrr .= ' target="' . $target . '" ';
    }
    
    if ($wireClick = $button->getWireClick()) {
        $buttonAtrr .= ' wire:click="' . $wireClick . '" ';
    }
    if ($buttonLink = $button->getButtonLink()) {
        $buttonAtrr .= 'href="' . $buttonLink . '" ';
    }
    
    if ($buttonType = $button->getButtonType()) {
        $buttonClass .= ' btn-' . $buttonType . ' ';
    }
    
    if ($buttonSize = $button->getButtonSize()) {
        $buttonClass .= ' btn-' . $buttonSize . ' ';
    }
    
@endphp
<a class="btn {{ $buttonClass }}" {!! $buttonAtrr !!}>
    {{ $button->getTitle() }}
</a>
