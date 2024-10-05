import message from "./message";
const dispatch = {
  sokeio_message: message,
};

function install(app) {
  if (app.type && dispatch[app.type]) {
    dispatch[app.type](app.payload);
  }
}
export default { dispatch, install };
