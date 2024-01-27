<div class="menu-content">
    <div class="menu-content_item" data-bs-toggle="collapse" data-bs-target="#menu-content-{{ $item['id'] }}"
        aria-expanded="false" aria-controls="menu-content-{{ $item['id'] }}">
        {{ $item['name'] }}
    </div>
    <div class="menu-content_body collapse" x-data="{
        custom_name: '{{ $item['name'] }}'
    }" id="menu-content-{{ $item['id'] }}">
        <div class="card card-body">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input x-model='custom_name' type="text" class="form-control ellock" placeholder="Name">
            </div>
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <a href="#" wire:click='removeMenu("{{ $item['id'] }}")' class="btn btn-orange">
                        Delete
                    </a>
                </div>
                <div class="col-auto">
                    <a href="#" class="btn btn-blue">
                        Update
                    </a>
                </div>
                <div class="col">
                </div>
            </div>

        </div>
    </div>

</div>
