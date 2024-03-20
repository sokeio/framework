<div class="modal modal-blur fade"tabindex="-1" role="dialog" aria-modal="true" wire:ignore>
    <div class="modal-dialog   @if (isset($formSize) && $formSize) {{$formSize}} @else modal-lg @endif modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$formTitle}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include($formViewInclude)
            </div>
            <div class="modal-footer">
                <span class="me-auto"></span>
                <div class="btn-list">
                    @if (isset($buttonAction) && $buttonAction)
                        @foreach ($buttonAction as $button)
                            <a {!! $button->getAttr(['refComponent' => $this->id]) !!}
                                class="btn {{ $button->getClass() ?? 'btn-primary' }} d-none d-sm-inline-block">
                                {!! $button->getIcon() !!}
                                {!! $button->getTitle() !!}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
