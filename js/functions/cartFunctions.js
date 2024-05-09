$(document).ready(function () {
  $(document).on("change", "#selectState", function () {
    var id_estado = this.value;
    $.ajax({
      url: "admin/php/controllers/colabs/colab_controller.php",
      method: "POST",
      data: {
        mod: "getMunicipios",
        id_estado: id_estado,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        $("#selectCity").prop("disabled", false);
        if (data.response == true) {
          $("#selectCity").empty();
          $("#selectCity").append(
            '<option value="">Seleccione un municipio</option>'
          );
          for (var i = 0; i < data.data.length; i++) {
            $("#selectCity").append(
              '<option value="' +
                data.data[i].id +
                '">' +
                data.data[i].municipio +
                "</option>"
            );
          }
        } else {
          Swal.fire({
            icon: "error",
            title: "Verifique los datos ingresados",
          });
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });
  var total_sale = sessionStorage.getItem("total_sale");
  total_sale = parseFloat(total_sale);
  paypal
    .Buttons({
      style: { label: "pay" },
      createOrder: function (data, actions) {
        return actions.order.create({
          purchase_units: [
            {
              amount: {
                currency_code: "MXN",
                value: total_sale,
              },
            },
          ],
        });
      },
      onApprove: function (data, actions) {
        return actions.order.capture().then(function (orderData) {
          successOrder(orderData, total_sale);
        });
      },
      oncancel: function (data) {
        /*  Swal.fire({
          title: "Pago cancelado!",
          text: "El pago ha sido cancelado!",
          icon: "info",
        }); */

        alert("El pago ha sido cancelado");
      },
    })
    .render("#paypal-button-container");

  $(".js-example-basic-single").select2();

  function successOrder(orderData) {
    // loading();
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    id_order = Date.now().toString(36).substr(2);
    console.log(id_order);

    $.ajax({
      url: "admin/php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "saveClientOrder",
        cart_shop: cart_shop,
        id_order: id_order,
        total_sale: total_sale,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          sendMailConfirmation(data);
          /*  Swal.fire({
            title: "Hecho!!!",
            icon: 'success',
            text: data.message,
          }).then((result) => {
            loading();
            sessionStorage.setItem("order_code", data.order_code);
            location.href = "thankyou.php";
          }); */

          //location.href = "thankyou.php";

          //sessionStorage.setItem("total_sale", data.totalSale);

          //            $("#navPagination").html(data.paginationNav);

          /* doneToast(data.message); */
        } else {
          errorToast("Ocurrió un error");
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        Swal.close();
        var myToast = Toastify({
          text: data.message,
          duration: 3000,
        });
        myToast.showToast();
      });

    console.log("success order");
    console.log(
      "capture result: " + orderData,
      JSON.stringify(orderData, null, 2)
    );
    /*  */
  }

  async function sendMailConfirmation(data) {
  
    $.ajax({
      url: "admin/php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "sendMailConfirmation",
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          Swal.fire({
            title: "Hecho!!!",
            icon: "success",
            text: data.message,
          }).then((result) => {
           // loading();
            sessionStorage.setItem("order_code", data.order_code);
            //location.href = "thankyou.php";
          });
        } else {
          errorToast("Ocurrió un error");
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        Swal.close();
        var myToast = Toastify({
          text: data.message,
          duration: 3000,
        });
        myToast.showToast();
      });
  }

  function loading() {
    Swal.fire({
      title: "Cargando...",
      html: '<img src="images/paint-loading-2.gif" width="300" height="175">',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
    });
  }
});
