$(document).ready(function () {
  if (sessionStorage.getItem("cart_shop")) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
//    console.log(cart_shop);
    $(".cartSizeTxt").text(cart_shop.length);
  }else{
    sessionStorage.setItem("cart_shop", JSON.stringify([]));
  }

  $(document).on("click", ".addCartProd", function (event) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));

    var id_product = $(this).attr("data-id-product");
    var price = $(this).attr("data-product-price");

    let arr_prod_cart = {id_product:id_product, quantity:1, price:price};
    cart_shop.push(arr_prod_cart);
    $(".cartSizeTxt").text(cart_shop.length);

    sessionStorage.setItem("cart_shop", JSON.stringify(cart_shop));
    //--- --- ---//

    var cart_shop1 = JSON.parse(sessionStorage.getItem("cart_shop"));
//    console.log(cart_shop1);
    doneToast("Añadido al carrito");
  });

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
        background: "#05b025",
        //background: "linear-gradient(to right, #00b09b, #96c93d)",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }
});
