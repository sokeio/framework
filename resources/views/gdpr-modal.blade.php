<div class="offcanvas offcanvas-bottom h-auto show" tabindex="-1" aria-modal="false" role="dialog">
    <div class="offcanvas-body {{ setting('COOKIE_BANNER_COLOR','') }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    @if ($text = setting('COOKIE_BANNER_TEXT'))
                        {!! $text !!}
                    @else
                        <strong>Do you like cookies?</strong>
                        🍪 We use cookies to ensure you get the best experience on our
                        website. <a href="#" target="_blank">Learn more</a>
                    @endif
                </div>
                <div class="col-auto">
                    <button type="button" wire:click='allowAll()' class="btn @if($color=setting('COOKIE_BUTTON_COLOR')) {{$color}} @else btn-primary  @endif" data-bs-dismiss="offcanvas">
                        
                        {{setting('COOKIE_BUTTON_TEXT','Allow All Cookies')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
