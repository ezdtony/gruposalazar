$(document).ready(function () {
  let limitProducts = 25;
  let searchInput = "";
  let actualPage = 1;
  loadProducts(limitProducts, searchInput, actualPage);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitProducts = $(this).val();

    loadProducts(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("click", ".loadMore", function (event) {
    $(this).remove();
    loading();

    actualPage = actualPage + 1;

    loadProducts(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });
  $(document).on("click", "#searchButton", function (event) {
    searchInput = $("#search-input").val();
    loading();
    $(".productsContent").html("");
    let actualPage = 1;
    loadProducts(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("keyup", "#search-input", function (e) {
    console.log(e.which);
    if (e.which == 13) {
      loading();
      searchInput = $(this).val();

      loadProducts(limitProducts, searchInput, actualPage);
      return false;
    }
    //--- --- ---//
  });

  function loadProducts(limitProducts, searchInput, actualPage) {
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);

    if (urlParams.has("search")) {
      console.log("here");
      //--- --- ---//
      const filter = urlParams.get("search");
      searchInput = filter;
      //--- --- ---//
    }
    if (actualPage != null) {
      actualPage = actualPage;
    }
    loading();
    //console.log(actualPage);

    $.ajax({
      url: "admin/php/controllers/articles/articles_controller.php",
      //url: "admin/php/controllers/articles/articles_controller.php",

      method: "POST",
      data: {
        mod: "getProductsItems",
        limit: limitProducts,
        searchInput: searchInput,
        actualPage: actualPage,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        //console.log(data);
        if (data.response == true) {
          $("#productsContent").append(data.html);

          // Aumentar cantidad
          $(".quantity-right-plus").click(function (e) {
            e.preventDefault();
            var productId = $(this).data("product-id");
            var $input = $("#quantity-prod-" + productId);
            var currentVal = parseInt($input.val());
            var maxVal = parseInt($input.attr("max")); // Obtener el valor máximo
            var minVal = parseInt($input.attr("min")); // Obtener el valor mínimo

            if (!isNaN(currentVal) && currentVal < maxVal) {
              $input.val(currentVal + 1); // Aumentar cantidad
            } else {
              $input.val(maxVal); // Si ya alcanza el máximo, no aumentamos más
            }
          });

          // Disminuir cantidad
          $(".quantity-left-minus").click(function (e) {
            e.preventDefault();
            var productId = $(this).data("product-id");
            var $input = $("#quantity-prod-" + productId);
            var currentVal = parseInt($input.val());
            var minVal = parseInt($input.attr("min")); // Obtener el valor mínimo

            if (!isNaN(currentVal) && currentVal > minVal) {
              $input.val(currentVal - 1); // Disminuir cantidad
            } else {
              $input.val(minVal); // Si ya alcanza el mínimo, no disminuimos más
            }
          });
          //            $("#navPagination").html(data.paginationNav);

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
