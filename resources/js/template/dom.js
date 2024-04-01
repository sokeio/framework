export class DomJS {
  $el;
  $parent;
  constructor($elOrTagName = "div", $parent = null) {
    if (typeof $elOrTagName === "string") {
      this.$el = document.createElement($elOrTagName);
      this.$parent = $parent;
    } else {
      this.$el = $tagName;
      this.$parent = $parent ?? this.parent();
    }
  }
  on(event, callback) {
    this.$el.addEventListener(event, callback);
  }
  off(event, callback) {
    this.$el.removeEventListener(event, callback);
  }
  html(html) {
    this.$el.innerHTML = html;
  }
  text(text) {
    this.$el.textContent = text;
  }
  remove() {
    this.$el.remove();
  }
  empty() {
    this.$el.innerHTML = "";
  }
  appendTo(dom) {
    dom.$el.appendChild(this.$el);
  }
  prependTo(dom) {
    dom.$el.prepend(this.$el);
  }
  prepend(dom) {
    this.$el.prepend(dom.$el);
  }
  insertBefore(dom) {
    this.$el.insertBefore(dom.$el, this.$el);
  }
  insertAfter(dom) {
    this.$el.insertBefore(dom.$el, this.$el.nextSibling);
  }
  replace(dom) {
    this.$el.parentNode.replaceChild(dom.$el, this.$el);
  }
  clone() {
    return new DomJS(this.$el.cloneNode(true));
  }
  append(dom) {
    this.$el.appendChild(dom.$el);
  }
  attr(name, value) {
    if (value) {
      this.$el.setAttribute(name, value);
    } else {
      return this.$el.getAttribute(name);
    }
  }
  removeAttr(name) {
    this.$el.removeAttribute(name);
  }
  css(name, value) {
    if (value) {
      this.$el.style[name] = value;
    } else {
      return this.$el.style[name];
    }
  }
  data(name, value) {
    if (value) {
      this.$el.dataset[name] = value;
    } else {
      return this.$el.dataset[name];
    }
  }
  removeData(name) {
    this.$el.removeAttribute(`data-${name}`);
  }
  show() {
    this.$el.style.display = "block";
  }
  hide() {
    this.$el.style.display = "none";
  }
  toggle() {
    this.$el.style.display =
      this.$el.style.display === "none" ? "block" : "none";
  }
  toggleClass(className) {
    if (this.$el.classList.contains(className)) {
      this.$el.classList.remove(className);
    } else {
      this.$el.classList.add(className);
    }
  }
  addClass(className) {
    this.$el.classList.add(className);
  }
  removeClass(className) {
    this.$el.classList.remove(className);
  }
  hasClass(className) {
    return this.$el.classList.contains(className);
  }
  children() {
    return this.$el.children;
  }
  parent() {
    return this.$el.parentNode;
  }
  siblings() {
    return this.$el.parentNode.children;
  }
  next() {
    return this.$el.nextSibling;
  }
  prev() {
    return this.$el.previousSibling;
  }
  find(selector) {
    return new DomJS(this.$el.querySelector(selector));
  }
  findAll(selector) {
    return this.$el.querySelectorAll(selector).map((el) => new DomJS(el));
  }
  render($parent = null) {
    if ($parent) {
      this.$parent = $parent;
    }
  }
}
