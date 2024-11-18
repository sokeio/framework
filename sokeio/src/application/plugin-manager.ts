export class Plugin {
  css: any[] = [];
  js: any[] = [];
  public load() {}
  public apply(_component: any) {}
}
export class PluginManager {
  plugins: any[] = [];
  public register(plugin: any) {
    this.plugins.push(plugin);
  }
  public load() {
    this.plugins.forEach((plugin) => {
      plugin.load();
    });
  }
}
