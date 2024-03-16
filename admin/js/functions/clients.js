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
  
    $(document).on("click", "#btnSaveNewClient", function () {
      loading();
  
      var name = $("#name_new_client").val();
      var lastname = $("#lastname_new_client").val();
      var email = $("#mail").val();
      var phonenumber = $("#phone_number").val();
  
    /*   var street = $("#street").val();
      var int_num = $("#int_num").val();
      var ext_num = $("#ext_num").val();
      var colony = $("#colony").val();
      var zipcode = $("#zipcode").val();
      var state = $("#selectState option:selected").text();
      var city = $("#selectCity option:selected").text(); */
      var password = $("#password").val();
  
  
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
          title: "No puede dejar campos obligatorios vacíos!!!",
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
  });
  