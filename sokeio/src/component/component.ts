import { Component } from ".";
import { getComponents } from "../application/common";
import { getModalOverlay, logDebug, tap } from "../utils";
import { DataValue } from "./datavalue";
import { Hook } from "./hook";
let number = new Date().getTime();
export default {
  getId() {
    return this.$id;
  },
  getComponent: function (_name: string, _props: any) {
    let components = getComponents();
    let template = undefined;
    if (this.$components?.[_name]) {
      template = this.$components[_name];
    }
    if (!template && this.$parent?.$components?.[_name]) {
      template = this.$parent?.$components?.[_name];
    }
    if (!template && this.$app?.$components?.[_name]) {
      template = this.$app?.$components?.[_name];
    }
    if (!template && components[_name]) {
      template = components[_name];
    }
    if (template) {
      return tap(Component(template, _props, this), (_component: any) => {
        _component.$name = _name;
      });
    }
    console.error("Component not found: " + _name);
    return null;
  },
  watch: function (_property: any, _callback: any) {
    if (this.__data__.check(_property)) {
      this.__data__.watch(_property, _callback.bind(this));
    }
    if (this.__props__.check(_property)) {
      this.__props__.watch(_property, _callback.bind(this));
    }
    return this;
  },
  lifecycle: function ($name: any, $callback: any = null, $runChildren = true) {
    if ($callback) {
      this.__hooks__.on($name, $callback);
      return this;
    }

    this.__hooks__.fire($name);
    if (this[$name]) {
      this[$name]();
    }
    if ($runChildren) {
      this.tapChildren((item: any) => {
        item["do" + $name.charAt(0).toUpperCase() + $name.slice(1)]();
      });
    }
    this.$app.$plugin.excute(this, $name);
    return this;
  },
  tapChildren: function ($callback: any) {
    if ($callback && this.$children) {
      this.$children.forEach((c: any) => {
        $callback(c);
      });
    }
    return this;
  },

  doInit: function () {
    this.__data__ = new DataValue(this.$initState, this);
    this.__props__ = new DataValue(this.$initProps, this, true);
    this.__hooks__ = new Hook(this);
    this.$id = (number++).toString();
  },

  onRegister: function (callback: any) {
    return this.lifecycle("register", callback);
  },
  onBoot: function (callback: any) {
    return this.lifecycle("boot", callback);
  },
  onRender: function (callback: any) {
    return this.lifecycle("render", callback);
  },
  onReady: function (callback: any) {
    return this.lifecycle("ready", callback);
  },
  onDestroy: function (callback: any) {
    return this.lifecycle("destroy", callback);
  },
  closeApp: function () {
    if (this.$app) {
      if (
        this.$app?.$el&&this.$app.$el.querySelector(".so-modal-close")?.style.display == "none"
      )
        return;
      this.$app.doDestroy();
    }
  },
  checkFnCallback: function () {
    if (this.$app) {
      return this.$app.fnCallback ? true : false;
    }
    return false;
  },
  fnCallbackAndClose($value: any, $closeApp = true) {
    if (this.$app) {
      let rs = this.$app.fnCallback?.($value);
      if ($closeApp && rs !== false) {
        this.$app.doDestroy();
      }
    }
  },

  refresh: function (delay = 100) {
    let self = this;
    let fnRefresh = function () {
      let elParent = self.$el.parentNode;
      let elNext = self.$el.nextSibling;
      self.$el.remove();
      self.$el = null;
      self.__data__.cleanup();
      self.doBoot();
      self.doRender();
      self.doReady();
      if (elNext) {
        elParent.insertBefore(self.$el, elNext);
      } else {
        elParent.appendChild(self.$el);
      }
    };
    if (delay == 0) {
      fnRefresh();
      return;
    }
    setTimeout(fnRefresh, delay);
  }, // Refresh component
  show: function () {
    if (!this.$el) {
      return;
    }
    if (!this.sokeAppSelector) {
      this.$el.style.display = "block";
      return;
    }
    logDebug("component:show", this);
    if (this.overlay) {
      if (!document.querySelector(".so-modal-overlay")) {
        document.body.classList.add("so-modal-open");
        document.body.style.overflow = "hidden";
      }
      this.$overlayEl = getModalOverlay();
      this.onDestroy(function () {
        logDebug("component:show:onDestroy");
        document.body.removeChild(this.$overlayEl);

        if (!document.querySelector(".so-modal-overlay")) {
          document.body.classList.remove("so-modal-open");
          document.body.style.overflow = "auto";
        }
      });
    }
    this.sokeAppSelector.appendChild(this.$el);
    if (this.focusInput) {
      this.focusInputAction();
    }
  },
  hide: function () {
    this.$el.style.display = "none";
    if (this.$overlayEl) {
      this.$overlayEl.display = "none";
    }
    this.__lifecycle("hide");
  },
  focusInputAction: function () {
    let attempts = 0;
    const maxAttempts = 20;
    let self = this;
    // Attempt to focus on the first element 5 times
    const interval = setInterval(function () {
      if (
        attempts >= maxAttempts ||
        self.$el?.contains(document.activeElement)
      ) {
        clearInterval(interval); // Stop the attempts after 5 times
      }
      self.$el
        ?.querySelector(
          "input:not([disabled]):not([readonly]), select:not([disabled]), textarea:not([readonly]"
        )
        ?.focus();
      attempts++;
    }, 100); // Focus every 1 second
  },
};
