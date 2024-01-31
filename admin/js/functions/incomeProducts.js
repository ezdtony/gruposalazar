$(document).ready(function () {
  let limitProducts = 10;
  let searchInput = "";
  let actualPage = 1;

  loadIncomes(limitProducts, searchInput, actualPage);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitProducts = $(this).val();

    loadIncomes(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });
  $(document).on("click", ".changePage", function (event) {
    loading();
    actualPage = $(this).text();

    loadIncomes(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("keyup", "#searchOrder", function (event) {
    /* loading(); */
    searchInput = $(this).val();

    loadIncomes(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("change", "#selectSubsidiary", function (event) {
    loading();
    let id_subsidiary = $(this).val();
    /* $("#product").attr("disabled", "disabled"); */
    $("#product").removeAttr("disabled");
    Swal.close();

    /* loadIncomes(limitProducts, searchInput, actualPage); */
    //--- --- ---//
  });

  $("#product").on("keypress", function (event) {
    if (event.which == 13 && !event.shiftKey) {
      console.log("buscar: " + $(this).val());
      loading();
      var searchProd = $(this).val();

      $.ajax({
        url: "php/controllers/income_prods/income_prods_controller.php",
        method: "POST",
        data: {
          mod: "searchProductIncome",
          searchProd: searchProd,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            $("#showResultsProds").html(data.html);
            $("#showResultsProds").show();
            Swal.close();
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

      $(this).val("");
      event.preventDefault();
    }
  });
  $(document).on("click", ".itemProductSearch", function (event) {
    loading();
    var product_name = $(this).attr("data-product-name");
    var product_code = $(this).attr("data-product-code");
    var id_product = $(this).attr("data-id-product");
    var brand = $(this).attr("data-product-brand");
    var product_price_buy = $(this).attr("data-product-price-buy");
    var product_price_sell = $(this).attr("data-product-price-sell");
    var max_stock = $(this).attr("data-max-stock");
    var quantity = 1;
    var valid_add = 1;

    $("#tableProductsIncome > tbody  > tr").each(function (index, tr) {
      //console.log(index);
      //console.log(tr);
      var id_product_table = $(tr).attr("data-id-product");
      if (id_product == id_product_table) {
        valid_add = 0;
      }
    });
    if (valid_add) {
      var html = "";
      html +=
        '<tr id="trProduct' +
        id_product +
        '" data-id-product="' +
        id_product +
        '" data-quantity="1">';
      html += "<td>" + product_code + "</td>";
      html += "<td>" + product_name + "</td>";
      html += "<td>" + brand + "</td>";
      html += "<td>" + product_price_buy + "</td>";
      html += "<td>" + product_price_sell + "</td>";
      html +=
        '<td><input type="number" class="form-control setProdQuantity" data-id-product="' +
        id_product +
        '" step="1" id="quantityProd' +
        id_product +
        '" name="tentacles" min="1" max="' +
        max_stock +
        '" value="' +
        quantity +
        '" /></td>';
      html +=
        '<td><button type="button"  class="btn btn-outline-danger btn-sm btnRemoveProdList" data-id-prod="' +
        id_product +
        '">x</button></td>';

      $("#tableProductsIncome").prepend(html);
      $("#saveNewIncome").prop("disabled", false);
      Swal.close();
    } else {
      Swal.fire({
        title: "Este producto ya ha sido agregado a la lista!!",
        icon: "info",
      });
    }

    //--- --- ---//
  });
  $(document).on("focusout", ".setProdQuantity", function (event) {
    loading();
    var id_product = $(this).attr("data-id-product");
    var prod_quantity = $(this).val();
    console.log(prod_quantity);
    $("#trProduct" + id_product).attr("data-quantity", prod_quantity);
    Swal.close();
    //--- --- ---//
  });
  $(document).on("click", "#saveNewIncome", function (event) {
    loading();
    var id_subsidiary = $("#selectSubsidiary").val();
    console.log(id_subsidiary);
    products_income = Array();
    if (
      id_subsidiary == "" ||
      id_subsidiary == null ||
      id_subsidiary == undefined
    ) {
      Swal.fire({
        title: "Por favor seleccione una sucursal",
        icon: "info",
      });
    } else {
      $("#tableProductsIncome > tbody  > tr").each(function (index, tr) {
        //console.log(index);
        //console.log(tr);
        var id_product = $(tr).attr("data-id-product");
        var quantity = $(tr).attr("data-quantity");
        //console.log("id_product:"+id_product);
        //console.log("quantity:"+quantity);
        products_income.push([id_product, quantity]);
      });
      console.log(products_income);
      $.ajax({
        url: "php/controllers/income_prods/income_prods_controller.php",
        method: "POST",
        data: {
          mod: "insertIncomeOrder",
          id_subsidiary: id_subsidiary,
          products_income: products_income,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          //console.log(data);
          if (data.response == true) {
            $("#showResultsProds").hide();
            $("#selectSubsidiary").val(""); // Select the option with a value of '1'
            $("#selectSubsidiary").trigger("change"); // Notify any JS components that the value changed
            $("#product").prop("disabled", true);
            $("#saveNewIncome").prop("disabled", true);
            $("#tableProductsIncome > tbody").empty();
            $("#modalNewIncome").modal("toggle");
            Swal.fire({
              title: data.message,
              icon: "success",
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                Location.reload();
              }
            });
            /* doneToast(data.message); */
          } else {
            Swal.fire({
              title: data.message,
              icon: "info",
            });
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
  $(document).on("click", ".btnRemoveProdList", function (event) {
    loading();
    var id_product = $(this).attr("data-id-prod");
    $("#trProduct" + id_product).remove();
    Swal.close();
  });
  $(document).on("click", ".deleteOrder", function (event) {
    loading();
    var id_income_order = $(this).attr("data-id-income-order");
    $.ajax({
      url: "php/controllers/income_prods/income_prods_controller.php",
      method: "POST",
      data: {
        mod: "insertIncomeOrder",
        id_subsidiary: id_subsidiary,
        products_income: products_income,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        //console.log(data);
        if (data.response == true) {
          
          $("#trIncomeOrder" + id_income_order).remove();
        } else {
          Swal.fire({
            title: data.message,
            icon: "info",
          });
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
    Swal.close();
  });

  $(document).on("click", ".btnRemoveProdList", function (event) {
    loading();
    var id_product = $(this).attr("data-id-prod");
    $("#trProduct" + id_product).remove();
    Swal.close();
  });

  showBreakdownOrder;

  function loadIncomes(limitProducts, searchInput, actualPage) {
    if (actualPage != null) {
      actualPage = actualPage;
    }
    //console.log(actualPage);

    $.ajax({
      url: "php/controllers/income_prods/income_prods_controller.php",
      method: "POST",
      data: {
        mod: "getIncomeTable",
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
          $("#tableProducts > tbody").html(data.html);
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