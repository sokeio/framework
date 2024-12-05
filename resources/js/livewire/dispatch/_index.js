import sokeio from "../directive/sokeio";
import call_func from "./call_func";
import close from "./close";
import message from "./message";
import refresh from "./refresh";
import refresh_page from "./refresh_page";
import refresh_parent from "./refresh_parent";
const dispatch = {
  sokeio_message: message,
  sokeio_close: close,
  sokeio_refresh: refresh,
  sokeio_refresh_parent: refresh_parent,
  sokeio_refresh_page: refresh_page,
  sokeio_call_func: call_func,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
