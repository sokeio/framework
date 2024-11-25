<div class="system-updater-wrapper"
    style="background-image: url({{ asset('platform/module/sokeio/sokeio_bg.svg') }});
    background-size: cover;
    background-repeat: repeat">
    <div class="system-updater-top">
        <h1 class="text-center fw-bold mb-4">System Updater</h1>
    </div>
    <div class="row system-updater-component">
        <div class="col-12 col-lg-6">
            <h1>Count: <span x-text="$wire.wireTest">0</span></h1>
            
        </div>
        <div class="col-12 col-lg-6">
            <img class="h-75" src="{{ asset('platform/module/sokeio/sokeio_update.svg') }}" alt="System Updater" />
        </div>
    </div>
    <div class="system-updater-bottom">
        <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <button class="btn btn-primary" wire:click='systemUpdater()'>Update Now</button>
    </div>
</div>
