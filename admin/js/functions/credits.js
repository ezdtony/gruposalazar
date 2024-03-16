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
  
    $(document).on("click", "#generate_password", function () {
      $("#password").val(autoCreate(6));
    });
  
    $(document).on("click", ".saveCreditClient", function () {
      loading();
  
      var id_client = $("#selectClient").val();
      var ammount = $("#credit_ammount").val();
  
      if (
        id_client != "" &&
        id_client != undefined &&
        ammount != "" &&
        ammount != undefined
      ) {
        console.log("save");
  
        $.ajax({
          url: "php/controllers/credits/credits_controller.php",
          method: "POST",
          data: {
            mod: "saveClientCredit",
            id_client:id_client,
            ammount:ammount,
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
              title: "No se pudoo completar el proceso!",
              icon: "error",
            });
          });
      } else {
        Swal.fire({
          title: "Debe ingresar todos los datos!!",
          icon: "error",
        });
      }
    });
  
    function autoCreate(plength) {
      var chars =
        "abcdefghijklmnopqrstubwsyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
      var password = "";
      for (i = 0; i < plength; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
      }
  
      return password;
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
  
    $("#selectClient").select2({
      dropdownParent: $("#newClientCredit"),
    });
  });
  