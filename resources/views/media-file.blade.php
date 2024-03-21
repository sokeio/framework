<div class=" m-2 h-min-100 bg-white">
    <div class="d-flex align-items-center g-2 bg-gray p-1">
        <div class="d-flex flex-grow-1 align-items-center">
            <button class="btn btn-secondary rounded-1 m-1" type="button">
                <i class="bi bi-upload"></i>
                <span class="ms-1"> @lang('Upload')</span>
            </button>
            <button class="btn btn-secondary rounded-1 m-1" type="button">
                <i class="bi bi-download "></i>
                <span class="ms-1">@lang('Download')</span>
            </button>
            <button class="btn btn-secondary rounded-1 m-1" type="button">
                <i class="bi bi-folder"></i>
                <span class="ms-1">@lang('Create Folder')</span>
            </button>
            <button class="btn btn-secondary rounded-1 m-1" type="button">
                <i class="bi bi-arrow-clockwise"></i>
                <span class="ms-1">@lang('Refresh')</span>
            </button>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-1 m-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-filter"></i>
                    <span class="ms-1">@lang('Filter')</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-1 m-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-eye"></i>
                    <span class="ms-1">@lang('Mode View')</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
        <div class="flex-shrink-0">
            <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
        </div>
    </div>
    <div class=" border-top"></div>
    <div class="row g-2 p-1">
        <div class="col">
            <div class="overflow-auto">
                <div class="d-flex flex-wrap" style="min-width: 650px; max-height: 500px;">
                    <template x-for="i in 10">
                        <div class="card text-center m-1 item-hover rounded-1" style="width: calc(20% - .5rem);">
                            <div class="card-body">
                                <i class="bi bi-folder2-open" style="font-size: 64px"></i>
                            </div>
                            <div class="card-footer text-body-secondary p-2" x-text="i">

                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
        <div class="col-3">
            <div class="card">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#20c997"></rect>
                </svg>
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                </div>
            </div>
        </div>
    </div>
</div>
