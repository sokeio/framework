# Sokeio Framework - No Build

Sokeio is a lightweight framework that allows you to build interactive applications without the need for a complex build process.

## Installation

If you prefer to use a CDN, you can include Sokeio directly in your HTML file:

```html
<script src="https://cdn.jsdelivr.net/npm/sokeio/dist/sokeio.umd.js" type="text/javascript"></script>
```

Alternatively, you can install Sokeio via npm:

```bash
npm install sokeio
```

## Registering a Plugin

To register a plugin, listen for the `sokeio::plugin::load` event and use the provided API:

```javascript
document.addEventListener("sokeio::plugin::load", function ({ detail: plugin }) {
  plugin.register({
    css: [], // Add your CSS files here
    js: [],  // Add your JavaScript files here
    check: function () {
      return true; // Check if JS is loaded successfully.
    },
    execute: function (component, event) {
      console.log("plugin_new:execute", event, component);
    },
  });
});
```

## Template Usage

You can define a Sokeio application template using the `sokeio-application-template` attribute. Here’s an example:

```html
<script sokeio-application-template type="javascript/x-template">
  const template = {
    state: {
      count: 0,
    },
    updateTime() {
      setTimeout(() => {
        this.count = new Date();
        this.updateTime();
      }, 1000);
    },
    ready() {
      this.updateTime();
    },
    render() {
      return `<div>
          <h1>hello world</h1>
          <p so-text="count">dfdfdfdf</p>
          <button class="btn btn-primary" so-on:click="count++;">click</button>
      </div>`;
    }
  };

  export default {
    components: {
      'sokeio::template': template
    },
    state: {
      count: 0,
    },
    updateTime() {
      setTimeout(() => {
        this.count = new Date();
        this.updateTime();
      }, 1000);
    },
    ready() {
      this.updateTime();
    },
    render() {
      return `<div>
          <h1>hello world</h1>
          <p so-text="count">dfdfdfdf</p>
          <button class="btn btn-primary" so-on:click="count++;">click</button>
          <button class="btn btn-primary" so-on:click="refresh();">Refresh</button>
          [sokeio::template/]
      </div>`;
    },
  };
</script>
```
Sure! Here’s how you can add additional templates to your HTML using Sokeio. This allows you to define multiple templates that can be reused throughout your application.

### Example: Using Multiple Templates in HTML

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sokeio Example</title>
    <script src="https://cdn.jsdelivr.net/npm/sokeio/dist/sokeio.umd.js" type="text/javascript"></script>
</head>
<body>

    <!-- One Template -->
    <script sokeio-application-template type="javascript/x-template">
      const mainTemplate = {
        state: {
          count: 0,
        },
        updateTime() {
          setTimeout(() => {
            this.count = new Date().toLocaleTimeString();
            this.updateTime();
          }, 1000);
        },
        ready() {
          this.updateTime();
        },
        render() {
          return `<div>
              <h1>Main Application</h1>
              <p so-text="count">Current Time: </p>
              <button class="btn btn-primary" so-on:click="count++;">Increment</button>
          </div>`;
        },
      };

      export default {
        components: {
          'sokeio::main-template': mainTemplate
        },
        state: {
          count: 0,
        },
        updateTime() {
          setTimeout(() => {
            this.count = new Date().toLocaleTimeString();
            this.updateTime();
          }, 1000);
        },
        ready() {
          this.updateTime();
        },
        render() {
          return `<div>
              <h1>Main Application</h1>
              <p so-text="count">Current Time: </p>
              <button class="btn btn-primary" so-on:click="count++;">Increment</button>
              <button class="btn btn-secondary" so-on:click="refresh();">Refresh</button>
              [sokeio::main-template/]
          </div>`;
        },
      };
    </script>

    <!-- Secondary Template -->
    <script sokeio-application-template type="javascript/x-template">
      const secondaryTemplate = {
        state: {
          message: "Welcome to the Sokeio Framework!",
        },
        render() {
          return `<div>
              <h2>Secondary Template</h2>
              <p so-text="message"></p>
          </div>`;
        },
      };

      export default {
        components: {
          'sokeio::secondary-template': secondaryTemplate
        },
            render() {
          return `<div>
              [sokeio::secondary-template/]
          </div>`;
        },
      };
    </script>

</body>
</html>
```

### Explanation


If you need more examples or further assistance, feel free to ask!
### Usage Notes

- Use the attribute `[sokeio-application-template]` to define your application templates. The template will execute once it is loaded.