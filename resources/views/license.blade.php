<div class="page-body mt-2">
    <div class="container m-0">
        <div class="card rounded-1" style="max-width: 700px;">
            @if ($licenseInfo['data'])
                <h5 class="card-header p-3">License Info</h5>
                <div class="card-body p-3">
                    @if (!$licenseInfo['status'])
                        <div class="alert alert-danger" role="alert">
                            @lang('Your license key is invalid.')
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="prodcutId" class="form-label">Your ProdcutId</label>
                        <input type="text" class="form-control" readonly id="prodcutId" placeholder="sokeio.com"
                            value="{{ $prodcutId }}">
                    </div>
                    <div class="mb-3">
                        <label for="licenseDomain" class="form-label">Your Domain</label>
                        <input type="text" class="form-control" readonly id="licenseDomain" placeholder="sokeio.com"
                            value="{{ $domain }}">
                    </div>
                    <div class="mb-3">
                        <label for="licenseKey" class="form-label">Your License Key</label>
                        <input type="text" class="form-control" readonly id="licenseKey"
                            placeholder="XX-XXXX-XXXX-XXXX-XXXX" value="***********">
                    </div>
                    <div class="mb-3">
                        <label for="licenseKey" class="form-label">Your License active</label>
                        <input type="text" class="form-control" readonly id="licenseKey"
                            placeholder="XX-XXXX-XXXX-XXXX-XXXX" value="{{ $licenseInfo['data']['activate_date'] }}">
                    </div>
                </div>
            @else
                <h5 class="card-header p-3">License Key</h5>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label for="prodcutId" class="form-label">Your ProdcutId</label>
                        <input type="text" class="form-control" readonly id="prodcutId" placeholder="sokeio.com"
                            value="{{ $prodcutId }}">
                    </div>
                    <div class="mb-3">
                        <label for="licenseDomain" class="form-label">Your Domain</label>
                        <input type="text" class="form-control" readonly id="licenseDomain" placeholder="sokeio.com"
                            value="{{ $domain }}">
                    </div>
                    <div class="mb-3">
                        <label for="licenseKey" class="form-label">Your License Key</label>
                        <input type="text" wire:model='licenseKey' maxlength="255" class="form-control"
                            id="licenseKey" placeholder="XX-XXXX-XXXX-XXXX-XXXX">
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" wire:click='doLicense()'>Check License</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
