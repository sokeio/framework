<div wire:ignore>
    <div x-data="{
        async doAddMenu() {
            if ($elManager = this.$wire.$el.closest('.menu-parent-manager')) {
                await (Alpine.$data($elManager)).addMenuItem({ ...$wire.data });
            }
            $wire.data = {};
        }
    }">
        @includeIf('sokeio::components.layout', ['layout' => $layout])
        <div class="d-flex">
            <button type="submit" class="btn btn-primary ms-auto" @click="doAddMenu()">Add
                To Menu</button>
        </div>
    </div>
</div>
