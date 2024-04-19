$(document).ready(function () {
  $(document).on("click", ".offerAddTags", function (event) {
    loading();
    var id_offer = $(this).attr("data-id-offer");
    $("#selectTag").attr("data-id-offer", id_offer);

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "getOfferTags",
        id_offer: id_offer,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $(".tagsProd").html(data.html);
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

  $(document).on("click", ".offerTagItem", function (event) {
    loading();
    var id_offer = $(this).attr("data-id-offer");
    var id_tag = $(this).attr("data-id-tag");
    var tagItem = $(this);

    console.log(id_tag);
    console.log(id_offer);

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "removeOfferTags",
        id_offer: id_offer,
        id_tag: id_tag,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          tagItem.remove();
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
  $(document).on("click", ".deleteOffer", function (event) {
    loading();
    var id_offer = $(this).attr("data-id-offer");
    var tagItem = $(this).closest("tr");

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "deleteOffer",
        id_offer: id_offer,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          tagItem.remove();
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

  $(document).on("change", "#selectTag", function (event) {
    if ($(this).find(":selected").val() !== "") {
      loading();
      var id_tag = $(this).find(":selected").val();
      var id_offer = $(this).attr("data-id-offer");
      var tag_name = $(this).find(":selected").text();

      $.ajax({
        url: "php/controllers/articles/articles_controller.php",
        method: "POST",
        data: {
          mod: "insertOfferTags",
          id_offer: id_offer,
          id_tag: id_tag,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            html =
              '<p style="font-size:1rem !important" class="badge text-bg-primary offerTagItem" data-id-offer="' +
              id_offer +
              '" data-id-tag="' +
              id_tag +
              '">' +
              tag_name +
              "</p>";
            $("#selectTag").val(""); // Select the option with a value of '1'
            $("#selectTag").trigger("change"); // Notify any JS components that the value changed
            $(".tagsProd").append(html);
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

      //--- --- ---//
    }
  });

  $(document).on("click", ".saveOffer", function (event) {
    loading();
    var offer_name = $("#offer_name").val();
    var percentage = $("#percentage").val();
    var money_discount = $("#money_discount").val();
    var init_date = $("#init_date").val();
    var end_date = $("#end_date").val();
    var offer_details = $("#offer_details").val();
    var min_ammount = $("#min_ammount").val();

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "saveNewOffer",
        offer_name: offer_name,
        percentage: percentage,
        money_discount: money_discount,
        init_date: init_date,
        end_date: end_date,
        offer_details: offer_details,
        min_ammount: min_ammount,
      },
    })
      .done(function (data) {
        Swal.close();
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
  $(document).on("click", ".editOffer", function (event) {
    loading();
    var id_offer = $(this).attr("data-id-offer");
    $(".btnCloseEditOffer").attr("data-id-offer", id_offer);

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "getOfferInfo",
        id_offer: id_offer,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $("#edit_offer_name").val(data.data[0].offer_name);
          $("#edit_percentage").val(data.data[0].percentage);
          $("#edit_money_discount").val(data.data[0].money_discount);
          $("#edit_min_ammount").val(data.data[0].min_ammount);
          $("#edit_init_date").val(data.data[0].d_start_date);
          $("#edit_end_date").val(data.data[0].d_end_date);
          $("#edit_offer_details").val(data.data[0].offer_details);
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

  $(document).on("change", ".input_edit_offer", function (event) {
    loading();
    var newVal = $(this).val();
    var column_name = $(this).attr("column-name");
    var id_offer = $(".btnCloseEditOffer").attr("data-id-offer");

    $.ajax({
      url: "php/controllers/articles/articles_controller.php",
      method: "POST",
      data: {
        mod: "editOffer",
        newVal: newVal,
        column_name: column_name,
        id_offer: id_offer,
      },
    })
      .done(function (data) {
        Swal.close();
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
  $("#edit_prod_image").change(function () {
    editImageProd();
  });
  function editImageProd() {
    loading();
    var id_offer = $(".btnCloseEditOffer").attr("data-id-offer");
    const prod_image = document.querySelector("#edit_prod_image");

    if (
      id_offer != null &&
      id_offer != "" &&
      id_offer != undefined &&
      prod_image.files.length > 0
    ) {
      let formData = new FormData();
      formData.append("mod", "editImageOffers");
      formData.append("prod_image", prod_image.files[0]);
      formData.append("id_offer", id_offer);
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
            text: "Registro actualizado exitosamente",
            timer: 3000,
          }).then((result) => {
            loading();
            location.reload();
          });
        });
    } else {
      console.log(id_prod);
      console.log(prod_image);
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

  $("#selectTag").select2({
    dropdownParent: $("#addTags"),
  });
});
