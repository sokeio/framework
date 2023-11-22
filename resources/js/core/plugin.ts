import { ByteManager } from "./manager";

export class SokeioPlugin {
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
