$(document).ready(function () {
  $(document).on("click", "#saveNewProduct", function () {
    var prod_code = $("#prod_code").val().trim();
    var prod_name = $("#prod_name").val().trim();
    var prod_brand = $("#prod_brand option:selected").val();
    var prod_sku = $("#prod_sku").val();
    var prod_barcode = $("#prod_barcode").val();
    var prod_meassure = $("#prod_meassure option:selected").val();
    var prod_purchase_price = $("#prod_purchase_price").val();
    var prod_price = $("#prod_price").val();
    var prod_bulk = 0;
    if ($("#prod_bulk").is(":checked")) {
      prod_bulk = 1;
    }
    var prod_stock = $("#prod_stock").val();
    var prod_min_stock = $("#prod_min_stock").val();
    var prod_max_stock = $("#prod_max_stock").val();
    var prod_description = $("#prod_description").val();
    const prod_image = document.querySelector("#prod_image");

    /*  console.log("prod_code: " + prod_code);
    console.log("prod_name: " + prod_name);
    console.log("prod_brand: " + prod_brand);
    console.log("prod_sku: " + prod_sku);
    console.log("prod_barcode: " + prod_barcode);
    console.log("prod_meassure: " + prod_meassure);
    console.log("prod_purchase_price: " + prod_purchase_price);
    console.log("prod_price: " + prod_price);
    console.log("prod_bulk: " + prod_bulk);
    console.log("prod_stock: " + prod_stock);
    console.log("prod_min_stock: " + prod_min_stock);
    console.log("prod_max_stock: " + prod_max_stock);
    console.log("prod_description: " + prod_description);
    console.log("prod_image: " + prod_image.files[0]); */
    if (
      prod_code != null &&
      prod_code != "" &&
      prod_code != undefined &&
      prod_name != null &&
      prod_name != "" &&
      prod_name != undefined &&
      prod_brand != null &&
      prod_brand != "" &&
      prod_brand != undefined &&
      prod_sku != null &&
      prod_sku != "" &&
      prod_sku != undefined &&
      prod_barcode != null &&
      prod_barcode != "" &&
      prod_barcode != undefined &&
      prod_meassure != null &&
      prod_meassure != "" &&
      prod_meassure != undefined &&
      prod_purchase_price != null &&
      prod_purchase_price != "" &&
      prod_purchase_price != undefined &&
      prod_price != null &&
      prod_price != "" &&
      prod_price != undefined &&
      prod_stock != null &&
      prod_stock != "" &&
      prod_stock != undefined &&
      prod_min_stock != null &&
      prod_min_stock != "" &&
      prod_min_stock != undefined &&
      prod_max_stock != null &&
      prod_max_stock != "" &&
      prod_max_stock != undefined &&
      prod_description != null &&
      prod_description != "" &&
      prod_description != undefined &&
      prod_image.files.length > 0
    ) {
      saveNewProd();
    } else {
      Swal.fire({
        title: "No puede dejar campos obligatorios vacíos!!!",
        icon: "error",
      });
    }
  });

  $(document).on("click", ".btnSeeStockSubsidiary", function () {
    loading();
    var id_product = $(this).attr("data-id-product");
    var product_name = $(this).attr("data-product-name");

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "getProductStocks",
        id_product: id_product,
        product_name: product_name,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        //console.log(data);
        if (data.response == true) {
          Swal.close();
          $("#divStocksSubsidiarys").html(data.html);
        } else {
          Swal.close();
          $("#divStocksSubsidiarys").html(data.html);
        }
      })
      .fail(function (message) {
        Swal.fire({
          title: "No se pudoo completar el proceso!",
          icon: "error",
        });
      });
  });

  $(document).on("click", ".btnGenerateBarcode", function () {
    var product_barcode = $(this).attr("data-barcode");
    JsBarcode("#barcodeImg", product_barcode, { format: "itf14" });

    /* printBarcode(); */
  });
  $(document).on("dblclick", ".tdStock", function () {
    var stock = $(this).attr("data-stock");
    var id_subsidiary = $(this).attr("data-id-subsidiary");
    var id_product = $(this).attr("data-id-prod");

    //console.log(ammount);
    var html = "";
    html +=
      '<input class="editSubsStock" data-id-subsidiary="' +
      id_subsidiary +
      '" id="editStockSubs' +
      id_subsidiary +
      '" data-id-prod="' +
      id_product +
      '" type="text" data-og-stock="' +
      stock +
      '" value="' +
      stock +
      '">';
    $(this).closest("td").removeClass("tdStock");
    /* $(this).closest("td").removeClass("text-center"); */
    $(this).closest("td").html(html);

    var strLength = $("#editStockSubs" + id_subsidiary).val().length * 2;

    $("#editStockSubs" + id_subsidiary).focus();
    $("#editStockSubs" + id_subsidiary)[0].setSelectionRange(
      strLength,
      strLength
    );

    $("#editStockSubs" + id_subsidiary).focus();
  });

  /* $(document).on("focusout", ".editSubsStock", function () {
    loading();
    let USDollar = new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",
    });
    //--- --- ---//
    var stock = $(this).val();
    var id_subsidiary = $(this).attr("data-id-subsidiary");
    var id_product = $(this).attr("data-id-prod");
    var og_stock = $(this).attr("data-og-stock");

    var html = "";
    //    html += USDollar.format(stock);
    html += stock;

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "updateStocksSubsidiary",
        stock: stock,
        id_subsidiary: id_subsidiary,
        id_product: id_product,
      },
    })
      .done(function (data) {
        Swal.close();

        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          doneToast(data.message);
          $("#tdStockSubs" + id_subsidiary)
            .empty()
            .html(html);
          $("#tdStockSubs" + id_subsidiary).addClass("tdStock");
          $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
          $(this).closest("td").addClass("text-center");
          var total_stock = $("#tdTotalStock").attr("data-total-stock");
          total_stock = parseFloat(total_stock);
          if (og_stock != "") {
            og_stock = parseFloat(og_stock);
          } else {
            og_stock = 0;
          }
          if (stock != "") {
            stock = parseFloat(stock);
          } else {
            stock = 0;
          }

          var new_total_stock = total_stock - og_stock;
          new_total_stock = new_total_stock + stock;
          console.log(og_stock);
          $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
          $("#tdTotalStock").text(new_total_stock);
          $("#tdTotalStock").attr("data-total-stock", new_total_stock);

          $("#tdTotalStock").fadeOut(1000, function () {
            $("#tdTotalStock").text(new_total_stock).fadeIn(500);
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

    //--- --- ---//
  }); */

  $(document).on("keypress", ".editSubsStock", function (event) {
    var keycode = event.keyCode || event.which;
    if (keycode == 13) {
      loading();
      var stock = $(this).val();
      var id_subsidiary = $(this).attr("data-id-subsidiary");
      var id_product = $(this).attr("data-id-prod");
      var og_stock = $(this).attr("data-og-stock");

      var html = "";
      //    html += USDollar.format(stock);
      html += stock;

      $.ajax({
        url: "php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "updateStocksSubsidiary",
          stock: stock,
          id_subsidiary: id_subsidiary,
          id_product: id_product,
        },
      })
        .done(function (data) {
          Swal.close();

          var data = JSON.parse(data);
          /* console.log(data); */
          if (data.response == true) {
            doneToast(data.message);
            $("#tdStockSubs" + id_subsidiary)
              .empty()
              .html(html);
            $("#tdStockSubs" + id_subsidiary).addClass("tdStock");
            $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
            $(this).closest("td").addClass("text-center");
            var total_stock = $("#tdTotalStock").attr("data-total-stock");
            total_stock = parseFloat(total_stock);
            if (og_stock != "") {
              og_stock = parseFloat(og_stock);
            } else {
              og_stock = 0;
            }
            if (stock != "") {
              stock = parseFloat(stock);
            } else {
              stock = 0;
            }

            var new_total_stock = total_stock - og_stock;
            new_total_stock = new_total_stock + stock;
            /* console.log(og_stock); */
            $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
            $("#tdTotalStock").text(new_total_stock);
            $("#tdTotalStock").attr("data-total-stock", new_total_stock);

            $("#tdTotalStock").fadeOut(1000, function () {
              $("#tdTotalStock").text(new_total_stock).fadeIn(500);
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

      //--- --- ---//
    }
  });
  $(document).on("keypress", ".editProduct", function (event) {
    var keycode = event.keyCode || event.which;
    if (keycode == 13) {
      loading();
      var stock = $(this).val();
      var id_subsidiary = $(this).attr("data-id-subsidiary");
      var id_product = $(this).attr("data-id-prod");
      var og_stock = $(this).attr("data-og-stock");

      var html = "";
      //    html += USDollar.format(stock);
      html += stock;

      $.ajax({
        url: "php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "updateStocksSubsidiary",
          stock: stock,
          id_subsidiary: id_subsidiary,
          id_product: id_product,
        },
      })
        .done(function (data) {
          Swal.close();

          var data = JSON.parse(data);
          /* console.log(data); */
          if (data.response == true) {
            doneToast(data.message);
            $("#tdStockSubs" + id_subsidiary)
              .empty()
              .html(html);
            $("#tdStockSubs" + id_subsidiary).addClass("tdStock");
            $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
            $(this).closest("td").addClass("text-center");
            var total_stock = $("#tdTotalStock").attr("data-total-stock");
            total_stock = parseFloat(total_stock);
            if (og_stock != "") {
              og_stock = parseFloat(og_stock);
            } else {
              og_stock = 0;
            }
            if (stock != "") {
              stock = parseFloat(stock);
            } else {
              stock = 0;
            }

            var new_total_stock = total_stock - og_stock;
            new_total_stock = new_total_stock + stock;
            /* console.log(og_stock); */
            $("#tdStockSubs" + id_subsidiary).attr("data-stock", stock);
            $("#tdTotalStock").text(new_total_stock);
            $("#tdTotalStock").attr("data-total-stock", new_total_stock);

            $("#tdTotalStock").fadeOut(1000, function () {
              $("#tdTotalStock").text(new_total_stock).fadeIn(500);
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

      //--- --- ---//
    }
  });

  $(document).on("click", ".editProduct", function (event) {
    loading();
    var id_product = $(this).attr("data-id-product");

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "getProductInfo",
        id_product: id_product,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $("#edit_prod_code").val(data.prod_info[0].product_short_name);
          $("#edit_prod_name").val(data.prod_info[0].product_name);
          $("#edit_prod_brand").val(data.prod_info[0].id_brands); // Select the option with a value of '1'
          $("#edit_prod_brand").trigger("change"); // Notify any JS components that the value changed
          $("#edit_prod_sku").val(data.prod_info[0].product_code);
          $("#edit_prod_barcode").val(data.prod_info[0].product_barcode);
          $("#edit_prod_meassure").val(data.prod_info[0].id_measurement_units); // Select the option with a value of '1'
          $("#edit_prod_meassure").trigger("change"); // Notify any JS components that the value changed
          $("#edit_prod_purchase_price").val(data.prod_info[0].purchase_price);
          $("#edit_prod_price").val(data.prod_info[0].price);
          if (data.prod_info[0].bulk_sell) {
            $("#edit_prod_bulk").prop("checked", true);
          }
          $("#edit_prod_stock").val(data.prod_info[0].stock);
          $("#edit_prod_min_stock").val(data.prod_info[0].min_stock);
          $("#edit_prod_max_stock").val(data.prod_info[0].ideal_stock);
          $("#edit_prod_description").val(data.prod_info[0].description);
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

    //--- --- ---//
  });

  $(document).on("click", ".updateProduct", function (event) {
    loading();
    var id_product = $(this).attr("data-id-product");

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "getProductInfo",
        id_product: id_product,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var prod_code = $("#edit_prod_code").val().trim();
          var prod_name = $("#edit_prod_name").val().trim();
          var prod_brand = $("#edit_prod_brand option:selected").val();
          var prod_sku = $("#edit_prod_sku").val();
          var prod_barcode = $("#edit_prod_barcode").val();
          var prod_meassure = $("#edit_prod_meassure option:selected").val();
          var prod_purchase_price = $("#edit_prod_purchase_price").val();
          var prod_price = $("#edit_prod_price").val();
          var prod_bulk = 0;
          if ($("#edit_prod_bulk").is(":checked")) {
            prod_bulk = 1;
          }
          var prod_stock = $("#edit_prod_stock").val();
          var prod_min_stock = $("#edit_prod_min_stock").val();
          var prod_max_stock = $("#edit_prod_max_stock").val();
          var prod_description = $("#edit_prod_description").val();
          const prod_image = document.querySelector("#prod_image");
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

    //--- --- ---//
  });

  function saveNewProd() {
    loading();
    var prod_code = $("#prod_code").val().trim();
    var prod_name = $("#prod_name").val().trim();
    var prod_brand = $("#prod_brand option:selected").val();
    var prod_sku = $("#prod_sku").val();
    var prod_barcode = $("#prod_barcode").val();
    var prod_meassure = $("#prod_meassure option:selected").val();
    var prod_purchase_price = $("#prod_purchase_price").val();
    var prod_price = $("#prod_price").val();
    var prod_bulk = 0;
    if ($("#prod_bulk").is(":checked")) {
      prod_bulk = 1;
    }
    var prod_stock = $("#prod_stock").val();
    var prod_min_stock = $("#prod_min_stock").val();
    var prod_max_stock = $("#prod_max_stock").val();
    var prod_description = $("#prod_description").val();
    const prod_image = document.querySelector("#prod_image");

    if (
      prod_code != null &&
      prod_code != "" &&
      prod_code != undefined &&
      prod_name != null &&
      prod_name != "" &&
      prod_name != undefined &&
      prod_brand != null &&
      prod_brand != "" &&
      prod_brand != undefined &&
      prod_sku != null &&
      prod_sku != "" &&
      prod_sku != undefined &&
      prod_barcode != null &&
      prod_barcode != "" &&
      prod_barcode != undefined &&
      prod_meassure != null &&
      prod_meassure != "" &&
      prod_meassure != undefined &&
      prod_purchase_price != null &&
      prod_purchase_price != "" &&
      prod_purchase_price != undefined &&
      prod_price != null &&
      prod_price != "" &&
      prod_price != undefined &&
      prod_stock != null &&
      prod_stock != "" &&
      prod_stock != undefined &&
      prod_min_stock != null &&
      prod_min_stock != "" &&
      prod_min_stock != undefined &&
      prod_max_stock != null &&
      prod_max_stock != "" &&
      prod_max_stock != undefined &&
      prod_description != null &&
      prod_description != "" &&
      prod_description != undefined &&
      prod_image.files.length > 0
    ) {
      let formData = new FormData();
      formData.append("mod", "saveNewProd");
      formData.append("prod_image", prod_image.files[0]);
      formData.append("prod_code", prod_code);
      formData.append("prod_name", prod_name);
      formData.append("prod_brand", prod_brand);
      formData.append("prod_sku", prod_sku);
      formData.append("prod_barcode", prod_barcode);
      formData.append("prod_meassure", prod_meassure);
      formData.append("prod_price", prod_price);
      formData.append("prod_purchase_price", prod_purchase_price);
      formData.append("prod_bulk", prod_bulk);
      formData.append("prod_stock", prod_stock);
      formData.append("prod_min_stock", prod_min_stock);
      formData.append("prod_max_stock", prod_max_stock);
      formData.append("prod_description", prod_description);
      formData.append("prod_image", prod_image);

      fetch("php/controllers/articles/articles_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          loading();
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Registro guardado exitosamente",
            timer: 3000,
          }).then((result) => {
            loading();
            location.reload();
          });
        });
    }
  }

  function printBarcode() {
    // printJS('printable', 'html')

    let printFrame = document.createElement("iframe");
    let printableElement = document.getElementById("barcodeImg");
    //
    // // printframe.setattribute("style", "visibility: hidden; height: 0; width: 0; position: absolute;")
    printFrame.setAttribute("id", "printjs");
    printFrame.srcdoc =
      "<html><head><title>document</title></head><body style='margin: 0;'>" +
      printableElement.outerHTML +
      "<style>@page { size: A4; } #barcodeImg { margin-left: 2.85cm; width: 1.6cm; height: 0.1cm; } #barcodeImg .barcode { width: 100%; }</style> </body></html>";

    document.body.appendChild(printFrame);

    let iframeElement = document.getElementById("printjs");
    iframeElement.focus();
    iframeElement.contentWindow.print();
    //
    // printframe.contentwindow.print()
    //
    // my_window = window.open('', 'mywindow', 'status=1,width=350,height=150');
    // my_window.document.write('<html><head><title>Print Me</title></head>');
    // my_window.document.write('<body onafterprint="self.close()">');
    // my_window.document.write(printablEelement.innerHTML);
    // my_window.document.write('</body></html>');
    // my_window.print();
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
  function doneToast(text) {
    Toastify({
      text: text,
      duration: 3000,
      destination: "https://github.com/apvarun/toastify-js",
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "right", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      style: {
        background: "#00b09b",
        //background: "linear-gradient(to right, #00b09b, #96c93d)",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }
  function errorToast(text) {
    Toastify({
      text: text,
      duration: 3000,
      destination: "https://github.com/apvarun/toastify-js",
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "left", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      style: {
        background: "#ff3333",
        //background: "linear-gradient(to right, #00b09b, #96c93d)",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }

  $(".js-example-basic-single").select2();

  $("#prod_brand").select2({
    dropdownParent: $("#modalNewArticle"),
  });
  $("#prod_meassure").select2({
    dropdownParent: $("#modalNewArticle"),
  });

  $("#edit_prod_brand").select2({
    dropdownParent: $("#modalEditArticle"),
  });
  $("#edit_prod_meassure").select2({
    dropdownParent: $("#modalEditArticle"),
  });
});
