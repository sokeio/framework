import { MakeObject } from "./object";
import { MakeObjectProxy } from "./proxy";

export class UI {
  $el = null;
  $target = [];
  $parent = null;
  $selector = null;
  $renderedCallback = [];
  $data = {};
  $self = this;
  $rendered = false;

  get(property) {
    if (this.$data.has(property)) return this.$data.get(property);
    return this[property];
  }

  set(property, value) {
    if (this.$data.has(property)) {
      this.$data.set(property, value);
    } else {
      this[property] = value;
    }
  }
  setState(value) {
    this.$data = MakeObject(value);
    return this;
  }
  $watch(property, callback) {
    this.$data.watch(property, callback);
    return this;
  }
  ready(callback) {
    this.$renderedCallback.push(callback);
    return this;
  }
  setSelector(selector) {
    this.$selector = selector;
    return this;
  }
  setParent(parent) {
    this.$parent = parent;
    return this;
  }
  targetQuery(selector, ui) {
    if (!this.$target[selector]) {
      this.$target[selector] = [];
    }
    if (Array.isArray(ui)) {
      this.$target[selector].push(...ui);
    } else {
      this.$target[selector].push(ui);
    }
    return this;
  }
  makeEl() {
    let el = document.createElement("div");
    el.___ui = this;
    this.$el = el;
  }
  constructor() {
    this.makeEl();
    this.init && this.init();
  }
  template() {
    return "";
  }
  query(selector) {
    return this.$el.querySelector(selector);
  }
  setHtml(selector, html) {
    this.query(selector).innerHTML = html;
  }
  setText(selector, text) {
    this.query(selector).innerText = text;
  }
  renderTarget() {
    let self = this;
    Object.keys(this.$target).forEach((key) => {
      self.$target[key].forEach((ui) => {
        ui.setParent(self);
        ui.setSelector(key);
        ui.render();
      });
    });
    if (this.$parent) {
      if (this.$selector) {
        this.$parent.query(this.$selector)?.appendChild(this.$el);
      } else {
        this.$parent.$el.appendChild(this.$el);
      }
    } else {
      document.body.appendChild(this.$el);
    }
  }
  doReady() {
    let $this = this;
    this.$renderedCallback.forEach((callback) => {
      callback.bind($this)($this);
    });
  }

  beforeRender() {
    return this;
  }
  afterRender() {
    return this;
  }
  init() {
    return this;
  }
  render() {
    if (this.$rendered) return this;
    this.beforeRender && this.beforeRender();

    this.html(this.template());
    this.renderTarget();
    this.afterRender && this.afterRender();
    this.doReady();
    this.$rendered = true;
    return this;
  }
  reRender() {
    this.html("");
    this.render();
    return this;
  }
  show() {
    this.$el.style.display = "block";
    return this;
  }
  hide() {
    this.$el.style.display = "none";
    return this;
  }
  isHide() {
    return this.$el.style.display === "none";
  }
  toggle() {
    if (this.isHide()) {
      this.show();
    } else {
      this.hide();
    }
    return this;
  }
  queryOnAll(selector, event, callback) {
    this.$el.querySelectorAll(selector).forEach((el) => {
      el.addEventListener(event, callback);
    });
    return this;
  }
  queryOn(selector, event, callback) {
    this.$el.querySelector(selector).addEventListener(event, callback);
    return this;
  }
  queryOff(selector, event, callback) {
    this.$el.querySelector(selector).removeEventListener(event, callback);
    return this;
  }
  queryOffAll(selector, event) {
    this.$el.querySelectorAll(selector).forEach((el) => {
      el.removeEventListener(event, callback);
    });
    return this;
  }
  on(event, callback) {
    this.$el.addEventListener(event, callback);
    return this;
  }
  off(event, callback) {
    this.$el.removeEventListener(event, callback);
    return this;
  }
  appendTo(dom) {
    dom.appendChild(this.$el);
    return this;
  }
  append(dom) {
    this.$el.appendChild(dom);
    return this;
  }
  prepend(dom) {
    this.$el.prepend(dom);
    return this;
  }
  insertBefore(dom) {
    this.$el.insertBefore(dom, this.$el);
    return this;
  }
  insertAfter(dom) {
    this.$el.insertAfter(dom, this.$el);
    return this;
  }
  replace(dom) {
    this.$el.replace(dom);
    return this;
  }
  clone() {
    return new UI(null, this.$el.cloneNode(true));
  }
  remove() {
    this.$el.remove();
    return this;
  }
  empty() {
    this.$el.innerHTML = "";
    return this;
  }
  attr(name, value) {
    if (value) {
      this.$el.setAttribute(name, value);
      return this;
    } else {
      return this.$el.getAttribute(name);
    }
  }
  css(name, value) {
    if (value) {
      this.$el.style[name] = value;
      return this;
    } else {
      return this.$el.style[name];
    }
  }
  data(name, value) {
    if (value) {
      this.$el.dataset[name] = value;
      return this;
    } else {
      return this.$el.dataset[name];
    }
  }

  className(className) {
    this.$el.className = className;
    return this;
  }
  addClass(className) {
    this.$el.classList.add(className);
    return this;
  }
  removeClass(className) {
    this.$el.classList.remove(className);
    return this;
  }
  hasClass(className) {
    return this.$el.classList.contains(className);
  }
  toggleClass(className) {
    if (this.hasClass(className)) {
      this.removeClass(className);
    } else {
      this.addClass(className);
    }
    return this;
  }
  text(text) {
    this.$el.innerText = text;
    return this;
  }
  html(html) {
    this.$el.innerHTML = html;
    return this;
  }
  static make() {
    return MakeObjectProxy(new this());
  }
}
