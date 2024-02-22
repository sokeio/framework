import { SokeioPlugin } from "../core/plugin";

export class LiveWireCarouselModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_CAROUSEL_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("carousel", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.livewire____carousel) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:carousel.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:carousel.options")};`
          )();
        }
        const clipboardCreate = () => {
          if (el.livewire____carousel) return;
          el.livewire____carousel =
            window.bootstrap.Carousel.getOrCreateInstance(el, options);
          console.log(el.livewire____carousel);
        };
        clipboardCreate();
      });
    }
  }
}
