<div class="col-6 col-md-6 col-lg-4 col-xl-3 col-xxl-2" wire:key='{{ $item->getId() }}_{{ $item->isActive() }}'>
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
                    @if ($mode_dev == true)
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dr{{ $item->getId() }}"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Add More
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dr{{ $item->getId() }}">
                                @php
                                    $url = route('admin.extension.' . $ExtentionType . '.create', [
                                        'ExtentionId' => $item->getName(),
                                    ]);
                                @endphp
                                <li><a class="dropdown-item" sokeio:modal="{{ $url }}"
                                        sokeio:modal-title="Create File In {!! $ExtentionType ?? '' !!}: {{ $item->getId() }}">
                                        Add File
                                    </a>
                                </li>

                                @if ($ExtentionType == 'module')
                                    @if (moduleIsActive('devtool'))
                                        <li>
                                            <a class="dropdown-item"
                                                sokeio:modal="{{ route('admin.devtool.crud.add', ['moduleId' => $item->getId()]) }}"
                                                sokeio:modal-title="Create Crud In {!! $ExtentionType ?? '' !!}: {{ $item->getId() }}"
                                                sokeio:modal-size="modal-xl">
                                                Add
                                                CURD
                                            </a>
                                        </li>
                                    @endif
                                    <li><a @click="alert('devlop....')" class="dropdown-item" href="#">Add
                                            Theme</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-auto ms-auto">
                    @if ($ExtentionType == 'theme' || !$item->isVendor())
                        <label class="form-check form-switch m-0">
                            <input class="form-check-input position-static" type="checkbox" value="1"
                                @if ($item->isActive()) checked="true" @endif
                                @change="$wire.ItemChangeStatus('{{ $item->getId() }}',$event.target.checked?1:0)">
                        </label>
                    @else
                        <label class="form-check form-switch m-0">
                            <input class="form-check-input position-static" type="checkbox" value="1" checked
                                readonly disabled />
                        </label>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
