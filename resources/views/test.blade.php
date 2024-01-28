<div>
    <div x-data="{
        async doAddMenu() {
            if ($elManager = this.$wire.$el.closest('.menu-parent-manager')) {
                await (Alpine.$data($elManager)).addMenuItem({ ...$wire.data });
            }
            $wire.data = {};
        }
    }">
        <div class="mb-3">
            <label class="form-label">Url</label>
            <input wire:model='data.link' type="text" class="form-control" placeholder="Url">
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input wire:model='data.name' type="text" class="form-control" placeholder="Name">
        </div>
        <div class="mb-3">
            <label class="form-label">Class</label>
            <input wire:model='data.class' type="text" class="form-control" placeholder="class">
        </div>
        <div class="d-flex">
            <button type="submit" class="btn btn-primary ms-auto" @click="doAddMenu()">Add
                To Menu</button>
        </div>
    </div>
</div>
