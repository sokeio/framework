<div>
    <template type="text/x-template" wire:ignore.self wire:sokeio>

      let test= {
            state: {
              tesst: "Dữ dữ liệu test",
            },
            testHam() {
                alert(this.$root.$wire.wireTest);
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
