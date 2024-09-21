export default {
  checkFirst: () => window.Tagify !== undefined,
  local: {
    js: ["platform/modules/sokeio/tagify/dist/tagify.min.js"],
    css: ["platform/modules/sokeio/tagify/dist/tagify.css"],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    // Only fire this handler on the "root" directive.
    if (directive.modifiers.length > 0 || el.$sokeio_tagify) {
      return;
    }
    let options = {};

    if (el.hasAttribute("wire:tagify-options")) {
      options = new Function(
        `return ${el.getAttribute("wire:tagify-options")};`
      )();
    }
    if (options.templates) {
      options.templates = new Function(`return ${options.templates};`)();
    }
    if (options.originalInputValueFormat) {
      options.originalInputValueFormat = new Function(
        `return ${options.originalInputValueFormat};`
      )();
    }
    if (options.validate) {
      options.validate = new Function(`return ${options.validate};`)();
    }
    if (options.transformTag) {
      options.transformTag = new Function(`return ${options.transformTag};`)();
    }
    if (options.callbacks) {
      options.callbacks = new Function(`return ${options.callbacks};`)();
    }
    if (options.hooks) {
      options.hooks = new Function(`return ${options.hooks};`)();
    }
    let modelKey = el.getAttribute("wire:model");
    const onInput = (e) => {
      if (!el.$sokeio_tagify) {
        return;
      }
      let value = e.detail.value;
      el.$sokeio_tagify.loading(true);
      component.$wire
        .callActionUI(options.whitelistAction, value)
        .then(function (rs) {
          el.$sokeio_tagify.whitelist = rs;
          el.$sokeio_tagify.loading(false).dropdown.show(value);
        });
    };
    const onChange = (e) => {
      self.getManager().dataSet(component.$wire, modelKey, e.detail.value);
    };
    el.$sokeio_tagify = new window.Tagify(el, options);
    if (options.whitelistAction) {
      el.$sokeio_tagify.on("input", onInput);
    }
    el.$sokeio_tagify.on("change", onChange);

    cleanup(() => {
      if (el?.$sokeio_tagify) {
        el.$sokeio_tagify?.destroy && el.$sokeio_tagify.destroy();
        el.$sokeio_tagify = null;
      }
      el.removeEventListener("input", onInput);
      el.removeEventListener("change", onChange);
    });
  },
};
