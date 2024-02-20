$(document).ready(function () {
  let limitProducts = 10;
  let searchInput = "";
  let actualPage = 1;
  let id_subsidiary = "";

  //loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitProducts = $(this).val();

    loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);
    //--- --- ---//
  });
  $(document).on("click", ".changePage", function (event) {
    loading();
    actualPage = $(this).text();

    loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);
    //--- --- ---//
  });

  $(document).on("keyup", "#searchOrder", function (event) {
    /* loading(); */
    searchInput = $(this).val();
    console.log("here");
    loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);
    //--- --- ---//
  });

  $(document).on("change", "#slct_subsidiary", function (event) {
    loading();
    id_subsidiary = $(this).val();
    loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);
    $("#divTable").show();
    Swal.close();

    //--- --- ---//
  });

  $(document).on("keyup", "#searchProd", function (e) {
    console.log(e.which);
    if (e.which == 13) {
      loading();
      searchInput = $(this).val();

      loadStocks(limitProducts, searchInput, actualPage, id_subsidiary);
      return false;
    }
    //--- --- ---//
  });
  $(document).on("dblclick", ".edit-stock", function (event) {
    loading();
    var id_product = $(this).attr("data-id-product");
    var id_subsidiary = $(this).attr("data-id-subsidiary");
    var stock = $(this).attr("data-stock");
    if (stock === "-") {
      stock = "";
    } else {
      stock = parseFloat(stock);
    }

    var html = "";
    html +=
      '<input class="form-control input-stock-subs" data-id-subsidiary="' +
      id_subsidiary +
      '" data-id-product="' +
      id_product +
      '" type="number" step="1" placeholder="Ingrese una cantidad" value="' +
      stock +
      '">';
    $(this).html(html);
    Swal.close();
  });

  $(document).on("keyup", ".input-stock-subs", function (e) {
    if (e.which == 13) {
      stock = $(this).val();
      var id_product = $(this).attr("data-id-product");
      var id_subsidiary = $(this).attr("data-id-subsidiary");

      loading();
      $.ajax({
        url: "php/controllers/stock_subsidiary/stock_subsidiary_controller.php",
        method: "POST",
        data: {
          mod: "saveNewStock",
          id_product: id_product,
          id_subsidiary: id_subsidiary,
          stock: stock
        },
      })
        .done(function (data) {
          console.log(data);
          var data = JSON.parse(data);
          //console.log(data);
          if (data.response == true) {
            if (stock == "") {
              stock = "-";
            }
            $("#tdEditStock"+id_product).attr("data-stock",stock);
            $("#tdEditStock"+id_product).html(stock);
            Swal.close();
          } else {
            Swal.close();
            errorToast("Ocurió un error al actualizar el stock!!");
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudoo completar el proceso!",
            icon: "error",
          });
        });
    }
    //--- --- ---//
  });
  function loadStocks(limitOrders, searchInput, actualPage, id_subsidiary) {
    if (actualPage != null) {
      actualPage = actualPage;
    }
    //console.log(actualPage);

    $.ajax({
      url: "php/controllers/stock_subsidiary/stock_subsidiary_controller.php",
      method: "POST",
      data: {
        mod: "getStocks",
        limit: limitOrders,
        searchInput: searchInput,
        actualPage: actualPage,
        id_subsidiary: id_subsidiary,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);

        if (data.response == true) {
          console.log(data);
          $("#tableStocks > tbody").empty().html(data.html);
          $("#lblTotal").html(
            "Mostrando " +
              data.totalFiltered +
              " de un total de  " +
              data.totalProds +
              " registros"
          );
          $("#navPagination").html(data.paginationNav);

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
});
$(".js-example-basic-single").select2();

$("#selectSubsidiary").select2({
  dropdownParent: $("#modalNewIncome"),
});
