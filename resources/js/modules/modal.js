import { SokeioPlugin } from "../core/plugin";

export class ModalModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_MODAL_MODULE";
  }

  booting() {
    let self = this;
    self.getManager().onDocument("click", "[sokeio\\:modal]", function (e) {
      let elCurrentTarget = e.target;
      let $url = elCurrentTarget.getAttribute("sokeio:modal");
      let $modelField = elCurrentTarget.getAttribute("sokeio:model");
      if (!$url) return;
      let $title = elCurrentTarget.getAttribute("sokeio:modal-title") ?? "";
      let $size = elCurrentTarget.getAttribute("sokeio:modal-size") ?? "";
      let $btnChoose = "";
      if (elCurrentTarget.hasAttribute("sokeio:modal-choose")) {
        $btnChoose = elCurrentTarget.getAttribute("sokeio:modal-choose");
        if ($btnChoose == "") $btnChoose = "Choose";
      }
      let parentEl = elCurrentTarget.closest("[wire\\:id]");
      let refComponent = parentEl?.getAttribute("wire:id");
      let selectIds = undefined;
      if ($btnChoose && $modelField) {
        selectIds = self
          .getManager()
          .dataGet(Livewire.find(refComponent), $modelField);
      }
      self.openModal(
        {
          $url,
          $title,
          $size,
          $btnChoose,
          $modelField,
          $btnElement: elCurrentTarget,
        },
        { refComponent, selectIds }
      );
    });
  }
  getModalHtml($title, $size, $modalId) {
    let self = this;
    return self.getManager()
      .htmlToElement(`<div class="modal modal-blur modal-sokeio fade" id="${$modalId}" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered ${$size}" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">${$title}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <h1 class="text-center pb-5 modal-loading opacity-50">
            <div class="progress progress-sm mb-5">
              <div class="progress-bar progress-bar-indeterminate"></div>
            </div>
            Loading<span class="animated-dots"></span>
        </h1>
       
      </div>
    </div>
  </div>`);
  }
  openModal(
    {
      $url,
      $title,
      $size,
      $btnChoose,
      $modelField,
      $btnElement,
      $callbackClosed,
    },
    dataModal = {}
  ) {
    let self = this;
    let elModal = self.getModalHtml(
      $title,
      $size,
      "modal-" + new Date().getTime()
    );
    const modalApp = window.bootstrap?.Modal?.getOrCreateInstance(elModal, {});
    modalApp.show();
    elModal.addEventListener("hidden.bs.modal", function (event) {
      elModal.__deleting = true;
      elModal.remove();
      window.Livewire?.rescan();
      $callbackClosed && $callbackClosed();
    });
    self
      .getManager()
      .$axios.post($url, dataModal, {
        timeout: 1000 * 10, // Wait for 10 seconds
        headers: {
          "Content-Type": "application/json",
        },
      })
      .then(({ data: htmlContent }) => {
        let $btnClass = "btn-choose-modal-" + new Date().getTime();
        if ($btnChoose && $btnChoose != "") {
          htmlContent +=
            '<div class="p-2 text-center"><button class="btn btn-primary ' +
            $btnClass +
            '">' +
            $btnChoose +
            "</button></div>";
        }
        elModal
          .querySelector(".modal-loading")
          .setAttribute("style", "display: none;");
        elModal
          .querySelector(".modal-header")
          .insertAdjacentHTML("afterend", htmlContent);
        window.Livewire?.rescan();
        let modalWireComponent = elModal.querySelector("[wire\\:id]");
        if ($btnChoose && $btnChoose != "" && modalWireComponent) {
          let modalWireId = modalWireComponent.getAttribute("wire:id");
          let refComponent = dataModal?.refComponent;
          elModal
            .querySelector("." + $btnClass)
            ?.addEventListener("click", function () {
              let WireComponent = Livewire.find(modalWireId);
              let $dataEl = undefined;
              setTimeout(async () => {
                if (
                  $btnElement &&
                  ($dataEl = $btnElement.closest("[x-data]"))
                ) {
                  if ($dataEl._x_dataStack[0].dataItems) {
                    $dataEl._x_dataStack[0].dataItems =
                      await WireComponent.getDataSelectItem();
                  }
                }
              }, 0);
              let WireComponentRef = Livewire.find(refComponent);
              if (WireComponentRef && $modelField) {
                self
                  .getManager()
                  .dataSet(
                    WireComponentRef,
                    $modelField,
                    self.getManager().dataGet(WireComponent, "selectIds")
                  );
              }
              modalApp.hide();
            });
        }
      })
      .catch(({ message }) => {
        setTimeout(() => {
          window.showToast(message, undefined, undefined, undefined, "error");
          modalApp.hide();
        }, 400);
      });
    return modalApp;
  }
}
