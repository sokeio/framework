<div class="{{ columnSize('col3') }}" wire:key='{{ $item->getId() }}_{{ $item->isActive() }}'>
    <div class="card @if ($item->isActiveOrVendor()) card-active @endif">
        @if (!$item->isVendor() && $item->isActive())
            <div class="ribbon bg-warning">Active</div>
        @endif
        @if ($ExtentionType == 'module' && $item->isVendor())
            <div class="ribbon bg-red">Vendor</div>
        @endif
        <!-- Photo -->
        <div class="img-responsive img-responsive-21x9 card-img-top"
            style="background-image: url('{{ route('sokeio.screenshot', ['types' => $ExtentionType, 'id' => $item->getId()]) }}')">
        </div>
        <div class="card-body p-3">
            <h3 class="card-title">{{ $item->getTitle() }} <span
                    class="badge rounded-pill bg-success">{{ $item->getVersion() ?? 'dev-main' }}</span></h3>
            <p class="text-secondary">{!! $item->getDescription() !!}</p>
        </div>
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dr{{ $item->getId() }}"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Add More
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dr{{ $item->getId() }}">
                            <li><a class="dropdown-item"
                                    sokeio:modal="{{ route('admin.extension.' . $ExtentionType . '.create-file', ['ExtentionId' => $item->getName()]) }}"
                                    sokeio:modal-title="Create File In {!! $ExtentionType ?? '' !!}">Add File</a></li>
                            <li><a @click="alert('devlop....')" class="dropdown-item" href="#">Add CURD</a></li>
                            @if ($ExtentionType == 'module')
                                <li><a @click="alert('devlop....')" class="dropdown-item" href="#">Add Theme</a>
                                </li>
                                <li><a @click="alert('devlop....')" class="dropdown-item" href="#">Add Plugin</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    @if ($ExtentionType == 'theme' || !$item->isVendor())
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
