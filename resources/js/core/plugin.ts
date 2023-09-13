import { ByteManager } from "./manager";

export class BytePlugin {
  private _manager: any;
  public getKey(): string {
    return "";
  }
  public manager(manager: any) {
    this._manager = manager;
  }
  public getManager(): ByteManager {
    return this._manager;
  }
  public register() {}
  public booting() {}
  public booted() {}
  public dispose() {}
}
