$(document).ready(function () {
  $(document).on("change", "#selectStateBill", function () {
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
        $("#selectCityBill").prop("disabled", false);
        if (data.response == true) {
          $("#selectCityBill").empty();
          $("#selectCityBill").append(
            '<option value="">Seleccione un municipio</option>'
          );
          for (var i = 0; i < data.data.length; i++) {
            $("#selectCityBill").append(
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

  $(document).on("change", "#editClientSelectStateBill", async function () {
    var id_estado = this.value;
    // Llamamos a la nueva función updateStateEdit con el id del estado seleccionado
    await updateStateEdit(id_estado, "");
  });

  // Función para actualizar el estado y los municipios
  async function updateStateEdit(id_state, id_city) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "php/controllers/colabs/colab_controller.php",
        method: "POST",
        data: {
          mod: "getMunicipios",
          id_estado: id_state,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          $("#editClientSelectCityBill").prop("disabled", false);
          if (data.response == true) {
            $("#editClientSelectCityBill").empty();
            $("#editClientSelectCityBill").append(
              '<option value="">Seleccione un municipio</option>'
            );
            for (var i = 0; i < data.data.length; i++) {
              $("#editClientSelectCityBill").append(
                '<option value="' +
                  data.data[i].id +
                  '">' +
                  data.data[i].municipio +
                  "</option>"
              );
            }

            // Si hay un id de ciudad, seleccionarlo
            if (id_city) {
              $("#editClientSelectCityBill").val(id_city);
            }
            resolve();
          } else {
            Swal.fire({
              icon: "error",
              title: "Verifique los datos ingresados",
            });
            reject("Error al obtener los municipios");
          }
        })
        .fail(function (message) {
          VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
          reject("Error en la solicitud AJAX");
        });
    });
  }

  $(document).on("click", "#btnSaveNewClient", function () {
    loading();

    var name = $("#name_new_client").val();
    var lastname = $("#lastname_new_client").val();
    var email = $("#mail").val();
    var phonenumber = $("#phone_number").val();

    var razon_social = $("#razon_social").val();
    var rfc = $("#rfc").val();
    var street = $("#street").val();
    var ext_num = $("#ext_num").val();
    var int_num = $("#int_num").val();
    var colony = $("#colony").val();
    var locality = $("#locality").val();
    var zipcode = $("#zipcode").val();
    var state = $("#selectStateBill").val();
    var city = $("#selectCityBill").val();

    var password = $("#password").val();
    var cdfi = $("#select-uso-cfdi").val();
    var reg_fiscal = $("#select-reg-fiscal").val();

    if (
      name != "" &&
      name != undefined &&
      lastname != "" &&
      lastname != undefined &&
      email != "" &&
      email != undefined &&
      phonenumber != "" &&
      phonenumber != undefined &&
      password != "" &&
      password != undefined
    ) {
      console.log("save");

      $.ajax({
        url: "php/controllers/clients/clients_controller.php",
        method: "POST",
        data: {
          mod: "saveClient",
          name: name,
          lastname: lastname,
          email: email,
          phonenumber: phonenumber,
          password: password,
          razon_social: razon_social,
          rfc: rfc,
          street: street,
          ext_num: ext_num,
          int_num: int_num,
          colony: colony,
          locality: locality,
          zipcode: zipcode,
          state: state,
          city: city,
          cdfi: cdfi,
          reg_fiscal: reg_fiscal,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            Swal.fire({
              title: data.message,
              icon: "success",
            }).then((result) => {
              loading();
              location.reload();
            });
          } else {
            Swal.fire({
              title: data.message,
              icon: "error",
            });
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudo completar el proceso!",
            icon: "error",
          });
        });
    } else {
      Swal.fire({
        title: "No puede dejar campos obligatorios vacíos!!!",
        icon: "error",
      });
    }
  });

  $(document).on("click", "#btnSaveClientEdits", function () {
    loading();

    var clientId = $(this).attr("data-id-client"); // Asumiendo que el ID del cliente está en un campo oculto con id "clientId"
    console.log(clientId);
    var name = $("#editClientName").val();
    var lastname = $("#editClientLastname").val();
    var email = $("#editClientMail").val();
    var phonenumber = $("#editClientCellphone").val();
    var password = $("#editClientPassword").val();
    var razon_social = $("#editClientRazonSocial").val();
    var rfc = $("#editClientRFC").val();
    var street = $("#editClientStreet").val();
    var ext_num = $("#editClientExt_num").val();
    var int_num = $("#editClientInt_num").val();
    var colony = $("#editClientColony").val();
    var locality = $("#editClientLocality").val();
    var zipcode = $("#editClientZipcode").val();
    var state = $("#editClientSelectStateBill").val();
    var city = $("#editClientSelectCityBill").val();

    var cdfi = $("#select-edit-uso-cfdi").val();
    var reg_fiscal = $("#select-edit-reg-fiscal").val();

    if (
      name != "" &&
      lastname != "" &&
      email != "" &&
      phonenumber != "" &&
      password != "" &&
      razon_social != "" &&
      rfc != "" &&
      street != "" &&
      ext_num != "" &&
      colony != "" &&
      locality != "" &&
      zipcode != "" &&
      state != "" &&
      city != ""
    ) {
      $.ajax({
        url: "php/controllers/clients/clients_controller.php",
        method: "POST",
        data: {
          mod: "updateClient",
          clientId: clientId,
          name: name,
          lastname: lastname,
          email: email,
          phonenumber: phonenumber,
          password: password,
          razon_social: razon_social,
          rfc: rfc,
          street: street,
          ext_num: ext_num,
          int_num: int_num,
          colony: colony,
          locality: locality,
          zipcode: zipcode,
          state: state,
          city: city,
          cdfi: cdfi,
          reg_fiscal: reg_fiscal,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            Swal.fire({
              title: data.message,
              icon: "success",
            }).then((result) => {
              loading();
              location.reload();
            });
          } else {
            Swal.fire({
              title: data.message,
              icon: "error",
            });
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudo completar el proceso!",
            icon: "error",
          });
        });
    } else {
      Swal.fire({
        title: "No puede dejar campos obligatorios vacíos!!!",
        icon: "error",
      });
    }
  });

  $(document).on("click", ".btnEditClient", async function () {
    loading();
    var clientId = $(this).data("id-client");
    $("#btnSaveClientEdits").attr("data-id-client", clientId); // Asumiendo que el ID del cliente está en un campo oculto con id "clientId"
    $.ajax({
      url: "php/controllers/clients/clients_controller.php",
      method: "POST",
      data: {
        mod: "getClientData",
        idClient: clientId,
      },
      success: async function (data) {
        var clientData = JSON.parse(data);

        if (clientData.response === true) {
          console.log(clientData);
          $("#editClientName").val(clientData.client[0].name);
          $("#editClientLastname").val(clientData.client[0].lastname);
          $("#editClientMail").val(clientData.client[0].email);
          $("#editClientCellphone").val(clientData.client[0].cellphone);
          $("#editClientPassword").val(clientData.client[0].password);
          $("#editClientRazonSocial").val(clientData.client[1].razon_social);
          $("#editClientRFC").val(clientData.client[1].rfc);
          $("#editClientStreet").val(clientData.client[1].street);
          $("#editClientExt_num").val(clientData.client[1].ext_number);
          $("#editClientInt_num").val(clientData.client[1].int_number);
          $("#editClientColony").val(clientData.client[1].colony);
          $("#editClientLocality").val(clientData.client[1].locality);
          $("#editClientZipcode").val(clientData.client[1].zip_code);
          $("#select-edit-uso-cfdi").val(clientData.client[1].c_UsoCFDI);
          $("#select-edit-reg-fiscal").val(
            clientData.client[1].c_RegimenFiscal
          );

          $("#editClientSelectStateBill")
            .val(clientData.client[1].state)
            .trigger("change");

          // Actualizar estado y ciudad usando la nueva función
          await updateStateEdit(
            clientData.client[1].state,
            clientData.client[1].city
          );
          Swal.close();
        } else {
          Swal.fire({
            title: "Error al obtener los datos del cliente",
            icon: "error",
          });
        }
      },
      error: function () {
        Swal.fire({
          title: "Hubo un error al cargar los datos",
          icon: "error",
        });
      },
    });
  });

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

  $("#selectState").select2({
    dropdownParent: $("#newColabModal"),
  });
  $("#selectCity").select2({
    dropdownParent: $("#newColabModal"),
  });
  $("#selectSubsidiary").select2({
    dropdownParent: $("#newColabModal"),
  });
  $("#selectPosition").select2({
    dropdownParent: $("#newColabModal"),
  });

  $("#editClientSelectStateBill").select2({
    dropdownParent: $("#editClientModal"),
  });
  $("#editClientSelectCityBill").select2({
    dropdownParent: $("#editClientModal"),
  });

  $("#selectStateBill").select2({
    dropdownParent: $("#newClientModal"),
  });
  $("#selectCityBill").select2({
    dropdownParent: $("#newClientModal"),
  });
});
