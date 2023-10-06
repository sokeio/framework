<div class="table-page">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title ps-2">
                        {{ page_title() }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @if ($mode_dev)
                            <button class="btn btn-primary"
                                byte:modal="{{ route('admin.create-extention', ['ExtentionType' => $ExtentionType]) }}"
                                byte:modal-title="Create {{ page_title() }}">
                                Add New
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button wire:click='switchManager()'
                                class="nav-link @if ($viewType == 'manager') active @endif" data-bs-toggle="tab"
                                aria-selected="true" role="tab">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-list-details" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M13 5h8"></path>
                                    <path d="M13 9h5"></path>
                                    <path d="M13 15h8"></path>
                                    <path d="M13 19h5"></path>
                                    <path
                                        d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                    </path>
                                    <path
                                        d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                    </path>
                                </svg>
                                <span class="ps-2">Management</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button wire:click='switchStore()'
                                class="nav-link @if ($viewType == 'store') active @endif" data-bs-toggle="tab"
                                aria-selected="false" role="tab" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-building-store" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 21l18 0"></path>
                                    <path
                                        d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4">
                                    </path>
                                    <path d="M5 21l0 -10.15"></path>
                                    <path d="M19 21l0 -10.15"></path>
                                    <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                                </svg>
                                <span class="ps-2">Store</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button wire:click='switchUpload()'
                                class="nav-link @if ($viewType == 'upload') active @endif" data-bs-toggle="tab"
                                aria-selected="false" role="tab" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                    <path d="M7 9l5 -5l5 5"></path>
                                    <path d="M12 4l0 12"></path>
                                </svg>
                                <span class="ps-2">Upload</span>
                            </button>
                        </li>


                    </ul>
                </div>
                <div class="card-body">
                    @livewire('byte::extentions.' . $viewType, ['ExtentionType' => $ExtentionType], key($viewType . $___number_loading))
                </div>
            </div>
        </div>
    </div>
</div>
