<div class="d-flex align-items-center g-2 bg-gray p-1">
    <div class="d-flex flex-grow-1 align-items-center">
        <button class="btn btn-secondary rounded-1 m-1" type="button" @click.stop="isShowUpload = true">
            <i class="bi bi-upload"></i>
            <span class="ms-1"> @lang('Upload')</span>
        </button>
        <button class="btn btn-secondary rounded-1 m-1" @click.stop="isShowFolderCreate=true" type="button">
            <i class="bi bi-download "></i>
            <span class="ms-1">@lang('Download')</span>
        </button>
        <button class="btn btn-secondary rounded-1 m-1" @click.stop="isShowFolderCreate=true" type="button">
            <i class="bi bi-folder"></i>
            <span class="ms-1">@lang('Create Folder')</span>
        </button>
        <button class="btn btn-secondary rounded-1 m-1" type="button" @click="loadAll()">
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
