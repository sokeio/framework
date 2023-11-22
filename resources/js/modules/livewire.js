import { SokeioPlugin } from "../core/plugin";

export class LiveWireModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_MODULE";
  }
  CacheRejs = undefined;
  CacheLivewire = undefined;
  booted() {
    let self = this;
    self.getManager().on("sokeio::trigger_after", (el) => {
      if (self.CacheRejs != undefined) {
        clearTimeout(self.CacheRejs);
      }
      self.CacheRejs = setTimeout(function () {
        self.CacheRejs = undefined;
        clearTimeout(self.CacheRejs);
        if (self.CacheLivewire == true) {
          window.Livewire?.rescan();
        } else {
          window.Livewire?.start();
        }
        self.CacheLivewire = true;
      }, 80);
    });
    self.getManager().on("sokeio::loaded", (el) => {
      if (!window.Livewire) return;
      window.ByteManager.doTrigger(el);
      window.addEventListener("sokeio::close", ({ detail: { option } }) => {
        let { id, component } = option;
        if (id) {
          let liveCom = window.Livewire.find(id)?.__instance;
          if (liveCom?.el) {
            liveCom.el.__deleting = true;
            let parentModal = liveCom.el.closest(".modal");
            let liveModal = bootstrap?.Modal?.getInstance(
              parentModal ?? liveCom.el
            );
            if (liveModal) {
              liveModal.hide();
            } else {
              liveCom.el.remove();
            }
          }
        }
        if (component) {
          let arr = window.Livewire.getByName(component);
          if (arr) {
            arr.forEach((item) => {
              let liveCom = item.__instance ?? item;
              if (liveCom?.el) {
                liveCom.el.__deleting = true;
                let liveModal = bootstrap?.Modal?.getInstance(
                  liveCom.__instance.el
                );
                if (liveModal) {
                  liveModal.hide();
                } else {
                  liveCom.el.remove();
                }
              }
            });
          }
        }
      });
      const LivewireRefreshData = (id) => {
        try {
          window.Livewire.dispatch("refreshData" + id);
        } catch (ex) {}
      };
      window.addEventListener("sokeio::refresh", ({ detail: { option } }) => {
        let { module, id, component } = option;
        if (module) {
          LivewireRefreshData(module);
        }
        if (component) {
          if (!Array.isArray(component)) {
            component = [component];
          }
          component.forEach((element) => {
            LivewireRefreshData(element);
          });
        }
        if (id) {
          LivewireRefreshData(id);
        }
      });
      window.addEventListener("sokeio::message", ({ detail: { option } }) => {
        console.log('sokeio::message')
        if (typeof option === "string") {
          window.ByteManager.addInfo(option, "sokeio::message");
        } else {
          const { error, message, type, meta, ...data } = option;
          window.ByteManager.addMessage(
            message ?? error,
            type,
            "sokeio::message",
            { ...data, ...meta }
          );
        }
      });
    });
  }
}
