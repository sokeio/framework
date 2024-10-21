import close from "./close";
import message from "./message";
import refresh from "./refresh";
const dispatch = {
  sokeio_message: message,
  sokeio_close: close,
  sokeio_refresh: refresh,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
