<div class="system-updater-wrapper"
    style="background-image: url({{ asset('platform/module/sokeio/sokeio_bg.svg') }});
    background-size: cover;
    background-repeat: repeat">
    <div class="system-updater-top">
        <h1 class="text-center fw-bold mb-4">System Updater</h1>
    </div>
    <div class="row system-updater-component">
        <div class="col-12 col-lg-6">
            @sokeio('system-updater.js', [], ['class' => ''])
        </div>
        <div class="col-12 col-lg-6">
            <img class="h-75" src="{{ asset('platform/module/sokeio/sokeio_update.svg') }}" alt="" />
        </div>
    </div>
    <div class="system-updater-bottom">
        <button class="btn btn-primary" wire:click='systemUpdater()'>Update Now</button>
    </div>
</div>
