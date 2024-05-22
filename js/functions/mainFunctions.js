$(document).ready(function () {
  if (sessionStorage.getItem("cart_shop")) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
//    console.log(cart_shop);
    $(".cartSizeTxt").text(cart_shop.length);
  }else{
    sessionStorage.setItem("cart_shop", JSON.stringify([]));
    sessionStorage.setItem("total_sale", 0);
  }

  $(document).on("click", ".addCartProd", async function (event) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));

    var id_product = $(this).attr("data-id-product");
    var price = $(this).attr("data-product-price");
    var stock = $(this).attr("data-stock");

    add_prod = 1;
    await cart_shop.forEach(function(cart_item, index) {

      if (cart_item.id_product == id_product) {
        add_prod = 0;
      }
    });
if (add_prod) {
  let arr_prod_cart = {id_product:id_product, quantity:1, price:price, stock:stock};
    cart_shop.push(arr_prod_cart);
    $(".cartSizeTxt").text(cart_shop.length);

    sessionStorage.setItem("cart_shop", JSON.stringify(cart_shop));
    
    //--- --- ---//

    var cart_shop1 = JSON.parse(sessionStorage.getItem("cart_shop"));
//    console.log(cart_shop1);
    doneToast("Añadido al carrito");
}else{
  errorToast("Este producto ya está en el carrito");
}
    
  });

  function doneToast(text) {
    Toastify({
      text: text,
      duration: 3000,
      destination: "cart.php",
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
