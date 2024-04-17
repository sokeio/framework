import { SokeioPlugin } from "../core/plugin";

export class LiveWireChartModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_CHART_MODULE";
  }
  booting() {
    if (window.Livewire) {
      const self = this;
      window.Livewire.directive(
        "apexcharts",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0 || el.$wire_apexcharts) {
            return;
          }
          let options = {};
          if (el.hasAttribute("wire:apexcharts")) {
            options = new Function(
              `return ${el.getAttribute("wire:apexcharts")};`
            )();
          }
          if (el.$wire_apexcharts) {
            el.$wire_apexcharts.updateOptions(options);
            return;
          }
          const apexchartsInit = () => {
            if (el.$wire_apexcharts) return;
            el.$wire_apexcharts = new window.ApexCharts(el, options);
            el.$wire_apexcharts.render();
          };
          window.addStyleToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/apexcharts/dist/apexcharts.css"
              ),
            function () {}
          );
          if (window.ApexCharts) {
            apexchartsInit();
          } else {
            window.addScriptToWindow(
              self
                .getManager()
                .getUrlPublic(
                  "platform/modules/sokeio/apexcharts/dist/apexcharts.min.js"
                ),
              function () {
                apexchartsInit();
              }
            );
          }
        }
      );
    }
  }
}
