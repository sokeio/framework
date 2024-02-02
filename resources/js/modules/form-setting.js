import { SokeioPlugin } from "../core/plugin";

export class FormSettingModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_FORM_SETING_MODULE";
  }

  booting() {
    let self = this;
    self
      .getManager()
      .onDocument("click", "[sokeio\\:form-setting]", function (e) {
        let elCurrentTarget = e.target;
        let $keyform = elCurrentTarget.getAttribute("sokeio:form-setting");
        if (!$keyform) return;
        console.log($keyform);
        let $modelField = elCurrentTarget.getAttribute(
          "sokeio:form-setting.model"
        );
        let $settingTitle = elCurrentTarget.getAttribute(
          "sokeio:form-setting.title"
        );
        let $settingSize = elCurrentTarget.getAttribute(
          "sokeio:form-setting.size"
        );
        let $settingEvent = elCurrentTarget.getAttribute(
          "sokeio:form-setting.event"
        );

        let $settingKey = "SettingValueField";
        let parentEl = elCurrentTarget.closest("[wire\\:id]");
        let refComponent = parentEl?.getAttribute("wire:id");
        let $wireComponent = Livewire.find(refComponent);
        self.getManager().openModalSetting(
          $keyform,
          elCurrentTarget,
          {
            [$settingKey]: self
              .getManager()
              .dataGet($wireComponent, $modelField),
          },
          function (data) {
            console.log($keyform, self.getManager().dataGet(data, $settingKey));
            self
              .getManager()
              .dataSet(
                $wireComponent,
                $modelField,
                self.getManager().dataGet(data, $settingKey)
              );
          }
        );
      });
  }
}
