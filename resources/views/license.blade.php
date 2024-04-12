<div class="page-body mt-2">
    <div class="container m-0">
        <div class="card rounded-1" style="max-width: 700px;">
            <h5 class="card-header p-3">License Key</h5>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label for="licenseDomain" class="form-label">Your Domain</label>
                    <input type="text" class="form-control" readonly id="licenseDomain" placeholder="sokeio.com"
                        value="{{ $domain }}">
                </div>
                <div class="mb-3">
                    <label for="licenseKey" class="form-label">Your License Key</label>
                    <input type="text" wire:model='licenseKey' maxlength="20" class="form-control" id="licenseKey"
                        placeholder="XX-XXXX-XXXX-XXXX-XXXX">
                </div>
                <div class="mb-3 text-center">
                    <button class="btn btn-primary" wire:click='doLicense()'>Check License</button>
                </div>
            </div>
        </div>
    </div>
</div>
