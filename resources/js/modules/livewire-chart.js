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
          if (directive.modifiers.length > 0) {
            return;
          }
          let options = {};
          if (el.hasAttribute("wire:apexcharts")) {
            options = new Function(
              `return ${el.getAttribute("wire:apexcharts")};`
            )();
          }
          if (el.$wire_apexcharts) {
            el.$wire_apexcharts.updateOptions(options, true, true);
            return;
          }
          const apexchartsInit = () => {
            if (el.$wire_apexcharts) return;
            el.$wire_apexcharts = new window.ApexCharts(el, options);
            el.$wire_apexcharts.render();
            cleanup(() => {
              el.$wire_apexcharts.destroy();
              el.$wire_apexcharts = null;
            });
            let $wire_apexchart_timer = null;
            window.addEventListener("resize", () => {
              if ($wire_apexchart_timer) {
                clearTimeout($wire_apexchart_timer);
              }
              $wire_apexchart_timer = setTimeout(() => {
                el.$wire_apexcharts.updateOptions(options, false, false);
                $wire_apexchart_timer = null;
              },100);
            });
            window.addEventListener("sokeio::rezize", () => {
              if ($wire_apexchart_timer) {
                clearTimeout($wire_apexchart_timer);
              }
              $wire_apexchart_timer = setTimeout(() => {
                el.$wire_apexcharts.updateOptions(options, false, false);
                $wire_apexchart_timer = null;
              },100);
            });
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
