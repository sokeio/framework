import { SokeioPlugin } from "../core/plugin";

export class DefaultModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_FILEMANAGER_MODULE";
  }
}
