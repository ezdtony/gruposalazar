$(document).ready(function () {
  $("#search_prod").on("keypress", function (event) {
    if (event.which == 13 && !event.shiftKey) {
      loading();
      var searchProd = $(this).val();
      var id_subsidiary = $("#id_subsidiary").val();

      $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "searchProduct",
          searchProd: searchProd,
          id_subsidiary: id_subsidiary,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          //console.log(data);
          if (data.response == true) {
            if (
              data.prod_data[0].sub_stock == "NULL" ||
              data.prod_data[0].sub_stock == null ||
              data.prod_data[0].sub_stock == 0
            ) {
              Swal.fire({
                icon: "info",
                title: "Producto sin existencias",
              });
            } else {
              $("#prod_quantity").attr("disabled", false);
              $("#prod_name").val(data.prod_data[0].product_name);
              $("#prod_price").val(data.prod_data[0].price.toFixed(2));
              $("#addProd").attr("data-barcode", searchProd);
              $("#addProd").attr(
                "data-id-product",
                data.prod_data[0].id_prducts
              );
              $("#prod_stock").text("Stock: " + data.prod_data[0].sub_stock);
              $("#prod_quantity").attr(
                "sub-stock",
                data.prod_data[0].sub_stock
              );
              Swal.close();
            }
            /* doneToast(data.message); */
          } else {
            Swal.fire({
              icon: "info",
              title: data.message,
            });
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

      $(this).val("");
      event.preventDefault();
    }
  });
  $("#prod_quantity").on("keypress", function (event) {
    if ($(this).val() != "") {
      if (event.which == 13 && !event.shiftKey) {
        loading();
        var prod_quantity = parseFloat($(this).val());
        var sub_stock = parseFloat($(this).attr("sub-stock"));

        if (sub_stock < prod_quantity) {
          Swal.fire({
            icon: "info",
            title: "Stock insuficiente",
          });
        } else {
          var price = parseFloat($("#prod_price").val());
          var subtotal = prod_quantity * price;
          $("#prod_subtotal").val(subtotal.toFixed(2));
          $("#addProd").attr("disabled", false);
          $(this).blur();
          $("#addProd").focus();
          Swal.close();
        }

        event.preventDefault();
      }
    }
  });
  $("#prod_quantity").on("focusout", function (event) {
    if ($(this).val() != "") {
      loading();
      var prod_quantity = parseFloat($(this).val());
      var sub_stock = parseFloat($(this).attr("sub-stock"));

      if (sub_stock < prod_quantity) {
        Swal.fire({
          icon: "info",
          title: "Stock insuficiente",
        });
      } else {
        var price = parseFloat($("#prod_price").val());
        var subtotal = prod_quantity * price;
        $("#prod_subtotal").val(subtotal.toFixed(2));
        $("#addProd").attr("disabled", false);

        Swal.close();
      }
    }
  });
  $("#id_subsidiary").on("change", function (event) {
    $("#search_prod").attr("disabled", false);
    $("#addProd").attr("data-barcode", "");
    $("#addProd").attr("disabled", true);
    $("#prod_name").val("");
    $("#prod_price").val("");
    $("#prod_quantity").val("");
    $("#prod_subtotal").val("");
    $("#prod_stock").text("Stock: -");
    $("#tableSale > tbody").empty();
  });

  $("#addProd").on("click", function (event) {
    loading();
    var barcode = $(this).attr("data-barcode");
    var id_product = $(this).attr("data-id-product");
    var barcode = $(this).attr("data-barcode");
    var prod_description = $("#prod_name").val();
    var price = parseFloat($("#prod_price").val());
    var prod_quantity = $("#prod_quantity").val();
    var subtotal = parseFloat($("#prod_subtotal").val());
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));

    total_sale = (total_sale + subtotal).toFixed(2);
    $("#lblTotalSale").attr("data-total", total_sale);
    $("#lblTotalSale").text("Total: $ " + total_sale + " MXN");
    var html = "";
    var row_num = $("#tableSale").find("tr").length;
    row_num - 1;
    html +=
      ' <tr data-id-product="' +
      id_product +
      '" data-price="' +
      price +
      '" data-quantity="' +
      prod_quantity +
      '"> ';
    html += '    <th scope="row">' + row_num + "</th>";
    html += "    <td>" + barcode + "</td>";
    html += "    <td>" + prod_description + "</td>";
    html += "    <td>" + price + "</td>";
    html += "    <td>" + prod_quantity + "</td>";
    html += "    <td>" + subtotal + "</td>";
    html +=
      '    <td><button type="button" class="btn btn-danger deleteProdOrder" data-subtotal="' +
      subtotal +
      '" ><i class="fa-solid fa-trash-can"></i></button></td>';
    html += "</tr>";
    $("#tableSale > tbody").append(html);
    $("#generateSale").attr("disabled", false);
    $("#cancelSale").attr("disabled", false);
    $(this).attr("data-barcode", "");
    $(this).attr("disabled", true);
    $("#prod_name").val("");
    $("#prod_price").val("");
    $("#prod_quantity").val("");
    $("#prod_subtotal").val("");
    $("#prod_stock").text("Stock: -");

    Swal.close();
  });

  $(document).on("click", ".deleteProdOrder", function (event) {
    loading();
    var subtotal = parseFloat($(this).attr("data-subtotal"));
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));

    total_sale = (total_sale - subtotal).toFixed(2);
    $("#lblTotalSale").attr("data-total", total_sale);
    $("#lblTotalSale").text("Total: $ " + total_sale + " MXN");
    $(this).closest("tr").remove();

    const tBody = $("#tableSale > tbody").find("tr");
    for (let index = 0; index < tBody.length; index++) {
      const tr = tBody[index];
      $(tr)
        .find("th:first")
        .text(index + 1);
    }
    if (tBody.length == 0) {
      $("#generateSale").attr("disabled", true);
      $("#cancelSale").attr("disabled", true);
    }
    Swal.close();
  });
  $(document).on("click", "#cancelSale", function (event) {
    Swal.fire({
      title: "Desea cancelar órden?",
      text: "Eliminará todas las partidas",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "Volver",
      confirmButtonText: "Si, cancelar!!",
    }).then((result) => {
      if (result.isConfirmed) {
        $("#tableSale > tbody").empty();
        $("#lblTotalSale").attr("data-total", 0);
        $("#lblTotalSale").text("Total:");
        Swal.fire({
          title: "Órden cancelada!",
          text: "La órden ha sido cancelada exitosamente!!",
          icon: "success",
        });
      }
    });
  });
  $(document).on("click", ".paymentMethodCard", function (event) {
    $(".paymentMethodCard").removeClass("text-bg-primary");
    $(".paymentMethodCard").removeClass("text-bg-success");
    $(".paymentMethodCard").removeClass("selected-payment-method");
    $(".paymentMethodCard").addClass("text-bg-secondary");
    $(this).removeClass("text-bg-secondary");
    $(this).removeClass("text-bg-primary");
    $(this).addClass("text-bg-success");
    $(this).addClass("selected-payment-method");

    var id_payment_method = $(this).attr("data-id-payment-method");
    switch (id_payment_method) {
      case "1":
        //EFECTIVO
        $("#divCashMethod").hide();
        processCashPayment();
        break;

      case "2":
        //TARJETA
        $("#divCashMethod").hide();
        processCashPayment();
        break;

      case "3":
        //TARJETA
        $("#divCashMethod").hide();
        processCreditSalazarPayment();
        break;
      default:
        $("#divCashMethod").hide();
        break;
    }
  });

  $("#recipt_cash").on("keypress", function (event) {
    if ($(this).val() != "") {
      if (event.which == 13 && !event.shiftKey) {
        loading();
        var cash_recipt = parseFloat($(this).val());
        var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));

        if (cash_recipt < total_sale) {
          Swal.fire({
            icon: "info",
            title:
              "La cantidad a recibir debe ser mayor o igual al total de venta",
          });
        } else {
          var exchange = cash_recipt - total_sale;
          $("#lblCashExchange").text("Cambio: " + exchange.toFixed(2));
          $("#btnSaveSaleCash").attr("disabled", false);
          $(this).blur();
          Swal.close();
        }

        event.preventDefault();
      }
    }
  });

  $(document).on("click", "#btnSaveSaleCash", function (event) {
    loading();
    var id_client = $("#id_client").val();
    if (id_client == "" || id_client == undefined) {
      id_client = 2;
    }
    //var id_offer = $("#id_offer").val();
    var id_offer = 1;
    var id_payment_method = $(".selected-payment-method").attr(
      "data-id-payment-method"
    );
    var id_subsidiary = $("#id_subsidiary").val();
    var pikup_subsidiary = $("#id_subsidiary").val();
    var ammount = parseFloat($("#lblTotalSale").attr("data-total"));
    var products = [];

    const tBody = $("#tableSale > tbody").find("tr");
    for (let index = 0; index < tBody.length; index++) {
      const tr = tBody[index];
      var id_product = $(tr).attr("data-id-product");
      var quantity = $(tr).attr("data-quantity");
      var price = $(tr).attr("data-price");

      products.push({ id_product, quantity, price });
    }
    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "SaveOrderCash",
        id_client: id_client,
        id_offer: id_offer,
        id_payment_method: id_payment_method,
        id_subsidiary: id_subsidiary,
        pikup_subsidiary: pikup_subsidiary,
        ammount: ammount,
        products: products,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          Swal.fire({
            title: "Venta guardada",
            text: "La venta se ha registrado",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#32a852",
            confirmButtonText: "Acepar",
          }).then((result) => {
            loading();
            location.reload();
          });
        } else {
          errorToast(data.message);
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
  });
  $(document).on("click", "#btnSaveSaleCredit", function (event) {
    loading();
    var id_client = $("#id_client").val();
    //var id_offer = $("#id_offer").val();
    var id_offer = 1;
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));
    var id_payment_method = $(".selected-payment-method").attr(
      "data-id-payment-method"
    );
    var id_subsidiary = $("#id_subsidiary").val();
    var pikup_subsidiary = $("#id_subsidiary").val();
    var ammount = parseFloat($("#lblTotalSale").attr("data-total"));
    var products = [];

    const tBody = $("#tableSale > tbody").find("tr");
    for (let index = 0; index < tBody.length; index++) {
      const tr = tBody[index];
      var id_product = $(tr).attr("data-id-product");
      var quantity = $(tr).attr("data-quantity");
      var price = $(tr).attr("data-price");

      products.push({ id_product, quantity, price });
    }
    var credit_client = $("#credit_client").val();
    var credit_deadlines = $("#credit_deadlines").val();
    var var_interests = 0;
    if ($("#check_interests").prop("checked")) {
      var_interests = 1;
    }

    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "SaveOrderCredit",
        id_client: id_client,
        id_offer: id_offer,
        id_payment_method: id_payment_method,
        id_subsidiary: id_subsidiary,
        pikup_subsidiary: pikup_subsidiary,
        ammount: ammount,
        products: products,
        credit_client:credit_client,
        credit_deadlines:credit_deadlines,
        var_interests:var_interests,
        total_sale:total_sale
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          Swal.fire({
            title: "Venta guardada",
            text: "La venta se ha registrado",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#32a852",
            confirmButtonText: "Acepar",
          }).then((result) => {
            loading();
            location.reload();
          });
        } else {
          errorToast(data.message);
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
  });

  function processCashPayment() {
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));
    $("#divCashMethod").show();
    $("#lblTotalSalePayment").text("Total: $ " + total_sale + " MXN");
  }
  function processCardPayment() {
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));
    $("#divCashMethod").show();
    $("#lblTotalSalePayment").text("Total: $ " + total_sale + " MXN");
  }
  function processCreditSalazarPayment() {
    var total_sale = parseFloat($("#lblTotalSale").attr("data-total"));
    loading();
    var id_subsidiary = $("#id_subsidiary").val();
    var id_client = $("#id_client").val();
    if (id_client != "") {
      $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "getCreditSalazarClient",
          total_sale: total_sale,
          id_client: id_client,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          //console.log(data);
          if (data.response == true) {
            html = data.html;
            $("#divCreditMethod").html(html);
            $("#divCreditMethod").show();
          } else {
            Swal.fire({
              icon: "info",
              title: data.message,
            });
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

      $(this).val("");
      $("#lblTotalSalePayment").text("Total: $ " + total_sale + " MXN");
    } else {
      Swal.fire({
        title: "No se seleccionó un cliente!!",
        icon: "info",
      });
    }
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

  $(".js-example-basic-single").select2();
});
