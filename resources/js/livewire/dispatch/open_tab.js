export default function (payload) {
  setTimeout(() => {
    let $window = window.open(payload.url, "_blank");
    $window.focus();
    //callbackClose and call To Function
    if (payload.callbackClose) {
      console.log(payload.callbackClose);
      let $waiter = setInterval(() => {
        if ($window.closed) {
          payload.$wire[payload.callbackClose]();
          clearInterval($waiter);
        }
      }, 200);
    }
  });
}
