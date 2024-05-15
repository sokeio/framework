import { SokeioPlugin } from "../core/plugin";

export class LiveWireMasonryModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_MASONRY_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("masonry", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_masonry) {
          el.$wire_masonry?.layout();
          return;
        }
        let $wire_masonry_timer = null;
        let options = {};
        const masonryCreate = () => {
          if (el.$wire_masonry) return;
          el.$wire_masonry = new Masonry(el, options);
          window.addEventListener("sokeio::rezize", () => {
            if ($wire_masonry_timer) {
              clearTimeout($wire_masonry_timer);
            }
            $wire_masonry_timer = setTimeout(() => {
              el.$wire_masonry.layout();
              $wire_masonry_timer = null;
            }, 100);
          });
        };
        if (window.Masonry) {
          masonryCreate();
        } else {
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/masonry/dist/masonry.min.js"
              ),
            function () {
              masonryCreate();
            }
          );
        }
      });
    }
  }
}
