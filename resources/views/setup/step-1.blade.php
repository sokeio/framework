<div class="card card-md card-bo">
    <div class="card-body text-center py-2 p-sm-2">
        <h1 class="mt-2">Database</h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Database</label>

            <select wire:model='database' class="form-select mb-0">
                <option value="mysql">Mysql</option>
                <option value="sqlite">sqlite</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Host</label>
            <input wire:model='host' class=" form-control" value="127.0.0.1" />
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input wire:model='username' class=" form-control" value="" />
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input wire:model='password' class=" form-control" value="" />
        </div>
    </div>
</div>
