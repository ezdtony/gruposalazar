$(document).ready(function () {
  $(document).on("change", "#selectState", function () {
    var id_estado = this.value;
    $.ajax({
      url: "php/controllers/colabs/colab_controller.php",
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

  $(document).on("click", "#saveNewSupplier", function () {
    loading();
    var company = $("#company").val().trim();
    var contact_name = $("#contact_name").val().trim();
    var contact_mail = $("#contact_mail").val();
    var contact_phone = $("#contact_phone").val();
    var contact_street = $("#contact_street").val();
    var contact_ext_num = $("#contact_ext_num").val();
    var contact_int_num = $("#contact_int_num").val();
    var contact_colony = $("#contact_colony").val();
    var contact_zipcode = $("#contact_zipcode").val();
    var state = $("#selectState option:selected").text();
    var city = $("#selectCity option:selected").text();

    if (company != null && company != "") {
      $.ajax({
        url: "php/controllers/colabs/suppliers_controller.php",
        method: "POST",
        data: {
          mod: "saveSuppliers",
          company: company,
          contact_name: contact_name,
          contact_mail: contact_mail,
          contact_phone: contact_phone,
          contact_street: contact_street,
          contact_ext_num: contact_ext_num,
          contact_int_num: contact_int_num,
          contact_colony: contact_colony,
          contact_zipcode: contact_zipcode,
          state: state,
          city: city,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            Swal.fire({
              title: data.message,
              icon: "success",
            });
            /* $("#newColabModal").find("input,textarea,select").val("");
            $("#newColabModal input[type='checkbox']")
              .prop("checked", false)
              .change();
            $("#closeModalNewUser").trigger("click");
            $("#selectState").select2("val", "");
            $("#selectCity").select2("val", "");
            $("#selectCity").attr("disabled", true);
            $("#tbodyColabs").append(data.html); */
          } else {
            Swal.fire({
              title: data.message,
              icon: "error",
            });
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudoo completar el proceso!",
            icon: "error",
          });
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
    JsBarcode("#barcodeImg", product_barcode, { format: "CODE128" });

    /* printBarcode(); */
  });
  $("#printBarcode").click(function () {
    /* window.print($("#barcodeImg")); */
    /* printJS('barcodeImg', ''); */
    printJS({
      printable: "barcodeImg",
      type: "html",
      header: "",
      maxWidth: "1201234567891233",
    });
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

              var max_stock = $("#ProgressProd" + id_product).attr(
                "aria-valuemax"
              );
              var now_stock = new_total_stock;
              var new_percentage = Math.round((now_stock / max_stock) * 100);
              var wid = "width:" + new_percentage + "%";
              if (new_percentage > 100) {
                wid = "width:100%";
                new_percentage = "+" + 100;
              }

              $("#ProgressProd" + id_product).attr("style", wid);
              $("#ProgressProd" + id_product).attr("aria-valuenow", now_stock);

              $("#txtPercentage" + id_product).text(new_percentage + "%");
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
          $(".closeModalEditProd").attr("data-id-product", id_product);
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

  $("#modalEditArticle").on("hidden.bs.modal", function () {
    $(".slctUpdateProduct").attr("allow-update", 0);
  });

  $(document).on("focusout", ".deleteProduct", function (event) {
    loading();
    var id_product = $(this).attr("data-id-product");

    Swal.fire({
      title: "Está seguro?",
      text: "Se eliminará el producto, esta acción no se podrá revertir, ¿desea continuar?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "php/controllers/articles/articles_controller.php",
          method: "POST",
          data: {
            mod: "deleteProduct",
            id_product: id_product,
          },
        })
          .done(function (data) {
            Swal.close();
            var data = JSON.parse(data);
            console.log(data);
            if (data.response == true) {
              $("#trProduct" + id_product).fadeTo("slow", 0.7, function () {
                $(this).remove();
              });

              doneToast(data.message);
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
      }
    });

    //--- --- ---//
  });

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

  let table = new DataTable("#tableProducts", {
    searching: true,
    ordering: false,
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });

  1;
  new DataTable("#tableProducts");

  $(".js-example-basic-single").select2();

  $("#selectState").select2({
    dropdownParent: $("#modalNewSupplier"),
  });
  $("#selectCity").select2({
    dropdownParent: $("#modalNewSupplier"),
  });
});
