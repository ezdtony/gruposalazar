$(document).ready(function () {
  var order_code = sessionStorage.getItem("order_code");
  console.log("oc: " + order_code);
  $("#lblOrderCode").text("SU CÓDIGO DE ÓRDEN ES: " + order_code);
  sessionStorage.clear();
});
