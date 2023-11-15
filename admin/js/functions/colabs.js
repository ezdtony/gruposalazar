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

  $(document).on("click", "#btnSaveNewUser", function () {
    loading();

    var name = $("#name_new_colab").val();
    var lastname = $("#lastname_new_colab").val();
    var email = $("#personal_mail").val();
    var phonenumber = $("#phone_number").val();
    var curp = $("#curp").val();
    var rfc = $("#rfc").val();
    var nss = $("#nss").val();

    var street = $("#street").val();
    var int_num = $("#int_num").val();
    var ext_num = $("#ext_num").val();
    var colony = $("#colony").val();
    var zipcode = $("#zipcode").val();
    var state = $("#selectState option:selected").text();
    var city = $("#selectCity option:selected").text();
    var subsidiary = $("#selectSubsidiary").val();
    var position = $("#selectPosition").val();
    var assigned_mail = $("#assigned_mail").val();
    var password = $("#password").val();

    console.log("name: " + name);
    console.log("lastname: " + lastname);
    console.log("email: " + email);
    console.log("phonenumber: " + phonenumber);
    console.log("curp: " + curp);
    console.log("rfc: " + rfc);
    console.log("street: " + street);
    console.log("int_num: " + int_num);
    console.log("colony: " + colony);
    console.log("zipcode: " + zipcode);
    console.log("state: " + state);
    console.log("subsidiary: " + subsidiary);
    console.log("position: " + position);
    console.log("assigned_mail: " + assigned_mail);
    console.log("password: " + password);
    console.log("city: " + city);
    console.log("nss: " + nss);

    if (
      name != "" &&
      name != undefined &&
      lastname != "" &&
      lastname != undefined &&
      email != "" &&
      email != undefined &&
      phonenumber != "" &&
      phonenumber != undefined &&
      curp != "" &&
      curp != undefined &&
      nss != "" &&
      nss != undefined &&
      street != "" &&
      street != undefined &&
      ext_num != "" &&
      ext_num != undefined &&
      colony != "" &&
      colony != undefined &&
      zipcode != "" &&
      zipcode != undefined &&
      state != "" &&
      state != undefined &&
      subsidiary != "" &&
      subsidiary != undefined &&
      position != "" &&
      position != undefined &&
      assigned_mail != "" &&
      assigned_mail != undefined &&
      password != "" &&
      password != undefined &&
      city != "" &&
      city != undefined
    ) {
      console.log("save");

      $.ajax({
        url: "php/controllers/colabs/colab_controller.php",
        method: "POST",
        data: {
          mod: "saveColab",
          name: name,
          lastname: lastname,
          email: email,
          phonenumber: phonenumber,
          curp: curp,
          rfc: rfc,
          street: street,
          int_num: int_num,
          ext_num: ext_num,
          colony: colony,
          zipcode: zipcode,
          state: state,
          subsidiary: subsidiary,
          position: position,
          assigned_mail: assigned_mail,
          password: password,
          city: city,
          nss: nss,
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
            $("#newColabModal").find("input,textarea,select").val("");
            $("#newColabModal input[type='checkbox']")
              .prop("checked", false)
              .change();
              $("#closeModalNewUser").trigger('click');
              $("#selectState").select2("val", "");
              $("#selectCity").select2("val", "");
              $("#selectCity").attr("disabled", true);
            $("#tbodyColabs").append(data.html);
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
