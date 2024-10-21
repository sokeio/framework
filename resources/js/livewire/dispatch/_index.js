import close from "./close";
import message from "./message";
const dispatch = {
  sokeio_message: message,
  sokeio_close: close,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
