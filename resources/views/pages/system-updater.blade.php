<div>
    <div wire:ignore>
        <template type="text/x-template" wire:sokeio>

            let test= {
            state: {
            tesst: "Dữ dữ liệu test",
            },
            testHam() {
            this.$wire.wireTest="bac"+ this.tesst ;
            this.tesst = new Date().getTime();
            },
            render() {
            return `
            <div class="p-4">
                <h1>hello world1</h1>
                <p so-text="tesst">dfdfdfdf</p>
                <button class="btn btn-primary" so-on:click="testHam()">click 123</button>
            </div>
            `;
            },
            };
            export default {
            components: {
            'sokeio::test': test
            },
            state: {
            tesst: "123456",
            },
            updateTime() {
            let self = this;
            setTimeout(function () {
            self.tesst = new Date();
            self.updateTime();
            }, 1000);
            },
            ready() {
            this.updateTime();
            },
            render() {
            return `
            <div class="p-4">
                <h1>hello world</h1>
                <p so-text="tesst">dfdfdfdf</p>
                <button class="btn btn-primary" so-on:click="this.tesst=new Date().getTime();">click</button>
                [sokeio::test /]
            </div>
            `;
            },
            };

        </template>
    </div>
    <input wire:model='wireTest' type="text" class="form-control" placeholder="Enter dashboard name">
    <button class="btn btn-primary" wire:click='saveDemo()'>Save</button>
</div>
