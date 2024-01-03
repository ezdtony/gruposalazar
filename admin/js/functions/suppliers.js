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

  $("#showAdress").change(function () {
    if (this.checked) {
      $("#divAddress").show();
    } else {
      $("#divAddress").hide();
    }
    $("#textbox1").val(this.checked);
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

    var active_address = 0;
    if ($("#showAdress").is(":checked")) {
      active_address = 1;

      console.log(active_address);
    }

    if (
      company != null &&
      company != "" &&
      contact_name != null &&
      contact_name != "" &&
      contact_mail != null &&
      contact_mail != "" &&
      contact_phone != null &&
      contact_phone != ""
    ) {
      $.ajax({
        url: "php/controllers/suppliers/suppliers_controller.php",
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
          active_address: active_address,
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
            $("#tableSuppliers").append(data.html);

            $("#modalNewSupplier").find("input,textarea,select").val("");
            $("#modalNewSupplier input[type='checkbox']")
              .prop("checked", false)
              .change();
            $(".closeModalNewSupplier").trigger("click");
            $("#selectState").select2("val", "");
            $("#selectCity").select2("val", "");
            $("#selectCity").attr("disabled", true);
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
    } else {
      Swal.fire({
        title: "Por favor ingrese todos los datos obligatorios!",
        icon: "error",
      });
    }
  });

  $(document).on("click", ".editSuppliers", function (event) {
    loading();
    var id_supplier = $(this).attr("data-id-supplier");
    console.log(id_supplier);

    $.ajax({
      url: "php/controllers/suppliers/suppliers_controller.php",
      method: "POST",
      data: {
        mod: "getSupplierInfo",
        id_supplier: id_supplier,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $(".closeModalEditSupplier").attr("data-id-supplier", id_supplier);
          $("#edit_company").val(data.supplier_info[0].supplier);
          $("#edit_contact_name").val(data.supplier_info[0].contact_name);
          $("#edit_contact_mail").val(data.supplier_info[0].email_contact);
          $("#edit_contact_phone").val(data.supplier_info[0].cellphone_contact);
          $("#edit_contact_address").val(
            data.supplier_info[0].address_supplier
          );
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

  $(document).on("focusout", ".inputSupplierInfo", function (event) {
    let column_name = $(this).attr("column-name");
    let new_val = $(this).val();
    let id_supplier = $(".closeModalEditSupplier").attr("data-id-supplier");

    console.log(column_name);
    console.log(id_supplier);
    loading();
    $.ajax({
      url: "php/controllers/suppliers/suppliers_controller.php",
      method: "POST",
      data: {
        mod: "updateSupplier",
        id_supplier: id_supplier,
        column_name: column_name,
        new_val: new_val,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $("#td_" + column_name + "_" + id_supplier).text(new_val);
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
  });

  $("#modalEditArticle").on("hidden.bs.modal", function () {
    $(".slctUpdateProduct").attr("allow-update", 0);
  });

  $(document).on("click", ".deleteSuppliers", function (event) {
    loading();
    var id_supplier = $(this).attr("data-id-supplier");

    Swal.fire({
      title: "Está seguro?",
      text: "Se eliminará el proveedor, esta acción no se podrá revertir, ¿desea continuar?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "php/controllers/suppliers/suppliers_controller.php",
          method: "POST",
          data: {
            mod: "deleteSupplier",
            id_supplier: id_supplier,
          },
        })
          .done(function (data) {
            Swal.close();
            var data = JSON.parse(data);
            console.log(data);
            if (data.response == true) {
              $("#trSupplier" + id_supplier).fadeTo("slow", 0.7, function () {
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
