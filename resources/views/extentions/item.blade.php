<div class="{{ column_size('col3') }}" wire:key='{{ $item->getId() }}_{{ $item->isActive() }}'>
    <div class="card @if ($item->isActiveOrVendor()) card-active @endif">
        @if ($ExtentionType == 'theme' && $item->isActive())
            <div class="ribbon bg-red">Active</div>
        @endif
        @if ($ExtentionType == 'module' && $item->isVendor())
            <div class="ribbon bg-red">Vendor</div>
        @endif
        <!-- Photo -->
        <div class="img-responsive img-responsive-21x9 card-img-top"
            style="background-image: url('{{ route('byte.screenshot', ['types' => $ExtentionType, 'id' => $item->getId()]) }}')">
        </div>
        <div class="card-body p-3">
            <h3 class="card-title">{{ $item->getTitle() }}</h3>
            <p class="text-secondary">{!! $item->getDescription() !!}</p>
        </div>
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Add More
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item"
                                    byte:modal="{{ route('admin.create-extention-file', ['ExtentionType' => $ExtentionType, 'ExtentionId' => $item->getName()]) }}"
                                    byte:modal-title="Create File In {{ page_title() }}">Add File</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    @if (!$item->isVendor())
                        <label class="form-check form-switch m-0">
                            <input class="form-check-input position-static" type="checkbox" value="1"
                                @if ($item->isActive()) checked="true" @endif
                                @change="$wire.ItemChangeStatus('{{ $item->getId() }}',$event.target.checked?1:0)">
                        </label>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
