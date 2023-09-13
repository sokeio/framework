import { BytePlugin } from "../core/plugin";

export class DefaultModule extends BytePlugin {
  getKey() {
    return "BYTE_FILEMANAGER_MODULE";
  }
}
