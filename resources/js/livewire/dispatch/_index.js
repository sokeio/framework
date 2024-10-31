import close from "./close";
import message from "./message";
import refresh from "./refresh";
import refresh_parent from "./refresh_parent";
const dispatch = {
  sokeio_message: message,
  sokeio_close: close,
  sokeio_refresh: refresh,
  sokeio_refresh_parent: refresh_parent,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
