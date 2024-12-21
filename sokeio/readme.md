# Sokeio Framework - No Build

Sokeio is a lightweight framework that allows you to build interactive applications without the need for a complex build process.

## Installation

If you prefer to use a CDN, you can include Sokeio directly in your HTML file:

```html
<script
  src="https://cdn.jsdelivr.net/npm/sokeio/dist/sokeio.umd.js"
  type="text/javascript"
></script>
```

Alternatively, you can install Sokeio via npm:

```bash
npm install sokeio
```

## Registering a Plugin

To register a plugin, listen for the `sokeio::plugin::load` event and use the provided API:

```javascript
document.addEventListener(
  "sokeio::plugin::load",
  function ({ detail: plugin }) {
    plugin.register({
      css: [], // Add your CSS files here
      js: [], // Add your JavaScript files here
      check: function () {
        return true; // Check if JS is loaded successfully.
      },
      execute: function (component, event) {
        console.log("plugin_new:execute", event, component);
      },
    });
  }
);
```

## Template Usage

You can define a Sokeio application template using the `sokeio:application` attribute. Here’s an example:

```html
<template sokeio:application-template="app-demo" sokeio:application>
  <script sokeio:application-main>
    export default {
      state: {},
      render() {
        return `
         <div> Demo [sokeio::modal::template][/sokeio::modal::template]</div>
         `;
      },
    };
  </script>
  <script sokeio:component-template="sokeio::modal::template">
    export default {
      state: {},
      render() {
        return `
         <div> Demo2</div>
         `;
      },
    };
  </script>
</template>
```

Sure! Here’s how you can add additional templates to your HTML using Sokeio. This allows you to define multiple templates that can be reused throughout your application.

### Explanation

If you need more examples or further assistance, feel free to ask!

### Usage Notes

- Use the attribute `[sokeio-application-template]` to define your application templates. The template will execute once it is loaded.
