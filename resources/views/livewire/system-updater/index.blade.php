<div class="position-fixed bottom-0 end-0 p-2" wire:init="lazySystemUpdate">
    @if ($isSystemVersionNew)
        <div class="card" style="width: 18rem;" wire:sokeio>
            <div class="ribbon bg-red">New</div>
            <div class="card-body p-2">
                <h3 class="card-title m-1">System Update </h3>
                <p class="text-secondary">{{ $message }}</p>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary p-1 me-1" wire:modal.id="system-updater-modal" wire:modal>
                        <i class="ti ti-refresh me-1"></i>
                        Update Now
                    </button>
                    <div wire:ignore>
                        <div sokeio:application="system-updater-modal">
                            <template sokeio:main>
                                @viewjs('sokeio/framework::livewire.system-updater.index')
                            </template>
                        </div>
                    </div>
                    <div class="btn-group ms-1" role="group">
                        <button id="btnUpdateLater" type="button" class="btn btn-secondary p-1 dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false"> <i class="ti ti-clock me-1"></i> Later
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnUpdateLater">
                            <li><a class="dropdown-item" href="#">30 minute</a></li>
                            <li><a class="dropdown-item" href="#">1 hour</a></li>
                            <li><a class="dropdown-item" href="#">4 hours</a></li>
                            <li><a class="dropdown-item" href="#">1 day</a></li>
                            <li><a class="dropdown-item" href="#">Do not update</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
