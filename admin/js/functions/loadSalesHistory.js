$(document).ready(function () {
  let limitSales = 10;
  let searchInput = "";
  let actualPage = 1;
  loadSalesHistory(limitSales, searchInput, actualPage);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitSales = $(this).val();

    loadSalesHistory(limitSales, searchInput, actualPage);
    //--- --- ---//
  });
  $(document).on("click", ".changePage", function (event) {
    loading();
    actualPage = $(this).text();

    loadSalesHistory(limitSales, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("keyup", "#searchProd", function (e) {
    console.log(e.which);
    if (e.which == 13) {
      loading();
      searchInput = $(this).val();

      loadSalesHistory(limitSales, searchInput, actualPage);
      return false;
    }
    //--- --- ---//
  });

  function loadSalesHistory(limitSales, searchInput, actualPage) {
    if (actualPage != null) {
      actualPage = actualPage;
    }
    //console.log(actualPage);

    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "getSalesTable",
        limit: limitSales,
        searchInput: searchInput,
        actualPage: actualPage,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        //console.log(data);
        if (data.response == true) {
          $("#tableSales > tbody").html(data.html);
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

  $(document).on("click", ".getSaleDetail", function () {
    loading();

    var id_sale = $(this).attr("data-id-order");
      $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "getSaleDetail",
          id_sale:id_sale,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          Swal.close();
          if (data.response == true) {
            $(".body-detail-sale").html(data.html);

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
