$(document).ready(function () {
  let limitProducts = "";
  let searchInput = "";
  let actualPage = 1;
  loadProductsCart(limitProducts, searchInput, actualPage);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitProducts = $(this).val();

    loadProductsCart(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });
  $(document).on("click", ".loadMore", function (event) {
    $(this).remove();
    loading();

    actualPage = actualPage + 1;

    loadProductsCart(limitProducts, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("keyup", "#searchProd", function (e) {
    console.log(e.which);
    if (e.which == 13) {
      loading();
      searchInput = $(this).val();

      loadProductsCart(limitProducts, searchInput, actualPage);
      return false;
    }
    //--- --- ---//
  });

  function loadProductsCart(limitProducts, searchInput, actualPage) {
    if (sessionStorage.getItem("cart_shop")) {
      var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    } else {
      cart_shop = [];
    }
    console.log(cart_shop);

    var url = window.location.search;
    const urlParams = new URLSearchParams(url);

    if (urlParams.has("parms")) {
      console.log("here");
      //--- --- ---//
      const filtered = urlParams.get("filtered");
      const filter = urlParams.get("filter");
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
      method: "POST",
      data: {
        mod: "getProductsCart",
        cart_shop: cart_shop,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          $(".tableCart > tbody").html(data.html);
          $(".txtTotalCart").text(data.totalSale);
          sessionStorage.setItem("total_sale", data.totalSale);

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
  var sitePlusMinus = function () {
    var value,
      quantity = document.getElementsByClassName("quantity-container");

    function createBindings(quantityContainer) {
      var quantityAmount =
        quantityContainer.getElementsByClassName("quantity-amount")[0];
      var increase = quantityContainer.getElementsByClassName("increase")[0];
      var decrease = quantityContainer.getElementsByClassName("decrease")[0];
      increase.addEventListener("click", function (e) {
        increaseValue(e, quantityAmount);
      });
      decrease.addEventListener("click", function (e) {
        decreaseValue(e, quantityAmount);
      });
    }

    function init() {
      for (var i = 0; i < quantity.length; i++) {
        createBindings(quantity[i]);
      }
    }

    function increaseValue(event, quantityAmount) {
      console.log(event);
      value = parseInt(quantityAmount.value, 10);

      console.log(quantityAmount, quantityAmount.value);

      value = isNaN(value) ? 0 : value;
      value++;
      quantityAmount.value = value;
      console.log(quantityAmount, quantityAmount.value);
    }

    function decreaseValue(event, quantityAmount) {
      value = parseInt(quantityAmount.value, 10);

      value = isNaN(value) ? 0 : value;
      if (value > 0) value--;

      quantityAmount.value = value;
    }

    init();
  };
  $(document).on("click", ".increase", async function (event) {
    //loading();
    var value = $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .val();
      var stock = $(this).attr("data-stock");
      stock = parseInt(stock, 10);

    var prod_price = $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .attr("data-price");

    value = parseInt(value, 10);
    value = isNaN(value) ? 1 : value;
    if (value >= stock) {
      value = value;
      errorToast("No hay existencias suficientes para este producto");
    }else{
      value++;
    }
    

    prod_price = parseFloat(prod_price, 10);
    prod_price = isNaN(prod_price) ? prod_price : prod_price;

    prod_total = (value * prod_price).toFixed(2);

    $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .val(value);
    $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .attr("value", value);
    $(this)
      .parents("tr")
      .children(".total-prod")
      .text("$" + prod_total);

    $(this)
      .parents("tr")
      .children(".total-prod")
      .attr("data-total-prod", prod_total);

    $(this)
      .parents("tr")
      .children(".total-prod")
      .attr("data-product-quantity", value);

    await recalcTotal();
    //    Swal.close();
    console.log(value);

    //--- --- ---//
  });

  async function recalcTotal() {
    var total_sale = 0;
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    $(".total-prod").each(function () {
      total_prod = $(this).attr("data-total-prod");
      var id_product = $(this).attr("data-id-product");
      var price = $(this).attr("data-price");
      var stock = $(this).attr("data-stock");
      var quantity = $(this).attr("data-product-quantity");
      var cart_index = $(this).attr("data-cart-index");

      total_prod = parseFloat(total_prod, 10);
      total_prod = isNaN(total_prod) ? total_prod : total_prod;

      total_sale = total_sale + total_prod;

      let arr_prod_cart = {
        id_product: id_product,
        quantity: quantity,
        price: price,
        stock: stock,
      };

      console.log(cart_shop);
      cart_shop[cart_index] = arr_prod_cart;
      console.log(cart_shop);
    });
    $(".txtTotalCart").text(total_sale.toFixed(2));

    sessionStorage.setItem("cart_shop", JSON.stringify(cart_shop));

    sessionStorage.setItem("total_sale", total_sale.toFixed(2));
    console.log(sessionStorage.getItem("cart_shop", JSON.stringify(cart_shop)));
  }

  $(document).on("click", ".decrease", async function (event) {
    //loading();
    var value = $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .val();

    var prod_price = $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .attr("data-price");

    value = parseInt(value, 10);
    value = isNaN(value) ? 0 : value;

    prod_price = parseFloat(prod_price, 10);
    prod_price = isNaN(prod_price) ? prod_price : prod_price;

    if (value > 0) value--;

    prod_total = (value * prod_price).toFixed(2);
    $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .val(value);
    $(this)
      .parents(".quantity-container")
      .children(".quantity-amount")
      .attr("value", value);

    $(this)
      .parents("tr")
      .children(".total-prod")
      .text("$" + prod_total);
    $(this)
      .parents("tr")
      .children(".total-prod")
      .attr("data-total-prod", prod_total);

    $(this)
      .parents("tr")
      .children(".total-prod")
      .attr("data-product-quantity", value);

    await recalcTotal();
    //Swal.close();
    console.log(value);

    //--- --- ---//
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
