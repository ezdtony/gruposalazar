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

  $(document).on("change", "#shippingMethod", function () {
    var total_sale = sessionStorage.getItem("total_sale");
    total_sale = parseFloat(total_sale);

    $("#paypal-button-container").html("");
    if (!$("#paymentMethod").prop("disabled")) {
      $("#paymentMethod").attr("disabled", true);
      $("#paymentMethod").val("");
    }

    // 1 entrega en sucursal
    // 2 envio a domicilio

    var id_method = this.value;
    switch (id_method) {
      case "1":
        $("#divHomeDelivery").hide();
        $("#divSubsidiaryDelivery").show();
        break;
      case "2":
        $("#divSubsidiaryDelivery").hide();
        if (total_sale < "2000") {
          $("#divHomeDelivery").hide();
          divSubsidiaryDelivery;
          Swal.fire({
            title: "Atención",
            icon: "error",
            text: "Su pedido no califica para envió a domicilio. Para esta opción la compa mínima debe ser de $2,000.",
          });
          $("#paymentMethod").attr("disabled", true);
          $("#paypal-button-container").html("");
          $(this).val("");
        } else {
          $("#divHomeDelivery").show();
        }
        break;

      default:
        break;
    }
  });

  $(document).on("click", "#checkUserDataCheckout", function () {
    var id_method = $("#shippingMethod").val();
    console.log(id_method);
    if (id_method != "" && id_method != null) {
      switch (id_method) {
        case "1":
          var client_name = $("#c_fname").val();
          var client_lastname = $("#c_lname").val();
          var client_email = $("#c_email_address").val();
          var client_phone = $("#c_phone").val();
          var order_notes = $("#c_order_notes").val();
          var id_subsidiary = $("#shippingSubsidiary").val();

          if (
            client_name != "" &&
            client_lastname != "" &&
            client_email != "" &&
            id_subsidiary != null &&
            client_phone != ""
          ) {
            $("#paymentMethod").attr("disabled", false);
          } else {
            console.log("here");
            $("#paymentMethod").attr("disabled", true);
            Swal.fire({
              title: "Atención",
              icon: "error",
              text: "Es necesario que ingrese todos los datos obligatorios para continuar con la compra",
            });
          }
          break;
        case "2":
          var client_name = $("#c_fname").val();
          var client_lastname = $("#c_lname").val();
          var client_address = $("#c_address").val();
          var client_colony = $("#c_colony").val();
          var client_zipcode = $("#c_zip_code").val();
          var client_state = $("#selectState").find(":selected").text();
          var client_city = $("#selectCity").find(":selected").text();
          var client_email = $("#c_email_address").val();
          var client_phone = $("#c_phone").val();
          var order_notes = $("#c_order_notes").val();

          if (
            $("#selectState").find(":selected").val() != "" &&
            $("#selectCity").find(":selected").val() != "" &&
            client_name != "" &&
            client_lastname != "" &&
            client_address != "" &&
            client_email != "" &&
            client_phone != "" &&
            client_colony != "" &&
            client_zipcode != ""
          ) {
            $("#paymentMethod").attr("disabled", false);
          } else {
            $("#paymentMethod").attr("disabled", true);
            Swal.fire({
              title: "Atención",
              icon: "error",
              text: "Es necesario que ingrese todos los datos obligatorios para continuar con la compra",
            });
          }
          break;

        default:
          break;
      }
    } else {
      Swal.fire({
        title: "Atención!!",
        icon: "error",
        text: "Debe seleccionar un método de entrega",
      });
    }
  });

  $(document).on("change", "#paymentMethod", function () {
    var total_sale = sessionStorage.getItem("total_sale");
    total_sale = parseFloat(total_sale);
    $("#paypal-button-container").html("");

    var id_method = this.value;

    if (id_method == 1 && $("#shippingMethod").val() == 2) {
      Swal.fire({
        title: "Atención",
        icon: "error",
        text: "Para el envío a domicilio no puede seleccionar la opción de pago en sucursal ",
      });
    } else {
      switch (id_method) {
        case "1":
          var html =
            '<br><button type="button" class="btn btn-primary" id="btnSaveSubsidiaryShip"  data-total-sale="' +
            total_sale +
            '">Confirmar compra</button>';
          $("#paypal-button-container").html(html);
          break;

        case "2":
          if ($("#paypal-button-container").html() == 0) {
            var total_sale = sessionStorage.getItem("total_sale");
            total_sale = parseFloat(total_sale);
            var id_method = $("#shippingMethod").val();
            switch (id_method) {
              //entrega en sucursal
              case "1":
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
                        var id_method = $("#shippingMethod").val();

                        switch (id_method) {
                          case "1":
                            subsidiaryDeliveryOrder(orderData, total_sale);
                            break;
                          case "2":
                            homeDeliveryOrder(orderData, total_sale);
                            break;

                          default:
                            break;
                        }
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
                $(".paypal-button").attr("disabled", true);

                break;
              case "2":
                if (total_sale < "2000") {
                  Swal.fire({
                    title: "Atención",
                    icon: "error",
                    text: "Su pedido no califica para envió a domicilio. Para esta opción la compa mínima debe ser de $2,000.",
                  });
                  $("#paymentMethod").attr("disabled", true);
                  $("#paypal-button-container").html("");
                } else {
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
                        return actions.order
                          .capture()
                          .then(function (orderData) {
                            var id_method = $("#shippingMethod").val();

                            switch (id_method) {
                              case "1":
                                subsidiaryDeliveryOrder(orderData, total_sale);
                                break;
                              case "2":
                                homeDeliveryOrder(orderData, total_sale);
                                break;

                              default:
                                break;
                            }
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
                  $(".paypal-button").attr("disabled", true);
                }
                break;

              default:
                break;
            }

            $(".js-example-basic-single").select2();
          }
          break;

        default:
          break;
      }
    }
    // 1 pago en sucursal
    // 2 PAYPAL
  });
  $(document).on("click", "#btnSaveSubsidiaryShip", function () {
    var total_sale = $(this).attr("data-total-sale");
    loading();
    subsidiaryDeliveryOrder(total_sale, total_sale);
  });

  function successOrder(
    orderData,
    total_sale,
    client_name,
    client_lastname,
    client_address,
    client_state,
    client_city,
    client_email,
    client_phone,
    order_notes,
    client_colony,
    client_zipcode
  ) {
    loading();
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
        client_name: client_name,
        client_lastname: client_lastname,
        client_address: client_address,
        client_state: client_state,
        client_city: client_city,
        client_email: client_email,
        client_phone: client_phone,
        order_notes: order_notes,
        client_colony: client_colony,
        client_zipcode: client_zipcode,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
          loading();
          sendMailConfirmation(
            data,
            total_sale,
            client_name,
            client_lastname,
            client_address,
            client_state,
            client_city,
            client_email,
            client_phone,
            order_notes,
            cart_shop
          );
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
    /* console.log(
      "capture result: " + orderData,
      JSON.stringify(orderData, null, 2)
    ); */
    /*  */
  }
  function homeDeliveryOrder(orderData, total_sale) {
    loading();
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    id_order = Date.now().toString(36).substr(2);
    console.log(id_order);

    var client_name = $("#c_fname").val();
    var client_lastname = $("#c_lname").val();
    var client_address = $("#c_address").val();
    var client_colony = $("#c_colony").val();
    var client_zipcode = $("#c_zip_code").val();
    var client_state = $("#selectState").find(":selected").text();
    var client_city = $("#selectCity").find(":selected").text();
    var client_email = $("#c_email_address").val();
    var client_phone = $("#c_phone").val();
    var order_notes = $("#c_order_notes").val();

    if (
      $("#selectState").find(":selected").val() != "" &&
      $("#selectCity").find(":selected").val() != "" &&
      client_name != "" &&
      client_lastname != "" &&
      client_address != "" &&
      client_email != "" &&
      client_phone != "" &&
      client_colony != "" &&
      client_zipcode != ""
    ) {
      $.ajax({
        url: "admin/php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "saveClientOrderHomeDelivery",
          cart_shop: cart_shop,
          id_order: id_order,
          total_sale: total_sale,
          client_name: client_name,
          client_lastname: client_lastname,
          client_address: client_address,
          client_state: client_state,
          client_city: client_city,
          client_email: client_email,
          client_phone: client_phone,
          order_notes: order_notes,
          client_colony: client_colony,
          client_zipcode: client_zipcode,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
            loading();

            sendMailConfirmation(
              data,
              total_sale,
              client_name,
              client_lastname,
              client_address,
              client_state,
              client_city,
              client_email,
              client_phone,
              order_notes,
              cart_shop
            );
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
    } else {
      Swal.fire({
        title: "Atención",
        icon: "error",
        text: "Su pago se procesó, pero ocurrió un error al registrar sus datos",
      });
    }

    console.log("success order home delivery");
    /* console.log(
      "capture result: " + orderData,
      JSON.stringify(orderData, null, 2)
    ); */
    /*  */
  }

  function subsidiaryDeliveryOrder(orderData, total_sale) {
    loading();
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    id_order = Date.now().toString(36).substr(2);
    console.log(id_order);

    var client_name = $("#c_fname").val();
    var client_lastname = $("#c_lname").val();
    var client_email = $("#c_email_address").val();
    var client_phone = $("#c_phone").val();
    var order_notes = $("#c_order_notes").val();
    var id_subsidiary = $("#shippingSubsidiary").val();
    var subsidiary_name = $("#shippingSubsidiary").find(":selected").text();
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    var payment_method = $("#paymentMethod").val();

    if (
      client_name != "" &&
      client_lastname != "" &&
      client_email != "" &&
      id_subsidiary != null &&
      id_subsidiary != "" &&
      client_phone != ""
    ) {
      $.ajax({
        url: "admin/php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "saveClientOrderSubsidiaryDelivery",
          cart_shop: cart_shop,
          id_order: id_order,
          total_sale: total_sale,
          client_name: client_name,
          client_lastname: client_lastname,
          client_email: client_email,
          client_phone: client_phone,
          order_notes: order_notes,
          id_subsidiary: id_subsidiary,
          subsidiary_name: subsidiary_name,
          payment_method: payment_method,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
            loading();
            sendMailConfirmationSubDelivery(
              data,
              total_sale,
              client_name,
              client_lastname,
              client_email,
              order_notes,
              cart_shop,
              client_phone
            );
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
    } else {
      Swal.fire({
        title: "Atención",
        icon: "error",
        text: "Su pago se procesó y fue recibido, sin embargo ocurrió un error al registrar sus datos personales...",
      });
    }
  }

  async function sendMailConfirmation(
    data,
    total_sale,
    client_name,
    client_lastname,
    client_address,
    client_state,
    client_city,
    client_email,
    client_phone,
    order_notes,
    cart_shop
  ) {
    sessionStorage.setItem("order_code", data.order_code);

    var pdf_string = await generateSalePDF(
      client_name,
      client_lastname,
      data.addressShip,
      client_phone,
      order_notes,
      client_email,
      data.order_code,
      cart_shop
    );
    //console.log(pdf_string);
    $.ajax({
      url: "admin/php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "sendMailConfirmation",
        total_sale: total_sale,
        client_name: client_name,
        client_lastname: client_lastname,
        client_address: client_address,
        client_state: client_state,
        client_city: client_city,
        client_email: client_email,
        client_phone: client_phone,
        order_notes: order_notes,
        cart_shop: cart_shop,
        order_code: data.order_code,
        subsidiary_phone: data.subsidiary_phone,
        addressShip: data.addressShip,
        pdf_string: pdf_string,
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
            loading();
            location.href = "thankyou.php";
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

  async function sendMailConfirmationSubDelivery(
    data,
    total_sale,
    client_name,
    client_lastname,
    client_email,
    order_notes,
    cart_shop,
    client_phone
  ) {
    loading();
    sessionStorage.setItem("order_code", data.order_code);

    var pdf_string = await generateSalePDF(
      client_name,
      client_lastname,
      data.addressShip,
      client_phone,
      order_notes,
      client_email,
      data.order_code,
      cart_shop
    );
    //console.log(pdf_string);

    $.ajax({
      url: "admin/php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "sendMailConfirmationSubsDelivery",
        total_sale: total_sale,
        client_name: client_name,
        client_lastname: client_lastname,
        client_email: client_email,
        order_notes: order_notes,
        cart_shop: cart_shop,
        order_code: data.order_code,
        subsidiary_name: data.subsidiary_name,
        subsidiary_phone: data.subsidiary_phone,
        addressShip: data.addressShip,
        client_phone: client_phone,
        pdf_string,
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
            loading();
            location.href = "thankyou.php";
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
  async function generateSalePDF(
    client_name,
    client_lastname,
    client_address,
    client_phone,
    order_notes,
    client_email,
    order_code,
    cart_shop
  ) {
    loading();
    var data = await asyncAjax(cart_shop);
    data = JSON.parse(data);

    /* if (data.response == true) { */
    var string = await generateOrderIncomePDF(
      client_name,
      client_lastname,
      client_address,
      data,
      client_phone,
      order_notes,
      client_email,
      order_code
    );
    return string;
  }

  function asyncAjax(cart_shop) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: "admin/php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "getProductsPDF",
          cart_shop: cart_shop,
        },
        beforeSend: function () {},
        success: function (data) {
          resolve(data); // Resolve promise and when success
        },
        error: function (err) {
          reject(err); // Reject the promise and go to catch()
        },
      });
    });
  }

  async function generateOrderIncomePDF(
    client_name,
    client_lastname,
    client_address,
    cart_shop,
    client_phone,
    order_notes,
    client_email,
    order_code
  ) {
    console.log(cart_shop);
    window.jsPDF = window.jspdf.jsPDF;
    var doc = new jsPDF("portrait");
    var font = getFont();
    doc.addFileToVFS("assets/fonts/VarelaRound-Regular.ttf", font);
    doc.addFont(
      "assets/fonts/VarelaRound-Regular.ttf",
      "VarelaRound-Regular",
      "normal"
    );

    let date = new Date();
    let output =
      String(date.getDate()).padStart(2, "0") +
      "/" +
      String(date.getMonth() + 1).padStart(2, "0") +
      "/" +
      date.getFullYear();

    var sbj_final = 0;
    //var order_code = data.info_order[0].order_code;
    //var subsidiary_name = data.info_order[0].subsidiary_name;
    //var date_register = data.info_order[0].date_register;
    //var subsidiary_name = data.info_order[0].order_code;
    //var username = data.info_order[0].username;
    //var status_description = data.info_order[0].status_description;
    //--- --- ---//
    //--- --- ---//
    var table_titles = ["Producto", "Precio U.", "Cant.", "Total"];
    products = [];
    var total_sale = 0;
    for (let prod = 0; prod < cart_shop.products.length; prod++) {
      var data_product = [
        cart_shop.products[prod].name,
        parseFloat(cart_shop.products[prod].price).toFixed(2),
        cart_shop.products[prod].quantity,
        parseFloat(cart_shop.products[prod].prod_total).toFixed(2),
      ];
      products.push(data_product);
    }

    lastPositions = 25;

    doc.autoTable({
      theme: "plain",
      startY: lastPositions,
      tableWidth: 180,
      margin: {
        left: 8,
      },
      headStyles: {
        halign: "left",
        valign: "middle",
        font: "VarelaRound-Regular",
        fillColor: [255, 255, 255],
        textColor: [0, 0, 0],
        fontSize: 10,
      },
      bodyStyles: {
        font: "VarelaRound-Regular",
        fillColor: [255, 255, 255],
        textColor: [0, 0, 0],
        fontSize: 13,
      },
      columnStyles: {
        0: {
          cellWidth: 180,
        },
      },
      body: [
        [
          {
            content: "DETALLES DE COMPRA",
            styles: { borders: "b" },
          },
        ],
      ],
    });
    lastPositions = doc.lastAutoTable.finalY + 10;

    doc.autoTable({
      theme: "plain",
      startY: lastPositions,
      tableWidth: 180,
      margin: {
        left: 8,
      },
      headStyles: {
        halign: "left",
        valign: "middle",
        font: "VarelaRound-Regular",
        fillColor: [43, 255, 255],
        textColor: [0, 0, 0],
        fontSize: 10,
      },
      bodyStyles: {
        font: "VarelaRound-Regular",
        fillColor: [255, 255, 255],
        textColor: [0, 0, 0],
        fontSize: 10,
      },
      columnStyles: {
        0: {
          cellWidth: 180,
        },
      },
      body: [
        [
          {
            content:
              "Código de Órden: " +
              order_code +
              "\n \nNombre Cliente: " +
              client_name +
              " " +
              client_lastname +
              "\nNúmero Cliente: " +
              client_phone +
              "\nCorreo Cliente: " +
              client_email +
              "\n \nDirección entrega: " +
              client_address +
              "\nNotas de órden: " +
              order_notes +
              "\n \nFecha de emisión: " +
              output,
            styles: { halign: "left" },
          },
        ],
      ],
    });
    lastPositions = doc.lastAutoTable.finalY + 7;

    doc.autoTable({
      theme: "striped",
      startY: lastPositions,
      tableWidth: 180,
      margin: {
        left: 8,
      },
      headStyles: {
        halign: "left",
        valign: "middle",
        font: "VarelaRound-Regular",
        fillColor: [44, 69, 191],
        textColor: [255, 255, 255],
        fontSize: 10,
      },
      bodyStyles: {
        font: "VarelaRound-Regular",
        fillColor: [255, 255, 255],
        textColor: [0, 0, 0],
        fontSize: 10,
      },
      head: [table_titles],
      body: products,
    });
    //--- --- ---//
    lastPositions = doc.lastAutoTable.finalY + 10;

    doc.setFontSize(14);
    doc.text(
      130,
      lastPositions,
      "Total de articulos: " + cart_shop.products.length
    );
    lastPositions = lastPositions + 8;
    doc.text(130, lastPositions, "Costo total: $ " + cart_shop.total_sale);

    doc.addImage(getMainLogo(), "png", 8, 5, 40, 20);
    let string = doc.output("datauristring");
    //doc.save("ORDEN COMPRA" + ".pdf");
    /* 
    Swal.close(); */
    return string;
    await timer(2000);

    //--- --- ---//
  }

  function timer(ms) {
    return new Promise((res) => setTimeout(res, ms));
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
