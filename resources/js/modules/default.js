import { SokeioPlugin } from "../core/plugin";

export class DefaultModule extends SokeioPlugin {
  getKey() {
    return "BYTE_FILEMANAGER_MODULE";
  }
}
