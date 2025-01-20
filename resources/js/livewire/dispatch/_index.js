import call_func from "./call_func";
import close from "./close";
import close_tab from "./close_tab";
import message from "./message";
import open_tab from "./open_tab";
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
  sokeio_open_tab: open_tab,
  sokeio_close_tab: close_tab,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
