import { tap } from "../utils";
import { proxy } from "./proxy";
import define from "./define";
import component from "./component";
import lifecycle from "./lifecycle";
import request from "../request";

export function Component($component: any, $props: any, $parent: any = null) {
  let state = $component.state ?? {};
  $props = $props ?? {};
  let components = $component.components ?? {};
  let app = $parent?.$app ?? $parent;
  let componentSokeio = {
    ...define,
    ...component,
    ...$component,
    ...lifecycle,
    $initState: { ...state },
    $initProps: { ...$props },
    $components: { ...components },
    $id: 0,
    $name: "",
    $el: null,
    $overlayEl: null,
    $children: [],
    $request: request,
    __data__: null,
    __props__: null,
    __hooks__: null,
    $parent: $parent,
    $app: app,
    $wire: app?.$wire ?? $props?.$wire,
  };
  if (componentSokeio.components) {
    delete componentSokeio.components;
  }
  if (componentSokeio.state) {
    delete componentSokeio.state;
  }
  return tap(proxy(componentSokeio), (_componentSokeio: any) => {
    _componentSokeio.doInit();
  });
}
